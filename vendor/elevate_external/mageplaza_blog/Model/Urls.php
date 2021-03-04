<?php

namespace Mageplaza\Blog\Model;

use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;
use Mageplaza\Blog\Helper\Data;

/**
 * Class Author
 * @package Mageplaza\Blog\Model
 */
class Urls extends AbstractModel
{
    /**
     * @inheritdoc
     */
    const CACHE_TAG = 'mageplaza_blog_urls';

    /**
     * @var \Mageplaza\Blog\Helper\Data
     */
    protected $helperData;

    /**
     * Author constructor.
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Mageplaza\Blog\Helper\Data $helperData
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        Data $helperData,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    )
    {
        $this->helperData = $helperData;

        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Constructor
     */
    protected function _construct()
    {
        $this->_init('Mageplaza\Blog\Model\ResourceModel\Urls');
    }

    /**
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

}
