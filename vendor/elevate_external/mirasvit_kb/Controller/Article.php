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



namespace Mirasvit\Kb\Controller;

use Magento\Framework\App\Action\Action;
use Magento\Customer\Model\Session;
use Magento\Backend\Model\View\Result\ForwardFactory;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
abstract class Article extends Action
{
    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;
    /**
     * @var \Mirasvit\Kb\Api\Service\Article\ArticleManagementInterface
     */
    private $articleManagementInterface;
    /**
     * @var \Mirasvit\Kb\Model\ArticleFactory
     */
    private $articleFactory;
    /**
     * @var \Magento\Catalog\Model\Session
     */
    private $session;
    /**
     * @var \Magento\Framework\App\Action\Context
     */
    private $context;
    /**
     * @var \Mirasvit\Kb\Helper\Vote
     */
    protected $kbVote;
    /**
     * @var \Mirasvit\Kb\Helper\Data
     */
    private $kbData;
    /**
     * @var \Mirasvit\Kb\Model\ResourceModel\Article\CollectionFactory
     */
    private $articleCollectionFactory;
    /**
     * @var ForwardFactory
     */
    protected $resultForwardFactory;

    /**
     * Article constructor.
     * @param ForwardFactory $resultForwardFactory
     * @param \Mirasvit\Kb\Api\Service\Article\ArticleManagementInterface $articleManagementInterface
     * @param \Mirasvit\Kb\Model\ArticleFactory $articleFactory
     * @param \Mirasvit\Kb\Model\ResourceModel\Article\CollectionFactory $articleCollectionFactory
     * @param \Magento\Catalog\Model\Session $session
     * @param \Mirasvit\Kb\Helper\Data $kbData
     * @param \Mirasvit\Kb\Helper\Vote $kbVote
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\App\Action\Context $context
     */
    public function __construct(
        ForwardFactory $resultForwardFactory,
        \Mirasvit\Kb\Api\Service\Article\ArticleManagementInterface $articleManagementInterface,
        \Mirasvit\Kb\Model\ArticleFactory $articleFactory,
        \Mirasvit\Kb\Model\ResourceModel\Article\CollectionFactory $articleCollectionFactory,
        \Magento\Catalog\Model\Session $session,
        \Mirasvit\Kb\Helper\Data $kbData,
        \Mirasvit\Kb\Helper\Vote $kbVote,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\Action\Context $context
    ) {
        $this->resultForwardFactory       = $resultForwardFactory;
        $this->articleManagementInterface = $articleManagementInterface;
        $this->articleFactory             = $articleFactory;
        $this->articleCollectionFactory   = $articleCollectionFactory;
        $this->session                    = $session;
        $this->kbData                     = $kbData;
        $this->kbVote                     = $kbVote;
        $this->registry                   = $registry;
        $this->context                    = $context;
        $this->resultFactory              = $context->getResultFactory();

        parent::__construct($context);
    }

    /**
     * @return \Magento\Catalog\Model\Session
     */
    protected function _getSession()
    {
        return $this->session;
    }

    /**
     * @return \Mirasvit\Kb\Model\Article
     */
    protected function _initArticle()
    {
        if ($id = $this->getRequest()->getParam('id')) {
            $article = $this->articleFactory->create()->load($id);
            $isAvailable = $this->articleManagementInterface->isAvailableForStore($article);

            if ($article->getId() > 0 && $isAvailable) {
                $this->registry->register('current_article', $article);

                return $article;
            }
        }
    }
}
