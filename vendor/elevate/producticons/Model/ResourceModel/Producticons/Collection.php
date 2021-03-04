<?php
namespace Elevate\ProductIcons\Model\ResourceModel\Producticons;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
  protected $_idFieldName = 'icon_id';
  protected $_eventPrefix = 'elevate_producticons_collection';
  protected $_eventObject = 'producticons_collection';
  /**
   * Define resource model
   *
   * @return void
   */
  protected function _construct()
  {
    $this->_init(
      'Elevate\ProductIcons\Model\Producticons',
      'Elevate\ProductIcons\Model\ResourceModel\Producticons');
  }
}