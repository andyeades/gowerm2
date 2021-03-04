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

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Stdlib\DateTime;

class Articlesections extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * @var \Mirasvit\Core\Api\UrlRewriteHelperInterface
     */
    protected $urlRewrite;

    /**
     * @var \Magento\Framework\Model\ResourceModel\Db\Context
     */
    protected $context;

    /**
     * @var string|null
     */
    protected $resourcePrefix;

    /**
     * Application Cache Manager.
     *
     * @var \Magento\Framework\App\CacheInterface
     */
    protected $cacheManager;
    /**
     * @var \Mirasvit\Kb\Model\Config
     */
    private $config;

    /**
     * @param \Mirasvit\Core\Api\UrlRewriteHelperInterface $urlRewrite
     * @param \Magento\Framework\Model\ResourceModel\Db\Context $context
     * @param \Magento\Framework\App\CacheInterface $cacheManager
     * @param \Mirasvit\Kb\Model\Config $config
     * @param null $resourcePrefix
     */
    public function __construct(
        \Mirasvit\Core\Api\UrlRewriteHelperInterface $urlRewrite,
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        \Magento\Framework\App\CacheInterface $cacheManager,
        \Mirasvit\Kb\Model\Config $config,
        $resourcePrefix = null
    ) {
        $this->urlRewrite = $urlRewrite;
        $this->context = $context;
        $this->cacheManager = $cacheManager;
        $this->resourcePrefix = $resourcePrefix;
        $this->config = $config;

        parent::__construct($context, $resourcePrefix);
    }

    /**
     *
     */
    protected function _construct()
    {
        $this->_init('mst_kb_articlesections', 'articlesection_id');
    }

    /**
     * @param AbstractModel|\Mirasvit\Kb\Model\Articlesections $object
     * @return $this
     */
    protected function _afterLoad(AbstractModel $object)
    {

        return parent::_afterLoad($object);
    }

    /**
     * @param AbstractModel $object
     *
     * @return $this
     */
    protected function _beforeSave(AbstractModel $object)
    {
        /** @var \Mirasvit\Kb\Model\Articlesections $object */

        if (!$object->getId()) {
            $object->setCreatedAt((new \DateTime())->format(DateTime::DATETIME_PHP_FORMAT));
        }

        $object->setUpdatedAt((new \DateTime())->format(DateTime::DATETIME_PHP_FORMAT));

        if (!$urlKey = $object->getUrlKey()) {
            $urlKey = $object->getName();
        }
        $object->setUrlKey($this->urlRewrite->normalize($urlKey));

        return parent::_beforeSave($object);
    }

    /**
     * @param AbstractModel $object
     * @return $this
     */
    protected function _afterSave(AbstractModel $object)
    {
        /** @var \Mirasvit\Kb\Model\Articlesections $object */
        //$this->saveStoreIds($object);
        //$this->saveCategoryIds($object);
        //$this->saveTagIds($object);
        //$this->saveCustomerCategoryIds($object);

        return parent::_afterSave($object);
    }

    /**
     * @param AbstractModel $object
     * @return $this
     */
    protected function _afterDelete(AbstractModel $object)
    {
        return parent::_afterDelete($object);
    }
}
