<?php


namespace Elevate\Delivery\Model;

use Elevate\Delivery\Api\DeliveryMethodRepositoryInterface;
use Elevate\Delivery\Api\Data\DeliveryMethodSearchResultsInterfaceFactory;
use Elevate\Delivery\Api\Data\DeliveryMethodInterfaceFactory;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Elevate\Delivery\Model\ResourceModel\DeliveryMethod as ResourceDeliveryMethod;
use Elevate\Delivery\Model\ResourceModel\DeliveryMethod\CollectionFactory as DeliveryMethodCollectionFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\Api\ExtensibleDataObjectConverter;

class DeliveryMethodRepository implements DeliveryMethodRepositoryInterface
{

    protected $resource;

    protected $deliveryMethodFactory;

    protected $deliveryMethodCollectionFactory;

    protected $searchResultsFactory;

    protected $dataObjectHelper;

    protected $dataObjectProcessor;

    protected $dataDeliveryMethodFactory;

    protected $extensionAttributesJoinProcessor;

    private $storeManager;

    private $collectionProcessor;

    protected $extensibleDataObjectConverter;

    /**
     * @param ResourceDeliveryMethod $resource
     * @param DeliveryMethodFactory $deliveryMethodFactory
     * @param DeliveryMethodInterfaceFactory $dataDeliveryMethodFactory
     * @param DeliveryMethodCollectionFactory $deliveryMethodCollectionFactory
     * @param DeliveryMethodSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     * @param CollectionProcessorInterface $collectionProcessor
     * @param JoinProcessorInterface $extensionAttributesJoinProcessor
     * @param ExtensibleDataObjectConverter $extensibleDataObjectConverter
     */
    public function __construct(
        ResourceDeliveryMethod $resource,
        DeliveryMethodFactory $deliveryMethodFactory,
        DeliveryMethodInterfaceFactory $dataDeliveryMethodFactory,
        DeliveryMethodCollectionFactory $deliveryMethodCollectionFactory,
        DeliveryMethodSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager,
        CollectionProcessorInterface $collectionProcessor,
        JoinProcessorInterface $extensionAttributesJoinProcessor,
        ExtensibleDataObjectConverter $extensibleDataObjectConverter
    ) {
        $this->resource = $resource;
        $this->deliveryMethodFactory = $deliveryMethodFactory;
        $this->deliveryMethodCollectionFactory = $deliveryMethodCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataDeliveryMethodFactory = $dataDeliveryMethodFactory;
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
        \Elevate\Delivery\Api\Data\DeliveryMethodInterface $deliveryMethod
    ) {
        /* if (empty($deliveryMethod->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $deliveryMethod->setStoreId($storeId);
        } */
        
        $deliveryMethodData = $this->extensibleDataObjectConverter->toNestedArray(
            $deliveryMethod,
            [],
            \Elevate\Delivery\Api\Data\DeliveryMethodInterface::class
        );
        
        $deliveryMethodModel = $this->deliveryMethodFactory->create()->setData($deliveryMethodData);
        
        try {
            $this->resource->save($deliveryMethodModel);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the deliveryMethod: %1',
                $exception->getMessage()
            ));
        }
        return $deliveryMethodModel->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function getById($deliveryMethodId)
    {
        $deliveryMethod = $this->deliveryMethodFactory->create();
        $this->resource->load($deliveryMethod, $deliveryMethodId);
        if (!$deliveryMethod->getId()) {
            throw new NoSuchEntityException(__('DeliveryMethod with id "%1" does not exist.', $deliveryMethodId));
        }
        return $deliveryMethod->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->deliveryMethodCollectionFactory->create();
        
        $this->extensionAttributesJoinProcessor->process(
            $collection,
            \Elevate\Delivery\Api\Data\DeliveryMethodInterface::class
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
        \Elevate\Delivery\Api\Data\DeliveryMethodInterface $deliveryMethod
    ) {
        try {
            $deliveryMethodModel = $this->deliveryMethodFactory->create();
            $this->resource->load($deliveryMethodModel, $deliveryMethod->getDeliverymethodId());
            $this->resource->delete($deliveryMethodModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the DeliveryMethod: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($deliveryMethodId)
    {
        return $this->delete($this->getById($deliveryMethodId));
    }

    /**
     * {@inheritdoc}
     */
    public function create() {
        return $this->metadata->getNewInstance();
    }
}
