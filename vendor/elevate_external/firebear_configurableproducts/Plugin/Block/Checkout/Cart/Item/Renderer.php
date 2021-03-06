<?php

namespace Firebear\ConfigurableProducts\Plugin\Block\Checkout\Cart\Item;

use Magento\Checkout\Block\Cart\Item\Renderer as CartItemRenderer;

class Renderer
{
    /**
     * @param CartItemRenderer $subject
     * @param $data
     * @param $allowedTags
     * @return array
     */
    public function beforeEscapeHtml(CartItemRenderer $subject, $data, $allowedTags = null)
    {
        if (is_string($data)) {
            $allowLinkTag = stripos($data, '/download/downloadCustomOption');
            $allowedTags = [];
        } else {
            $allowLinkTag = false;
        }
        if (is_array($allowedTags) && $allowLinkTag) {
            array_push($allowedTags, 'a');
        } elseif (is_array($allowedTags) && !in_array('div', $allowedTags)) {
            array_push($allowedTags, 'div');
        }
        return [$data, $allowedTags];
    }
}
