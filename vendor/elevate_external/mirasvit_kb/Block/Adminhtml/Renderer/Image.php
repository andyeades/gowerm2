<?php
/**
 * Mageplaza
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the mageplaza.com license that is
 * available through the world-wide-web at this URL:
 * https://www.mageplaza.com/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Mageplaza
 * @package     Mageplaza_Core
 * @copyright   Copyright (c) Mageplaza (https://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */

namespace Mirasvit\Kb\Block\Adminhtml\Renderer;

/**
 * Renderer image for admin form
 * Add media path to url
 *
 * Class Image
 * @package
 */

class Image extends \Magento\Framework\Data\Form\Element\Image
{
    /**
     * Get image preview url
     *
     * @return string
     */
    protected function _getUrl()
    {
        $url = parent::_getUrl();

        if ($this->getPath()) {
            $url = $this->getPath() . '/' . trim($url, '/');
        }

        return $url;
    }
    /**
     * @return string
     */
    protected function _getDeleteCheckbox()
    {
        $html = '';
        if ($this->getValue()) {
            $label = __('Delete Image');
            $html  .= '<span class="delete-image">';
            $html  .= '<input style="margin: auto;" type="checkbox"' .
                ' name="' .
                $this->getName() .
                '[delete]" value="1" class="checkbox"' .
                ' id="' .
                $this->getHtmlId() .
                '_delete"' .
                ($this->getDisabled() ? ' disabled="disabled"' : '') .
                '/>';
            $html  .= '<label for="' . $this->getHtmlId() .
                '_delete"' . ($this->getDisabled() ? ' class="disabled"' : '') . '> ' .
                $label .
                '</label>';
            $html  .= $this->_getHiddenInput();
            $html  .= '</span>';
        }
        $html .= '';

        return $html;
    }
}
