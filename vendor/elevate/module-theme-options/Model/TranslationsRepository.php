<?php
namespace Elevate\Themeoptions\Model;

use Elevate\Themeoptions\Api\TranslationsRepositoryInterface;
use Elevate\Themeoptions\Api\Data\TranslationsSearchResultsInterfaceFactory;
use Elevate\Themeoptions\Api\Data\TranslationsInterfaceFactory;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Elevate\Themeoptions\Model\ResourceModel\Translations as ResourceTranslations;
use Elevate\Themeoptions\Model\ResourceModel\Translations\CollectionFactory as TranslationsCollectionFactory;
use Elevate\Themeoptions\Model\ResourceModel\Metadata;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\Api\ExtensibleDataObjectConverter;

class TranslationsRepository implements TranslationsRepositoryInterface
{

    /**
     * @var Metadata
     */
    private $metadata;

    protected $resource;

    protected $translationsFactory;

    protected $translationsCollectionFactory;

    protected $searchResultsFactory;

    protected $dataObjectHelper;

    protected $dataObjectProcessor;

    protected $dataTranslationsFactory;

    protected $extensionAttributesJoinProcessor;

    private $storeManager;

    private $collectionProcessor;

    protected $extensibleDataObjectConverter;

    /**
     * @param Metadata $evtranslationsMetadata
     * @param ResourceTranslations $resource
     * @param TranslationsFactory $translationsFactory
     * @param TranslationsInterfaceFactory $dataTranslationsFactory
     * @param TranslationsCollectionFactory $translationsCollectionFactory
     * @param TranslationsSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     * @param CollectionProcessorInterface $collectionProcessor
     * @param JoinProcessorInterface $extensionAttributesJoinProcessor
     * @param ExtensibleDataObjectConverter $extensibleDataObjectConverter
     */
    public function __construct(
        Metadata $evtranslationsMetadata,
        ResourceTranslations $resource,
        TranslationsFactory $translationsFactory,
        TranslationsInterfaceFactory $dataTranslationsFactory,
        TranslationsCollectionFactory $translationsCollectionFactory,
        TranslationsSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager,
        CollectionProcessorInterface $collectionProcessor,
        JoinProcessorInterface $extensionAttributesJoinProcessor,
        ExtensibleDataObjectConverter $extensibleDataObjectConverter
    ) {
        $this->metadata = $evtranslationsMetadata;
        $this->resource = $resource;
        $this->translationsFactory = $translationsFactory;
        $this->translationsCollectionFactory = $translationsCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataTranslationsFactory = $dataTranslationsFactory;
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
        \Elevate\Themeoptions\Api\Data\TranslationsInterface $translations
    ) {
        /* if (empty($translations->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $translations->setStoreId($storeId);
        } */

        $translationsData = $this->extensibleDataObjectConverter->toNestedArray(
            $translations,
            [],
            \Elevate\Themeoptions\Api\Data\TranslationsInterface::class
        );

        $translationsModel = $this->translationsFactory->create()->setData($translationsData);

        try {
            $this->resource->save($translationsModel);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the translations: %1',
                $exception->getMessage()
            ));
        }
        return $translationsModel->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function getById($translationsId)
    {
        $translations = $this->translationsFactory->create();
        $this->resource->load($translations, $translationsId);
        if (!$translations->getId()) {
            throw new NoSuchEntityException(__('Translations with id "%1" does not exist.', $translationsId));
        }
        return $translations->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->translationsCollectionFactory->create();

        $this->extensionAttributesJoinProcessor->process(
            $collection,
            \Elevate\Themeoptions\Api\Data\TranslationsInterface::class
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
        \Elevate\Themeoptions\Api\Data\TranslationsInterface $translations
    ) {
        try {
            $translationsModel = $this->translationsFactory->create();
            $this->resource->load($translationsModel, $translations->getEntityId());
            $this->resource->delete($translationsModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the Translations: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($translationsId)
    {
        return $this->delete($this->getById($translationsId));
    }

    /**
     * {@inheritdoc}
     */
    public function create() {
        return $this->metadata->getNewInstance();
    }
}
