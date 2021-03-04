<?php


namespace Elevate\Microsite\Model;

use Elevate\Microsite\Api\MicrositeRepositoryInterface;
use Elevate\Microsite\Api\Data\MicrositeSearchResultsInterfaceFactory;
use Elevate\Microsite\Api\Data\MicrositeInterfaceFactory;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Elevate\Microsite\Model\ResourceModel\Microsite as ResourceMicrosite;
use Elevate\Microsite\Model\ResourceModel\Microsite\CollectionFactory as MicrositeCollectionFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\Api\ExtensibleDataObjectConverter;

class MicrositeRepository implements MicrositeRepositoryInterface
{

    protected $resource;

    protected $micrositeFactory;

    protected $micrositeCollectionFactory;

    protected $searchResultsFactory;

    protected $dataObjectHelper;

    protected $dataObjectProcessor;

    protected $dataMicrositeFactory;

    protected $extensionAttributesJoinProcessor;

    private $storeManager;

    private $collectionProcessor;

    protected $extensibleDataObjectConverter;

    /**
     * @param ResourceMicrosite $resource
     * @param MicrositeFactory $micrositeFactory
     * @param MicrositeInterfaceFactory $dataMicrositeFactory
     * @param MicrositeCollectionFactory $micrositeCollectionFactory
     * @param MicrositeSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     * @param CollectionProcessorInterface $collectionProcessor
     * @param JoinProcessorInterface $extensionAttributesJoinProcessor
     * @param ExtensibleDataObjectConverter $extensibleDataObjectConverter
     */
    public function __construct(
        ResourceMicrosite $resource,
        MicrositeFactory $micrositeFactory,
        MicrositeInterfaceFactory $dataMicrositeFactory,
        MicrositeCollectionFactory $micrositeCollectionFactory,
        MicrositeSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager,
        CollectionProcessorInterface $collectionProcessor,
        JoinProcessorInterface $extensionAttributesJoinProcessor,
        ExtensibleDataObjectConverter $extensibleDataObjectConverter
    ) {
        $this->resource = $resource;
        $this->micrositeFactory = $micrositeFactory;
        $this->micrositeCollectionFactory = $micrositeCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataMicrositeFactory = $dataMicrositeFactory;
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
        \Elevate\Microsite\Api\Data\MicrositeInterface $microsite
    ) {
        /* if (empty($microsite->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $microsite->setStoreId($storeId);
        } */
        
        $micrositeData = $this->extensibleDataObjectConverter->toNestedArray(
            $microsite,
            [],
            \Elevate\Microsite\Api\Data\MicrositeInterface::class
        );
        
        $micrositeModel = $this->micrositeFactory->create()->setData($micrositeData);
        
        try {
            $this->resource->save($micrositeModel);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the microsite: %1',
                $exception->getMessage()
            ));
        }
        return $micrositeModel->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function getById($micrositeId)
    {
        $microsite = $this->micrositeFactory->create();
        $this->resource->load($microsite, $micrositeId);
        if (!$microsite->getId()) {
            throw new NoSuchEntityException(__('microsite with id "%1" does not exist.', $micrositeId));
        }
        return $microsite->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->micrositeCollectionFactory->create();
        
        $this->extensionAttributesJoinProcessor->process(
            $collection,
            \Elevate\Microsite\Api\Data\MicrositeInterface::class
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
        \Elevate\Microsite\Api\Data\MicrositeInterface $microsite
    ) {
        try {
            $micrositeModel = $this->micrositeFactory->create();
            $this->resource->load($micrositeModel, $microsite->getMicrositeId());
            $this->resource->delete($micrositeModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the microsite: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($micrositeId)
    {
        return $this->delete($this->getById($micrositeId));
    }
}
