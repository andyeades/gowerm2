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
namespace Aheadworks\Giftcard\Plugin\Data;

use Magento\Framework\Data\Structure;

/**
 * Class Plugin
 *
 * @package Aheadworks\Giftcard\Plugin\Data
 */
class StructurePlugin
{
    /**#@+
     * Constants for checking parent container of Gift Card cart block
     */
    const GC_BLOCK_ID = 'checkout.cart.aw.giftcard';
    const TARGET_CONTAINER_ID = 'cart.discount';
    /**#@-*/

    /**
     * Check is target container exist
     *
     * @param Structure $subject
     * @param \Closure $proceed
     * @param string $elementId
     * @param string $parentId
     * @param string $alias
     * @param string|null $position
     */
    public function aroundSetAsChild(
        Structure $subject,
        \Closure $proceed,
        $elementId,
        $parentId,
        $alias = '',
        $position = null
    ) {
        $allowSetAsChild = true;
        if ($elementId == self::GC_BLOCK_ID && $parentId == self::TARGET_CONTAINER_ID) {
            $allowSetAsChild = $subject->hasElement($parentId);
        }
        if ($allowSetAsChild) {
            $proceed($elementId, $parentId, $alias, $position);
        }
    }
}
