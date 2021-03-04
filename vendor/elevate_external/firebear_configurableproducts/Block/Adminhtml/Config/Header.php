<?php
declare(strict_types=1);
/**
 * Header
 *
 * @copyright Copyright © 2020 Firebear Studio. All rights reserved.
 * @author    fbeardev@gmail.com
 */

namespace Firebear\ConfigurableProducts\Block\Adminhtml\Config;

use Magento\Backend\Block\Context;
use Magento\Backend\Model\Auth\Session;
use Magento\Config\Block\System\Config\Form\Fieldset;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\Module\ResourceInterface;
use Magento\Framework\View\Helper\Js;

/**
 * Class Header
 * @package Firebear\ConfigurableProducts\Block\Adminhtml\Config
 */
class Header extends Fieldset
{
    /**
     * @var ResourceInterface
     */
    protected $moduleResource;

    /**
     * Header constructor.
     * @param Context $context
     * @param Session $authSession
     * @param Js $jsHelper
     * @param ResourceInterface $moduleResource
     * @param array $data
     */
    public function __construct(
        Context $context,
        Session $authSession,
        Js $jsHelper,
        ResourceInterface $moduleResource,
        array $data = []
    ) {
        parent::__construct($context, $authSession, $jsHelper, $data);
        $this->moduleResource = $moduleResource;
    }

    /**
     * @param AbstractElement $element
     * @return string
     */
    protected function _getHeaderHtml($element)
    {
        $logoUrl = $this->getViewFileUrl('Firebear_ConfigurableProducts::images/logo.png');
        $html = '<div class="fire_menu">';
        $html .= '<img src="' . $logoUrl . '" height="50px">';
        return $html;
    }

    /**
     * @param AbstractElement $element
     * @return string
     * @since 100.1.0
     */
    protected function _getChildrenElementsHtml(AbstractElement $element)
    {
        $html = '<div> <div class="name">';
        $html .= '<a target="_blank" href="https://firebearstudio.com/improved-configurable-products-for-magento-2.html">';
        $html .= __('Firebear Improved Configurable Product v. %1', $this->getModuleVersion()) . '</a></div>';
        $html .= '<a target="_blank" href="https://firebearstudio.com/blog/improved-configurable-products-for-magento-2.html">';
        $html .= __('Extension manual') . '</a>';
        $html .= '<a target="_blank" href="https://firebearstudio.com/blog/firebear-improved-configurable-product-for-magento-2-extension-change-log.html">';
        $html .= __('Extension changelog') . '</a>';
        $html .= '<a target="_blank" href="https://firebearstudio.com/contacts">';
        $html .= __('Support') . '</a>';
        $html .= '<a target="_blank" href="https://firebearstudio.com/downloadable/customer/products/">';
        $html .= __('Download latest version') . '</a>';
        $html .= '</div>';
        return $html;
    }

    /**
     * @return false|string
     */
    public function getModuleVersion()
    {
        return $this->moduleResource->getDbVersion('Firebear_ConfigurableProducts');
    }

    /**
     * @param AbstractElement $element
     * @return string
     */
    protected function _getFooterHtml($element)
    {
        return '</div>';
    }
}
