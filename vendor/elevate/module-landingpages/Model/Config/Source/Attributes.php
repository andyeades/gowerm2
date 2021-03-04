<?php

namespace Elevate\LandingPages\Model\Config\Source;

class Attributes implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
    
$objectManager = \Magento\Framework\App\ObjectManager::getInstance();

$filterableAttributes = $objectManager->get(\Magento\Catalog\Model\Layer\Category\FilterableAttributeList::class);
$attributes = $filterableAttributes->getList();
         //print_r($attributes->getData());
        $result = [];
        foreach ($attributes->getData() as $k => $v) {
         $result[] = ['value' => $v['attribute_code'], 'label' => $v['attribute_code']];
        }
       
        return $result;
    }
}
