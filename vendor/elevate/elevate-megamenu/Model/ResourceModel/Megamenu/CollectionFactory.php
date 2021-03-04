<?php
namespace Elevate\Megamenu\Model\ResourceModel\Megamenu;

/**
 * Factory class for @see \Elevate\Megamenu\Model\ResourceModel\Megamenu\Collection
 */
class CollectionFactory
{
  /**
   * Object Manager instance
   *
   * @var \Magento\Framework\ObjectManagerInterface
   */
  protected $_objectManager = null;

  /**
   * Instance name to create
   *
   * @var string
   */
  protected $_instanceName = null;

  /**
   * Factory constructor
   *
   * @param \Magento\Framework\ObjectManagerInterface $objectManager
   * @param string $instanceName
   */
  public function __construct(\Magento\Framework\ObjectManagerInterface $objectManager, $instanceName = '\\Elevate\\Megamenu\\Model\\ResourceModel\\Megamenu\\Collection')
  {
    $this->_objectManager = $objectManager;
    $this->_instanceName = $instanceName;
  }

  /**
   * Create class instance with specified parameters
   *
   * @param array $data
   * @return \Elevate\Megamenu\Model\ResourceModel\Megamenu\Collection
   */
  public function create(array $data = array())
  {
    return $this->_objectManager->create($this->_instanceName, $data);
  }
}
