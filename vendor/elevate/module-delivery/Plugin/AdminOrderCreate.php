<?php

namespace Elevate\Delivery\Plugin;


/**
 * Class
 */
class AdminOrderCreate
{

    public function afterImportPostData(
        \Magento\Sales\Model\AdminOrder\Create $subject,
        $result
    ) {
        $data = $subject->getData();

        if (isset($data['delivery_date_selected'])) {
            $subject->getQuote()->setDeliveryDateSelected($data['delivery_date_selected']);
        }
        if (isset($data['detailed_delivery_info_dates'])) {
            $subject->getQuote()->setDetailedDeliveryInfoDates($data['detailed_delivery_info_dates']);
        }

        return $result;
    }

}
