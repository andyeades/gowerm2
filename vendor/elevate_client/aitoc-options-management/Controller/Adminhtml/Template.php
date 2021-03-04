<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2019 Aitoc (https://www.aitoc.com)
 * @package Aitoc_OptionsManagement
 */

/**
 * Copyright © 2018 Aitoc. All rights reserved.
 */

namespace Aitoc\OptionsManagement\Controller\Adminhtml;

use Magento\Framework\View\Result\PageFactory;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Aitoc\OptionsManagement\Model\TemplateFactory;
use Aitoc\OptionsManagement\Model\TemplateRepository;

/**
 * Template controller
 */
abstract class Template extends \Magento\Backend\App\Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Aitoc_OptionsManagement::templates';

    /**
     * @var Registry
     */
    protected $coreRegistry = null;

    /**
     * Store manager
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var TemplateFactory
     */
    protected $templateFactory;

    /**
     * @var TemplateRepository
     */
    protected $templateRepository;

    /**
     * @var \Magento\Framework\Controller\Result\RawFactory
     */
    protected $resultRawFactory;

    /**
     * @var \Magento\Framework\View\LayoutFactory
     */
    protected $layoutFactory;

    /**
     * @var \Magento\Catalog\Controller\Adminhtml\Product\Initialization\Helper
     */
    protected $initializationHelper;

    /**
     * @param Context $context
     * @param Registry $coreRegistry
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param PageFactory $resultPageFactory
     * @param TemplateFactory $templateFactory
     * @param TemplateRepository $templateRepository
     * @param \Magento\Framework\Controller\Result\RawFactory $resultRawFactory
     * @param \Magento\Framework\View\LayoutFactory $layoutFactory
     * @param \Magento\Catalog\Controller\Adminhtml\Product\Initialization\Helper $initializationHelper
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        PageFactory $resultPageFactory,
        TemplateFactory $templateFactory,
        TemplateRepository $templateRepository,
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory,
        \Magento\Framework\View\LayoutFactory $layoutFactory,
        \Magento\Catalog\Controller\Adminhtml\Product\Initialization\Helper $initializationHelper
    ) {
        parent::__construct($context);
        $this->coreRegistry = $coreRegistry;
        $this->storeManager = $storeManager;
        $this->resultPageFactory = $resultPageFactory;
        $this->templateFactory  = $templateFactory;
        $this->templateRepository = $templateRepository;
        $this->resultRawFactory = $resultRawFactory;
        $this->layoutFactory = $layoutFactory;
        $this->initializationHelper = $initializationHelper;
    }

    /**
     * Initialize requested template and put it into registry.
     *
     * @return \Aitoc\OptionsManagement\Model\Template
     */
    protected function initCurrentTemplate()
    {
        $storeId = $this->getRequest()->getParam('store', 0);
        $store = $this->storeManager->getStore($storeId);
        $this->storeManager->setCurrentStore($store->getCode());

        $templateId = (int)$this->getRequest()->getParam('id');
        if (!$templateId) {
            $templateId = (int)$this->getRequest()->getParam('template_id');
        }

        if ($templateId) {
            $model = $this->templateRepository->getById($templateId, $storeId);
        } else {
            $model = $this->templateRepository->getEmpty($storeId);
        }

        // set entered data if was error when we do save
        $data = $this->_session->getTemplateData(true);
        if (!empty($data)) {
            $model->addData($data);
        }

        $this->coreRegistry->register('current_template', $model);
        return $model;
    }
}
