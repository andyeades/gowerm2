<?php

namespace Elevate\LayoutSelector\Plugin\Frontend\Magento\Catalog\Helper\Product;

use Magento\Catalog\Model\Product;
use Magento\CatalogInventory\Model\Stock\StockItemRepository;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Result\Page;

class View {
    /**
     * @param \Magento\Catalog\Helper\Product\View $subject
     * @param Page                                 $resultPage
     * @param Product                              $product
     * @param                                      $params
     *
     * @return array
     */
    public function beforeInitProductLayout(
        \Magento\Catalog\Helper\Product\View $subject,
        $resultPage,
        $product,
        $params
    ) {
        try {
            //echo 'HERE';
            //exit;
            $default_layout_handle = $resultPage->getDefaultLayoutHandle(); //catalog_product_view
            // Some kind of Admin Check here maybe?
            // If catalog_product_view and uhh template selected to use = this then use this:
            $resultPage->getLayout()->getUpdate()->addHandle('catalog_product_view_gowercottage');

        } catch(NoSuchEntityException $noSuchEntityException) {
            // Do no thing
        }

        return [
            $resultPage,
            $product,
            $params
        ];
    }

    public function afterInitProductLayout(
        \Magento\Catalog\Helper\Product\View $subject,
        $result,
        $resultPage,
        $params = NULL,
        $product
    ) {
        //echo 'HERE';
        //exit;
        //$test = 1;

        //Your plugin code
        return $result;
    }
}

