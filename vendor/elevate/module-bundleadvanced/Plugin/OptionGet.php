<?php

namespace Elevate\BundleAdvanced\Plugin;

//use Magento\Bundle\Api\Data\OptionInterface;
//use Magento\Framework\Api\ExtensionAttributesFactory;

class OptionGet {

    public function afterGet(
        \Magento\Bundle\Api\ProductOptionRepositoryInterface $subject,
        \Magento\Bundle\Api\Data\OptionInterface $resultOrder
    ) {
      //  $resultOrder = $this->getFoomanAttribute($resultOrder);
        $resultOrder = false;
        return $resultOrder;
    }

    private function getFoomanAttribute(\Magento\Bundle\Api\Data\OptionInterface $order) {


        try {
            // The actual implementation of the repository is omitted
            // but it is where you would load your value from the database (or any other persistent storage)
        //    $foomanAttributeValue = $this->foomanExampleRepository->get($order->getEntityId());
        } catch(NoSuchEntityException $e) {
            return $order;
        }

        $extensionAttributes = $order->getExtensionAttributes();
        $orderExtension = $extensionAttributes ? $extensionAttributes: $this->orderExtensionFactory->create();
        $foomanAttribute = $this->foomanAttributeFactory->create();
        $foomanAttribute->setValue($foomanAttributeValue);


        $orderExtension->setFoomanAttribute($foomanAttribute);
        $order->setExtensionAttributes($orderExtension);

        return $order;
    }
}