<?php

namespace Elevate\Discontinuedproducts\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{
    const XML_PATH_DISCONTINUEDPRODUCTS = 'discontinuedproducts/';

    protected $productFactory;

    protected $stockItem;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\CatalogInventory\Model\Stock\Item $stockItem
    ) {
        parent::__construct($context);
        $this->productFactory = $productFactory;
        $this->stockItem = $stockItem;
    }

    public function getConfigValue(
        $field,
        $storeId = null
    ) {
        return $this->scopeConfig->getValue(
            $field,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    public function getGeneralConfig(
        $code,
        $storeId = null
    ) {
        return $this->getConfigValue(self::XML_PATH_HELLOWORLD . 'general/' . $code, $storeId);
    }

    public function getChildProducts($_productId)
    {
        $outOfStockProducts = [];
        try {
            $configProduct = $this->productFactory->create()->load($_productId);
            $childProducts = $configProduct->getTypeInstance()->getUsedProducts($configProduct);
            foreach ($childProducts as $childProduct) {





                $stockItem = $this->getStockItem($childProduct->getID());
                if (!$stockItem->getQty()) {
                    $outOfStockProducts[$childProduct->getID()] = $childProduct->getAttributeText('choose_size');
                }
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }

        // choose_size
        return $outOfStockProducts;
    }

    public function getChildProductsSizes($_productId)
    {
        $outOfStockProducts = [];
        try {
            $configProduct = $this->productFactory->create()->load($_productId);
            $childProducts = $configProduct->getTypeInstance()->getUsedProducts($configProduct);
            foreach ($childProducts as $childProduct) {
                echo '<pre>';
                print_r($childProduct);
                echo '</pre>';
                die();
                $stockItem = $this->getStockItem($childProduct->getID());
                if (!$stockItem->getQty()) {
                    $outOfStockProducts[$childProduct->getID()] = $childProduct->getName();
                }
            }
        } catch (\Exception $e) {
            return $e->getMassage();
        }

        return $outOfStockProducts;
    }



    public function getStockItem($productId)
    {
        $stockItem = $this->stockItem->load($productId, 'product_id');

        return $stockItem;
    }
}
