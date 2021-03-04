<?php

namespace Punchout2go\Purchaseorder\Observer;

use Magento\Framework\Event\ObserverInterface;

class AddressMerger implements ObserverInterface
{

    /** @var \Punchout2go\Purchaseorder\Helper\Data $helper */
    protected $helper;

    /**
     * AddressMerger constructor.
     * @param \Punchout2go\Purchaseorder\Helper\Data $helper
     */
    public function __construct(
        \Punchout2go\Purchaseorder\Helper\Data $helper
    )
    {
        $this->helper = $helper;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /** @var \Punchout2go\Purchaseorder\Helper\Data $helper */
        $helper = $this->helper;
        $result = $observer->getData('result');

        $address = $result->getAddress();
        $defaults = $result->getDefault();

        $helper->debug("merging address with defaults");

        if (isset($defaults['enabled'])
            && !empty($defaults['enabled'])) {
            unset($defaults['enabled']);

            if (empty($address['firstname'])
                && empty($address['lastname'])
                && !empty($defaults['firstname'])
                && !empty($defaults['lastname'])
            ) {
                $address['firstname'] = $defaults['firstname'];
                $address['lastname'] = $defaults['lastname'];
            }

            if (empty($address['telephone'])
                && !empty($defaults['telephone'])
            ) {
                $address['telephone'] = $defaults['telephone'];
            }

            if (empty($address['country_id'])
                && empty($address['region_id'])
                && empty($address['postcode'])
                && empty($address['city'])
                && !empty($defaults['country_id'])
            ) {
                $address['country_id'] = $defaults['country_id'];
                if (!empty($defaults['region_id'])) {
                    $address['region_id'] = $defaults['region_id'];
                }
                if (!empty($defaults['city'])) {
                    $address['city'] = $defaults['city'];
                }
                if (!empty($defaults['postcode'])) {
                    $address['postcode'] = $defaults['postcode'];
                }
                if (!empty($defaults['street_line1'])) {
                    $address['street'] = $defaults['street_line1'];
                }
                if (!empty($defaults['street_line2'])) {
                    if (isset($address['street'])) {
                        $address['street'] = (array)$address['street'];
                        $address['street'][] = $defaults['street_line2'];
                    } else {
                        $address['street'] = $defaults['street_line2'];
                    }
                }
            }

            $result->setAddress($address);
        }
    }
}
