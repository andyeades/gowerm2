<?php
/**
 * @copyright Copyright (c) 2016 www.tigren.com
 */

namespace Tigren\ProgressiveWebApp\Model;

class Notification extends \Magento\Framework\Model\AbstractModel
{

    /**
     * CMS page cache tag
     */
    const CACHE_TAG = 'pwa_notification';

    protected $_cacheTag = 'pwa_notification';

    /**
     * Prefix of model name
     *
     * @var string
     */
    protected $_notificationPrefix = 'pwa_notification';

    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    protected function _construct()
    {
        $this->_init('Tigren\ProgressiveWebApp\Model\ResourceModel\Notification');
    }
}
