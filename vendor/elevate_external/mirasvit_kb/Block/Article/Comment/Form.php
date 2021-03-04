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

use Magento\Customer\Model\Session;
use Magento\Customer\Model\Url as CustomerUrl;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;
use Mirasvit\Kb\Model\Article;

class Form extends Template
{
    /**
     * @var Session
     */
    private $customerSession;

    /**
     * @var CustomerUrl
     */
    private $customerUrl;

    /**
     * @var Template\Context
     */
    private $context;

    /**
     * @var Registry
     */
    private $registry;

    /**
     * Form constructor.
     * @param Session $customerSession
     * @param CustomerUrl $customerUrl
     * @param Registry $registry
     * @param Template\Context $context
     * @param array $data
     */
    public function __construct(
        Session $customerSession,
        CustomerUrl $customerUrl,
        Registry $registry,
        Template\Context $context,
        array $data = []
    ) {
        $this->customerSession = $customerSession;
        $this->customerUrl     = $customerUrl;
        $this->registry        = $registry;
        $this->context         = $context;

        parent::__construct($context, $data);
    }

    /**
     * @return Article
     */
    public function getArticle()
    {
        return $this->registry->registry('current_article');
    }

    /**
     * @return bool
     */
    public function isAllowed()
    {
        return $this->customerSession->isLoggedIn();
    }

    /**
     * @return string
     */
    public function getLoginUrl()
    {
        return $this->customerUrl->getLoginUrl();
    }

    /**
     * @return string
     */
    public function getSubmitUrl()
    {
        return $this->context->getUrlBuilder()->getUrl('kbase/comment/save');
    }
}