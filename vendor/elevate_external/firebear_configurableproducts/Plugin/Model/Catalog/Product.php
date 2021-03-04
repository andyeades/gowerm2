<?php
/**
 * Copyright © 2016 Firebear Studio. All rights reserved.
 */

namespace Firebear\ConfigurableProducts\Plugin\Model\Catalog;

use Magento\Catalog\Model\Product\Url;
use Magento\Catalog\Model\Session;
use Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Registry;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManagerInterface;
use Firebear\ConfigurableProducts\Helper\Data as ICPHelperData;
use Magento\Framework\App\RequestInterface;

class Product
{
    /**
     * Configurable product resource model.
     *
     * @var Configurable
     */
    private $configurableResource;

    /**
     * Catalog session.
     *
     * @var Session
     */
    private $catalogSession;

    /**
     * Core registry.
     *
     * @var Registry
     */
    private $coreRegistry;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var Url
     */
    private $url;

    /**
     * @var ICPHelperData
     */
    public $icpHelper;

    /**
     * @var RequestInterface
     */
    protected $request;


    /**
     * @param Configurable                       $configurableResource
     * @param Session                            $catalogSession
     * @param Registry                           $coreRegistry
     * @param ProductRepositoryInterface         $productRepository
     * @param StoreManagerInterface              $storeManager
     * @param Url $url
     *
     * @internal param Configurable $catalogProductTypeConfigurable
     */
    public function __construct(
        Configurable $configurableResource,
        Session $catalogSession,
        Registry $coreRegistry,
        ProductRepositoryInterface $productRepository,
        StoreManagerInterface $storeManager,
        Url $url,
        ICPHelperData $icpHelper,
        RequestInterface $request
    ) {
        $this->configurableResource = $configurableResource;
        $this->catalogSession = $catalogSession;
        $this->coreRegistry = $coreRegistry;
        $this->productRepository = $productRepository;
        $this->storeManager = $storeManager;
        $this->url = $url;
        $this->icpHelper = $icpHelper;
        $this->request = $request;
    }

    /**
     * Return simple product url.
     *
     * @param \Magento\Catalog\Model\Product $subject
     * @param callable proceed
     * @param null $useSid
     * @throws NoSuchEntityException
     * @return mixed
     */
    public function aroundGetProductUrl(\Magento\Catalog\Model\Product $subject, callable $proceed, $useSid = null)
    {
        $url = $proceed($useSid);
        $data = $this->coreRegistry->registry('firebear_configurableproducts');

        if (isset($data['child_id']) && $data['parent_id'] == $subject->getId()) {
            $productId = $data['child_id'];
            $childProduct = $this->productRepository->getById(
                $productId,
                false,
                $this->storeManager->getStore()->getId()
            );
            $url = $this->url->getProductUrl($childProduct, $useSid);
        }

        return $url;
    }

    /**
     * @param \Magento\Catalog\Model\Product $subject
     * @param $useSid
     * @param $result
     * @return bool
     */
    public function afterIsSaleable(\Magento\Catalog\Model\Product $subject, $result, $useSid = null)
    {
        if ($this->icpHelper->hidePrice() &&
            $this->request->getFullActionName() !== 'catalog_product_view') {
            return false;
        } else {
            return $result;
        }
    }
}
