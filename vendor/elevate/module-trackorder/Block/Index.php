<?php
namespace Elevate\TrackOrder\Block;
class Index extends \Magento\Framework\View\Element\Template
{
 protected function _prepareLayout() {

    $this->pageConfig->getTitle()->set(__('Track Your Order | Happy Beds'));
    $this->pageConfig->setKeywords(__(''));
    $this->pageConfig->setDescription(__('Need to track your Happy Beds order? Simply input your order number and email address here to track your order from our warehouse to your front door.'));



    return parent::_prepareLayout();
}
}