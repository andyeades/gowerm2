<?php
namespace Elevate\Themeoptions\Model;

use Elevate\Themeoptions\Api\OptionsRepositoryInterface;
use Elevate\Themeoptions\Api\Data\OptionsSearchResultsInterfaceFactory;
use Elevate\Themeoptions\Api\Data\OptionsInterfaceFactory;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Elevate\Themeoptions\Model\ResourceModel\Options as ResourceOptions;
use Elevate\Themeoptions\Model\ResourceModel\Options\CollectionFactory as OptionsCollectionFactory;
use Elevate\Themeoptions\Model\ResourceModel\Metadata;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\Api\ExtensibleDataObjectConverter;

class OptionsRepository implements OptionsRepositoryInterface
{

    /**
     * @var Metadata
     */
    private $metadata;

    protected $resource;

    protected $optionsFactory;

    protected $optionsCollectionFactory;

    protected $searchResultsFactory;

    protected $dataObjectHelper;

    protected $dataObjectProcessor;

    protected $dataOptionsFactory;

    protected $extensionAttributesJoinProcessor;

    private $storeManager;

    private $collectionProcessor;

    protected $extensibleDataObjectConverter;

    /**
     * @param Metadata $evoptionsMetadata
     * @param ResourceOptions $resource
     * @param OptionsFactory $optionsFactory
     * @param OptionsInterfaceFactory $dataOptionsFactory
     * @param OptionsCollectionFactory $optionsCollectionFactory
     * @param OptionsSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     * @param CollectionProcessorInterface $collectionProcessor
     * @param JoinProcessorInterface $extensionAttributesJoinProcessor
     * @param ExtensibleDataObjectConverter $extensibleDataObjectConverter
     */
    public function __construct(
        Metadata $evoptionsMetadata,
        ResourceOptions $resource,
        OptionsFactory $optionsFactory,
        OptionsInterfaceFactory $dataOptionsFactory,
        OptionsCollectionFactory $optionsCollectionFactory,
        OptionsSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager,
        CollectionProcessorInterface $collectionProcessor,
        JoinProcessorInterface $extensionAttributesJoinProcessor,
        ExtensibleDataObjectConverter $extensibleDataObjectConverter
    ) {
        $this->metadata = $evoptionsMetadata;
        $this->resource = $resource;
        $this->optionsFactory = $optionsFactory;
        $this->optionsCollectionFactory = $optionsCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataOptionsFactory = $dataOptionsFactory;
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
        \Elevate\Themeoptions\Api\Data\OptionsInterface $options
    ) {
        /* if (empty($options->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $options->setStoreId($storeId);
        } */
        
        $optionsData = $this->extensibleDataObjectConverter->toNestedArray(
            $options,
            [],
            \Elevate\Themeoptions\Api\Data\OptionsInterface::class
        );
        
        $optionsModel = $this->optionsFactory->create()->setData($optionsData);
        
        try {
            $this->resource->save($optionsModel);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the options: %1',
                $exception->getMessage()
            ));
        }
        return $optionsModel->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function getById($optionsId)
    {
        $options = $this->optionsFactory->create();
        $this->resource->load($options, $optionsId);
        if (!$options->getId()) {
            throw new NoSuchEntityException(__('Options with id "%1" does not exist.', $optionsId));
        }
        return $options->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->optionsCollectionFactory->create();
        
        $this->extensionAttributesJoinProcessor->process(
            $collection,
            \Elevate\Themeoptions\Api\Data\OptionsInterface::class
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
        \Elevate\Themeoptions\Api\Data\OptionsInterface $options
    ) {
        try {
            $optionsModel = $this->optionsFactory->create();
            $this->resource->load($optionsModel, $options->getEntityId());
            $this->resource->delete($optionsModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the Options: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($optionsId)
    {
        return $this->delete($this->getById($optionsId));
    }

    /**
     * {@inheritdoc}
     */
    public function create() {
        return $this->metadata->getNewInstance();
    }
}
