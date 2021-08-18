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
namespace Aheadworks\Giftcard\Model\Source\History;

use Magento\Framework\Option\ArrayInterface;

/**
 * Class Action
 *
 * @package Aheadworks\Giftcard\Model\Source\History
 */
class Action implements ArrayInterface
{
    /**#@+
     * History action values
     */
    const CREATED = 1;
    const UPDATED = 2;
    const USED = 3;
    const PARTIALLY_USED = 4; // Was used before version 1.1.0
    const EXPIRED = 5;
    const DEACTIVATED = 6;
    const ACTIVATED = 7;
    /**#@-*/

    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => self::CREATED,
                'label' => __('Created')
            ],
            [
                'value' => self::UPDATED,
                'label' => __('Updated')
            ],
            [
                'value' => self::USED,
                'label' => __('Used')
            ],
            [
                'value' => self::EXPIRED,
                'label' => __('Expired')
            ],
            [
                'value' => self::DEACTIVATED,
                'label'=> __('Deactivated')
            ],
            [
                'value' => self::ACTIVATED,
                'label'=> __('Activated')
            ]
        ];
    }
}
