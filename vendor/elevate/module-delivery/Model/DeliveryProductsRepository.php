<?php


namespace Elevate\Delivery\Model;

use Elevate\Delivery\Api\DeliveryProductsRepositoryInterface;
use Elevate\Delivery\Api\Data\DeliveryProductsSearchResultsInterfaceFactory;
use Elevate\Delivery\Api\Data\DeliveryProductsInterfaceFactory;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Elevate\Delivery\Model\ResourceModel\DeliveryProducts as ResourceDeliveryProducts;
use Elevate\Delivery\Model\ResourceModel\DeliveryProducts\CollectionFactory as DeliveryProductsCollectionFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\Api\ExtensibleDataObjectConverter;

class DeliveryProductsRepository implements DeliveryProductsRepositoryInterface
{



    protected $resource;

    protected $deliveryProductsFactory;

    protected $deliveryProductsCollectionFactory;

    protected $searchResultsFactory;

    protected $dataObjectHelper;

    protected $dataObjectProcessor;

    protected $dataDeliveryProductsFactory;

    protected $extensionAttributesJoinProcessor;

    private $storeManager;

    private $collectionProcessor;

    protected $extensibleDataObjectConverter;

    /**
     * @param ResourceDeliveryProducts $resource
     * @param DeliveryProductsFactory $deliveryProductsFactory
     * @param DeliveryProductsInterfaceFactory $dataDeliveryProductsFactory
     * @param DeliveryProductsCollectionFactory $deliveryProductsCollectionFactory
     * @param DeliveryProductsSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     * @param CollectionProcessorInterface $collectionProcessor
     * @param JoinProcessorInterface $extensionAttributesJoinProcessor
     * @param ExtensibleDataObjectConverter $extensibleDataObjectConverter
     */
    public function __construct(
        ResourceDeliveryProducts $resource,
        DeliveryProductsFactory $deliveryProductsFactory,
        DeliveryProductsInterfaceFactory $dataDeliveryProductsFactory,
        DeliveryProductsCollectionFactory $deliveryProductsCollectionFactory,
        DeliveryProductsSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager,
        CollectionProcessorInterface $collectionProcessor,
        JoinProcessorInterface $extensionAttributesJoinProcessor,
        ExtensibleDataObjectConverter $extensibleDataObjectConverter
    ) {
        $this->resource = $resource;
        $this->deliveryProductsFactory = $deliveryProductsFactory;
        $this->deliveryProductsCollectionFactory = $deliveryProductsCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataDeliveryProductsFactory = $dataDeliveryProductsFactory;
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
        \Elevate\Delivery\Api\Data\DeliveryProductsInterface $deliveryProducts
    ) {
        /* if (empty($deliveryProducts->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $deliveryProducts->setStoreId($storeId);
        } */

        $deliveryProductsData = $this->extensibleDataObjectConverter->toNestedArray(
            $deliveryProducts,
            [],
            \Elevate\Delivery\Api\Data\DeliveryProductsInterface::class
        );

        $deliveryProductsModel = $this->deliveryProductsFactory->create()->setData($deliveryProductsData);

        try {
            $this->resource->save($deliveryProductsModel);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the deliveryProducts: %1',
                $exception->getMessage()
            ));
        }
        return $deliveryProductsModel->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function getById($deliveryProductsId)
    {
        $deliveryProducts = $this->deliveryProductsFactory->create();
        $this->resource->load($deliveryProducts, $deliveryProductsId);
        if (!$deliveryProducts->getId()) {
            throw new NoSuchEntityException(__('DeliveryProducts with id "%1" does not exist.', $deliveryProductsId));
        }
        return $deliveryProducts->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->deliveryProductsCollectionFactory->create();

        $this->extensionAttributesJoinProcessor->process(
            $collection,
            \Elevate\Delivery\Api\Data\DeliveryProductsInterface::class
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
    public function delete(\Elevate\Delivery\Api\Data\DeliveryProductsInterface $deliveryProducts)
    {
        try {
            $deliveryProductsModel = $this->deliveryProductsFactory->create();
            $this->resource->load($deliveryProductsModel, $deliveryProducts->getDeliveryproductsId());
            $this->resource->delete($deliveryProductsModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                                                  'Could not delete the DeliveryProducts: %1',
                                                  $exception->getMessage()
                                              ));
        }
        return true;
    }


    /**
     * {@inheritdoc}
     */
    public function deleteById($deliveryProductsId)
    {
        return $this->delete($this->getById($deliveryProductsId));

    }

    /**
     * {@inheritdoc}
     */
    public function create() {
        return $this->deliveryProductsFactory->create();

    }
}
