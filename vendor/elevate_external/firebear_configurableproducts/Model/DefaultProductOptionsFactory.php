<?php
declare(strict_types=1);
/**
 * DefaultProductOptionsFactory
 *
 * @copyright Copyright © 2020 Firebear Studio. All rights reserved.
 * @author    fbeardev@gmail.com
 */

namespace Firebear\ConfigurableProducts\Model;

use Magento\Framework\ObjectManagerInterface;

/**
 * Class DefaultProductOptionsFactory
 * @package Firebear\ConfigurableProducts\Model
 * @see \Firebear\ConfigurableProducts\Model\DefaultProductOptions
 */
class DefaultProductOptionsFactory
{
    /**
     * Object Manager instance
     *
     * @var ObjectManagerInterface
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
     * @param ObjectManagerInterface $objectManager
     * @param string $instanceName
     */
    public function __construct(
        ObjectManagerInterface $objectManager,
        $instanceName = '\\Firebear\\ConfigurableProducts\\Model\\DefaultProductOptions'
    ) {
        $this->_objectManager = $objectManager;
        $this->_instanceName = $instanceName;
    }

    /**
     * Create class instance with specified parameters
     *
     * @param array $data
     * @return ProductOptions
     */
    public function create(array $data = [])
    {
        return $this->_objectManager->create($this->_instanceName, $data);
    }
}
