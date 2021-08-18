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
namespace Aheadworks\Giftcard\Block\Checkout\Cart;

use Magento\Framework\View\Element\Template;

/**
 * Class Giftcard
 *
 * @method bool|null getIsFormOpened()
 * @method string|null getRedirectTo()
 * @package Aheadworks\Giftcard\Block\Checkout\Cart
 */
class Giftcard extends Template
{
    /**
     * Retrieve action url
     *
     * @return string
     */
    public function getActionUrl()
    {
        $params = [];
        if ($this->getRedirectTo()) {
            $params['redirect_to'] = $this->getRedirectTo();
        }
        return $this->getUrl('awgiftcard/cart/apply', $params);
    }

    /**
     * Retrieve check Gift Card code url
     *
     * @return string
     */
    public function getCheckCodeUrl()
    {
        return $this->getUrl('awgiftcard/card/checkCode');
    }
}
