<?php

namespace Elevate\LandingPagesRewrite\Observer;

use Magento\Framework\Event\Observer as EventObserver;
use Magento\UrlRewrite\Model\UrlPersistInterface;
use Magento\Framework\Event\ObserverInterface;
use Elevate\LandingPagesRewrite\Model\LandingPageUrlRewriteGenerator;
use Magento\UrlRewrite\Service\V1\Data\UrlRewrite;

class ProcessLandingPageRewriteSavingObserver implements ObserverInterface {
    /**
     * @var \Elevate\LandingPagesRewrite\Model\LandingPageUrlRewriteGenerator
     */
    protected $landingPageUrlRewriteGenerator;

    /**
     * @var UrlPersistInterface
     */
    protected $urlPersist;

    /**
     * @param \Magento\LandingPages\Model\LandingPageUrlRewriteGenerator $landingPageUrlRewriteGenerator
     * @param UrlPersistInterface                                        $urlPersist
     */
    public function __construct(
        LandingPageUrlRewriteGenerator $landingPageUrlRewriteGenerator,
        UrlPersistInterface $urlPersist
    ) {
        $this->landingPageUrlRewriteGenerator = $landingPageUrlRewriteGenerator;
        $this->urlPersist = $urlPersist;
    }

    /**
     * Generate urls for UrlRewrite and save it in storage
     *
     * @param \Magento\Framework\Event\Observer $observer
     *
     * @return void$displayText = $observer->getData('mp_text');
    echo $displayText->getText() . " - Event </br>";
    $displayText->setText('Execute event successfully.');
     */
    public function execute(EventObserver $observer) {


        /** @var $landingPage \Elevate\LandingPages\Model\LandingPage */
        $landingPage = $observer->getEvent()->getData('landingpage');



        if ($landingPage->dataHasChangedFor('identifier') || $landingPage->dataHasChangedFor('store_id')) {
            $urls = $this->landingPageUrlRewriteGenerator->generate($landingPage);

            $this->urlPersist->deleteByData(
                [
                    UrlRewrite::ENTITY_ID   => $landingPage->getId(),
                    UrlRewrite::ENTITY_TYPE => LandingPageUrlRewriteGenerator::ENTITY_TYPE,
                ]
            );

            $this->urlPersist->replace($urls);
        }

    }
}
