<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2019 Aitoc (https://www.aitoc.com)
 * @package Aitoc_OptionsManagement
 */

/**
 * Copyright © 2018 Aitoc. All rights reserved.
 */
namespace Aitoc\OptionsManagement\Model\Template\Option\Type;

class Factory
{
    /**
     * Object Manager
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;

    /**
     * Construct
     *
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     */
    public function __construct(\Magento\Framework\ObjectManagerInterface $objectManager)
    {
        $this->_objectManager = $objectManager;
    }

    /**
     * Create product option
     *
     * @param string $className
     * @param array $data
     * @return \Aitoc\OptionsManagement\Model\Template\Option\Type\DefaultType
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function create($className, array $data = [])
    {
        $option = $this->_objectManager->create($className, $data);

        if (!$option instanceof \Aitoc\OptionsManagement\Model\Template\Option\Type\DefaultType) {
            throw new \Magento\Framework\Exception\LocalizedException(
                __('%1 doesn\'t extends \Aitoc\OptionsManagement\Model\Template\Option\Type\DefaultType', $className)
            );
        }
        return $option;
    }
}
