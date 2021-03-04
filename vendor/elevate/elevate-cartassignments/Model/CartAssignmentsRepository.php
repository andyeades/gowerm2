<?php


namespace Elevate\CartAssignments\Model;

use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Api\ExtensibleDataObjectConverter;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Api\DataObjectHelper;
use Elevate\CartAssignments\Api\Data\CartAssignmentsSearchResultsInterfaceFactory;
use Magento\Framework\Exception\CouldNotDeleteException;
use Elevate\CartAssignments\Model\ResourceModel\CartAssignments as ResourceCartAssignments;
use Magento\Framework\Reflection\DataObjectProcessor;
use Elevate\CartAssignments\Model\ResourceModel\CartAssignments\CollectionFactory as CartAssignmentsCollectionFactory;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Elevate\CartAssignments\Api\CartAssignmentsRepositoryInterface;
use Elevate\CartAssignments\Api\Data\CartAssignmentsInterfaceFactory;

/**
 * Class CartAssignmentsRepository
 *
 * @package Elevate\CartAssignments\Model
 */
class CartAssignmentsRepository implements CartAssignmentsRepositoryInterface
{

    protected $searchResultsFactory;

    protected $dataObjectHelper;

    protected $cartAssignmentsFactory;

    protected $cartAssignmentsCollectionFactory;

    protected $dataObjectProcessor;

    protected $dataCartAssignmentsFactory;

    protected $extensionAttributesJoinProcessor;

    private $collectionProcessor;

    protected $resource;

    private $storeManager;

    protected $extensibleDataObjectConverter;

    /**
     * @param ResourceCartAssignments $resource
     * @param CartAssignmentsFactory $cartAssignmentsFactory
     * @param CartAssignmentsInterfaceFactory $dataCartAssignmentsFactory
     * @param CartAssignmentsCollectionFactory $cartAssignmentsCollectionFactory
     * @param CartAssignmentsSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     * @param CollectionProcessorInterface $collectionProcessor
     * @param JoinProcessorInterface $extensionAttributesJoinProcessor
     * @param ExtensibleDataObjectConverter $extensibleDataObjectConverter
     */
    public function __construct(
        ResourceCartAssignments $resource,
        CartAssignmentsFactory $cartAssignmentsFactory,
        CartAssignmentsInterfaceFactory $dataCartAssignmentsFactory,
        CartAssignmentsCollectionFactory $cartAssignmentsCollectionFactory,
        CartAssignmentsSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager,
        CollectionProcessorInterface $collectionProcessor,
        JoinProcessorInterface $extensionAttributesJoinProcessor,
        ExtensibleDataObjectConverter $extensibleDataObjectConverter
    ) {
        $this->resource = $resource;
        $this->cartAssignmentsFactory = $cartAssignmentsFactory;
        $this->cartAssignmentsCollectionFactory = $cartAssignmentsCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataCartAssignmentsFactory = $dataCartAssignmentsFactory;
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
        \Elevate\CartAssignments\Api\Data\CartAssignmentsInterface $cartAssignments
    ) {
        /* if (empty($cartAssignments->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $cartAssignments->setStoreId($storeId);
        } */
        
        $cartAssignmentsData = $this->extensibleDataObjectConverter->toNestedArray(
            $cartAssignments,
            [],
            \Elevate\CartAssignments\Api\Data\CartAssignmentsInterface::class
        );
        
        $cartAssignmentsModel = $this->cartAssignmentsFactory->create()->setData($cartAssignmentsData);
        
        try {
            $this->resource->save($cartAssignmentsModel);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the cartAssignments: %1',
                $exception->getMessage()
            ));
        }
        return $cartAssignmentsModel->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function get($cartAssignmentsId)
    {
        $cartAssignments = $this->cartAssignmentsFactory->create();
        $this->resource->load($cartAssignments, $cartAssignmentsId);
        if (!$cartAssignments->getId()) {
            throw new NoSuchEntityException(__('CartAssignments with id "%1" does not exist.', $cartAssignmentsId));
        }
        return $cartAssignments->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->cartAssignmentsCollectionFactory->create();
        
        $this->extensionAttributesJoinProcessor->process(
            $collection,
            \Elevate\CartAssignments\Api\Data\CartAssignmentsInterface::class
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
        \Elevate\CartAssignments\Api\Data\CartAssignmentsInterface $cartAssignments
    ) {
        try {
            $cartAssignmentsModel = $this->cartAssignmentsFactory->create();
            $this->resource->load($cartAssignmentsModel, $cartAssignments->getCartassignmentsId());
            $this->resource->delete($cartAssignmentsModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the CartAssignments: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($cartAssignmentsId)
    {
        return $this->delete($this->get($cartAssignmentsId));
    }
}

