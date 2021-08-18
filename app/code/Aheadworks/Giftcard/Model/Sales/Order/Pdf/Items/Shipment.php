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
namespace Aheadworks\Giftcard\Model\Sales\Order\Pdf\Items;

use Magento\Framework\Model\Context;
use Magento\Framework\Registry;
use Magento\Framework\Filesystem;
use Magento\Framework\Filter\FilterManager;
use Magento\Framework\Stdlib\StringUtils;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Tax\Helper\Data as TaxHelperData;
use Magento\Sales\Model\Order\Pdf\Items\Shipment\DefaultShipment;
use Aheadworks\Giftcard\Model\Product\Option\Render as OptionRender;

/**
 * Class Shipment
 *
 * @package Aheadworks\Giftcard\Model\Sales\Order\Pdf\Items
 */
class Shipment extends DefaultShipment
{
    /**
     * @var OptionRender
     */
    private $optionRender;

    /**
     * @param Context $context
     * @param Registry $registry
     * @param TaxHelperData $taxData
     * @param Filesystem $filesystem
     * @param FilterManager $filterManager
     * @param StringUtils $string
     * @param OptionRender $optionRender
     * @param AbstractResource|null $resource
     * @param AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        TaxHelperData $taxData,
        Filesystem $filesystem,
        FilterManager $filterManager,
        StringUtils $string,
        OptionRender $optionRender,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->optionRender = $optionRender;
        parent::__construct(
            $context,
            $registry,
            $taxData,
            $filesystem,
            $filterManager,
            $string,
            $resource,
            $resourceCollection,
            $data
        );
    }

    /**
     * @inheritdoc
     */
    public function getItemOptions()
    {
        $orderItem = $this->getItem()->getOrderItem();
        return $this->optionRender->render(
            $orderItem->getProductOptions(),
            OptionRender::FRONTEND_SECTION
        );
    }
}
