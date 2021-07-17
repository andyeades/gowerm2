<?php
/**
 * @copyright Copyright (c) 2017 www.tigren.com
 */
namespace Tigren\ProgressiveWebApp\Model\ResourceModel;

use Magento\Framework\Model\AbstractModel;

/**
 * Mysql resource
 */
class Notification extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        $resourcePrefix = null
    ) {
        parent::__construct($context, $resourcePrefix);
    }

    protected function _construct()
    {
        $this->_init('tigren_pwa_notification', 'notification_id');
    }

}
