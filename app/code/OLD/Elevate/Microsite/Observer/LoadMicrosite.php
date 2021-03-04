<?php

namespace Elevate\Microsite\Observer;

use Magento\Framework\Event\ObserverInterface;

class LoadMicrosite implements ObserverInterface
{
    /**
     * @var \Elevate\Microsite\Model\MicrositeFactory
     */
    private $micrositeFactory;
    protected $_urlInterface;

    /**
     * @var \Magento\Framework\Registry
     */
    private $coreRegistry;

    /**
     * Restrict constructor.
     *
     * @param \Elevate\Microsite\Model\MicrositeFactory $micrositeFactory
     */
    public function __construct(
        \Elevate\Microsite\Model\MicrositeFactory $micrositeFactory,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\UrlInterface $urlInterface
    ) {
        $this->micrositeFactory = $micrositeFactory;
        $this->coreRegistry = $coreRegistry;
        $this->_urlInterface = $urlInterface;
    }
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $id = 1;

         $current_url = $this->_urlInterface->getCurrentUrl();
         $url_parts = parse_url($current_url);

         $lookup_url = $url_parts['host'];

        $microsite = $this->micrositeFactory->create()->load($lookup_url,'microsite_url');

        $microsite_id = $microsite->getId();


        //TODO: test !empty speed vs other options, is_numeric, isset
        if(!empty($microsite_id)){

            print_r($microsite->getData());



        }

       // echo $this->_urlInterface->getCurrentUrl()."<br>";

       // echo $this->_urlInterface->getUrl()."<br>";

       // echo $this->_urlInterface->getUrl('test/test2')."<br>";

       // echo $this->_urlInterface->getBaseUrl()."<br>";

        // insert code here
        //echo "TEST";
    }

}