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
namespace Aheadworks\Giftcard\Model\Source;

use Magento\Framework\Option\ArrayInterface;

/**
 * Class Yesno
 *
 * @package Aheadworks\Giftcard\Model\Source
 */
class YesNo implements ArrayInterface
{
    /**#@+
     * Yes and No action values
     */
    const YES = 1;
    const NO = 0;
    /**#@-*/

    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::YES, 'label' => __('Yes')],
            ['value' => self::NO, 'label' => __('No')]
        ];
    }
}
