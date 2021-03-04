<?php

namespace Elevate\BundleAdvanced\Api\Data;

interface ExtraOptionsInterface
{
    const VALUE = 'value';

    /**
     * Return value.
     *
     * @return string|null
     */
    public function getOptionTooltip();

    /**
     * Set value.
     *
     * @param string|null $value
     * @return $this
     */
    public function setOptionTooltip($value);
    /**
     * Return value.
     *
     * @return string|null
     */
    public function getDefaultOptionText();

    /**
     * Set value.
     *
     * @param string|null $value
     * @return $this
     */
    public function setDefaultOptionText($value);
}