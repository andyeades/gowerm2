<?php


namespace Elevate\Support\Model;

use Elevate\Support\Api\SupportRepositoryInterface;
use Elevate\Support\Api\Data\SupportSearchResultsInterfaceFactory;
use Elevate\Support\Api\Data\SupportInterfaceFactory;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Elevate\Support\Model\ResourceModel\Support as ResourceSupport;
use Elevate\Support\Model\ResourceModel\Support\CollectionFactory as SupportCollectionFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\Api\ExtensibleDataObjectConverter;

class SupportRepository implements SupportRepositoryInterface
{

    protected $resource;

    protected $supportFactory;

    protected $supportCollectionFactory;

    protected $searchResultsFactory;

    protected $dataObjectHelper;

    protected $dataObjectProcessor;

    protected $dataSupportFactory;

    protected $extensionAttributesJoinProcessor;

    private $storeManager;

    private $collectionProcessor;

    protected $extensibleDataObjectConverter;

    /**
     * @param ResourceSupport $resource
     * @param SupportFactory $supportFactory
     * @param SupportInterfaceFactory $dataSupportFactory
     * @param SupportCollectionFactory $supportCollectionFactory
     * @param SupportSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     * @param CollectionProcessorInterface $collectionProcessor
     * @param JoinProcessorInterface $extensionAttributesJoinProcessor
     * @param ExtensibleDataObjectConverter $extensibleDataObjectConverter
     */
    public function __construct(
        ResourceSupport $resource,
        SupportFactory $supportFactory,
        SupportInterfaceFactory $dataSupportFactory,
        SupportCollectionFactory $supportCollectionFactory,
        SupportSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager,
        CollectionProcessorInterface $collectionProcessor,
        JoinProcessorInterface $extensionAttributesJoinProcessor,
        ExtensibleDataObjectConverter $extensibleDataObjectConverter
    ) {
        $this->resource = $resource;
        $this->supportFactory = $supportFactory;
        $this->supportCollectionFactory = $supportCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataSupportFactory = $dataSupportFactory;
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
        \Elevate\Support\Api\Data\SupportInterface $support
    ) {
        /* if (empty($support->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $support->setStoreId($storeId);
        } */
        
        $supportData = $this->extensibleDataObjectConverter->toNestedArray(
            $support,
            [],
            \Elevate\Support\Api\Data\SupportInterface::class
        );
        
        $supportModel = $this->supportFactory->create()->setData($supportData);
        
        try {
            $this->resource->save($supportModel);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the support: %1',
                $exception->getMessage()
            ));
        }
        return $supportModel->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function getById($supportId)
    {
        $support = $this->supportFactory->create();
        $this->resource->load($support, $supportId);
        if (!$support->getId()) {
            throw new NoSuchEntityException(__('support with id "%1" does not exist.', $supportId));
        }
        return $support->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->supportCollectionFactory->create();
        
        $this->extensionAttributesJoinProcessor->process(
            $collection,
            \Elevate\Support\Api\Data\SupportInterface::class
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
        \Elevate\Support\Api\Data\SupportInterface $support
    ) {
        try {
            $supportModel = $this->supportFactory->create();
            $this->resource->load($supportModel, $support->getSupportId());
            $this->resource->delete($supportModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the support: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($supportId)
    {
        return $this->delete($this->getById($supportId));
    }
}
