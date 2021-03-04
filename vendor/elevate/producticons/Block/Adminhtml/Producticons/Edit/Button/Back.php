<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Elevate\ProductIcons\Block\Adminhtml\Producticons\Edit\Button;

/**
 * Class Back
 */
class Back extends Generic
{
    /**
     * @return array
     */
    public function getButtonData()
    {
        return [
            'label' => __('Back'),
            'on_click' => sprintf("location.href = '%s';", $this->getUrl('producticons/index/index')),
            'class' => 'back',
            'sort_order' => 10
        ];
    }
}
