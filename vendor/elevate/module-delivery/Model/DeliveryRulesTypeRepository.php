<?php


namespace Elevate\Delivery\Model;

use Elevate\Delivery\Api\DeliveryRulesTypeRepositoryInterface;
use Elevate\Delivery\Api\Data\DeliveryRulesTypeSearchResultsInterfaceFactory;
use Elevate\Delivery\Api\Data\DeliveryRulesTypeInterfaceFactory;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Elevate\Delivery\Model\ResourceModel\DeliveryRulesType as ResourceDeliveryRulesType;
use Elevate\Delivery\Model\ResourceModel\DeliveryRulesType\CollectionFactory as DeliveryRulesTypeCollectionFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\Api\ExtensibleDataObjectConverter;

class DeliveryRulesTypeRepository implements DeliveryRulesTypeRepositoryInterface
{

    protected $resource;

    protected $deliveryRulesTypeFactory;

    protected $deliveryRulesTypeCollectionFactory;

    protected $searchResultsFactory;

    protected $dataObjectHelper;

    protected $dataObjectProcessor;

    protected $dataDeliveryRulesTypeFactory;

    protected $extensionAttributesJoinProcessor;

    private $storeManager;

    private $collectionProcessor;

    protected $extensibleDataObjectConverter;

    /**
     * @param ResourceDeliveryRulesType $resource
     * @param DeliveryRulesTypeFactory $deliveryRulesTypeFactory
     * @param DeliveryRulesTypeInterfaceFactory $dataDeliveryRulesTypeFactory
     * @param DeliveryRulesTypeCollectionFactory $deliveryRulesTypeCollectionFactory
     * @param DeliveryRulesTypeSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     * @param CollectionProcessorInterface $collectionProcessor
     * @param JoinProcessorInterface $extensionAttributesJoinProcessor
     * @param ExtensibleDataObjectConverter $extensibleDataObjectConverter
     */
    public function __construct(
        ResourceDeliveryRulesType $resource,
        DeliveryRulesTypeFactory $deliveryRulesTypeFactory,
        DeliveryRulesTypeInterfaceFactory $dataDeliveryRulesTypeFactory,
        DeliveryRulesTypeCollectionFactory $deliveryRulesTypeCollectionFactory,
        DeliveryRulesTypeSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager,
        CollectionProcessorInterface $collectionProcessor,
        JoinProcessorInterface $extensionAttributesJoinProcessor,
        ExtensibleDataObjectConverter $extensibleDataObjectConverter
    ) {
        $this->resource = $resource;
        $this->deliveryRulesTypeFactory = $deliveryRulesTypeFactory;
        $this->deliveryRulesTypeCollectionFactory = $deliveryRulesTypeCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataDeliveryRulesTypeFactory = $dataDeliveryRulesTypeFactory;
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
        \Elevate\Delivery\Api\Data\DeliveryRulesTypeInterface $deliveryRulesType
    ) {
        /* if (empty($deliveryRulesType->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $deliveryRulesType->setStoreId($storeId);
        } */
        
        $deliveryRulesTypeData = $this->extensibleDataObjectConverter->toNestedArray(
            $deliveryRulesType,
            [],
            \Elevate\Delivery\Api\Data\DeliveryRulesTypeInterface::class
        );
        
        $deliveryRulesTypeModel = $this->deliveryRulesTypeFactory->create()->setData($deliveryRulesTypeData);
        
        try {
            $this->resource->save($deliveryRulesTypeModel);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the deliveryRulesType: %1',
                $exception->getMessage()
            ));
        }
        return $deliveryRulesTypeModel->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function getById($deliveryRulesTypeId)
    {
        $deliveryRulesType = $this->deliveryRulesTypeFactory->create();
        $this->resource->load($deliveryRulesType, $deliveryRulesTypeId);
        if (!$deliveryRulesType->getId()) {
            throw new NoSuchEntityException(__('DeliveryRulesType with id "%1" does not exist.', $deliveryRulesTypeId));
        }
        return $deliveryRulesType->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->deliveryRulesTypeCollectionFactory->create();
        
        $this->extensionAttributesJoinProcessor->process(
            $collection,
            \Elevate\Delivery\Api\Data\DeliveryRulesTypeInterface::class
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
        \Elevate\Delivery\Api\Data\DeliveryRulesTypeInterface $deliveryRulesType
    ) {
        try {
            $deliveryRulesTypeModel = $this->deliveryRulesTypeFactory->create();
            $this->resource->load($deliveryRulesTypeModel, $deliveryRulesType->getDeliverymethodId());
            $this->resource->delete($deliveryRulesTypeModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the DeliveryRule: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($deliveryRulesTypeId)
    {
        return $this->delete($this->getById($deliveryRulesTypeId));
    }

    /**
     * {@inheritdoc}
     */
    public function create() {
        return $this->metadata->getNewInstance();
    }
}
