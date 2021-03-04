<?php

namespace Elevate\BundleAdvanced\Plugin\Controller\Adminhtml;

use Elevate\BundleAdvanced\Observer\DuplicateAsSbpAfterProductSave;
use Magento\Catalog\Controller\Adminhtml\Product\Save;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\Registry;

/**
 * Class ProductSavePlugin
 * @package Elevate\BundleAdvanced\Plugin\Controller\Adminhtml
 */
class ProductSavePlugin
{
    /**
     * @var Registry
     */
    private $registry;

    /**
     * @param Registry $registry
     */
    public function __construct(Registry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * Redirect to duplicate product
     *
     * @param Save $subject
     * @param Redirect $result
     * @return Redirect
     */
    public function afterExecute($subject, $result)
    {
        $product = $this->registry->registry(DuplicateAsSbpAfterProductSave::AW_SBP_DUPLICATE_PRODUCT);
        if ($product) {
            $result->setPath(
                'catalog/*/edit',
                ['id' => $product->getEntityId(), 'back' => null, '_current' => true]
            );
        }
        return $result;
    }
}
