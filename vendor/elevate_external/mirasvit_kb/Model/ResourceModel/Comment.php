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


namespace Mirasvit\Kb\Model\ResourceModel;

use Mirasvit\Kb\Model\Config;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Comment extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * @var Config
     */
    private $config;

    /**
     * Comment constructor.
     * @param Config $config
     * @param \Magento\Framework\Model\ResourceModel\Db\Context $context
     * @param null $resourcePrefix
     */
    public function __construct(
        Config $config,
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        $resourcePrefix = null
    ) {
        $this->config = $config;

        parent::__construct($context, $resourcePrefix);
    }

    /**
     *
     */
    protected function _construct()
    {
        $this->_init('mst_kb_comments', 'comment_id');
    }

    /**
     * @param \Magento\Framework\Model\AbstractModel $object
     *
     * @return $this
     */
    protected function _beforeSave(\Magento\Framework\Model\AbstractModel $object)
    {
        /** @var  \Mirasvit\Kb\Model\Comment $object */
        if (!$object->getId()) {
            $object->setCreatedAt((new \DateTime())->format(\Magento\Framework\Stdlib\DateTime::DATETIME_PHP_FORMAT));
            $object->setIsApproved(!$this->config->getApproveLocalComments());
        }

        return parent::_beforeSave($object);
    }
}
