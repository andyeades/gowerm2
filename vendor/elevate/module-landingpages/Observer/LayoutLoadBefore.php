<?php

namespace Elevate\LandingPages\Observer;

    class LayoutLoadBefore implements \Magento\Framework\Event\ObserverInterface
    {
        /**
         * @var \Magento\Framework\Registry
         */
        protected $_registry;

        /**
         * @var \Elevate\LandingPages\Api\LandingPageRepositoryInterface;
         */
        protected $landingPageRepository;

        public function __construct(
            \Magento\Framework\Registry $registry,
            \Elevate\LandingPages\Api\LandingPageRepositoryInterface $landingPageRepository
        )
        {
            $this->_registry = $registry;
            $this->landingPageRepository = $landingPageRepository;
        }


        public function execute(\Magento\Framework\Event\Observer $observer)
        {

            $layout = $observer->getLayout();

            if ($layout->getUpdate()->pageHandleExists('elevate_landingpages_index_index')) {
                $data = $this->_registry->registry('elevate_landingpage_data');
                $id = $this->_registry->registry('elevate_landingpage_data')['landingpage_id'];

                $no_index = $this->_registry->registry('elevate_landingpage_data')['no_index'];


                if (isset($id)) {

                    if (!empty($no_index)) {
                        // no index these

                        $layout->getUpdate()->addHandle('elevate_landingpages_noindexnofollow');

                    }


                }

            }

            return $this;
        }
    }
