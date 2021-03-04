<?php

namespace Firebear\ConfigurableProducts\Plugin\Seo;

use Magento\Framework\View\Page\Config as PageConfig;
use Magento\Framework\App\Request\Http;
use Magento\Framework\UrlInterface;
use Firebear\ConfigurableProducts\Helper\Data;

/**
 * Class SeoBeforeRender
 * @package Mageplaza\Seo\Plugin
 */
class Canonical
{

    /**
     * @var \Magento\Framework\View\Page\Config
     */
    protected $pageConfig;

    /**
     * @var \Magento\Framework\App\Request\Http
     */
    protected $request;

    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $url;

    /**
     * @var Data
     */
    protected $icpDataHelper;

    /**
     * SeoProRender constructor.
     * @param \Magento\Framework\View\Page\Config $pageConfig
     * @param \Magento\Framework\App\Request\Http $request
     * @param \Magento\Framework\UrlInterface $url
     * @param Data
     */
    function __construct(
        PageConfig $pageConfig,
        Http $request,
        UrlInterface $url,
        Data $icpDataHelper
    ) {
        $this->pageConfig = $pageConfig;
        $this->request = $request;
        $this->url = $url;
        $this->icpDataHelper = $icpDataHelper;
    }

    /**
     * @param \Magento\Framework\View\Page\Config\Renderer $subject
     * @param $result
     * @return mixed
     */
    public function afterRenderMetadata(\Magento\Framework\View\Page\Config\Renderer $subject, $result)
    {
        if ($this->icpDataHelper->getGeneralConfig('general/icp_canonical_url')) {
            $this->pageConfig->addRemotePageAsset(
                $this->url->escape($this->url->getCurrentUrl()),
                'canonical',
                ['attributes' => ['rel' => 'canonical']]
            );
        }
        return $result;
    }
}
