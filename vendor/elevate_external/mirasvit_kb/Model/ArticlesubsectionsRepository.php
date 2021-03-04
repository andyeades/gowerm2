<?php


namespace Mirasvit\Kb\Model;

use Mirasvit\Kb\Api\ArticlesubsectionsRepositoryInterface;
use Mirasvit\Kb\Api\Data\ArticlesubsectionsSearchResultsInterfaceFactory;
use Mirasvit\Kb\Api\Data\ArticlesubsectionsInterfaceFactory;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Mirasvit\Kb\Model\ResourceModel\Articlesubsections as ResourceArticlesubsections;
use Mirasvit\Kb\Model\ResourceModel\Articlesubsections\CollectionFactory as ArticlesubsectionsCollectionFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\Api\ExtensibleDataObjectConverter;
use Mirasvit\Kb\Model\ResourceModel\Metadata;

class ArticlesubsectionsRepository implements ArticlesubsectionsRepositoryInterface
{

    protected $resource;

    protected $articlesubsectionsFactory;

    protected $articlesubsectionsCollectionFactory;

    protected $searchResultsFactory;

    protected $dataObjectHelper;

    protected $dataObjectProcessor;

    protected $dataArticlesubsectionsFactory;

    protected $extensionAttributesJoinProcessor;

    private $storeManager;

    private $collectionProcessor;

    protected $extensibleDataObjectConverter;

    /**
     * @var Metadata
     */
    private $metadata;

    /**
     * @param ResourceArticlesubsections $resource
     * @param ArticlesubsectionsFactory $articlesubsectionsFactory
     * @param ArticlesubsectionsInterfaceFactory $dataArticlesubsectionsFactory
     * @param ArticlesubsectionsCollectionFactory $articlesubsectionsCollectionFactory
     * @param ArticlesubsectionsSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     * @param CollectionProcessorInterface $collectionProcessor
     * @param JoinProcessorInterface $extensionAttributesJoinProcessor
     * @param ExtensibleDataObjectConverter $extensibleDataObjectConverter
     * @param Metadata $articlesubsectionsMetadata
     */
    public function __construct(
        ResourceArticlesubsections $resource,
        ArticlesubsectionsFactory $articlesubsectionsFactory,
        ArticlesubsectionsInterfaceFactory $dataArticlesubsectionsFactory,
        ArticlesubsectionsCollectionFactory $articlesubsectionsCollectionFactory,
        ArticlesubsectionsSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager,
        CollectionProcessorInterface $collectionProcessor,
        JoinProcessorInterface $extensionAttributesJoinProcessor,
        ExtensibleDataObjectConverter $extensibleDataObjectConverter,
        Metadata $articlesubsectionsMetadata
    ) {
        $this->resource = $resource;
        $this->articlesubsectionsFactory = $articlesubsectionsFactory;
        $this->articlesubsectionsCollectionFactory = $articlesubsectionsCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataArticlesubsectionsFactory = $dataArticlesubsectionsFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
        $this->collectionProcessor = $collectionProcessor;
        $this->extensionAttributesJoinProcessor = $extensionAttributesJoinProcessor;
        $this->extensibleDataObjectConverter = $extensibleDataObjectConverter;
        $this->metadata = $articlesubsectionsMetadata;
    }

    /**
     * {@inheritdoc}
     */
    public function save(
        \Mirasvit\Kb\Api\Data\ArticlesubsectionsInterface $articlesubsections
    ) {
        /* if (empty($articlesubsections->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $articlesubsections->setStoreId($storeId);
        } */

        $articlesubsectionsData = $this->extensibleDataObjectConverter->toNestedArray(
            $articlesubsections,
            [],
            \Mirasvit\Kb\Api\Data\ArticlesubsectionsInterface::class
        );

        $articlesubsectionsModel = $this->articlesubsectionsFactory->create()->setData($articlesubsectionsData);

        try {
            $this->resource->save($articlesubsectionsModel);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the article: %1',
                $exception->getMessage()
            ));
        }
        return $articlesubsectionsModel->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function getById($articlesubsectionsId)
    {
        $articlesubsections = $this->articlesubsectionsFactory->create();
        $this->resource->load($articlesubsections, $articlesubsectionsId);
        if (!$articlesubsections->getId()) {
            throw new NoSuchEntityException(__('Article sub sectionwith id "%1" does not exist.', $articlesubsectionsId));
        }
        return $articlesubsections->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->articlesubsectionsCollectionFactory->create();

        $this->extensionAttributesJoinProcessor->process(
            $collection,
            \Mirasvit\Kb\Api\Data\ArticlesubsectionsInterface::class
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
        \Mirasvit\Kb\Api\Data\ArticlesubsectionsInterface $articlesubsections
    ) {
        try {
            $articlesubsectionsModel = $this->articlesubsectionsFactory->create();
            $this->resource->load($articlesubsectionsModel, $articlesubsections->getArticlesubsectionsId());
            $this->resource->delete($articlesubsectionsModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the Article: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($articlesubsectionsId)
    {
        return $this->delete($this->getById($articlesubsectionsId));
    }

    /**
     * {@inheritdoc}
     */
    public function create() {
        return $this->metadata->getNewInstance();
    }
}
