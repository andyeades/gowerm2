<?php

namespace Elevate\Themeoptions\Block\Frontend;

use \Magento\Framework\View\Element\Template;

class Headertoplist extends \Magento\Framework\View\Element\Template {

    /** @var Magento\Customer\Model\Session */
    protected $customerSession;


    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        array $data = []
    ) {
         parent::__construct($context, $data);
         $this->customerSession = $customerSession;
    }


    public function getCmsBlock() {

        if ($this->customerSession->isLoggedIn()) {
            // Return Logged in block
            $blockHtml = $this->getLayout()->createBlock('Magento\Cms\Block\Block')->setBlockId('ev-hdr-top-list-loggedin')->toHtml();

        } else {
            // Return Normal Block;
            $blockHtml = $this->getLayout()->createBlock('Magento\Cms\Block\Block')->setBlockId('ev-hdr-top-list')->toHtml();
        }
        return $blockHtml;
    }

}
?>
