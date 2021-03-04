<?php

namespace Firebear\ConfigurableProducts\Plugin\Wishlist\Controller\Index;

use Magento\Framework\Serialize\Serializer\Json;

class Add
{
    /**
     * @var Json
     */
    protected $serializerJson;

    public function __construct(Json $serializerJson)
    {
        $this->serializerJson = $serializerJson;
    }

    public function beforeExecute(\Magento\Wishlist\Controller\Index\Add $subject)
    {
        $paramsRequest = $subject->getRequest()->getParams();
        if (isset($paramsRequest['super_attribute']) && !is_array($paramsRequest['super_attribute'])) {
            $paramsRequest['super_attribute'] = $this->serializerJson->unserialize($paramsRequest['super_attribute']);
            foreach ($paramsRequest['super_attribute'] as $bundleOptionId => $configProdOptions) {
                foreach ($configProdOptions as $optionId => $optionValue) {
                    if (!$optionValue) {
                        unset($paramsRequest['bundle_option'][$bundleOptionId]);
                        $subject->getRequest()->setPostvalue('bundle_option', $paramsRequest['bundle_option']);
                    }
                }
            }
            if (isset($paramsRequest['bundle_custom_options'])) {
                $paramsRequest['options'] = $paramsRequest['bundle_custom_options'];
            }
        }
        $subject->getRequest()->setParams($paramsRequest);
    }
}
