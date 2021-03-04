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


namespace Mirasvit\Kb\Model;

use Magento\Framework\DataObject\IdentityInterface;
use Mirasvit\Kb\Api\Data\CommentInterface;

class Comment extends \Magento\Framework\Model\AbstractModel implements IdentityInterface, CommentInterface
{
    const CACHE_TAG = 'kb_article_comment';

    /**
     * @var string
     */
    protected $_cacheTag = 'kb_article_comment';//@codingStandardsIgnoreLine

    /**
     * @var string
     */
    protected $_eventPrefix = 'kb_article_comment';//@codingStandardsIgnoreLine
    /**
     * @var \Magento\Framework\Data\Collection\AbstractDb
     */
    private $resourceCollection;
    /**
     * @var \Magento\Framework\Model\ResourceModel\AbstractResource
     */
    private $resource;
    /**
     * @var Config
     */
    private $config;
    /**
     * @var \Magento\Framework\Registry
     */
    private $registry;
    /**
     * @var \Magento\Framework\Model\Context
     */
    private $context;

    /**
     * Get identities.
     *
     * @return array
     */
    public function getIdentities()
    {
        $identities = [];
        if ($this->hasDataChanges() || $this->isDeleted()) {
            $identities[] = self::CACHE_TAG. '_' . $this->getId();
        }

        return $identities;
    }

    /**
     * Comment constructor.
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param Config $config
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Mirasvit\Kb\Model\Config $config,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->context            = $context;
        $this->registry           = $registry;
        $this->config             = $config;
        $this->resource           = $resource;
        $this->resourceCollection = $resourceCollection;

        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     *
     */
    protected function _construct()
    {
        $this->_init('Mirasvit\Kb\Model\ResourceModel\Comment');
    }

    /**
     * {@inheritdoc}
     */
    public function getArticleId()
    {
        return $this->getData(self::KEY_ARTICLE_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setArticleId($articleId)
    {
        return $this->setData(self::KEY_ARTICLE_ID, $articleId);
    }

    /**
     * {@inheritdoc}
     */
    public function getCustomerId()
    {
        return $this->getData(self::KEY_CUSTOMER_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setCustomerId($customerId)
    {
        return $this->setData(self::KEY_CUSTOMER_ID, $customerId);
    }

    /**
     * {@inheritdoc}
     */
    public function getIsApproved()
    {
        return $this->getData(self::KEY_IS_APPROVED);
    }

    /**
     * {@inheritdoc}
     */
    public function setIsApproved($isApproved)
    {
        return $this->setData(self::KEY_IS_APPROVED, $isApproved);
    }

    /**
     * {@inheritdoc}
     */
    public function getIsDeleted()
    {
        return $this->getData(self::KEY_IS_DELETED);
    }

    /**
     * {@inheritdoc}
     */
    public function setIsDeleted($isDeleted)
    {
        return $this->setData(self::KEY_IS_DELETED, $isDeleted);
    }

    /**
     * {@inheritdoc}
     */
    public function getMessage()
    {
        return $this->getData(self::KEY_MESSAGE);
    }

    /**
     * {@inheritdoc}
     */
    public function setMessage($message)
    {
        return $this->setData(self::KEY_MESSAGE, $message);
    }

    /**
     * {@inheritdoc}
     */
    public function getCreatedAt()
    {
        return $this->getData(self::KEY_CREATED_AT);
    }

    /**
     * {@inheritdoc}
     */
    public function setCreatedAt($createdAt)
    {
        return $this->setData(self::KEY_CREATED_AT, $createdAt);
    }
}
