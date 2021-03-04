<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2019 Aitoc (https://www.aitoc.com)
 * @package Aitoc_OptionsManagement
 */

/**
 * Copyright © 2018 Aitoc. All rights reserved.
 */
namespace Aitoc\OptionsManagement\Model\Template\Option\Validator;

class Pool
{
    /**
     * @var \Zend_Validate_Interface
     */
    protected $validators;

    /**
     * @param \Zend_Validate_Interface[] $validators
     */
    public function __construct(array $validators)
    {
        $this->validators = $validators;
    }

    /**
     * Get validator
     *
     * @param string $type
     * @return \Zend_Validate_Interface
     */
    public function get($type)
    {
        return isset($this->validators[$type]) ? $this->validators[$type] : $this->validators['default'];
    }
}
