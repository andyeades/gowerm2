<?php
namespace Elevate\Promotions\Controller\Email;

use Magento\Framework\Controller\ResultFactory;

class Countdown extends \Magento\Framework\App\Action\Action
{
    protected $_pageFactory;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory
    ) {
        $this->_pageFactory = $pageFactory;
        parent::__construct($context);
    }
    public function execute() {

        return $this->_pageFactory->create();
    }

}