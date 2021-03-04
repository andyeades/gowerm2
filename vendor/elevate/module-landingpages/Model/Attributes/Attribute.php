<?php

namespace Elevate\LandingPages\Model\Attributes;

/**
 * Class AttributeOptions
 *
 * @package Elevate\LandingPages\Model\Attributes
 */
class Attribute implements \Magento\Framework\Option\ArrayInterface {
  /** @var \Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory */
  private $collectionFactory;

  /** @var \Elevate\LandingPages\Model\Attributes\AttributeScope */
  private $scope;

  /** @var array */
  private $items;

  protected $_filterableAttributeList;

  /**
   * @param \Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory $collectionFactory
   * @param \Elevate\LandingPages\Model\Attributes\AttributeScope                    $scope
   */
  public function __construct(\Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory $collectionFactory,
                              \Elevate\LandingPages\Model\Attributes\AttributeScope $scope,
                              \Magento\Catalog\Model\Layer\Category\FilterableAttributeList $filterableAttributeList

  ) {
    $this->collectionFactory = $collectionFactory;
    $this->filterableAttributeList = $filterableAttributeList;
    $this->scope = $scope;
  }

  /**
   * @inheritdoc
   */
  public function toOptionArray() {
    if (is_null($this->items)) {
      $this->items = $this->getOptions();
    }

    return $this->items;
  }

  /**
   * @return \Magento\Catalog\Model\ResourceModel\Eav\Attribute[]|\Magento\Framework\DataObject[]
   */
  public function getAttributes() {
    $codes = $this->scope->getCodes();
    $collection = $this->getCollection();
    if (!empty($codes)) {
      //    $collection->addFieldToFilter('attribute_code', $codes);
    }

    return $collection->getItems();
  }

  /**
   * @return array
   */
  private function getOptions() {
    $items = [];
    foreach ($this->getAttributes() as $attribute) {
      $items[] = [
        'label' => $attribute->getStoreLabel(),
        'value' => $attribute->getId(),
      ];
    }
    //   return $items;

    $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

    $filterableAttributes = $this->filterableAttributeList;
    $attributes = $filterableAttributes->getList();
    //print_r($attributes->getData());
    $result = [];
    foreach ($attributes->getData() as $k => $v) {
      $result[] = [
        'value' => $v['attribute_id'],
        'label' => $v['attribute_code']
      ];
    }

    return $result;
  }

  /**
   * @return \Magento\Catalog\Model\ResourceModel\Product\Attribute\Collection
   */
  private function getCollection() {
    return $this->collectionFactory->create();
  }
}
