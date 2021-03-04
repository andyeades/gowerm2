<?php
namespace Elevate\Delivery\Plugin\Checkout\Model;

class DefaultConfigProvider
{

    /**
     *
     * @param \Magento\Checkout\Model\DefaultConfigProvider $subject
     * @param array $result
     * @return void
     */
    public function afterGetConfig(
        \Magento\Checkout\Model\DefaultConfigProvider $subject,
        $result
    ) {
        if(isset($result['quoteData'])) {
            foreach($result['quoteData'] as $itemKey => $quote) {
                if(isset($quote['extension_attributes']) && !is_array($quote['extension_attributes'])) {
                    $extensionAttributes = $quote['extension_attributes'];
                    $data = [];
                    foreach ($extensionAttributes->__toArray() as $key => $value) {
                        if (!is_object($value)) {
                            $data[$key] = $value;
                        }
                    }
                    $result['quoteData'][$itemKey]['extension_attributes'] = $data;
                }
            }
        }
        return $result;
    }
}
