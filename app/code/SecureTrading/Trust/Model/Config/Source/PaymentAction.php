<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace SecureTrading\Trust\Model\Config\Source;


class PaymentAction implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [['value' => 'authorize', 'label' => 'Authorize Only'],
                ['value' => 'authorize_capture', 'label' => 'Authorize & Capture']];
    }
}