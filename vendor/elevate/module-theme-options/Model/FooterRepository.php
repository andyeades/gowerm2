<?php
namespace Elevate\Themeoptions\Model;

use Elevate\Themeoptions\Api\FooterRepositoryInterface;
use Elevate\Themeoptions\Api\Data\FooterSearchResultsInterfaceFactory;
use Elevate\Themeoptions\Api\Data\FooterInterfaceFactory;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Elevate\Themeoptions\Model\ResourceModel\Footer as ResourceFooter;
use Elevate\Themeoptions\Model\ResourceModel\Footer\CollectionFactory as FooterCollectionFactory;
use Elevate\Themeoptions\Model\ResourceModel\Metadata;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\Api\ExtensibleDataObjectConverter;

class FooterRepository implements FooterRepositoryInterface
{

    /**
     * @var Metadata
     */
    private $metadata;

    protected $resource;

    protected $footerFactory;

    protected $footerCollectionFactory;

    protected $searchResultsFactory;

    protected $dataObjectHelper;

    protected $dataObjectProcessor;

    protected $dataFooterFactory;

    protected $extensionAttributesJoinProcessor;

    private $storeManager;

    private $collectionProcessor;

    protected $extensibleDataObjectConverter;

    /**
     * @param Metadata $evfooterMetadata
     * @param ResourceFooter $resource
     * @param FooterFactory $footerFactory
     * @param FooterInterfaceFactory $dataFooterFactory
     * @param FooterCollectionFactory $footerCollectionFactory
     * @param FooterSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     * @param CollectionProcessorInterface $collectionProcessor
     * @param JoinProcessorInterface $extensionAttributesJoinProcessor
     * @param ExtensibleDataObjectConverter $extensibleDataObjectConverter
     */
    public function __construct(
        Metadata $evfooterMetadata,
        ResourceFooter $resource,
        FooterFactory $footerFactory,
        FooterInterfaceFactory $dataFooterFactory,
        FooterCollectionFactory $footerCollectionFactory,
        FooterSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager,
        CollectionProcessorInterface $collectionProcessor,
        JoinProcessorInterface $extensionAttributesJoinProcessor,
        ExtensibleDataObjectConverter $extensibleDataObjectConverter
    ) {
        $this->metadata = $evfooterMetadata;
        $this->resource = $resource;
        $this->footerFactory = $footerFactory;
        $this->footerCollectionFactory = $footerCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataFooterFactory = $dataFooterFactory;
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
        \Elevate\Themeoptions\Api\Data\FooterInterface $footer
    ) {
        /* if (empty($footer->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $footer->setStoreId($storeId);
        } */
        
        $footerData = $this->extensibleDataObjectConverter->toNestedArray(
            $footer,
            [],
            \Elevate\Themeoptions\Api\Data\FooterInterface::class
        );
        
        $footerModel = $this->footerFactory->create()->setData($footerData);
        
        try {
            $this->resource->save($footerModel);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the footer: %1',
                $exception->getMessage()
            ));
        }
        return $footerModel->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function getById($footerId)
    {
        $footer = $this->footerFactory->create();
        $this->resource->load($footer, $footerId);
        if (!$footer->getId()) {
            throw new NoSuchEntityException(__('Footer with id "%1" does not exist.', $footerId));
        }
        return $footer->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->footerCollectionFactory->create();
        
        $this->extensionAttributesJoinProcessor->process(
            $collection,
            \Elevate\Themeoptions\Api\Data\FooterInterface::class
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
        \Elevate\Themeoptions\Api\Data\FooterInterface $footer
    ) {
        try {
            $footerModel = $this->footerFactory->create();
            $this->resource->load($footerModel, $footer->getEntityId());
            $this->resource->delete($footerModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the Footer: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($footerId)
    {
        return $this->delete($this->getById($footerId));
    }

    /**
     * {@inheritdoc}
     */
    public function create() {
        return $this->metadata->getNewInstance();
    }
}
