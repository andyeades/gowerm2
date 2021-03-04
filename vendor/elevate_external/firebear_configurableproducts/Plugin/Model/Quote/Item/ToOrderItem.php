<?php
declare(strict_types=1);
/**
 * ToOrderItem
 *
 * @copyright Copyright © 2020 Firebear Studio. All rights reserved.
 * @author    fbeardev@gmail.com
 */

namespace Firebear\ConfigurableProducts\Plugin\Model\Quote\Item;

use Closure;
use Exception;
use Firebear\ConfigurableProducts\Logger\Logger;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Quote\Model\Quote\Item\AbstractItem;
use Magento\Sales\Api\Data\OrderItemInterface;

class ToOrderItem
{
    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @var Json|mixed|null
     */
    protected $serializer;

    /**
     * ToOrderItem constructor.
     * @param Logger $logger
     * @param Json|null $serializer
     */
    public function __construct(
        Logger $logger,
        Json $serializer = null
    ) {
        $this->logger = $logger;
        $this->serializer = $serializer ?: ObjectManager::getInstance()
            ->get(Json::class);
    }

    /**
     * @param \Magento\Quote\Model\Quote\Item\ToOrderItem $subject
     * @param Closure $proceed
     * @param AbstractItem $item
     * @param array $additional
     * @return OrderItemInterface
     */
    public function aroundConvert(
        \Magento\Quote\Model\Quote\Item\ToOrderItem $subject,
        Closure $proceed,
        AbstractItem $item,
        $additional = []
    ) {
        $additionalOptions = [];
        /** @var $orderItem OrderItemInterface */
        $orderItem = $proceed($item, $additional);
        try {
            $options = $orderItem->getProductOptions();
            if ($item->getOptionByCode('additional_options')) {
                $additionalOptions = $item->getOptionByCode('additional_options');
            }

            if (!empty($additionalOptions)) {
                $options['additional_options'] = $this->serializer->unserialize(
                    $item->getOptionByCode('additional_options')->getValue()
                );
            }
            $orderItem->setProductOptions($options);
        } catch (Exception $exception) {
            $this->logger->error($exception->getMessage());
        }
        return $orderItem;
    }
}
