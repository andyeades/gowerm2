<?php

namespace Elevate\PrintLabels\Model;

use Elevate\PrintLabels\Api\Data\PrintlabelsApiInterfaceFactory;
use Elevate\PrintLabels\Api\Data\PrintlabelsApiSearchResultsInterfaceFactory;
use Elevate\PrintLabels\Api\PrintlabelsApiRepositoryInterface;
use Elevate\PrintLabels\Model\ResourceModel\PrintlabelsApi as ResourcePrintlabelsApi;
use Elevate\PrintLabels\Model\ResourceModel\PrintlabelsApi\CollectionFactory as PrintlabelsApiCollectionFactory;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\ExtensibleDataObjectConverter;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class PrintlabelsApiRepository
 *
 * @category Elevate
 * @package  Elevate\PrintLabels\Model
 * @author   Richard Jones <richard.jones@elevateweb.co.uk>
 */
class PrintlabelsApiRepository implements PrintlabelsApiRepositoryInterface
{

    protected $resource;

    protected $printlabelsApiFactory;

    protected $printlabelsApiCollectionFactory;

    protected $searchResultsFactory;

    protected $dataObjectHelper;

    protected $dataObjectProcessor;

    protected $dataPrintlabelsApiFactory;

    protected $extensionAttributesJoinProcessor;

    private $storeManager;

    private $collectionProcessor;

    protected $extensibleDataObjectConverter;

    /**
     * @param ResourcePrintlabelsApi $resource
     * @param PrintlabelsApiFactory $printlabelsApiFactory
     * @param PrintlabelsApiInterfaceFactory $dataPrintlabelsApiFactory
     * @param PrintlabelsApiCollectionFactory $printlabelsApiCollectionFactory
     * @param PrintlabelsApiSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     * @param CollectionProcessorInterface $collectionProcessor
     * @param JoinProcessorInterface $extensionAttributesJoinProcessor
     * @param ExtensibleDataObjectConverter $extensibleDataObjectConverter
     */
    public function __construct(
        ResourcePrintlabelsApi $resource,
        PrintlabelsApiFactory $printlabelsApiFactory,
        PrintlabelsApiInterfaceFactory $dataPrintlabelsApiFactory,
        PrintlabelsApiCollectionFactory $printlabelsApiCollectionFactory,
        PrintlabelsApiSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager,
        CollectionProcessorInterface $collectionProcessor,
        JoinProcessorInterface $extensionAttributesJoinProcessor,
        ExtensibleDataObjectConverter $extensibleDataObjectConverter
    ) {
        $this->resource = $resource;
        $this->printlabelsApiFactory = $printlabelsApiFactory;
        $this->printlabelsApiCollectionFactory = $printlabelsApiCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataPrintlabelsApiFactory = $dataPrintlabelsApiFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
        $this->collectionProcessor = $collectionProcessor;
        $this->extensionAttributesJoinProcessor = $extensionAttributesJoinProcessor;
        $this->extensibleDataObjectConverter = $extensibleDataObjectConverter;
    }

    /**
     * {@inheritdoc}
     */
    public function create()
    {
        return $this->printlabelsApiFactory->create();
    }
    /**
     * {@inheritdoc}
     */
    public function save(
        \Elevate\PrintLabels\Api\Data\PrintlabelsApiInterface $printlabelsApi
    ) {
        /* if (empty($printlabelsApi->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $printlabelsApi->setStoreId($storeId);
        } */

        $printlabelsApiData = $this->extensibleDataObjectConverter->toNestedArray(
            $printlabelsApi,
            [],
            \Elevate\PrintLabels\Api\Data\PrintlabelsApiInterface::class
        );

        $printlabelsApiModel = $this->printlabelsApiFactory->create()->setData($printlabelsApiData);

        try {
            $this->resource->save($printlabelsApiModel);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the Print Labels Api: %1',
                $exception->getMessage()
            ));
        }
        return $printlabelsApiModel->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function getById($printlabelsApiId)
    {
        $printlabelsApi = $this->printlabelsApiFactory->create();
        $this->resource->load($printlabelsApi, $printlabelsApiId);
        if (!$printlabelsApi->getId()) {
            throw new NoSuchEntityException(__('Print Labels Api with id "%1" does not exist.', $printlabelsApiId));
        }
        return $printlabelsApi->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->printlabelsApiCollectionFactory->create();

        $this->extensionAttributesJoinProcessor->process(
            $collection,
            \Elevate\PrintLabels\Api\Data\PrintlabelsApiInterface::class
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
        \Elevate\PrintLabels\Api\Data\PrintlabelsApiInterface $printlabelsApi
    ) {
        try {
            $printlabelsApiModel = $this->printlabelsApiFactory->create();
            $this->resource->load($printlabelsApiModel, $printlabelsApi->getPrintlabelsprintlabelsApiId());
            $this->resource->delete($printlabelsApiModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the Print Labels API: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($printlabelsApiId)
    {
        return $this->delete($this->getById($printlabelsApiId));
    }
}
