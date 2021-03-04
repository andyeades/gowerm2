<?php


namespace Elevate\Delivery\Model;

use Elevate\Delivery\Api\DeliveryRulesCombinationsRepositoryInterface;
use Elevate\Delivery\Api\Data\DeliveryRulesCombinationsSearchResultsInterfaceFactory;
use Elevate\Delivery\Api\Data\DeliveryRulesCombinationsInterfaceFactory;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Elevate\Delivery\Model\ResourceModel\DeliveryRulesCombinations as ResourceDeliveryRulesCombinations;
use Elevate\Delivery\Model\ResourceModel\DeliveryRulesCombinations\CollectionFactory as DeliveryRulesCombinationsCollectionFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\Api\ExtensibleDataObjectConverter;

class DeliveryRulesCombinationsRepository implements DeliveryRulesCombinationsRepositoryInterface
{

    protected $resource;

    protected $deliveryRulesCombinationsFactory;

    protected $deliveryRulesCombinationsCollectionFactory;

    protected $searchResultsFactory;

    protected $dataObjectHelper;

    protected $dataObjectProcessor;

    protected $dataDeliveryRulesCombinationsFactory;

    protected $extensionAttributesJoinProcessor;

    private $storeManager;

    private $collectionProcessor;

    protected $extensibleDataObjectConverter;

    /**
     * @param ResourceDeliveryRulesCombinations $resource
     * @param DeliveryRulesCombinationsFactory $deliveryRulesCombinationsFactory
     * @param DeliveryRulesCombinationsInterfaceFactory $dataDeliveryRulesCombinationsFactory
     * @param DeliveryRulesCombinationsCollectionFactory $deliveryRulesCombinationsCollectionFactory
     * @param DeliveryRulesCombinationsSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     * @param CollectionProcessorInterface $collectionProcessor
     * @param JoinProcessorInterface $extensionAttributesJoinProcessor
     * @param ExtensibleDataObjectConverter $extensibleDataObjectConverter
     */
    public function __construct(
        ResourceDeliveryRulesCombinations $resource,
        DeliveryRulesCombinationsFactory $deliveryRulesCombinationsFactory,
        DeliveryRulesCombinationsInterfaceFactory $dataDeliveryRulesCombinationsFactory,
        DeliveryRulesCombinationsCollectionFactory $deliveryRulesCombinationsCollectionFactory,
        DeliveryRulesCombinationsSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager,
        CollectionProcessorInterface $collectionProcessor,
        JoinProcessorInterface $extensionAttributesJoinProcessor,
        ExtensibleDataObjectConverter $extensibleDataObjectConverter
    ) {
        $this->resource = $resource;
        $this->deliveryRulesCombinationsFactory = $deliveryRulesCombinationsFactory;
        $this->deliveryRulesCombinationsCollectionFactory = $deliveryRulesCombinationsCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataDeliveryRulesCombinationsFactory = $dataDeliveryRulesCombinationsFactory;
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
        \Elevate\Delivery\Api\Data\DeliveryRulesCombinationsInterface $deliveryRulesCombinations
    ) {
        /* if (empty($deliveryRulesCombinations->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $deliveryRulesCombinations->setStoreId($storeId);
        } */

        $deliveryRulesCombinationsData = $this->extensibleDataObjectConverter->toNestedArray(
            $deliveryRulesCombinations,
            [],
            \Elevate\Delivery\Api\Data\DeliveryRulesCombinationsInterface::class
        );

        $deliveryRulesCombinationsModel = $this->deliveryRulesCombinationsFactory->create()->setData($deliveryRulesCombinationsData);

        try {
            $this->resource->save($deliveryRulesCombinationsModel);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the deliveryRulesCombinations: %1',
                $exception->getMessage()
            ));
        }
        return $deliveryRulesCombinationsModel->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function getById($deliveryRulesCombinationsId)
    {
        $deliveryRulesCombinations = $this->deliveryRulesCombinationsFactory->create();
        $this->resource->load($deliveryRulesCombinations, $deliveryRulesCombinationsId);
        if (!$deliveryRulesCombinations->getId()) {
            throw new NoSuchEntityException(__('DeliveryRulesCombinations with id "%1" does not exist.', $deliveryRulesCombinationsId));
        }
        return $deliveryRulesCombinations->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->deliveryRulesCombinationsCollectionFactory->create();

        $this->extensionAttributesJoinProcessor->process(
            $collection,
            \Elevate\Delivery\Api\Data\DeliveryRulesCombinationsInterface::class
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
        \Elevate\Delivery\Api\Data\DeliveryRulesCombinationsInterface $deliveryRulesCombinations
    ) {
        try {
            $deliveryRulesCombinationsModel = $this->deliveryRulesCombinationsFactory->create();
            $this->resource->load($deliveryRulesCombinationsModel, $deliveryRulesCombinations->getDeliverymethodId());
            $this->resource->delete($deliveryRulesCombinationsModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the DeliveryRulesCombinations: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($deliveryRulesCombinationsCombinationsId)
    {
        return $this->delete($this->getById($deliveryRulesCombinationsCombinationsId));
    }

    /**
     * {@inheritdoc}
     */
    public function create() {
        return $this->metadata->getNewInstance();

    }
}
