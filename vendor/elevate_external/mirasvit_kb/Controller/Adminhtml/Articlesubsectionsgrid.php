<?php

namespace Mirasvit\Kb\Controller\Adminhtml;

class Articlesubsectionsgrid extends \Mirasvit\Kb\Controller\Adminhtml\Articlesubsections
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
     * @var \Magento\Framework\View\Result\LayoutFactory
     */
    protected $resultLayoutFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\LayoutFactory $resultLayoutFactory,
        \Mirasvit\Kb\Api\Service\Articlesubsections\ArticlesubsectionsManagementInterface $articlesubsectionsManagement,
        \Mirasvit\Kb\Model\ArticlesubsectionsFactory $articlesubsectionsFactory,
        \Mirasvit\Kb\Model\CategoryFactory $categoryFactory,
        \Mirasvit\Kb\Helper\Tag $kbTag,
        \Mirasvit\Kb\Helper\Data $kbData,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate,
        \Magento\Framework\Registry $registry
    ) {
        parent::__construct($articlesubsectionsManagement, $articlesubsectionsFactory, $categoryFactory, $kbTag, $kbData,$localeDate, $registry, $context);
        $this->resultLayoutFactory = $resultLayoutFactory;
    }

    /**
     * @return \Magento\Framework\View\Result\Layout
     */
    public function execute()
    {
        $resultLayout = $this->resultLayoutFactory->create();
        $resultLayout->getLayout()->getBlock('mirasvit.kb.article.edit.tab.articlesubsections');
        return $resultLayout;
    }

}
