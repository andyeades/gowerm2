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



namespace Mirasvit\Kb\Model\Provider\Failure\RedirectUrl;

use Mirasvit\Kb\Model\ArticleFactory;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\UrlInterface;

if (interface_exists('\MSP\ReCaptcha\Model\Provider\Failure\RedirectUrlProviderInterface', false)) {
    interface MspRedirectUrlProviderInterface
        extends \MSP\ReCaptcha\Model\Provider\Failure\RedirectUrlProviderInterface {}
} else {
    interface MspRedirectUrlProviderInterface {}
}

class LocalComments implements MspRedirectUrlProviderInterface
{
    /**
     * @var ArticleFactory
     */
    private $articleFactory;
    /**
     * @var RequestInterface
     */
    private $request;
    /**
     * @var UrlInterface
     */
    private $url;

    /**
     * LocalComments constructor.
     * @param ArticleFactory $articleFactory
     * @param RequestInterface $request
     * @param UrlInterface $url
     */
    public function __construct(
        ArticleFactory $articleFactory,
        RequestInterface $request,
        UrlInterface $url
    ) {
        $this->articleFactory = $articleFactory;
        $this->request        = $request;
        $this->url            = $url;
    }

    /**
     * Get redirection URL
     * @return string
     */
    public function execute()
    {
        $articleId = (int)$this->request->getParam('article_id');
        $article = $this->getArticle($articleId);

        return $article->getUrl();
    }

    /**
     * @param int $articleId
     * @return \Mirasvit\Kb\Model\Article
     */
    protected function getArticle($articleId)
    {
        $article = $this->articleFactory->create();
        $article->getResource()->load($article, $articleId);

        return $article;
    }
}
