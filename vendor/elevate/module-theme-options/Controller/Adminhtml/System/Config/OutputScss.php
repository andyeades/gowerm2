<?php

namespace Elevate\Themeoptions\Controller\Adminhtml\System\Config;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Sales\Api\Data\ShipmentTrackInterface;
use Magento\Sales\Api\InvoiceRepositoryInterface;

/**
 * Class OutputScss
 *
 * @category Elevate
 * @package  Elevate\Themeoptions\Controller\Adminhtml\Edit
 * @author   Richard Jones <richard.jones@elevateweb.co.uk>
 */
class OutputScss extends \Magento\Backend\App\Action {

    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $resultJsonFactory;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    protected $scssHelper;

    protected $footeroptionsset_in_use;

    protected $headeroptionsset_in_use;

    protected $headerstyle_selected;

    protected $temp_footerfile_to_include;

    protected $custom_scss;
    /**
     * Index constructor.
     *
     * @param Context                                                         $context
     * @param \Magento\Framework\Controller\Result\JsonFactory                $resultJsonFactory
     * @param \Magento\Framework\App\Config\ScopeConfigInterface              $scopeConfig
     * @param \Psr\Log\LoggerInterface                                        $logger
     * @param \Elevate\Themeoptions\Helper\GenerateScss                     $scssHelper
     *
     */
    public function __construct(
        Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Psr\Log\LoggerInterface $logger,
        \Elevate\Themeoptions\Helper\GenerateScss $scssHelper
    ) {
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
        $this->scopeConfig = $scopeConfig;
        $this->logger = $logger;
        $this->scssHelper = $scssHelper;
        $this->custom_scss = $this->scopeConfig->getValue('theme_options/customisation/custom_scss', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $this->footeroptionsset_in_use = $this->scopeConfig->getValue('theme_options/themeoptions/footeroptionsset_in_use');
        $this->temp_footerfile_to_include = $this->scopeConfig->getValue('theme_options/themeoptions/temp_footerfile_to_include');
        $this->headeroptionsset_in_use = $this->scopeConfig->getValue('theme_options/themeoptions/headeroptionsset_in_use');
        $this->headerstyle_selected = $this->scopeConfig->getValue('theme_options/themeoptionsheader/header_style');
    }

    /**
     * Index action
     *
     * @return \Magento\Framework\Controller\Result\Json
     * @throws \Exception
     */
    public function execute() {

        $params = $this->getRequest()->getParams();
        $formdata = $this->getRequest()->getParam('formdata');

        $themevals =  array();

        $themevals[] = array(
            'name' => 'footer_temp',
            'value' => $this->temp_footerfile_to_include
        );

        $themevals[] = array(
            'name' => 'header',
            'value' => $this->headeroptionsset_in_use
        );
        $themevals[] = array(
            'name' => 'headerstyle_selected',
            'value' => $this->headerstyle_selected
        );
        $themevals[] = array(
            'name' => 'footer',
            'value' => $this->footeroptionsset_in_use
        );

        $options_array_ids = array(
            'header'  => $this->headeroptionsset_in_use,
            'footer'  => $this->footeroptionsset_in_use
        );

        $custom_scss_admin_overrides = $this->custom_scss;


        $writetofile = $this->scssHelper->compileThemeOptions($options_array_ids, $themevals, $custom_scss_admin_overrides);

        $response = $writetofile;

        $json_response = $this->resultJsonFactory->create();
        $json_response->setData($response);

        return $json_response;
    }

    /**
     * Is the user allowed to view the blog post grid.
     *
     * @return bool
     */
    protected function _isAllowed() {
        return true;

        return $this->_authorization->isAllowed('Elevate_Themeoptions::elevate_Themeoptions');
    }
}
