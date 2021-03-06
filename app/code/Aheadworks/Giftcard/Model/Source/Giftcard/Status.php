<?php
/**
 * Aheadworks Inc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://aheadworks.com/end-user-license-agreement/
 *
 * @package    Giftcard
 * @version    1.4.6
 * @copyright  Copyright (c) 2021 Aheadworks Inc. (https://aheadworks.com/)
 * @license    https://aheadworks.com/end-user-license-agreement/
 */
namespace Aheadworks\Giftcard\Model\Source\Giftcard;

use Magento\Framework\Option\ArrayInterface;

/**
 * Class Status
 *
 * @package Aheadworks\Giftcard\Model\Source\Giftcard
 */
class Status implements ArrayInterface
{
    /**#@+
     * Constants defined for Gift Card status
     */
    const ACTIVE = 1;
    const EXPIRED = 2;
    const USED = 3;
    const DEACTIVATED = 4;
    const PARTIALLY_USED = 5; // Was used before version 1.1.0
    /**#@-*/

    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => self::ACTIVE,
                'label' => __('Active')
            ],
            [
                'value' => self::EXPIRED,
                'label' => __('Expired')
            ],
            [
                'value' => self::USED,
                'label' => __('Used')
            ],
            [
                'value' => self::DEACTIVATED,
                'label' => __('Deactivated')
            ]
        ];
    }

    /**
     * Retrieve option by value
     *
     * @param int $value
     * @return string|null
     */
    public function getOptionByValue($value)
    {
        $options = $this->toOptionArray();
        foreach ($options as $option) {
            if ($option['value'] == $value) {
                return $option['label'];
            }
        }
        return null;
    }
}
