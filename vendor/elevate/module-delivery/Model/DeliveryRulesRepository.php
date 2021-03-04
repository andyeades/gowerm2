<?php


namespace Elevate\Delivery\Model;

use Elevate\Delivery\Api\DeliveryRulesRepositoryInterface;
use Elevate\Delivery\Api\Data\DeliveryRulesSearchResultsInterfaceFactory;
use Elevate\Delivery\Api\Data\DeliveryRulesInterfaceFactory;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Elevate\Delivery\Model\ResourceModel\DeliveryRules as ResourceDeliveryRules;
use Elevate\Delivery\Model\ResourceModel\DeliveryRules\CollectionFactory as DeliveryRulesCollectionFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\Api\ExtensibleDataObjectConverter;

class DeliveryRulesRepository implements DeliveryRulesRepositoryInterface
{

    protected $resource;

    protected $deliveryRulesFactory;

    protected $deliveryRulesCollectionFactory;

    protected $searchResultsFactory;

    protected $dataObjectHelper;

    protected $dataObjectProcessor;

    protected $dataDeliveryRulesFactory;

    protected $extensionAttributesJoinProcessor;

    private $storeManager;

    private $collectionProcessor;

    protected $extensibleDataObjectConverter;

    /**
     * @param ResourceDeliveryRules $resource
     * @param DeliveryRulesFactory $deliveryRulesFactory
     * @param DeliveryRulesInterfaceFactory $dataDeliveryRulesFactory
     * @param DeliveryRulesCollectionFactory $deliveryRulesCollectionFactory
     * @param DeliveryRulesSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     * @param CollectionProcessorInterface $collectionProcessor
     * @param JoinProcessorInterface $extensionAttributesJoinProcessor
     * @param ExtensibleDataObjectConverter $extensibleDataObjectConverter
     */
    public function __construct(
        ResourceDeliveryRules $resource,
        DeliveryRulesFactory $deliveryRulesFactory,
        DeliveryRulesInterfaceFactory $dataDeliveryRulesFactory,
        DeliveryRulesCollectionFactory $deliveryRulesCollectionFactory,
        DeliveryRulesSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager,
        CollectionProcessorInterface $collectionProcessor,
        JoinProcessorInterface $extensionAttributesJoinProcessor,
        ExtensibleDataObjectConverter $extensibleDataObjectConverter
    ) {
        $this->resource = $resource;
        $this->deliveryRulesFactory = $deliveryRulesFactory;
        $this->deliveryRulesCollectionFactory = $deliveryRulesCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataDeliveryRulesFactory = $dataDeliveryRulesFactory;
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
        \Elevate\Delivery\Api\Data\DeliveryRulesInterface $deliveryRules
    ) {
        /* if (empty($deliveryRules->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $deliveryRules->setStoreId($storeId);
        } */
        
        $deliveryRulesData = $this->extensibleDataObjectConverter->toNestedArray(
            $deliveryRules,
            [],
            \Elevate\Delivery\Api\Data\DeliveryRulesInterface::class
        );
        
        $deliveryRulesModel = $this->deliveryRulesFactory->create()->setData($deliveryRulesData);
        
        try {
            $this->resource->save($deliveryRulesModel);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the deliveryRules: %1',
                $exception->getMessage()
            ));
        }
        return $deliveryRulesModel->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function getById($deliveryRulesId)
    {
        $deliveryRules = $this->deliveryRulesFactory->create();
        $this->resource->load($deliveryRules, $deliveryRulesId);
        if (!$deliveryRules->getId()) {
            throw new NoSuchEntityException(__('DeliveryRules with id "%1" does not exist.', $deliveryRulesId));
        }
        return $deliveryRules->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->deliveryRulesCollectionFactory->create();
        
        $this->extensionAttributesJoinProcessor->process(
            $collection,
            \Elevate\Delivery\Api\Data\DeliveryRulesInterface::class
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
        \Elevate\Delivery\Api\Data\DeliveryRulesInterface $deliveryRules
    ) {
        try {
            $deliveryRulesModel = $this->deliveryRulesFactory->create();
            $this->resource->load($deliveryRulesModel, $deliveryRules->getDeliverymethodId());
            $this->resource->delete($deliveryRulesModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the DeliveryRules: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($deliveryRulesId)
    {
        return $this->delete($this->getById($deliveryRulesId));
    }

    /**
     * {@inheritdoc}
     */
    public function create() {
        return $this->metadata->getNewInstance();

    }
}
