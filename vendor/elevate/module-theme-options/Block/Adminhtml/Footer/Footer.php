<?php

namespace Elevate\Themeoptions\Block\Adminhtml\Footer;

use Elevate\Themeoptions\Helper\GenerateScss;
use Magento\Framework\Message\ManagerInterface;


class Footer extends \Magento\Backend\Block\Widget\Container
{

    /**
     * @var ManagerInterface
     */
    private $messageManager;

    protected $_backendUrl;


    /**
     * @var \Elevate\Themeoptions\Helper\GenerateScss $helper,
     */
    protected $helper;

    /**
     * Index constructor.
     *
     * @param \Magento\Backend\Block\Widget\Context                      $context
     * @param \Magento\Backend\Model\UrlInterface                        $backendUrl
     * @param \Elevate\Themeoptions\Helper\GenerateScss                 $helper
     * @param ManagerInterface                                           $messageManager
     * @param array                                                      $data
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Backend\Model\UrlInterface $backendUrl,
        \Elevate\Themeoptions\Helper\GenerateScss $helper,
        ManagerInterface $messageManager,
        array $data = [])
    {
        parent::__construct($context, $data);
        $this->messageManager = $messageManager;
        $this->_backendUrl = $backendUrl;
        $this->helper = $helper;
    }

    public function getPostUrl()
    {
        $params = $this->getRequest()->getParams();
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Elevate_Themeoptions::footer');
    }
    protected function _isAllowedAction($resourceId) {
        return $this->_authorization->isAllowed($resourceId);
    }
}

