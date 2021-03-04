<?php
namespace Elevate\LandingPagesRewrite\Model;

use Magento\Store\Model\StoreManagerInterface;
use Magento\UrlRewrite\Service\V1\Data\UrlRewriteFactory;

class LandingPageUrlRewriteGenerator
{
    /**
     * Entity type code
     */
    const ENTITY_TYPE = 'elevate-landingpage';

    /**
     * @var \Magento\UrlRewrite\Service\V1\Data\UrlRewriteFactory
     */
    protected $urlRewriteFactory;

    /**
     * @var \Elevate\LandingPagesRewrite\Model\LandingPageUrlPathGenerator
     */
    protected $landingPageUrlPathGenerator;

    /**
     * Store manager
     *
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Elevate\LandingPages\Model\LandingPage
     */
    protected $landingPage;

    /**
     * @param \Magento\UrlRewrite\Service\V1\Data\UrlRewriteFactory $urlRewriteFactory
     * @param \Elevate\LandingPagesRewrite\Model\LandingPageUrlPathGenerator $landingPageUrlPathGenerator
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        UrlRewriteFactory $urlRewriteFactory,
        LandingPageUrlPathGenerator $landingPageUrlPathGenerator,
        StoreManagerInterface $storeManager
    ) {
        $this->urlRewriteFactory = $urlRewriteFactory;
        $this->storeManager = $storeManager;
        $this->landingPageUrlPathGenerator = $landingPageUrlPathGenerator;
    }

    /**
     * @param \Elevate\LandingPages\Model\LandingPage $landingPage
     * @return \Magento\UrlRewrite\Service\V1\Data\UrlRewrite[]
     */
    public function generate($landingPage)
    {
        $stores = $landingPage->getStores();
        $this->landingPage = $landingPage;

      //  $urls = array_search('0', $stores) === false ? $this->generateForSpecificStores($stores)
        //    : $this->generateForAllStores();

        $urls = $this->generateForAllStores();

        $this->landingPage = null;
        return $urls;
    }

    /**
     * Generate list of urls for default store
     *
     * @return \Magento\UrlRewrite\Service\V1\Data\UrlRewrite[]
     */
    protected function generateForAllStores()
    {

        $urls = [];
        foreach ($this->storeManager->getStores() as $store) {
            $urls[] = $this->createUrlRewrite($store->getStoreId());
        }
        return $urls;
    }

    /**
     * Generate list of urls per store
     *
     * @param int[] $storeIds
     * @return \Magento\UrlRewrite\Service\V1\Data\UrlRewrite[]
     */
    protected function generateForSpecificStores($storeIds)
    {


        $urls = [];
        $existingStores = $this->storeManager->getStores();
        foreach ($storeIds as $storeId) {
            if (!isset($existingStores[$storeId])) {
                continue;
            }

            $urls[] = $this->createUrlRewrite($storeId);
        }
        return $urls;
    }

    /**
     * Create url rewrite object
     *
     * @param int $storeId
     * @param int $redirectType
     * @return \Magento\UrlRewrite\Service\V1\Data\UrlRewrite
     */
    protected function createUrlRewrite($storeId, $redirectType = 0)
    {



        return $this->urlRewriteFactory->create()->setStoreId($storeId)
            ->setEntityType(self::ENTITY_TYPE)
            ->setEntityId($this->landingPage->getId())
            ->setRequestPath($this->landingPage->getUrlKey())
            ->setTargetPath($this->landingPageUrlPathGenerator->getCanonicalUrlPath($this->landingPage))
            ->setIsAutogenerated(1)
            ->setRedirectType($redirectType);
    }
}