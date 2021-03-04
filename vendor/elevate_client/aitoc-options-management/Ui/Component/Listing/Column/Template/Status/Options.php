<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2019 Aitoc (https://www.aitoc.com)
 * @package Aitoc_OptionsManagement
 */

/**
 * Copyright © 2018 Aitoc. All rights reserved.
 */
namespace Aitoc\OptionsManagement\Ui\Component\Listing\Column\Template\Status;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class Options
 */
class Options implements OptionSourceInterface
{
    /**
     * @var array
     */
    protected $options;

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        if ($this->options === null) {
            $this->options = [
                [
                    'value' => \Aitoc\OptionsManagement\Model\Template::ACTIVE,
                    'label' => __('Active'),
                ],
                [
                    'value' => \Aitoc\OptionsManagement\Model\Template::INACTIVE,
                    'label' => __('Inactive'),
                ]
            ];
        }
        return $this->options;
    }
}
