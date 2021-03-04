<?php

namespace Elevate\LandingPages\Model\Attributes;

/**
 * Class AttributeScope
 * @package Elevate\LandingPages\Model\Attributes
 */
class AttributeScope
{
    /** @var array */
    private $codes;

    /**
     * @param array $codes
     */
    public function __construct(
        $codes = []
    ) {
        $this->codes = $codes;
    }

    /**
     * Get attribute codes to scope in form select element
     *
     * @return array
     */
    public function getCodes()
    {
        return $this->codes;
    }
}
