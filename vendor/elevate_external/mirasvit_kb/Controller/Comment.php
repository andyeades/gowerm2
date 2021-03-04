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

use Mirasvit\Kb\Model\ArticleFactory;
use Mirasvit\Kb\Model\CommentFactory;
use Mirasvit\Kb\Model\Config;
use Magento\Framework\App\Action\Action;
use Magento\Framework\Exception\NotFoundException;
use Magento\Customer\Model\Session;
use Magento\Backend\Model\View\Result\ForwardFactory;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
abstract class Comment extends Action
{
    /**
     * @var ArticleFactory
     */
    protected $articleFactory;
    /**
     * @var CommentFactory
     */
    protected $commentFactory;
    /**
     * @var Config
     */
    protected $config;
    /**
     * @var Session
     */
    protected $customerSession;
    /**
     * @var ForwardFactory
     */
    protected $resultForwardFactory;
    /**
     * @var \Magento\Framework\App\Action\Context
     */
    private $context;

    /**
     * Comment constructor.
     * @param ArticleFactory $articleFactory
     * @param CommentFactory $commentFactory
     * @param Config $config
     * @param Session $customerSession
     * @param ForwardFactory $resultForwardFactory
     * @param \Magento\Framework\App\Action\Context $context
     */
    public function __construct(
        ArticleFactory $articleFactory,
        CommentFactory $commentFactory,
        Config $config,
        Session $customerSession,
        ForwardFactory $resultForwardFactory,
        \Magento\Framework\App\Action\Context $context
    ) {
        $this->articleFactory       = $articleFactory;
        $this->commentFactory       = $commentFactory;
        $this->config               = $config;
        $this->customerSession      = $customerSession;
        $this->resultForwardFactory = $resultForwardFactory;
        $this->context              = $context;
        $this->resultFactory        = $context->getResultFactory();

        parent::__construct($context);
    }

    /**
     * @param \Magento\Framework\App\RequestInterface $request
     * @return \Magento\Framework\App\ResponseInterface
     * @throws NotFoundException
     */
    public function dispatch(\Magento\Framework\App\RequestInterface $request)
    {
        if (!$this->customerSession->authenticate()) {
            $this->_actionFlag->set('', self::FLAG_NO_DISPATCH, true);
            throw new NotFoundException(__('Page not found.'));
        }

        return parent::dispatch($request);
    }
}
