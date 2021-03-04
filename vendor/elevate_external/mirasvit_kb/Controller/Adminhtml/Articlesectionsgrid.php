<?php

namespace Mirasvit\Kb\Controller\Adminhtml;

class Articlesectionsgrid extends \Mirasvit\Kb\Controller\Adminhtml\Article
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
     * @var \Mirasvit\Kb\Model\ArticleFactory
     */
    protected $articleFactory;
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
     * @var \Mirasvit\Kb\Api\Service\Article\ArticleManagementInterface
     */
    protected $articleManagement;


    /**
     * @var \Magento\Framework\View\Result\LayoutFactory
     */
    protected $resultLayoutFactory;


    /**
     * @param \Magento\Framework\View\Result\LayoutFactory $resultLayoutFactory
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Mirasvit\Kb\Api\Service\Article\ArticleManagementInterface $articleManagement
     * @param \Mirasvit\Kb\Model\ArticleFactory $articleFactory
     * @param \Mirasvit\Kb\Model\CategoryFactory $categoryFactory
     * @param \Mirasvit\Kb\Helper\Tag $kbTag
     * @param \Mirasvit\Kb\Helper\Data $kbData
     * @param \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate
     * @param \Magento\Framework\Registry $registry
     *
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\LayoutFactory $resultLayoutFactory,
        \Mirasvit\Kb\Api\Service\Article\ArticleManagementInterface $articleManagement,
        \Mirasvit\Kb\Model\ArticleFactory $articleFactory,
        \Mirasvit\Kb\Model\CategoryFactory $categoryFactory,
        \Mirasvit\Kb\Helper\Tag $kbTag,
        \Mirasvit\Kb\Helper\Data $kbData,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate,
        \Magento\Framework\Registry $registry
    ) {
        parent::__construct($articleManagement, $articleFactory, $categoryFactory, $kbTag, $kbData,$localeDate, $registry, $context);
        $this->resultLayoutFactory = $resultLayoutFactory;
    }

    /**
     * @return \Magento\Framework\View\Result\Layout
     */
    public function execute()
    {
        $resultLayout = $this->resultLayoutFactory->create();
        $resultLayout->getLayout()->getBlock('mirasvit.kb.article.edit.tab.articlesections');
        return $resultLayout;
    }

}
