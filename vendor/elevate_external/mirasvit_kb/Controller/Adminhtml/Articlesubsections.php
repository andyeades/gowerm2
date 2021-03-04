<?php
/**
 * Mirasvit
 *
 * This source file is subject to the Mirasvit Software License, which is available at https://mirasvit.com/license/.
 * Do not edit or add to this file if you wish to upgrade the to newer versions in the future.
 * If you wish to customize this module for your needs.
 * Please refer to http://www.magentocommerce.com for more information.
 *
 * @category  Mirasvit
 * @package   mirasvit/module-kb
 * @version   1.0.69
 * @copyright Copyright (C) 2020 Mirasvit (https://mirasvit.com/)
 */


namespace Mirasvit\Kb\Controller\Adminhtml;

use Magento\Store\Model\StoreFactory;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
abstract class Articlesubsections extends \Magento\Backend\App\Action
{
    /**
     * @var StoreFactory
     */
    protected $storeFactory;
    /**
     * @var \Magento\Backend\App\Action\Context
     */
    private $context;
    /**
     * @var \Magento\Framework\Registry
     */
    private $registry;
    /**
     * @var \Mirasvit\Kb\Model\ArticlesubsectionsFactory
     */
    protected $articlesubsectionsFactory;
    /**
     * @var \Magento\Backend\Model\Session
     */
    protected $backendSession;
    /**
     * @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface
     */
    private $localeDate;
    /**
     * @var \Mirasvit\Kb\Helper\Data
     */
    protected $kbData;
    /**
     * @var \Mirasvit\Kb\Helper\Tag
     */
    protected $kbTag;
    /**
     * @var \Mirasvit\Kb\Model\CategoryFactory
     */
    private $categoryFactory;
    /**
     * @var \Mirasvit\Kb\Api\Service\Articlesubsections\ArticlesubsectionsManagementInterface
     */
    protected $articlesubsectionsManagement;

    /**
     * Article constructor.
     * @param \Mirasvit\Kb\Api\Service\Articlesubsections\ArticlesubsectionsManagementInterface $articlesubsectionsManagement
     * @param \Mirasvit\Kb\Model\ArticlesubsectionsFactory $articlesubsectionsFactory
     * @param \Mirasvit\Kb\Model\CategoryFactory $categoryFactory
     * @param \Mirasvit\Kb\Helper\Tag $kbTag
     * @param \Mirasvit\Kb\Helper\Data $kbData
     * @param \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Backend\App\Action\Context $context
     */
    public function __construct(
        \Mirasvit\Kb\Api\Service\Articlesubsections\ArticlesubsectionsManagementInterface $articlesubsectionsManagement,
        \Mirasvit\Kb\Model\ArticlesubsectionsFactory $articlesubsectionsFactory,
        \Mirasvit\Kb\Model\CategoryFactory $categoryFactory,
        \Mirasvit\Kb\Helper\Tag $kbTag,
        \Mirasvit\Kb\Helper\Data $kbData,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate,
        \Magento\Framework\Registry $registry,
        \Magento\Backend\App\Action\Context $context
    ) {
        $this->articleManagement = $articlesubsectionsManagement;
        $this->articlesubsectionsFactory    = $articlesubsectionsFactory;
        $this->categoryFactory   = $categoryFactory;
        $this->kbTag             = $kbTag;
        $this->kbData            = $kbData;
        $this->localeDate        = $localeDate;
        $this->registry          = $registry;
        $this->context           = $context;
        $this->backendSession    = $context->getSession();
        $this->resultFactory     = $context->getResultFactory();

        parent::__construct($context);
    }

    /**
     * {@inheritdoc}
     * @param \Magento\Backend\Model\View\Result\Page $resultPage
     * @return \Magento\Backend\Model\View\Result\Page
     */
    protected function initPage($resultPage)
    {
        $resultPage->setActiveMenu('Mirasvit_Kb::kb');
        $resultPage->getConfig()->getTitle()->prepend(__('Knowledge Base'));
        $resultPage->getConfig()->getTitle()->prepend(__('Article Sub Sections'));

        return $resultPage;
    }
    /**
     * @return \Mirasvit\Kb\Model\Articlesubsections
     */
    public function _initModel()
    {
        $model = $this->articlesubsectionsFactory->create();
        if ($this->getRequest()->getParam('id')) {
            $model->load($this->getRequest()->getParam('id'));
        }
        $store = $this->getStoreFactory()->create();
        $store->load($this->getRequest()->getParam('store', 0));

        $this->registry->register('current_articlesubsection', $model);
        $this->registry->register('current_store', $store);

        return $model;
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->context->getAuthorization()->isAllowed('Mirasvit_Kb::kb_articlesubsection');
    }

    /**
     * @return StoreFactory
     */
    private function getStoreFactory()
    {
        if (null === $this->storeFactory) {
            $this->storeFactory = \Magento\Framework\App\ObjectManager::getInstance()
                ->get('Magento\Store\Model\StoreFactory');
        }
        return $this->storeFactory;
    }

}
