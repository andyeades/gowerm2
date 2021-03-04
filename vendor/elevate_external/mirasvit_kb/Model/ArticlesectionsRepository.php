<?php

namespace Mirasvit\Kb\Model;

use Mirasvit\Kb\Api\ArticlesectionsRepositoryInterface;
use Mirasvit\Kb\Api\Data\ArticlesectionsSearchResultsInterfaceFactory;
use Mirasvit\Kb\Api\Data\ArticlesectionsInterfaceFactory;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Mirasvit\Kb\Model\ResourceModel\Articlesections as ResourceArticlesections;
use Mirasvit\Kb\Model\ResourceModel\Articlesections\CollectionFactory as ArticlesectionsCollectionFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\Api\ExtensibleDataObjectConverter;
use Mirasvit\Kb\Model\ResourceModel\Metadata;

class ArticlesectionsRepository implements ArticlesectionsRepositoryInterface {

    protected $resource;

    protected $articlesectionsFactory;

    protected $articlesectionsCollectionFactory;

    protected $searchResultsFactory;

    protected $dataObjectHelper;

    protected $dataObjectProcessor;

    protected $dataArticlesectionsFactory;

    protected $extensionAttributesJoinProcessor;

    private $storeManager;

    private $collectionProcessor;

    protected $extensibleDataObjectConverter;

    /**
     * @var Metadata
     */
    private $metadata;

    /**
     * @param ResourceArticlesections                      $resource
     * @param ArticlesectionsFactory                       $articlesectionsFactory
     * @param ArticlesectionsInterfaceFactory              $dataArticlesectionsFactory
     * @param ArticlesectionsCollectionFactory             $articlesectionsCollectionFactory
     * @param ArticlesectionsSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper                             $dataObjectHelper
     * @param DataObjectProcessor                          $dataObjectProcessor
     * @param StoreManagerInterface                        $storeManager
     * @param CollectionProcessorInterface                 $collectionProcessor
     * @param JoinProcessorInterface                       $extensionAttributesJoinProcessor
     * @param ExtensibleDataObjectConverter                $extensibleDataObjectConverter
     * @param Metadata                                     $articlesectionsMetadata
     */
    public function __construct(
        ResourceArticlesections $resource,
        ArticlesectionsFactory $articlesectionsFactory,
        ArticlesectionsInterfaceFactory $dataArticlesectionsFactory,
        ArticlesectionsCollectionFactory $articlesectionsCollectionFactory,
        ArticlesectionsSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager,
        CollectionProcessorInterface $collectionProcessor,
        JoinProcessorInterface $extensionAttributesJoinProcessor,
        ExtensibleDataObjectConverter $extensibleDataObjectConverter,
        Metadata $articlesectionsMetadata
    ) {
        $this->resource = $resource;
        $this->articlesectionsFactory = $articlesectionsFactory;
        $this->articlesectionsCollectionFactory = $articlesectionsCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataArticlesectionsFactory = $dataArticlesectionsFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
        $this->collectionProcessor = $collectionProcessor;
        $this->extensionAttributesJoinProcessor = $extensionAttributesJoinProcessor;
        $this->extensibleDataObjectConverter = $extensibleDataObjectConverter;
        $this->metadata = $articlesectionsMetadata;
    }

    /**
     * {@inheritdoc}
     */
    public function save(
        \Mirasvit\Kb\Api\Data\ArticlesectionsInterface $articlesections
    ) {

        $articlesectionsData = $this->extensibleDataObjectConverter->toNestedArray(
            $articlesections, [], \Mirasvit\Kb\Api\Data\ArticlesectionsInterface::class
        );

        $articlesectionsModel = $this->articlesectionsFactory->create()->setData($articlesectionsData);

        try {
            $this->resource->save($articlesectionsModel);
        } catch(\Exception $exception) {
            throw new CouldNotSaveException(
                __(
                    'Could not save the article section: %1', $exception->getMessage()
                )
            );
        }

        return $articlesectionsModel->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function getById($articlesectionsId) {
        $articlesections = $this->articlesectionsFactory->create();
        $this->resource->load($articlesections, $articlesectionsId);
        if (!$articlesections->getId()) {
            throw new NoSuchEntityException(__('Article section with id "%1" does not exist.', $articlesectionsId));
        }

        return $articlesections->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->articlesectionsCollectionFactory->create();

        $this->extensionAttributesJoinProcessor->process(
            $collection, \Mirasvit\Kb\Api\Data\ArticlesectionsInterface::class
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
        \Mirasvit\Kb\Api\Data\ArticlesectionsInterface $articlesections
    ) {
        try {
            $articlesectionsModel = $this->articlesectionsFactory->create();
            $this->resource->load($articlesectionsModel, $articlesections->getArticlesectionsId());
            $this->resource->delete($articlesectionsModel);
        } catch(\Exception $exception) {
            throw new CouldNotDeleteException(
                __(
                    'Could not delete the Article Section: %1', $exception->getMessage()
                )
            );
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($articlesectionsId) {
        return $this->delete($this->getById($articlesectionsId));
    }

    /**
     * {@inheritdoc}
     */
    public function create() {
        return $this->metadata->getNewInstance();
    }
}
