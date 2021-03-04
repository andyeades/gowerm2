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



namespace Mirasvit\Kb\Block\Article\Comment;

use Magento\Eav\Model\Entity\Collection\AbstractCollection;
use Magento\Framework\DataObject\IdentityInterface;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Listing extends \Magento\Framework\View\Element\Template implements IdentityInterface
{
    /**
     * Default toolbar block name.
     * @var string
     */
    protected $defaultToolbarBlock = 'Mirasvit\Kb\Block\Article\CommentsList\Toolbar';
    /**
     * @var \Mirasvit\Kb\Model\Config
     */
    private $config;
    /**
     * @var AbstractCollection
     */
    private $collection;
    /**
     * @var \Magento\Framework\Registry
     */
    private $registry;
    /**
     * @var \Mirasvit\Kb\Model\ResourceModel\Comment\CollectionFactory
     */
    private $commentCollectionFactory;
    /**
     * @var \Magento\Customer\Model\CustomerFactory
     */
    private $customerFactory;
    /**
     * @var \Magento\Framework\View\Element\Template\Context
     */
    private $context;
    /**
     * @var \Mirasvit\Kb\Helper\Data
     */
    private $kbData;
    /**
     * @var \Magento\Framework\Registry
     */
    private $coreRegistry;
    /**
     * @var \Magento\Framework\Url\Helper\Data
     */
    private $urlHelper;
    /**
     * @var \Mirasvit\Kb\Model\CategoryFactory
     */
    private $categoryFactory;
    /**
     * @var \Magento\Customer\Model\Session
     */
    private $customerSession;

    /**
     * Listing constructor.
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Customer\Model\CustomerFactory $customerFactory
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Mirasvit\Kb\Model\Config $config
     * @param \Mirasvit\Kb\Model\CategoryFactory $categoryFactory
     * @param \Mirasvit\Kb\Model\ResourceModel\Comment\CollectionFactory $commentCollectionFactory
     * @param \Magento\Framework\Url\Helper\Data $urlHelper
     * @param \Magento\Framework\Registry $registry
     * @param \Mirasvit\Kb\Helper\Data $kbData
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        \Magento\Customer\Model\Session $customerSession,
        \Mirasvit\Kb\Model\Config $config,
        \Mirasvit\Kb\Model\CategoryFactory $categoryFactory,
        \Mirasvit\Kb\Model\ResourceModel\Comment\CollectionFactory $commentCollectionFactory,
        \Magento\Framework\Url\Helper\Data $urlHelper,
        \Magento\Framework\Registry $registry,
        \Mirasvit\Kb\Helper\Data $kbData,
        array $data = []
    ) {
        $this->customerFactory          = $customerFactory;
        $this->customerSession          = $customerSession;
        $this->config                   = $config;
        $this->categoryFactory          = $categoryFactory;
        $this->commentCollectionFactory = $commentCollectionFactory;
        $this->urlHelper                = $urlHelper;
        $this->coreRegistry             = $registry;
        $this->registry                 = $registry;
        $this->kbData                   = $kbData;
        $this->context                  = $context;

        parent::__construct($context, $data);
    }

    /**
     * Retrieve loaded category collection.
     * @return Listing|\Mirasvit\Kb\Model\ResourceModel\Comment\Collection
     */
    public function getLoadedCommentCollection()
    {
        return $this->getCommentCollection();
    }

    /**
     * Retrieve current view mode.
     * @return string
     */
    public function getMode()
    {
        return $this->getChildBlock('toolbar')->getCurrentMode();
    }

    /**
     * Need use as _prepareLayout - but problem in declaring collection from
     * another block (was problem with search result).
     * @return $this
     */
    protected function _beforeToHtml()
    {
        $toolbar = $this->getToolbarBlock();

        // called prepare sortable parameters
        $collection = $this->getCommentCollection();

        // use sortable parameters
        $orders = $this->getAvailableOrders();
        if ($orders) {
            $toolbar->setAvailableOrders($orders);
        }
        $sort = $this->getSortBy();
        if ($sort) {
            $toolbar->setDefaultOrder($sort);
        }
        $dir = $this->getDefaultDirection();
        if ($dir) {
            $toolbar->setDefaultDirection($dir);
        }
        $modes = $this->getModes();
        if ($modes) {
            $toolbar->setModes($modes);
        }

        // set collection to toolbar and apply sort
        $toolbar->setCollection($collection);

        $this->setChild('toolbar', $toolbar);
        $this->_eventManager->dispatch(
            'article_block_comment_list_collection',
            ['collection' => $this->getCommentCollection()]
        );

        $this->setCollection($toolbar->getCollection());

        $this->getCommentCollection()->load();

        return parent::_beforeToHtml();
    }

    /**
     * Retrieve Toolbar block.
     * @return \Mirasvit\Kb\Block\Article\ArticleList\Toolbar
     */
    public function getToolbarBlock()
    {
        $blockName = $this->getToolbarBlockName();
        if ($blockName) {
            $block = $this->getLayout()->getBlock($blockName);
            if ($block) {
                return $block;
            }
        }
        $block = $this->getLayout()->createBlock($this->defaultToolbarBlock, uniqid(microtime()));

        return $block;
    }

    /**
     * Retrieve additional blocks html.
     * @return string
     */
    public function getAdditionalHtml()
    {
        return $this->getChildHtml('additional');
    }

    /**
     * Retrieve list toolbar HTML.
     * @return string
     */
    public function getToolbarHtml()
    {
        return $this->getLayout()->getBlock($this->getToolbarBlockName())->toHtml();
    }

    /**
     * @param AbstractCollection $collection
     *
     * @return $this
     */
    public function setCollection($collection)
    {
        $this->collection = $collection;

        return $this;
    }

    /**
     * @param int $code
     *
     * @return $this
     */
    public function addAttribute($code)
    {
        $this->getCommentCollection()->addAttributeToSelect($code);

        return $this;
    }

    /**
     * Return identifiers for article content.
     * @return array
     */
    public function getIdentities()
    {
        $identities = [];
        foreach ($this->getCommentCollection() as $item) {
            $identities = array_merge($identities, $item->getIdentities());
        }

        return $identities;
    }

    /**
     * @param \Mirasvit\Kb\Model\Comment $comment
     *
     * @return string
     */
    public function getCustomerName($comment)
    {
        $customer = $this->customerFactory->create();
        $customer->getResource()->load($customer, $comment->getCustomerId());

        return $customer->getName();
    }

    /**
     * @return $this|\Mirasvit\Kb\Model\ResourceModel\Comment\Collection
     */
    public function getCommentCollection()
    {
        $toolbar = $this->getToolbarBlock();

        if (empty($this->collection)) {
            $collection = $this->commentCollectionFactory->create()
                ->addFieldToFilter('main_table.is_approved', true)
                ->addFieldToFilter('main_table.is_deleted', 0);
            $collection->addFieldToFilter('main_table.article_id', $this->registry->registry('current_article')->getId());

            $collection->setCurPage($this->getCurrentPage());

            $limit = (int)$toolbar->getLimit();
            if ($limit) {
                $collection->setPageSize($limit);
            }
            $page = (int)$toolbar->getCurrentPage();
            if ($page) {
                $collection->setCurPage($page);
            }
            if ($order = $toolbar->getCurrentOrder()) {
                $collection->setOrder($order, $toolbar->getCurrentDirection());
            }
            $this->collection = $collection;
        }

        return $this->collection;
    }

    /**
     * @return bool
     */
    public function isRatingEnabled()
    {
        return $this->config->isRatingEnabled();
    }

    /**
     * @param null $type
     *
     * @return bool|\Magento\Framework\View\Element\AbstractBlock
     */
    public function getDetailsRenderer($type = null)
    {
        if ($type === null) {
            $type = 'kb.default';
        }
        $rendererList = $this->getDetailsRendererList();
        if ($rendererList) {
            return $rendererList->getRenderer($type, 'kb.default');
        }

        return;
    }

    /**
     * @return \Magento\Framework\View\Element\RendererList
     */
    protected function getDetailsRendererList()
    {
        return $this->getDetailsRendererListName() ? $this->getLayout()->getBlock(
            $this->getDetailsRendererListName()
        ) : $this->getChildBlock(
            'kb.details.renderers'
        );
    }
}
