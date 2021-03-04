<?php
namespace Elevate\Megamenu\Model\Megamenu;
/**
 * Class DataProvider
 * @package Elevate\Megamenu\Model\Megamenu
 */
class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
  /**
   * @var
   */
  protected $_loadedData;
  /**
   * @var \Magento\Framework\Registry
   */
  protected $_coreRegistry;

  /**
   * DataProvider constructor.
   *
   * @param null $name
   * @param null $primaryFieldName
   * @param null $requestFieldName
   * @param \Elevate\Megamenu\Model\ResourceModel\Megamenu\CollectionFactory $collectionFactory
   * @param \Magento\Framework\Registry                                      $registry
   * @param array                                                            $meta
   * @param array                                                            $data
   */
  public function __construct(
    $name,
    $primaryFieldName,
    $requestFieldName,
    \Elevate\Megamenu\Model\ResourceModel\Megamenu\CollectionFactory $collectionFactory,
    \Magento\Framework\Registry $registry,
    array $meta = [],
    array $data = []
  ) {
    parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    $this->collection = $collectionFactory->create();
    $this->_coreRegistry = $registry;
  }

  /**
   * @return array
   */
  public function getData() {
    if (isset($this->_loadedData)) {
      return $this->_loadedData;
    }
    $items = $this->collection->getItems();
    foreach($items as $item) {
      $this->_loadedData[$item->getId()] = $item->getData();
    }

    return $this->_loadedData;
  }
}