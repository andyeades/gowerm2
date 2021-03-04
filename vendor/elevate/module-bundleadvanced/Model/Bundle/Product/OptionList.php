<?php

namespace Elevate\BundleAdvanced\Model\Bundle\Product;

class OptionList extends \Magento\Bundle\Model\Product\OptionList
{
    /**
     * @param \Magento\Catalog\Api\Data\ProductInterface $product
     * @return \Magento\Bundle\Api\Data\OptionInterface[]
     */
    public function getItems(\Magento\Catalog\Api\Data\ProductInterface $product)
    {
        $optionCollection = $this->type->getOptionsCollection($product);
        $this->extensionAttributesJoinProcessor->process($optionCollection);
        $optionList = [];
        /** @var \Magento\Bundle\Model\Option $option */
        foreach ($optionCollection as $option) {
            $productLinks = $this->linkList->getItems($product, $option->getOptionId());
            /** @var \Magento\Bundle\Api\Data\OptionInterface $optionDataObject */
            $optionDataObject = $this->optionFactory->create();
            $this->dataObjectHelper->populateWithArray(
                $optionDataObject,
                $option->getData(),
                \Magento\Bundle\Api\Data\OptionInterface::class
            );
            $optionDataObject->setOptionId($option->getOptionId())
                             ->setTitle($option->getTitle() === null ? $option->getDefaultTitle() : $option->getTitle())
                             ->setDefaultTitle($option->getDefaultTitle())
                             ->setSku($product->getSku())
                             ->setMinQty($option->getMinQty()) // retrieve "min_qty" from db
                             ->setMaxQty($option->getMaxQty()) // retrieve "max_qty" from db
                             ->setIsLeaseMachine($option->getIsLeaseMachine()) // retrieve "is_lease_machine" from db
                             ->setOptionTooltip($option->getOptionTooltip()) // retrieve "is_lease_machine" from db
                             ->setDefaultOptionText($option->getDefaultOptionText()) // retrieve "is_lease_machine" from db
                             ->setProductLinks($productLinks);
            $optionList[] = $optionDataObject;
        }
        return $optionList;
    }
}