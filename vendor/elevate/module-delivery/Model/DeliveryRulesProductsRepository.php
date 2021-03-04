<?php


namespace Elevate\Delivery\Model;

use Elevate\Delivery\Api\DeliveryRulesProductsRepositoryInterface;
use Elevate\Delivery\Api\Data\DeliveryRulesProductsSearchResultsInterfaceFactory;
use Elevate\Delivery\Api\Data\DeliveryRulesProductsInterfaceFactory;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Elevate\Delivery\Model\ResourceModel\DeliveryRulesProducts as ResourceDeliveryRulesProducts;
use Elevate\Delivery\Model\ResourceModel\DeliveryRulesProducts\CollectionFactory as DeliveryRulesProductsCollectionFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\Api\ExtensibleDataObjectConverter;

class DeliveryRulesProductsRepository implements DeliveryRulesProductsRepositoryInterface
{

    protected $resource;

    protected $deliveryRulesProductsFactory;

    protected $deliveryRulesProductsCollectionFactory;

    protected $searchResultsFactory;

    protected $dataObjectHelper;

    protected $dataObjectProcessor;

    protected $dataDeliveryRulesProductsFactory;

    protected $extensionAttributesJoinProcessor;

    private $storeManager;

    private $collectionProcessor;

    protected $extensibleDataObjectConverter;

    /**
     * @param ResourceDeliveryRulesProducts $resource
     * @param DeliveryRulesProductsFactory $deliveryRulesProductsFactory
     * @param DeliveryRulesProductsInterfaceFactory $dataDeliveryRulesProductsFactory
     * @param DeliveryRulesProductsCollectionFactory $deliveryRulesProductsCollectionFactory
     * @param DeliveryRulesProductsSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     * @param CollectionProcessorInterface $collectionProcessor
     * @param JoinProcessorInterface $extensionAttributesJoinProcessor
     * @param ExtensibleDataObjectConverter $extensibleDataObjectConverter
     */
    public function __construct(
        ResourceDeliveryRulesProducts $resource,
        DeliveryRulesProductsFactory $deliveryRulesProductsFactory,
        DeliveryRulesProductsInterfaceFactory $dataDeliveryRulesProductsFactory,
        DeliveryRulesProductsCollectionFactory $deliveryRulesProductsCollectionFactory,
        DeliveryRulesProductsSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager,
        CollectionProcessorInterface $collectionProcessor,
        JoinProcessorInterface $extensionAttributesJoinProcessor,
        ExtensibleDataObjectConverter $extensibleDataObjectConverter
    ) {
        $this->resource = $resource;
        $this->deliveryRulesProductsFactory = $deliveryRulesProductsFactory;
        $this->deliveryRulesProductsCollectionFactory = $deliveryRulesProductsCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataDeliveryRulesProductsFactory = $dataDeliveryRulesProductsFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
        $this->collectionProcessor = $collectionProcessor;
        $this->extensionAttributesJoinProcessor = $extensionAttributesJoinProcessor;
        $this->extensibleDataObjectConverter = $extensibleDataObjectConverter;
    }

    /**
     * {@inheritdoc}
     */
    public function save(
        \Elevate\Delivery\Api\Data\DeliveryRulesProductsInterface $deliveryRulesProducts
    ) {
        /* if (empty($deliveryRulesProducts->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $deliveryRulesProducts->setStoreId($storeId);
        } */
        
        $deliveryRulesProductsData = $this->extensibleDataObjectConverter->toNestedArray(
            $deliveryRulesProducts,
            [],
            \Elevate\Delivery\Api\Data\DeliveryRulesProductsInterface::class
        );
        
        $deliveryRulesProductsModel = $this->deliveryRulesProductsFactory->create()->setData($deliveryRulesProductsData);
        
        try {
            $this->resource->save($deliveryRulesProductsModel);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the deliveryRulesProducts: %1',
                $exception->getMessage()
            ));
        }
        return $deliveryRulesProductsModel->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function getById($deliveryRulesProductsId)
    {
        $deliveryRulesProducts = $this->deliveryRulesProductsFactory->create();
        $this->resource->load($deliveryRulesProducts, $deliveryRulesProductsId);
        if (!$deliveryRulesProducts->getId()) {
            throw new NoSuchEntityException(__('DeliveryRulesProducts with id "%1" does not exist.', $deliveryRulesProductsId));
        }
        return $deliveryRulesProducts->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->deliveryRulesProductsCollectionFactory->create();
        
        $this->extensionAttributesJoinProcessor->process(
            $collection,
            \Elevate\Delivery\Api\Data\DeliveryRulesProductsInterface::class
        );
        
        $this->collectionProcessor->process($criteria, $collection);
        
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        
        $items = [];
        foreach ($collection as $model) {
            $items[] = $model->getDataModel();
        }
        
        $searchResults->setItems($items);
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(
        \Elevate\Delivery\Api\Data\DeliveryRulesProductsInterface $deliveryRulesProducts
    ) {
        try {
            $deliveryRulesProductsModel = $this->deliveryRulesProductsFactory->create();
            $this->resource->load($deliveryRulesProductsModel, $deliveryRulesProducts->getDeliverymethodId());
            $this->resource->delete($deliveryRulesProductsModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the DeliveryRulesProducts: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($deliveryRulesProductsId)
    {
        return $this->delete($this->getById($deliveryRulesProductsId));
    }

    /**
     * {@inheritdoc}
     */
    public function create() {
        return $this->metadata->getNewInstance();

    }
}
