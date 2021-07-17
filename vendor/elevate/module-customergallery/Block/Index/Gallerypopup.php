<?php
namespace Elevate\CustomerGallery\Block\Index;

use Magento\Framework\View\Element\Template;

class Gallerypopup extends Template
{
    public function __construct(Template\Context $context, array $data = [])
    {
        parent::__construct($context, $data);
    }

    protected function _prepareLayout()
    {
        return parent::_prepareLayout(); // TODO: Change the autogenerated stub
    }

    public function getProducts($productId)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $product = $objectManager->create('Magento\Catalog\Model\Product')->load($productId);
     return $product;
  }




}