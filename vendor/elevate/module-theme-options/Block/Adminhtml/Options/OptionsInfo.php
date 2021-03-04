<?php

namespace Elevate\Themeoptions\Block\Adminhtml\Options;

use Elevate\Themeoptions\Helper\GenerateScss;
use Magento\Framework\Message\ManagerInterface;


class OptionsInfo extends \Magento\Backend\Block\Widget\Container
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
     * @param \Elevate\Themeoptions\Api\OptionsRepositoryInterface      $optionsRepository
     * @param \Magento\Framework\App\Config\ScopeConfigInterface              $scopeConfig
     * @param ManagerInterface                                           $messageManager
     * @param array                                                      $data
     * @param \Magento\Framework\UrlInterface $urlBuilder
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Backend\Model\UrlInterface $backendUrl,
        \Elevate\Themeoptions\Helper\GenerateScss $helper,
        \Elevate\Themeoptions\Api\OptionsRepositoryInterface $optionsRepository,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        ManagerInterface $messageManager,
        \Magento\Framework\UrlInterface $urlBuilder,
        array $data = [])
    {
        parent::__construct($context, $data);
        $this->scopeConfig = $scopeConfig;
        $this->optionsRepository = $optionsRepository;
        $this->messageManager = $messageManager;
        $this->_backendUrl = $backendUrl;
        $this->helper = $helper;
        $this->urlBuilder = $urlBuilder;
    }

    public function getThemeInUse() {

        $themeId = $this->scopeConfig->getValue('theme_options/themeoptions/headeroptionsset_in_use');;

        $output = '';

        if (!empty($themeId)) {
            $themeDetails = $this->optionsRepository->getById($themeId);
            $themeName = $themeDetails->getThemeOptionsName();
            $output .= $themeId.' - '.$themeName;
        } else {

            $output .= 'No theme currently set! ';
        }
       return $output;
    }

    public function getConfigOptionUrl() {
        return $this->urlBuilder->getRouteUrl('adminhtml/system_config/edit/section/theme_options',[ 'key'=>$this->urlBuilder->getSecretKey('adminhtml','system_config','edit')]);
    }


    public function getPostUrl()
    {
        $params = $this->getRequest()->getParams();
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Elevate_Themeoptions::elevatethemeoptions');
    }
    protected function _isAllowedAction($resourceId) {
        return $this->_authorization->isAllowed($resourceId);
    }
}

