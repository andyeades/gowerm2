<?php

namespace Elevate\Discontinuedproducts\Ui\DataProvider\Product\Related;

use Magento\Catalog\Ui\DataProvider\Product\Related\AbstractDataProvider;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\Data\ProductLinkInterface;
use Magento\Catalog\Ui\DataProvider\Product\ProductDataProvider;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\ResourceModel\Product\Collection;

use Magento\Framework\App\RequestInterface;
use Magento\Catalog\Api\ProductLinkRepositoryInterface;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Store\Api\StoreRepositoryInterface;

/**
 * Class DiscontinuedproductsDataProvider
 */
class DiscontinuedproductsDataProvider extends AbstractDataProvider
{
    /**
     * Product collection
     *
     * @var \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    protected $collection;
    /**
     * @var RequestInterface
     * @since 101.0.0
     */
    protected $request;

    /**
     * @var ProductRepositoryInterface
     * @since 101.0.0
     */
    protected $productRepository;

    /**
     * @var StoreRepositoryInterface
     * @since 101.0.0
     */
    protected $storeRepository;

    /**
     * @var ProductLinkRepositoryInterface
     * @since 101.0.0
     */
    protected $productLinkRepository;


    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param RequestInterface $request
     * @param ProductRepositoryInterface $productRepository
     * @param StoreRepositoryInterface $storeRepository
     * @param ProductLinkRepositoryInterface $productLinkRepository
     * @param array $addFieldStrategies
     * @param array $addFilterStrategies
     * @param array $meta
     * @param array $data
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        RequestInterface $request,
        ProductRepositoryInterface $productRepository,
        StoreRepositoryInterface $storeRepository,
        ProductLinkRepositoryInterface $productLinkRepository,
        $addFieldStrategies,
        $addFilterStrategies,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct(
            $name,
            $primaryFieldName,
            $requestFieldName,
            $collectionFactory,
            $request,
            $productRepository,
            $storeRepository,
            $productLinkRepository,
            $addFieldStrategies,
            $addFilterStrategies,
            $meta,
            $data
        );

        $this->request = $request;
        $this->productRepository = $productRepository;
        $this->storeRepository = $storeRepository;
        $this->productLinkRepository = $productLinkRepository;
        $this->collection = $collectionFactory->create();
    }



    /**
     * {@inheritdoc
     */
    protected function getLinkType()
    {
        return 'discontinuedproducts';
    }

    public function getCollection()
    {
        /** @var Collection $collection */
        $collection = $this->collection;
        $collection->addAttributeToSelect('status');

        if ($this->getStore()) {
            $collection->setStore($this->getStore());
        }

        if (!$this->getProduct()) {
            return $collection;
        }

        //excludes current product from grid
        //$collection->addAttributeToFilter(
        //    $collection->getIdFieldName(),
        //    ['nin' => [$this->getProduct()->getId()]]
        //);

        return $this->addCollectionFilters($collection);
    }

}
