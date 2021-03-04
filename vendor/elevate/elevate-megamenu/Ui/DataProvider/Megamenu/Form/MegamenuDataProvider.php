<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Elevate\Megamenu\Ui\DataProvider\Megamenu\Form;

use Elevate\Megamenu\Model\ResourceModel\Megamenu\CollectionFactory;
use Magento\Ui\DataProvider\AbstractDataProvider;


/**
 * DataProvider for Edit Form
 *
 * @api
 * @since 101.0.0
 */
class MegamenuDataProvider extends AbstractDataProvider
{


  /**
   * @var array
   */
  protected $_loadedData;

  /**
   * @param CollectionFactory $collectionFactory
   * @param string $name
   * @param string $primaryFieldName
   * @param string $requestFieldName
   * @param array $meta
   * @param array $data
   */
  public function __construct(
    CollectionFactory $collectionFactory,
    $name,
    $primaryFieldName,
    $requestFieldName,
    array $meta = [],
    array $data = []
  ) {
    parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    $this->collection = $collectionFactory->create();
  }

  /**
   * Get data
   *
   * @return array
   */
  public function getData()
  {
    if (isset($this->_loadedData)) {
      return $this->_loadedData;
    }
    $items = $this->collection->getItems();
    foreach ($items as $item) {
      $this->_loadedData[$item->getEntityId()] = $item->getData();
    }
    return $this->_loadedData;
  }

  /**
   * {@inheritdoc}
   * @since 101.0.0
   */
  public function getMeta()
  {
    $meta = parent::getMeta();

    return $meta;
  }
}
