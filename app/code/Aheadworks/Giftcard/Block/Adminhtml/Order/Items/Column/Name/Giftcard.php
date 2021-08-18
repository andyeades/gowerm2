<?php
/**
 * Aheadworks Inc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://aheadworks.com/end-user-license-agreement/
 *
 * @package    Giftcard
 * @version    1.4.6
 * @copyright  Copyright (c) 2021 Aheadworks Inc. (https://aheadworks.com/)
 * @license    https://aheadworks.com/end-user-license-agreement/
 */
namespace Aheadworks\Giftcard\Block\Adminhtml\Order\Items\Column\Name;

use Magento\Sales\Block\Adminhtml\Items\Column\Name as SalesColumnName;
use Magento\Backend\Block\Template\Context;
use Magento\CatalogInventory\Api\StockRegistryInterface;
use Magento\CatalogInventory\Api\StockConfigurationInterface;
use Magento\Framework\Registry;
use Magento\Catalog\Model\Product\OptionFactory;
use Aheadworks\Giftcard\Model\Product\Option\Render as OptionRender;

/**
 * Class Giftcard
 *
 * @package Aheadworks\Giftcard\Block\Adminhtml\Order\Items\Column\Name
 */
class Giftcard extends SalesColumnName
{
    /**
     * @var OptionRender
     */
    private $optionRender;

    /**
     * @param Context $context
     * @param StockRegistryInterface $stockRegistry
     * @param StockConfigurationInterface $stockConfiguration
     * @param Registry $registry
     * @param OptionFactory $optionFactory
     * @param OptionRender $optionRender
     * @param [] $data
     */
    public function __construct(
        Context $context,
        StockRegistryInterface $stockRegistry,
        StockConfigurationInterface $stockConfiguration,
        Registry $registry,
        OptionFactory $optionFactory,
        OptionRender $optionRender,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $stockRegistry,
            $stockConfiguration,
            $registry,
            $optionFactory,
            $data
        );
        $this->optionRender = $optionRender;
    }

    /**
     * {@inheritdoc}
     */
    public function getOrderOptions()
    {
        return $this->optionRender->render($this->getItem()->getProductOptions());
    }
}
