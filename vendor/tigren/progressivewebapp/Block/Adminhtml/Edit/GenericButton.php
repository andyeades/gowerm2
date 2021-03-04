<?php
/**
 * @copyright Copyright (c) 2017 www.tigren.com
 */

namespace Tigren\ProgressiveWebApp\Block\Adminhtml\Edit;

class GenericButton
{
    protected $urlBuilder;
    protected $registry;

    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $registry
    ) {
        $this->urlBuilder = $context->getUrlBuilder();
        $this->registry = $registry;
    }

    public function getNotificationId()
    {
        return $this->registry->registry('current_notification_id');
    }

    public function getUrl($route = '', $params = [])
    {
        return $this->urlBuilder->getUrl($route, $params);
    }
}
