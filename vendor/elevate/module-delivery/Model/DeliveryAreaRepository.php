<?php


namespace Elevate\Delivery\Model;

use Elevate\Delivery\Api\DeliveryAreaRepositoryInterface;
use Elevate\Delivery\Api\Data\DeliveryAreaSearchResultsInterfaceFactory;
use Elevate\Delivery\Api\Data\DeliveryAreaInterfaceFactory;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Elevate\Delivery\Model\ResourceModel\DeliveryArea as ResourceDeliveryArea;
use Elevate\Delivery\Model\ResourceModel\DeliveryArea\CollectionFactory as DeliveryAreaCollectionFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\Api\ExtensibleDataObjectConverter;

class DeliveryAreaRepository implements DeliveryAreaRepositoryInterface
{

    protected $resource;

    protected $deliveryAreaFactory;

    protected $deliveryAreaCollectionFactory;

    protected $searchResultsFactory;

    protected $dataObjectHelper;

    protected $dataObjectProcessor;

    protected $dataDeliveryAreaFactory;

    protected $extensionAttributesJoinProcessor;

    private $storeManager;

    private $collectionProcessor;

    protected $extensibleDataObjectConverter;

    /**
     * @param ResourceDeliveryArea $resource
     * @param DeliveryAreaFactory $deliveryAreaFactory
     * @param DeliveryAreaInterfaceFactory $dataDeliveryAreaFactory
     * @param DeliveryAreaCollectionFactory $deliveryAreaCollectionFactory
     * @param DeliveryAreaSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     * @param CollectionProcessorInterface $collectionProcessor
     * @param JoinProcessorInterface $extensionAttributesJoinProcessor
     * @param ExtensibleDataObjectConverter $extensibleDataObjectConverter
     */
    public function __construct(
        ResourceDeliveryArea $resource,
        DeliveryAreaFactory $deliveryAreaFactory,
        DeliveryAreaInterfaceFactory $dataDeliveryAreaFactory,
        DeliveryAreaCollectionFactory $deliveryAreaCollectionFactory,
        DeliveryAreaSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager,
        CollectionProcessorInterface $collectionProcessor,
        JoinProcessorInterface $extensionAttributesJoinProcessor,
        ExtensibleDataObjectConverter $extensibleDataObjectConverter
    ) {
        $this->resource = $resource;
        $this->deliveryAreaFactory = $deliveryAreaFactory;
        $this->deliveryAreaCollectionFactory = $deliveryAreaCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataDeliveryAreaFactory = $dataDeliveryAreaFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
        $this->collectionProcessor = $collectionProcessor;
        $this->extensionAttributesJoinProcessor = $extensionAttributesJoinProcessor;
        $this->extensibleDataObjectConverter = $extensibleDataObjectConverter;
    }

    /**
     * {@inheritdoc}
     */
    public function create() {
        return $this->metadata->getNewInstance();

    }
    /**
     * {@inheritdoc}
     */
    public function save(
        \Elevate\Delivery\Api\Data\DeliveryAreaInterface $deliveryArea
    ) {
        /* if (empty($deliveryArea->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $deliveryArea->setStoreId($storeId);
        } */
        
        $deliveryAreaData = $this->extensibleDataObjectConverter->toNestedArray(
            $deliveryArea,
            [],
            \Elevate\Delivery\Api\Data\DeliveryAreaInterface::class
        );
        
        $deliveryAreaModel = $this->deliveryAreaFactory->create()->setData($deliveryAreaData);
        
        try {
            $this->resource->save($deliveryAreaModel);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the deliveryArea: %1',
                $exception->getMessage()
            ));
        }
        return $deliveryAreaModel->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function getById($deliveryAreaId)
    {
        $deliveryArea = $this->deliveryAreaFactory->create();
        $this->resource->load($deliveryArea, $deliveryAreaId);
        if (!$deliveryArea->getId()) {
            throw new NoSuchEntityException(__('DeliveryArea with id "%1" does not exist.', $deliveryAreaId));
        }
        return $deliveryArea->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->deliveryAreaCollectionFactory->create();
        
        $this->extensionAttributesJoinProcessor->process(
            $collection,
            \Elevate\Delivery\Api\Data\DeliveryAreaInterface::class
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
        \Elevate\Delivery\Api\Data\DeliveryAreaInterface $deliveryArea
    ) {
        try {
            $deliveryAreaModel = $this->deliveryAreaFactory->create();
            $this->resource->load($deliveryAreaModel, $deliveryArea->getDeliveryareaId());
            $this->resource->delete($deliveryAreaModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the DeliveryArea: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($deliveryAreaId)
    {
        return $this->delete($this->getById($deliveryAreaId));
    }
}
