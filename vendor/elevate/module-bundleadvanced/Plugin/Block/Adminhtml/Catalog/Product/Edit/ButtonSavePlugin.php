<?php

namespace Elevate\BundleAdvanced\Plugin\Block\Adminhtml\Catalog\Product\Edit;

use Magento\Catalog\Block\Adminhtml\Product\Edit\Button\Save;
use Elevate\BundleAdvanced\Model\Product\Checker as ProductChecker;

/**
 * Class ButtonSavePlugin
 * @package Elevate\BundleAdvanced\Plugin\Block\Adminhtml\Catalog\Product\Edit
 */
class ButtonSavePlugin
{
    /**
     * @var string
     */
    const BACK_ACTION = 'elevate_bundleadvanced_duplicate_as_simple_bundle';

    /**
     * @var ProductChecker
     */
    private $productChecker;

    /**
     * @param ProductChecker $productChecker
     */
    public function __construct(
        ProductChecker $productChecker
    ) {
        $this->productChecker = $productChecker;
    }

    /**
     * Add buttons related with ba to save section bundle product
     *
     * @param Save $subject
     * @param array $result
     * @return array
     */
    public function afterGetButtonData($subject, $result)
    {
        if (isset($result['options']) && $this->productChecker->isNotSimpleBundleProduct()) {
            $result['options'][] = [
                'label' => __('Save & Duplicate as Simple Bundle'),
                'id_hard' => 'elevate_bundleadvanced_save_and_duplicate_as_simple_bundle',
                'data_attribute' => [
                    'mage-init' => [
                        'buttonAdapter' => [
                            'actions' => [
                                [
                                    'targetName' => 'product_form.product_form',
                                    'actionName' => 'save',
                                    'params' => [
                                        true,
                                        [
                                            'back' => self::BACK_ACTION
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ],
            ];
        }
        return $result;
    }
}
