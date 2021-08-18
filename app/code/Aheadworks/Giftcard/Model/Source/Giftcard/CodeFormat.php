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
 * Class CodeFormat
 *
 * @package Aheadworks\Giftcard\Model\Source\Giftcard
 */
class CodeFormat implements ArrayInterface
{
    /**#@+
     * Constants defined for Gift Card code format
     */
    const ALPHANUMERIC = 'alphanumeric';
    const ALPHABETIC = 'alphabetic';
    const NUMERIC = 'numeric';
    /**#@-*/

    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => self::ALPHANUMERIC,
                'label' => __('Alphanumeric')
            ],
            [
                'value' => self::ALPHABETIC,
                'label' => __('Alphabetic')
            ],
            [
                'value' => self::NUMERIC,
                'label' => __('Numeric')
            ]
        ];
    }
}
