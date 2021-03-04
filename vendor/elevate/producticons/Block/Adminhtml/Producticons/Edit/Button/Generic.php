<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Elevate\ProductIcons\Block\Adminhtml\Producticons\Edit\Button;

use Elevate\ProductIcons\Api\ProducticonsRepositoryInterface;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\UiComponent\Context;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * Class Generic
 */
class Generic implements ButtonProviderInterface
{
    /**
     * Url Builder
     *
     * @var Context
     */
    protected $context;

    /**
     * Registry
     *
     * @var Registry
     */
    protected $registry;

  /**
   * Url Builder
   *
   * @var \Magento\Framework\UrlInterface
   */
  protected $urlBuilder;

    /**
     * Generic constructor
     *
     * @param Context $context
     * @param Registry $registry
     */
    public function __construct(
        Context $context,
        Registry $registry
    ) {
        $this->context = $context;
        $this->registry = $registry;
    }

    /**
     * Generate url by route and parameters
     *
     * @param string $route
     * @param array $params
     * @return string
     */
    public function getUrl($route = '', $params = [])
    {
        return $this->context->getUrl($route, $params);
    }

    /**
     * Get item
     *
     * @return ProducticonsRepositoryInterface
     */
    public function getProducticonsItem()
    {
        return $this->registry->registry('producticons');
    }

    /**
     * {@inheritdoc}
     */
    public function getButtonData()
    {
        return [];
    }
}
