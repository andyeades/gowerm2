<?php


namespace Elevate\CartAssignments\Model;

use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Elevate\CartAssignments\Api\Data\QuoteItemAssignmentsInterfaceFactory;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Api\ExtensibleDataObjectConverter;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Api\DataObjectHelper;
use Elevate\CartAssignments\Model\ResourceModel\QuoteItemAssignments\CollectionFactory as QuoteItemAssignmentsCollectionFactory;
use Magento\Framework\Exception\CouldNotDeleteException;
use Elevate\CartAssignments\Model\ResourceModel\QuoteItemAssignments as ResourceQuoteItemAssignments;
use Elevate\CartAssignments\Api\QuoteItemAssignmentsRepositoryInterface;
use Elevate\CartAssignments\Api\Data\QuoteItemAssignmentsSearchResultsInterfaceFactory;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;

/**
 * Class QuoteItemAssignmentsRepository
 *
 * @package Elevate\CartAssignments\Model
 */
class QuoteItemAssignmentsRepository implements QuoteItemAssignmentsRepositoryInterface
{

    protected $searchResultsFactory;

    protected $dataObjectHelper;

    protected $dataObjectProcessor;

    protected $quoteItemAssignmentsFactory;

    protected $extensionAttributesJoinProcessor;

    protected $dataQuoteItemAssignmentsFactory;

    private $collectionProcessor;

    protected $resource;

    protected $quoteItemAssignmentsCollectionFactory;

    private $storeManager;

    protected $extensibleDataObjectConverter;

    /**
     * @param ResourceQuoteItemAssignments $resource
     * @param QuoteItemAssignmentsFactory $quoteItemAssignmentsFactory
     * @param QuoteItemAssignmentsInterfaceFactory $dataQuoteItemAssignmentsFactory
     * @param QuoteItemAssignmentsCollectionFactory $quoteItemAssignmentsCollectionFactory
     * @param QuoteItemAssignmentsSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     * @param CollectionProcessorInterface $collectionProcessor
     * @param JoinProcessorInterface $extensionAttributesJoinProcessor
     * @param ExtensibleDataObjectConverter $extensibleDataObjectConverter
     */
    public function __construct(
        ResourceQuoteItemAssignments $resource,
        QuoteItemAssignmentsFactory $quoteItemAssignmentsFactory,
        QuoteItemAssignmentsInterfaceFactory $dataQuoteItemAssignmentsFactory,
        QuoteItemAssignmentsCollectionFactory $quoteItemAssignmentsCollectionFactory,
        QuoteItemAssignmentsSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager,
        CollectionProcessorInterface $collectionProcessor,
        JoinProcessorInterface $extensionAttributesJoinProcessor,
        ExtensibleDataObjectConverter $extensibleDataObjectConverter
    ) {
        $this->resource = $resource;
        $this->quoteItemAssignmentsFactory = $quoteItemAssignmentsFactory;
        $this->quoteItemAssignmentsCollectionFactory = $quoteItemAssignmentsCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataQuoteItemAssignmentsFactory = $dataQuoteItemAssignmentsFactory;
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
        \Elevate\CartAssignments\Api\Data\QuoteItemAssignmentsInterface $quoteItemAssignments
    ) {
        /* if (empty($quoteItemAssignments->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $quoteItemAssignments->setStoreId($storeId);
        } */
        
        $quoteItemAssignmentsData = $this->extensibleDataObjectConverter->toNestedArray(
            $quoteItemAssignments,
            [],
            \Elevate\CartAssignments\Api\Data\QuoteItemAssignmentsInterface::class
        );
        
        $quoteItemAssignmentsModel = $this->quoteItemAssignmentsFactory->create()->setData($quoteItemAssignmentsData);
        
        try {
            $this->resource->save($quoteItemAssignmentsModel);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the quoteItemAssignments: %1',
                $exception->getMessage()
            ));
        }
        return $quoteItemAssignmentsModel->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function get($quoteItemAssignmentsId)
    {
        $quoteItemAssignments = $this->quoteItemAssignmentsFactory->create();
        $this->resource->load($quoteItemAssignments, $quoteItemAssignmentsId);
        if (!$quoteItemAssignments->getId()) {
            throw new NoSuchEntityException(__('QuoteItemAssignments with id "%1" does not exist.', $quoteItemAssignmentsId));
        }
        return $quoteItemAssignments->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->quoteItemAssignmentsCollectionFactory->create();
        
        $this->extensionAttributesJoinProcessor->process(
            $collection,
            \Elevate\CartAssignments\Api\Data\QuoteItemAssignmentsInterface::class
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
        \Elevate\CartAssignments\Api\Data\QuoteItemAssignmentsInterface $quoteItemAssignments
    ) {
        try {
            $quoteItemAssignmentsModel = $this->quoteItemAssignmentsFactory->create();
            $this->resource->load($quoteItemAssignmentsModel, $quoteItemAssignments->getQuoteitemassignmentsId());
            $this->resource->delete($quoteItemAssignmentsModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the QuoteItemAssignments: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($quoteItemAssignmentsId)
    {
        return $this->delete($this->get($quoteItemAssignmentsId));
    }
}

