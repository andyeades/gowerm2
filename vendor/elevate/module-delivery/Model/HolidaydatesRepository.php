<?php

namespace Elevate\Delivery\Model;

use Elevate\Delivery\Api\Data\HolidaydatesInterfaceFactory;
use Elevate\Delivery\Api\Data\HolidaydatesSearchResultsInterfaceFactory;
use Elevate\Delivery\Api\HolidaydatesRepositoryInterface;
use Elevate\Delivery\Model\ResourceModel\Holidaydates as ResourceHolidaydates;
use Elevate\Delivery\Model\ResourceModel\Holidaydates\CollectionFactory as HolidaydatesCollectionFactory;
use Elevate\Delivery\Model\ResourceModel\Metadata;
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
 * Class HolidaydatesRepository
 *
 * @category Elevate
 * @package  Elevate\Delivery\Model
 * @author   Richard Jones <richard.jones@elevateweb.co.uk>
 */
class HolidaydatesRepository implements HolidaydatesRepositoryInterface
{

    /**
     * @var Metadata
     */
    private $metadata;

    /**
     * @var \Elevate\Delivery\Model\ResourceModel\Holidaydates
     */
    protected $resource;

    /**
     * @var \Elevate\Delivery\Model\HolidaydatesFactory
     */
    protected $holidaydatesFactory;

    /**
     * @var \Elevate\Delivery\Model\ResourceModel\Holidaydates\CollectionFactory
     */
    protected $holidaydatesCollectionFactory;

    /**
     * @var \Elevate\Delivery\Api\Data\HolidaydatesSearchResultsInterfaceFactory
     */
    protected $searchResultsFactory;

    /**
     * @var \Magento\Framework\Api\DataObjectHelper
     */
    protected $dataObjectHelper;

    /**
     * @var \Magento\Framework\Reflection\DataObjectProcessor
     */
    protected $dataObjectProcessor;

    /**
     * @var \Elevate\Delivery\Api\Data\HolidaydatesInterfaceFactory
     */
    protected $dataHolidaydatesFactory;

    /**
     * @var \Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface
     */
    protected $extensionAttributesJoinProcessor;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var \Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface
     */
    private $collectionProcessor;

    /**
     * @var \Magento\Framework\Api\ExtensibleDataObjectConverter
     */
    protected $extensibleDataObjectConverter;

    /**
     * @param Metadata                                  $evholidaydatesMetadata
     * @param ResourceHolidaydates                      $resource
     * @param HolidaydatesFactory                       $holidaydatesFactory
     * @param HolidaydatesInterfaceFactory              $dataHolidaydatesFactory
     * @param HolidaydatesCollectionFactory             $holidaydatesCollectionFactory
     * @param HolidaydatesSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper                          $dataObjectHelper
     * @param DataObjectProcessor                       $dataObjectProcessor
     * @param StoreManagerInterface                     $storeManager
     * @param CollectionProcessorInterface              $collectionProcessor
     * @param JoinProcessorInterface                    $extensionAttributesJoinProcessor
     * @param ExtensibleDataObjectConverter             $extensibleDataObjectConverter
     */
    public function __construct(
        Metadata $evholidaydatesMetadata,
        ResourceHolidaydates $resource,
        HolidaydatesFactory $holidaydatesFactory,
        HolidaydatesInterfaceFactory $dataHolidaydatesFactory,
        HolidaydatesCollectionFactory $holidaydatesCollectionFactory,
        HolidaydatesSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager,
        CollectionProcessorInterface $collectionProcessor,
        JoinProcessorInterface $extensionAttributesJoinProcessor,
        ExtensibleDataObjectConverter $extensibleDataObjectConverter
    ) {
        $this->metadata = $evholidaydatesMetadata;
        $this->resource = $resource;
        $this->holidaydatesFactory = $holidaydatesFactory;
        $this->holidaydatesCollectionFactory = $holidaydatesCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataHolidaydatesFactory = $dataHolidaydatesFactory;
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
        return $this->metadata->getNewInstance();
    }

    /**
     * {@inheritdoc}
     */
    public function save(
        \Elevate\Delivery\Api\Data\HolidaydatesInterface $holidaydates
    ) {
        /* if (empty($holidaydates->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $holidaydates->setStoreId($storeId);
        } */

        $holidaydatesData = $this->extensibleDataObjectConverter->toNestedArray(
            $holidaydates,
            [],
            \Elevate\Delivery\Api\Data\HolidaydatesInterface::class
        );

        $holidaydatesModel = $this->holidaydatesFactory->create()->setData($holidaydatesData);

        try {
            $this->resource->save($holidaydatesModel);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(
                __(
                    'Could not save the Holiday Date: %1',
                    $exception->getMessage()
                )
            );
        }

        return $holidaydatesModel->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function getById($holidaydatesId)
    {
        $holidaydates = $this->holidaydatesFactory->create();
        $this->resource->load($holidaydates, $holidaydatesId);
        if (!$holidaydates->getId()) {
            throw new NoSuchEntityException(__('Holiday Date with id "%1" does not exist.', $holidaydatesId));
        }

        return $holidaydates->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->holidaydatesCollectionFactory->create();

        $this->extensionAttributesJoinProcessor->process(
            $collection,
            \Elevate\Delivery\Api\Data\HolidaydatesInterface::class
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
        \Elevate\Delivery\Api\Data\HolidaydatesInterface $holidaydates
    ) {
        try {
            $holidaydatesModel = $this->holidaydatesFactory->create();
            $this->resource->load($holidaydatesModel, $holidaydates->getDeliveryholidaydatesId());
            $this->resource->delete($holidaydatesModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(
                __(
                    'Could not delete the Holiday Dates: %1',
                    $exception->getMessage()
                )
            );
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($holidaydatesId)
    {
        return $this->delete($this->getById($holidaydatesId));
    }
}
