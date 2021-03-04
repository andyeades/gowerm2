<?php

namespace Elevate\Themeoptions\Block\Frontend;

use \Magento\Framework\View\Element\Template;

class Header extends \Magento\Framework\View\Element\Template {

    /** @var Magento\Customer\Model\Session */
    protected $customerSession;


  public function __construct(
    \Magento\Framework\View\Element\Template\Context $context,
    \Magento\Customer\Model\Session $customerSession,
    array $data = []
  ) {
      $this->customerSession = $customerSession;
    parent::__construct($context, $data);
  }

    public function getCmsBlock() {
      if ($this->customerSession->isLoggedIn()) {
          // Return Logged in block
          $blockHtml = $this->getLayout()->createBlock('Magento\Cms\Block\Block')->setBlockId('ev-hdr-top-list-loggedin')->toHtml();

      } else {
          // Return Normal Block;
          $blockHtml = $this->getLayout()->createBlock('Magento\Cms\Block\Block')->setBlockId('ev-hdr-top-list')->toHtml();
      }
        return $this->escapeHtml($blockHtml);
    }

}
?>
