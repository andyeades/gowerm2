<?php

namespace Elevate\CartAssignments\Controller\Addon;



class Getpopup extends \Magento\Framework\App\Action\Action
{

    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $resultJsonFactory;
    protected $_pageFactory;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory)
    {
        $this->_pageFactory = $pageFactory;
        $this->resultJsonFactory = $resultJsonFactory;

        return parent::__construct($context);
    }

    public function execute()
    {

        $layout = $this->_pageFactory->create();;

      //  $params = $this->getRequest()->getParams();
      //      $id = $params['id'];
        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->resultJsonFactory->create();
        //$product = $this->_productFactory->create()->load($id);
        $response = ['success' => 'true'];

        $block = $layout->getLayout()
                             ->createBlock('Elevate\CartAssignments\Block\Popup')
                             ->setTemplate('Elevate_CartAssignments::popup.phtml')
                             ->toHtml();

        $response = ['html' => $block];
        $resultJson->setData($response);
        return $resultJson;
        //return $this->_pageFactory->create();
    }
}