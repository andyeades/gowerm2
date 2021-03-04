<?php
namespace Elevate\ProductIcons\Model\ResourceModel\Producticons;

/**
 * Factory class for @see \Elevate\ProductIcons\Model\ResourceModel\Producticons\Collection
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
  public function __construct(\Magento\Framework\ObjectManagerInterface $objectManager, $instanceName = '\\Elevate\\ProductIcons\\Model\\ResourceModel\\Producticons\\Collection')
  {
    $this->_objectManager = $objectManager;
    $this->_instanceName = $instanceName;
  }

  /**
   * Create class instance with specified parameters
   *
   * @param array $data
   * @return \Elevate\ProductIcons\Model\ResourceModel\Producticons\Collection
   */
  public function create(array $data = array())
  {
    return $this->_objectManager->create($this->_instanceName, $data);
  }
}
