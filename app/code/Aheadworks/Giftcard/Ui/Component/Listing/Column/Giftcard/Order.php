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
namespace Aheadworks\Giftcard\Ui\Component\Listing\Column\Giftcard;

use Magento\Ui\Component\Listing\Columns\Column;

/**
 * Class Order
 *
 * @package Aheadworks\Giftcard\Ui\Component\Listing\Column
 */
class Order extends Column
{
    /**
     * {@inheritdoc}
     */
    public function prepareDataSource(array $dataSource)
    {
        $fieldName = $this->getData('name');
        foreach ($dataSource['data']['items'] as &$item) {
            if ($orderId = $item['order_id']) {
                if ($orderIncrementId = $item[$fieldName]) {
                    $item[$fieldName . '_url'] = $this->context->getUrl('sales/order/view', ['order_id' => $orderId]);
                    $item[$fieldName . '_label'] = $orderIncrementId;
                } else {
                    $item[$fieldName . '_label'] = $orderId;
                }
            }
        }
        return $dataSource;
    }
}
