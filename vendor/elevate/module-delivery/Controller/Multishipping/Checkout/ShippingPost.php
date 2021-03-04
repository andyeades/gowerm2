<?php
/**
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Elevate\Delivery\Controller\Multishipping\Checkout;

use Magento\Multishipping\Model\Checkout\Type\Multishipping\State;

class ShippingPost extends \Magento\Multishipping\Controller\Checkout
{
    /**
     * @return void
     */
    public function execute()
    {
        $shippingMethods = $this->getRequest()->getPost('shipping_method');

        try {
            $this->_eventManager->dispatch(
                'checkout_controller_multishipping_shipping_post',
                ['request' => $this->getRequest(), 'quote' => $this->_getCheckout()->getQuote()]
            );
            // Check If Method Ok
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            /* @var $evDeliveryHelper \Elevate\Delivery\Helper\General */
            $evDeliveryHelper = $objectManager->get('Elevate\Delivery\Helper\General');

            $quote = $this->_getCheckout()->getQuote();


            $params = $this->getRequest()->getPost();

            $deliveries_to_check = array();
            $data = array();

            foreach($params as $key => $value) {



                if (strpos($key, 'dateselected_') !== false)
                {
                    $address_id = substr($key, strrpos($key, '_'));
                    $deliveries_to_check[$address_id]['dateselected'] = $value;
                    $split = explode('_', $value);

                    $date = $split[0];
                    $method_id = $split[1];
                    $delivery_area_id = $split[2];
                    $deliveries_to_check[$address_id]['method_id'] = $method_id;
                    $deliveries_to_check[$address_id]['area_id'] = $delivery_area_id;
                    $deliveries_to_check[$address_id]['date'] = $date;
                }

                if (strpos($key, 'postcode_') !== false)
                {
                    $address_id = substr($key, strrpos($key, '_'));
                    $deliveries_to_check[$address_id]['postcode'] = $value;
                }

                if (strpos($key, 'country_') !== false)
                {
                    $address_id = substr($key, strrpos($key, '_'));
                    $deliveries_to_check[$address_id]['country'] = $value;
                }

            }

            $validchecks = array();

            foreach($deliveries_to_check as $key => $value) {
                $cart = $quote;



                $checkValid = $evDeliveryHelper->checkDeliveryMethodValid($value['method_id'], $value['postcode'], $value['country'], $value['dateselected'], $cart);

                $validchecks[] = $checkValid;
            }

            $all_valid = true;

            foreach ($validchecks as $check) {
                if ($check != true) {
                    $all_valid = false;
                }
            }




            if ($all_valid == true) {
                // All Delivery Selected = Good - Save the info to the Quote?

                $all_shipping_addresses = $cart->getAllShippingAddresses();

                foreach ($all_shipping_addresses as $shipping_address) {

                    $items = $shipping_address->getItems();

                    $shipping_address_id = (int) $shipping_address->getAddressId();

                    $this_address_data = $deliveries_to_check['_'.$shipping_address_id];

                    $shipping_address->setDeliveryDateSelected($this_address_data['dateselected']);
                    $shipping_address->setDeliveryOptionSelected($this_address_data['method_id']);
                    $shipping_address->setDeliveryAreaSelected($this_address_data['area_id']);
                    $shipping_address->save();


                }

            } else {
                // Throw Error
                throw new \Exception(
                    'Method selected is invalid, please try again.'
                );
            }

            $this->_getCheckout()->setShippingMethods($shippingMethods);
            $this->_getState()->setActiveStep(State::STEP_BILLING);
            $this->_getState()->setCompleteStep(State::STEP_SHIPPING);
            $this->_redirect('*/*/billing');
        } catch (\Exception $e) {
            $this->messageManager->addError($e->getMessage());
            $this->_redirect('*/*/shipping');
        }
    }
}
