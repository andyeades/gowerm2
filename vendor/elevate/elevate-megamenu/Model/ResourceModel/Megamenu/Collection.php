<?php
namespace Elevate\Megamenu\Model\ResourceModel\Megamenu;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
  protected $_idFieldName = 'entity_id';
  protected $_eventPrefix = 'elevate_megamenu_collection';
  protected $_eventObject = 'megamenu_collection';
  /**
   * Define resource model
   *
   * @return void
   */
  protected function _construct()
  {
    $this->_init(
      'Elevate\Megamenu\Model\Megamenu',
      'Elevate\Megamenu\Model\ResourceModel\Megamenu');
  }
}