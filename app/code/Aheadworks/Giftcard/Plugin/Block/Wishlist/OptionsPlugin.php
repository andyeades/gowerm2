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
namespace Aheadworks\Giftcard\Plugin\Block\Wishlist;

use Magento\Framework\View\Element\Template;

/**
 * Class Plugin
 *
 * @package Aheadworks\Giftcard\Plugin\Block\Wishlist
 */
class OptionsPlugin
{
    /**
     * Add Gift Cart options to wishlist widget
     *
     * @param Template $subject
     * @param [] $result
     * @return []
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGetWishlistOptions(Template $subject, $result)
    {
        return array_merge($result, ['aw_giftcardInfo' => '[name^=aw_gc_]']);
    }
}
