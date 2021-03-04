<?php
namespace Elevate\CustomerGallery\Model\ResourceModel\Items;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
  protected $_idFieldName = 'items_id';
  protected $_eventPrefix = 'elevate_customergallery_collection';
  protected $_eventObject = 'customergallery_collection';
  /**
   * Define resource model
   *
   * @return void
   */
  protected function _construct()
  {
    $this->_init(
      'Elevate\CustomerGallery\Model\Items',
      'Elevate\CustomerGallery\Model\ResourceModel\Items');
  }
}