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
abstract class Comment extends \Magento\Backend\App\Action
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
     * @var \Magento\Backend\Model\Session
     */
    private $backendSession;
    /**
     * @var \Magento\Framework\Registry
     */
    private $registry;
    /**
     * @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface
     */
    private $localeDate;
    /**
     * @var \Mirasvit\Kb\Helper\Data
     */
    private $kbData;
    /**
     * @var \Mirasvit\Kb\Model\CommentFactory
     */
    private $commentFactory;
    /**
     * @var \Mirasvit\Kb\Model\ArticleFactory
     */
    private $articleFactory;
    /**
     * @var \Mirasvit\Kb\Api\Service\Article\ArticleManagementInterface
     */
    private $articleManagement;

    /**
     * Comment constructor.
     * @param \Mirasvit\Kb\Api\Service\Article\ArticleManagementInterface $articleManagement
     * @param \Mirasvit\Kb\Model\ArticleFactory $articleFactory
     * @param \Mirasvit\Kb\Model\CommentFactory $commentFactory
     * @param \Mirasvit\Kb\Helper\Data $kbData
     * @param \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Backend\App\Action\Context $context
     */
    public function __construct(
        \Mirasvit\Kb\Api\Service\Article\ArticleManagementInterface $articleManagement,
        \Mirasvit\Kb\Model\ArticleFactory $articleFactory,
        \Mirasvit\Kb\Model\CommentFactory $commentFactory,
        \Mirasvit\Kb\Helper\Data $kbData,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate,
        \Magento\Framework\Registry $registry,
        \Magento\Backend\App\Action\Context $context
    ) {
        $this->articleManagement = $articleManagement;
        $this->articleFactory    = $articleFactory;
        $this->commentFactory    = $commentFactory;
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
        $resultPage->getConfig()->getTitle()->prepend(__('Comments'));

        return $resultPage;
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->context->getAuthorization()->isAllowed('Mirasvit_Kb::kb_comment');
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
