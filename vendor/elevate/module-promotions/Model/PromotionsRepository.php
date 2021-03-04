<?php


namespace Elevate\Promotions\Model;

use Elevate\Promotions\Model\ResourceModel\Promotions as ResourcePromotions;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Elevate\Promotions\Model\ResourceModel\Promotions\CollectionFactory as PromotionsCollectionFactory;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Framework\Api\ExtensibleDataObjectConverter;
use Elevate\Promotions\Api\Data\PromotionsSearchResultsInterfaceFactory;
use Elevate\Promotions\Api\Data\PromotionsInterfaceFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Elevate\Promotions\Api\PromotionsRepositoryInterface;

class PromotionsRepository implements PromotionsRepositoryInterface
{

    protected $dataObjectHelper;

    private $storeManager;

    protected $dataPromotionsFactory;

    protected $promotionsCollectionFactory;

    protected $searchResultsFactory;

    protected $dataObjectProcessor;

    protected $extensionAttributesJoinProcessor;

    private $collectionProcessor;

    protected $resource;

    protected $extensibleDataObjectConverter;
    protected $promotionsFactory;


    /**
     * @param ResourcePromotions $resource
     * @param PromotionsFactory $promotionsFactory
     * @param PromotionsInterfaceFactory $dataPromotionsFactory
     * @param PromotionsCollectionFactory $promotionsCollectionFactory
     * @param PromotionsSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     * @param CollectionProcessorInterface $collectionProcessor
     * @param JoinProcessorInterface $extensionAttributesJoinProcessor
     * @param ExtensibleDataObjectConverter $extensibleDataObjectConverter
     */
    public function __construct(
        ResourcePromotions $resource,
        PromotionsFactory $promotionsFactory,
        PromotionsInterfaceFactory $dataPromotionsFactory,
        PromotionsCollectionFactory $promotionsCollectionFactory,
        PromotionsSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager,
        CollectionProcessorInterface $collectionProcessor,
        JoinProcessorInterface $extensionAttributesJoinProcessor,
        ExtensibleDataObjectConverter $extensibleDataObjectConverter
    ) {
        $this->resource = $resource;
        $this->promotionsFactory = $promotionsFactory;
        $this->promotionsCollectionFactory = $promotionsCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataPromotionsFactory = $dataPromotionsFactory;
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
        \Elevate\Promotions\Api\Data\PromotionsInterface $promotions
    ) {
        /* if (empty($promotions->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $promotions->setStoreId($storeId);
        } */
        
        $promotionsData = $this->extensibleDataObjectConverter->toNestedArray(
            $promotions,
            [],
            \Elevate\Promotions\Api\Data\PromotionsInterface::class
        );
        
        $promotionsModel = $this->promotionsFactory->create()->setData($promotionsData);
        
        try {
            $this->resource->save($promotionsModel);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the promotions: %1',
                $exception->getMessage()
            ));
        }
        return $promotionsModel->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function get($promotionsId)
    {
        $promotions = $this->promotionsFactory->create();
        $this->resource->load($promotions, $promotionsId);
        if (!$promotions->getId()) {
            throw new NoSuchEntityException(__('promotions with id "%1" does not exist.', $promotionsId));
        }
        return $promotions->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->promotionsCollectionFactory->create();
        
        $this->extensionAttributesJoinProcessor->process(
            $collection,
            \Elevate\Promotions\Api\Data\PromotionsInterface::class
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
        \Elevate\Promotions\Api\Data\PromotionsInterface $promotions
    ) {
        try {
            $promotionsModel = $this->promotionsFactory->create();
            $this->resource->load($promotionsModel, $promotions->getPromotionsId());
            $this->resource->delete($promotionsModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the promotions: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($promotionsId)
    {
        return $this->delete($this->get($promotionsId));
    }
}
