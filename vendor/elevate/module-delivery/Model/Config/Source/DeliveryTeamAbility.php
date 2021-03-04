<?php

namespace Elevate\Delivery\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class DeliveryTeamAbility
 *
 * @package Elevate\Delivery\Model\Config\Source
 */
class DeliveryTeamAbility implements OptionSourceInterface
{

    /**
     *
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        return array(
            array(
                'value' => 'Delivered To Room of Choice',
                'label' => 'Delivered To Room of Choice',
            ),
            array(
                'value' => 'Doorstep Delivery Only',
                'label' => 'Doorstep Delivery Only',
            )
        );
    }
}