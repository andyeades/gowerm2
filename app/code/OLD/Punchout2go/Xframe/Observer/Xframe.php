<?php

namespace Punchout2go\Xframe\Observer;

use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;
use Punchout2go\Xframe\Helper\Data as HelperData;

class Xframe implements ObserverInterface
{
    /**
     * @var \Punchout2go\Xframe\Helper\Data
     */
    protected $helper;

    /**
     * Postdispatch constructor.
     *
     * @param \Punchout2go\Xframe\Helper\Data   $dataHelper
     */
    public function __construct(
        HelperData $dataHelper
    ) {
        $this->helper = $dataHelper;
    }


    /**
     * @param \Magento\Framework\Event\Observer $observer
     *
     * @return void
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function execute(EventObserver $observer)
    {
        // is module enabled
        if ($this->helper->isEnabled()) {
            // all enabled
            $header_value = $this->helper->getHeaderValue();
            $this->helper->debug('header value : '. $header_value);
            /** @var \Magento\Framework\App\Response\Http\Interceptor $responseObj */
            if (empty($header_value) && $header_value !== 0) {
                $this->helper->debug('no value provided, use default behavior');
            } else {
                $responseObj = $observer->getEvent()->getData('controller_action')->getResponse();
                $this->helper->debug('updating xframe header');
                $this->updateHeader($responseObj,$header_value);
            }
        }

        /** @var \Magento\Framework\App\Response\Http\Interceptor $responseObj
        $headers = $this->helper->getHeaders($responseObj);
        foreach ($headers as $header) {
            foreach ($header as $headerKey => $headerValue) {
                $responseObj->setHeader($headerKey, $headerValue);
            }
        }*/
    }


    /**
     * update the header
     *
     * @param \Magento\Framework\App\Response\Http\Interceptor $responseObj
     * @param string $header_value
     *
     */
    public function updateHeader ($responseObj, $header_value)
    {
        if ($header_value === 0) {

        } else {
            $header = $this->getDeploymentConfig()->set(ConfigOptionsListConstants::CONFIG_PATH_X_FRAME_OPT);

        }
        $option = $responseObj->getHeader('x-frame-options');
        $headers = headers_list();
        $this->helper->debug('current '. $option);
        $this->helper->debug('php',$headers);
        $this->helper->debug('controller',$responseObj->getHeaders()->toArray());
    }

}
