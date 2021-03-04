<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Elevate\LandingPagesRewrite\Model;

use Elevate\LandingPagesRewrite\Api\Data\LandingPageInterface;


class LandingPageUrlPathGenerator
{
    /**
     * @var \Magento\Framework\Filter\FilterManager
     */
    protected $filterManager;

    /**
     */
    public function __construct(
        \Magento\Framework\Filter\FilterManager $filterManager
    ) {
        $this->filterManager = $filterManager;
    }

    /**
     * @param PageInterface $landingPage
     *
     * @return string
     */
    public function getUrlPath(PageInterface $landingPage)
    {
        return $landingPage->getIdentifier();
    }

    /**
     * Get canonical product url path
     *
     * @param PageInterface $landingPage
     * @return string
     */
    public function getCanonicalUrlPath($landingPage)
    {

        return 'elevate_landingpages/index/index/landingpage_id/' . $landingPage->getId();
    }

    /**
     * Generate Landing page url key based on url_key entered by merchant or page title
     *
     * @param PageInterface $landingPage
     * @return string
     */
    public function generateUrlKey(LandingPageInterface $landingPage)
    {


        $urlKey = $landingPage->getUrlKey();
        return $this->filterManager->translitUrl($urlKey === '' || $urlKey === null ? $landingPage->getTitle() : $urlKey);
    }
}
