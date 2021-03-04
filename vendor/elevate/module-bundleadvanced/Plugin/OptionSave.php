<?php

namespace Elevate\BundleAdvanced\Plugin;

use Magento\Framework\Exception\CouldNotSaveException;

class OptionSave {


    public function afterSave(
        \Magento\Bundle\Api\ProductOptionRepositoryInterface $subject,
        \Magento\Bundle\Api\Data\OptionInterface $resultOrder
    ) {
        $resultOrder = $this->saveFoomanAttribute($resultOrder);
        $resultOrder = false;
        return $resultOrder;
    }

    private function saveFoomanAttribute(\Magento\Bundle\Api\Data\OptionInterface $order) {
        echo "SAVE";
        exit;

        $extensionAttributes = $order->getExtensionAttributes();
        if (NULL !== $extensionAttributes && NULL !== $extensionAttributes->getFoomanAttribute()) {
            $foomanAttributeValue = $extensionAttributes->getFoomanAttribute()->getValue();
            try {
                // The actual implementation of the repository is omitted
                // but it is where you would save to the database (or any other persistent storage)
                $this->foomanExampleRepository->save($order->getEntityId(), $foomanAttributeValue);
            } catch(\Exception $e) {
                throw new CouldNotSaveException(
                    __('Could not add attribute to order: "%1"', $e->getMessage()), $e
                );
            }
        }

        return $order;
    }
}