<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Elevate\Productdeepercontent\Model;

use Elevate\Productdeepercontent\Api\Data\DeepercontentInterfaceFactory;
use Elevate\Productdeepercontent\Api\Data\DeepercontentSearchResultsInterfaceFactory;
use Elevate\Productdeepercontent\Api\DeepercontentRepositoryInterface;
use Elevate\Productdeepercontent\Model\ResourceModel\Deepercontent as ResourceDeepercontent;
use Elevate\Productdeepercontent\Model\ResourceModel\Deepercontent\CollectionFactory as DeepercontentCollectionFactory;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\ExtensibleDataObjectConverter;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Store\Model\StoreManagerInterface;

class DeepercontentRepository implements DeepercontentRepositoryInterface
{

    private $collectionProcessor;

    protected $dataObjectProcessor;

    protected $deepercontentFactory;

    protected $dataDeepercontentFactory;

    protected $resource;

    protected $extensionAttributesJoinProcessor;

    protected $dataObjectHelper;

    protected $extensibleDataObjectConverter;
    protected $searchResultsFactory;

    protected $deepercontentCollectionFactory;

    private $storeManager;


    /**
     * @param ResourceDeepercontent $resource
     * @param DeepercontentFactory $deepercontentFactory
     * @param DeepercontentInterfaceFactory $dataDeepercontentFactory
     * @param DeepercontentCollectionFactory $deepercontentCollectionFactory
     * @param DeepercontentSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     * @param CollectionProcessorInterface $collectionProcessor
     * @param JoinProcessorInterface $extensionAttributesJoinProcessor
     * @param ExtensibleDataObjectConverter $extensibleDataObjectConverter
     */
    public function __construct(
        ResourceDeepercontent $resource,
        DeepercontentFactory $deepercontentFactory,
        DeepercontentInterfaceFactory $dataDeepercontentFactory,
        DeepercontentCollectionFactory $deepercontentCollectionFactory,
        DeepercontentSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager,
        CollectionProcessorInterface $collectionProcessor,
        JoinProcessorInterface $extensionAttributesJoinProcessor,
        ExtensibleDataObjectConverter $extensibleDataObjectConverter
    ) {
        $this->resource = $resource;
        $this->deepercontentFactory = $deepercontentFactory;
        $this->deepercontentCollectionFactory = $deepercontentCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataDeepercontentFactory = $dataDeepercontentFactory;
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
        \Elevate\Productdeepercontent\Api\Data\DeepercontentInterface $deepercontent
    ) {
        /* if (empty($deepercontent->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $deepercontent->setStoreId($storeId);
        } */



        $deepercontentData = $this->extensibleDataObjectConverter->toNestedArray(
            $deepercontent,
            [],
            \Elevate\Productdeepercontent\Api\Data\DeepercontentInterface::class
        );


        echo '<pre>';
        print_r($deepercontentData);
        die();

        $deepercontentModel = $this->deepercontentFactory->create()->setData($deepercontentData);

        try {
            $this->resource->save($deepercontentModel);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the deepercontent: %1',
                $exception->getMessage()
            ));
        }
        return $deepercontentModel->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function get($deepercontentId)
    {
        $deepercontent = $this->deepercontentFactory->create();
        $this->resource->load($deepercontent, $deepercontentId);
        if (!$deepercontent->getId()) {
            throw new NoSuchEntityException(__('Deepercontent with id "%1" does not exist.', $deepercontentId));
        }
        return $deepercontent->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->deepercontentCollectionFactory->create();

        $this->extensionAttributesJoinProcessor->process(
            $collection,
            \Elevate\Productdeepercontent\Api\Data\DeepercontentInterface::class
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
        \Elevate\Productdeepercontent\Api\Data\DeepercontentInterface $deepercontent
    ) {
        try {
            $deepercontentModel = $this->deepercontentFactory->create();
            $this->resource->load($deepercontentModel, $deepercontent->getDeepercontentId());
            $this->resource->delete($deepercontentModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the Deepercontent: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($deepercontentId)
    {
        return $this->delete($this->get($deepercontentId));
    }
}

