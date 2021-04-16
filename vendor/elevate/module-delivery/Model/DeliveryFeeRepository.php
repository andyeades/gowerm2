<?php

namespace Elevate\Delivery\Model;

use Elevate\Delivery\Api\Data\DeliveryFeeInterfaceFactory;
use Elevate\Delivery\Api\Data\DeliveryFeeSearchResultsInterfaceFactory;
use Elevate\Delivery\Api\DeliveryFeeRepositoryInterface;
use Elevate\Delivery\Model\ResourceModel\DeliveryFee as ResourceDeliveryFee;
use Elevate\Delivery\Model\ResourceModel\DeliveryFee\CollectionFactory as DeliveryFeeCollectionFactory;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\ExtensibleDataObjectConverter;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Store\Model\StoreManagerInterface;

class DeliveryFeeRepository implements DeliveryFeeRepositoryInterface
{
    protected $resource;

    protected $deliveryFeeFactory;

    protected $deliveryFeeCollectionFactory;

    protected $searchResultsFactory;

    protected $dataObjectHelper;

    protected $dataObjectProcessor;

    protected $dataDeliveryFeeFactory;

    protected $extensionAttributesJoinProcessor;

    private $storeManager;

    private $collectionProcessor;

    protected $extensibleDataObjectConverter;

    /**
     * @param ResourceDeliveryFee $resource
     * @param DeliveryFeeFactory $deliveryFeeFactory
     * @param DeliveryFeeInterfaceFactory $dataDeliveryFeeFactory
     * @param DeliveryFeeCollectionFactory $deliveryFeeCollectionFactory
     * @param DeliveryFeeSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     * @param CollectionProcessorInterface $collectionProcessor
     * @param JoinProcessorInterface $extensionAttributesJoinProcessor
     * @param ExtensibleDataObjectConverter $extensibleDataObjectConverter
     */
    public function __construct(
        ResourceDeliveryFee $resource,
        DeliveryFeeFactory $deliveryFeeFactory,
        DeliveryFeeInterfaceFactory $dataDeliveryFeeFactory,
        DeliveryFeeCollectionFactory $deliveryFeeCollectionFactory,
        DeliveryFeeSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager,
        CollectionProcessorInterface $collectionProcessor,
        JoinProcessorInterface $extensionAttributesJoinProcessor,
        ExtensibleDataObjectConverter $extensibleDataObjectConverter
    ) {
        $this->resource = $resource;
        $this->deliveryFeeFactory = $deliveryFeeFactory;
        $this->deliveryFeeCollectionFactory = $deliveryFeeCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataDeliveryFeeFactory = $dataDeliveryFeeFactory;
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
        \Elevate\Delivery\Api\Data\DeliveryFeeInterface $deliveryFee
    ) {
        /* if (empty($deliveryFee->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $deliveryFee->setStoreId($storeId);
        } */

        $deliveryFeeData = $this->extensibleDataObjectConverter->toNestedArray(
            $deliveryFee,
            [],
            \Elevate\Delivery\Api\Data\DeliveryFeeInterface::class
        );

        $deliveryFeeModel = $this->deliveryFeeFactory->create()->setData($deliveryFeeData);

        try {
            $this->resource->save($deliveryFeeModel);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the deliveryFee: %1',
                $exception->getMessage()
            ));
        }
        return $deliveryFeeModel->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function getById($deliveryFeeId)
    {
        $deliveryFee = $this->deliveryFeeFactory->create();
        $this->resource->load($deliveryFee, $deliveryFeeId);
        if (!$deliveryFee->getId()) {
            throw new NoSuchEntityException(__('DeliveryFee with id "%1" does not exist.', $deliveryFeeId));
        }
        return $deliveryFee->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->deliveryFeeCollectionFactory->create();

        $this->extensionAttributesJoinProcessor->process(
            $collection,
            \Elevate\Delivery\Api\Data\DeliveryFeeInterface::class
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
        \Elevate\Delivery\Api\Data\DeliveryFeeInterface $deliveryFee
    ) {
        try {
            $deliveryFeeModel = $this->deliveryFeeFactory->create();
            $this->resource->load($deliveryFeeModel, $deliveryFee->getDeliveryfeeId());
            $this->resource->delete($deliveryFeeModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the DeliveryFee: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($deliveryFeeId)
    {
        return $this->delete($this->getById($deliveryFeeId));
    }

    /**
     * {@inheritdoc}
     */
    public function create()
    {
        return $this->metadata->getNewInstance();
    }
}
