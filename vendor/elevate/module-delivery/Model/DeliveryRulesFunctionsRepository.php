<?php


namespace Elevate\Delivery\Model;

use Elevate\Delivery\Api\DeliveryRulesFunctionsRepositoryInterface;
use Elevate\Delivery\Api\Data\DeliveryRulesFunctionsSearchResultsInterfaceFactory;
use Elevate\Delivery\Api\Data\DeliveryRulesFunctionsInterfaceFactory;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Elevate\Delivery\Model\ResourceModel\DeliveryRulesFunctions as ResourceDeliveryRulesFunctions;
use Elevate\Delivery\Model\ResourceModel\DeliveryRulesFunctions\CollectionFactory as DeliveryRulesFunctionsCollectionFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\Api\ExtensibleDataObjectConverter;

class DeliveryRulesFunctionsRepository implements DeliveryRulesFunctionsRepositoryInterface
{

    protected $resource;

    protected $deliveryRulesFunctionsFactory;

    protected $deliveryRulesFunctionsCollectionFactory;

    protected $searchResultsFactory;

    protected $dataObjectHelper;

    protected $dataObjectProcessor;

    protected $dataDeliveryRulesFunctionsFactory;

    protected $extensionAttributesJoinProcessor;

    private $storeManager;

    private $collectionProcessor;

    protected $extensibleDataObjectConverter;

    /**
     * @param ResourceDeliveryRulesFunctions $resource
     * @param DeliveryRulesFunctionsFactory $deliveryRulesFunctionsFactory
     * @param DeliveryRulesFunctionsInterfaceFactory $dataDeliveryRulesFunctionsFactory
     * @param DeliveryRulesFunctionsCollectionFactory $deliveryRulesFunctionsCollectionFactory
     * @param DeliveryRulesFunctionsSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     * @param CollectionProcessorInterface $collectionProcessor
     * @param JoinProcessorInterface $extensionAttributesJoinProcessor
     * @param ExtensibleDataObjectConverter $extensibleDataObjectConverter
     */
    public function __construct(
        ResourceDeliveryRulesFunctions $resource,
        DeliveryRulesFunctionsFactory $deliveryRulesFunctionsFactory,
        DeliveryRulesFunctionsInterfaceFactory $dataDeliveryRulesFunctionsFactory,
        DeliveryRulesFunctionsCollectionFactory $deliveryRulesFunctionsCollectionFactory,
        DeliveryRulesFunctionsSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager,
        CollectionProcessorInterface $collectionProcessor,
        JoinProcessorInterface $extensionAttributesJoinProcessor,
        ExtensibleDataObjectConverter $extensibleDataObjectConverter
    ) {
        $this->resource = $resource;
        $this->deliveryRulesFunctionsFactory = $deliveryRulesFunctionsFactory;
        $this->deliveryRulesFunctionsCollectionFactory = $deliveryRulesFunctionsCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataDeliveryRulesFunctionsFactory = $dataDeliveryRulesFunctionsFactory;
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
        \Elevate\Delivery\Api\Data\DeliveryRulesFunctionsInterface $deliveryRulesFunctions
    ) {
        /* if (empty($deliveryRulesFunctions->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $deliveryRulesFunctions->setStoreId($storeId);
        } */
        
        $deliveryRulesFunctionsData = $this->extensibleDataObjectConverter->toNestedArray(
            $deliveryRulesFunctions,
            [],
            \Elevate\Delivery\Api\Data\DeliveryRulesFunctionsInterface::class
        );
        
        $deliveryRulesFunctionsModel = $this->deliveryRulesFunctionsFactory->create()->setData($deliveryRulesFunctionsData);
        
        try {
            $this->resource->save($deliveryRulesFunctionsModel);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the deliveryRulesFunctions: %1',
                $exception->getMessage()
            ));
        }
        return $deliveryRulesFunctionsModel->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function getById($deliveryRulesFunctionsId)
    {
        $deliveryRulesFunctions = $this->deliveryRulesFunctionsFactory->create();
        $this->resource->load($deliveryRulesFunctions, $deliveryRulesFunctionsId);
        if (!$deliveryRulesFunctions->getId()) {
            throw new NoSuchEntityException(__('DeliveryRulesFunctions with id "%1" does not exist.', $deliveryRulesFunctionsId));
        }
        return $deliveryRulesFunctions->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->deliveryRulesFunctionsCollectionFactory->create();
        
        $this->extensionAttributesJoinProcessor->process(
            $collection,
            \Elevate\Delivery\Api\Data\DeliveryRulesFunctionsInterface::class
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
        \Elevate\Delivery\Api\Data\DeliveryRulesFunctionsInterface $deliveryRulesFunctions
    ) {
        try {
            $deliveryRulesFunctionsModel = $this->deliveryRulesFunctionsFactory->create();
            $this->resource->load($deliveryRulesFunctionsModel, $deliveryRulesFunctions->getDeliverymethodId());
            $this->resource->delete($deliveryRulesFunctionsModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the DeliveryRulesFunctions: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($deliveryRulesFunctionsId)
    {
        return $this->delete($this->getById($deliveryRulesFunctionsId));
    }

    /**
     * {@inheritdoc}
     */
    public function create() {
        return $this->metadata->getNewInstance();

    }
}
