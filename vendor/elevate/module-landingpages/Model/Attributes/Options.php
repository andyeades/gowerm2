<?php

namespace Elevate\LandingPages\Model\Attributes;

/**
 * Class AttributeOptions
 * @package Elevate\LandingPages\Model\Attributes
 */
class Options implements \Magento\Framework\Option\ArrayInterface
{
    /** @var \Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory */
    private $collectionFactory;

    /** @var \Elevate\LandingPages\Model\Attributes\AttributeScope */
    private $scope;

    /** @var array */
    private $items;
    protected $_filterableAttributeList;
    /**
     * @param \Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory $collectionFactory
     * @param \Elevate\LandingPages\Model\Attributes\AttributeScope $scope
     */
    public function __construct(
        \Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory $collectionFactory,
        \Elevate\LandingPages\Model\Attributes\AttributeScope $scope,
        \Magento\Catalog\Model\Layer\Category\FilterableAttributeList $filterableAttributeList
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->scope = $scope;
        $this->filterableAttributeList = $filterableAttributeList;
    }

    /**
     * @inheritdoc
     */
    public function toOptionArray()
    {
        if (is_null($this->items)) {
            $this->items = $this->getOptions();
        }

        return $this->items;
    }

     /**
     * @return \Magento\Catalog\Model\ResourceModel\Eav\Attribute[]|\Magento\Framework\DataObject[]
     */
    public function getAttributes($attribute = false)
    {
    
        $codes = $this->scope->getCodes();
        $collection = $this->getCollection();
        if (! empty($codes)) {
        //    $collection->addFieldToFilter('attribute_code', $codes);
        }

        return $collection->getItems();
    }

    /**
     * @return array
     */
    private function getOptions($attribute = false)
    {
     


$filterableAttributes = $this->filterableAttributeList;
$attributes = $filterableAttributes->getList();
        
           $options = [];
        foreach ($attributes as $attribute) {
        
           if($attribute->getAttributeCode() == 'mattress_firmness'){
               $options[] = [
                   'label' => 'Soft', 'value' => 'soft', 'attribute_id' => $attribute->getId()
               ];
               $options[] = [
                   'label' => 'Soft / Medium', 'value' => 'soft_medium', 'attribute_id' => $attribute->getId()
               ];
               $options[] = [
                   'label' => 'Medium', 'value' => 'firm', 'medium' => $attribute->getId()
               ];
               $options[] = [
                   'label' => 'Medium Firm', 'value' => 'medium_firm', 'attribute_id' => $attribute->getId()
               ];
               $options[] = [
                   'label' => 'Firm', 'value' => 'firm', 'attribute_id' => $attribute->getId()
               ];



           }else{
         
            foreach ($attribute->getOptions() as $option) {
                if (empty($option->getValue())) {
                    continue;
                }
                $options[] = [
                    'label' => $option->getLabel(), 'value' => $option->getValue(), 'attribute_id' => $attribute->getId()
                ];
            }
           }
           // $items[$attribute->getId()] = $options;
        }

      return $options;
  
    }

  /**
   * @param string $attrCode
   * @param string $optionKey
   * @param null   $store
   *
   * @return string
   */
    public function getOptionId($attrCode, $optionKey, $store = null)
    {
        if (!$optionKey) {
            return $optionKey;
        }
        $options = $this->getOptions($attrCode);

       // $storeId = Mage::app()->getStore($store)->getId();
          $optionId = false;
        if(isset($options[$optionKey])){
        $optionId = $options[$optionKey];
        }
        return $optionId;
    }

    /**
     * @return \Magento\Catalog\Model\ResourceModel\Product\Attribute\Collection
     */
    private function getCollection()
    {
        return $this->collectionFactory->create();
    }
}
