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


namespace Mirasvit\Kb\Api\Data;

interface CommentInterface
{
    const TABLE_NAME = 'mst_kb_comments';

    const KEY_ARTICLE_ID  = 'article_id';
    const KEY_CUSTOMER_ID = 'customer_id';
    const KEY_IS_APPROVED = 'is_approved';
    const KEY_IS_DELETED  = 'is_deleted';
    const KEY_MESSAGE     = 'message';
    const KEY_CREATED_AT  = 'created_at';

    /**
     * @return int
     */
    public function getArticleId();

    /**
     * @param int $articleId
     * @return $this
     */
    public function setArticleId($articleId);

    /**
     * @return int
     */
    public function getCustomerId();

    /**
     * @param int $customerId
     * @return $this
     */
    public function setCustomerId($customerId);

    /**
     * @return bool
     */
    public function getIsApproved();

    /**
     * @param bool $isApproved
     * @return $this
     */
    public function setIsApproved($isApproved);

    /**
     * @return bool
     */
    public function getIsDeleted();

    /**
     * @param bool $isDeleted
     * @return $this
     */
    public function setIsDeleted($isDeleted);

    /**
     * @return string
     */
    public function getMessage();

    /**
     * @param string $message
     * @return $this
     */
    public function setMessage($message);

    /**
     * @return string
     */
    public function getCreatedAt();

    /**
     * @param string $createdAt
     * @return $this
     */
    public function setCreatedAt($createdAt);
}

