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


namespace Mirasvit\Kb\Controller\Comment;

use Magento\Framework\Controller\ResultFactory;

class Save extends \Mirasvit\Kb\Controller\Comment
{
    /**
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);

        if (
            ($message = htmlspecialchars($this->getRequest()->getParam('message'))) && //XSS protection here
            ($articleId = (int)$this->getRequest()->getParam('article_id'))
        ) {
            $article = $this->getArticle($articleId);
            /** @var \Mirasvit\Kb\Model\Comment $comment */
            $comment = $this->commentFactory->create();
            $comment->setMessage($message)
                ->setCustomerId($this->customerSession->getCustomerId())
                ->setArticleId($article->getId());

            $comment->getResource()
                ->save($comment);

            if ($this->config->getApproveLocalComments()) {
                $this->messageManager->addSuccessMessage(__('You submitted your comment for moderation.'));
            }
            $this->_redirect($article->getUrl());

            return $resultPage;
        } else {
            return $this->_forward('noroute');
        }
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
