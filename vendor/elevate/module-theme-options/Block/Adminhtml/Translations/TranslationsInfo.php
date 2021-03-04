<?php

namespace Elevate\Themeoptions\Block\Adminhtml\Translations;

use Elevate\Themeoptions\Helper\GenerateScss;
use Magento\Framework\Message\ManagerInterface;


class TranslationsInfo extends \Magento\Backend\Block\Widget\Container
{

    /**
     * @var ManagerInterface
     */
    private $messageManager;

    protected $_backendUrl;

    protected $scopeConfig;

    protected $optionsRepository;

    protected $urlBuilder;

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
     * @param \Elevate\Themeoptions\Api\TranslationsRepositoryInterface      $optionsRepository
     * @param \Magento\Framework\App\Config\ScopeConfigInterface              $scopeConfig
     * @param ManagerInterface                                           $messageManager
     * @param array                                                      $data
     * @param \Magento\Framework\UrlInterface $urlBuilder
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Backend\Model\UrlInterface $backendUrl,
        \Elevate\Themeoptions\Helper\GenerateScss $helper,
        \Elevate\Themeoptions\Api\TranslationsRepositoryInterface $translationsRepository,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        ManagerInterface $messageManager,
        \Magento\Framework\UrlInterface $urlBuilder,
        array $data = [])
    {
        parent::__construct($context, $data);
        $this->scopeConfig = $scopeConfig;
        $this->optionsRepository = $translationsRepository;
        $this->messageManager = $messageManager;
        $this->_backendUrl = $backendUrl;
        $this->helper = $helper;
        $this->urlBuilder = $urlBuilder;
    }

    public function getPostUrl()
    {
        $params = $this->getRequest()->getParams();
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Elevate_Themeoptions::translations');
    }
    protected function _isAllowedAction($resourceId) {
        return $this->_authorization->isAllowed($resourceId);
    }
}

