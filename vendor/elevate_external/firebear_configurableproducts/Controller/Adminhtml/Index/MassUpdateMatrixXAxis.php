<?php

namespace Firebear\ConfigurableProducts\Controller\Adminhtml\Index;

use Exception;
use Firebear\ConfigurableProducts\Model\ProductOptions;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpPostActionInterface as HttpPostActionInterface;
use Magento\Catalog\Controller\Adminhtml\Product;
use Magento\Framework\Controller\ResultFactory;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Firebear\ConfigurableProducts\Model\ProductOptionsRepository;
use Magento\Catalog\Model\ProductRepository;
use Magento\Ui\Component\MassAction\Filter;

/**
 * Class MassUpdateMatrixXAxis
 * @package Firebear\ConfigurableProducts\Controller\Adminhtml\Index
 */
class MassUpdateMatrixXAxis extends Product
{
    /**
     * @var Filter
     */
    protected $filter;

    /**
     * @var CollectionFactory
     */

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var ProductOptionsRepository
     */
    protected $productOptionsRepository;

    /**
     * @var ProductRepository
     */
    protected $productRepository;

    /**
     * MassUpdateMatrixXAxis constructor.
     * @param Context $context
     * @param Product\Builder $productBuilder
     * @param CollectionFactory $collectionFactory
     * @param Filter $filter
     * @param ProductOptionsRepository $productOptionsRepository
     * @param ProductRepository $productRepository
     */
    public function __construct(
        Context $context,
        Product\Builder $productBuilder,
        CollectionFactory $collectionFactory,
        Filter $filter,
        ProductOptionsRepository $productOptionsRepository,
        ProductRepository $productRepository
    ) {
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        $this->productOptionsRepository = $productOptionsRepository;
        $this->productRepository = $productRepository;
        parent::__construct($context, $productBuilder);
    }

    /**
     * Update product(s) matrix attributes action
     *
     * @return Redirect
     * @throws Exception
     */
    public function execute()
    {
        $productsUpdated = 0;
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        $productIds = $collection->getAllIds();
        $storeId = $this->getRequest()->getParam('store', null);
        $attributeForMatrixAxis = $this->getRequest()->getParam('attribute_code');

        foreach ($productIds as $productId) {
            try {
                $productOption = $this->productOptionsRepository->getByProductId($productId);
                $currentProduct = $this->productRepository->getById($productId);
                if ($currentProduct && $currentProduct->getTypeId() == 'configurable') {
                    if (!$productOption->getId()) {
                        $productOption->setProductId($productId);
                    }
                    $this->updateMatrixAxis($productOption, $attributeForMatrixAxis);
                    $this->productOptionsRepository->save($productOption);
                    $productsUpdated++;
                }
            } catch (Exception $e) {
                $this->messageManager->addErrorMessage(__('Something went wrong when save product(s)'));
            }
        }
        if ($productsUpdated) {
            $this->messageManager->addSuccessMessage(__('A total of %1 record(s) were updated.', $productsUpdated));
        }

        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect = $resultRedirect->setPath('catalog/product/', ['store' => $storeId]);
        return $resultRedirect;
    }

    /**
     * @param ProductOptions $productOption
     * @param $attributeForMatrixAxis
     */
    protected function updateMatrixAxis($productOption, $attributeForMatrixAxis)
    {
        $productOption->setXAxis($attributeForMatrixAxis);
    }
}
