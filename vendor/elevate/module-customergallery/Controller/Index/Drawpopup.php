<?php


namespace Elevate\CustomerGallery\Controller\Index;


use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\View\Result\PageFactory;


class Drawpopup extends Action
{

    /**
     * @var PageFactory
     */
    protected $_resultPageFactory;

    /**
     * @var JsonFactory
     */
    protected $_resultJsonFactory;


    /**
     * View constructor.
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param JsonFactory $resultJsonFactory
     */
    public function __construct(Context $context, PageFactory $resultPageFactory, JsonFactory $resultJsonFactory)
    {

        $this->_resultPageFactory = $resultPageFactory;
        $this->_resultJsonFactory = $resultJsonFactory;

        parent::__construct($context);
    }


    /**
     * @return \Magento\Framework\Controller\Result\Json
     */
    public function execute()
    {
        $result = $this->_resultJsonFactory->create();
        $resultPage = $this->_resultPageFactory->create();
        $currentProductId = $this->getRequest()->getParam('itemid');


        $data = array('currentproductid'=>$currentProductId);

        $block = $resultPage->getLayout()
                            ->createBlock('Elevate\CustomerGallery\Block\Index\Drawpopup')
                            ->setTemplate('Elevate_CustomerGallery::index/drawpopup.phtml')
                           ->setData('data',$data)
                            ->toHtml();

        $result->setData(['html' => $block]);
        return $result;
    }

}