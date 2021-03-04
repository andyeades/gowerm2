<?php
namespace Elevate\CartAssignments\Block;
class Popup extends \Magento\Framework\View\Element\Template
{
    protected $_productFactory;


    public function __construct(\Magento\Framework\View\Element\Template\Context $context,
                                \Magento\Catalog\Model\ProductFactory $productFactory)
    {
        $this->_productFactory = $productFactory;

        parent::__construct($context);
    }

    public function getProduct()
    {


          $params = $this->getRequest()->getParams();
          $id = $params['id'];

        $product = $this->_productFactory->create()->load($id);

        return $product;
        //return __('Hello World');
    }
}