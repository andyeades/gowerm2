<?php

/**
 * Solwin Infotech
 * Solwin Instagram Extension
 *
 * @category   Solwin
 * @package    Solwin_Instagram
 * @copyright  Copyright © 2006-2020 Solwin (https://www.solwininfotech.com)
 * @license    https://www.solwininfotech.com/magento-extension-license/
 */

namespace Solwin\Instagram\Model\Source;

class Resolution implements \Magento\Framework\Option\ArrayInterface
{

    protected $_options;

    /**
     * to option array
     *
     * @return array
     */
    public function toOptionArray()
    {

        $this->_options = [
            ['label' => '150px x 150px', 'value' => 'thumbnail'],
            ['label' => '320px x 320px', 'value' => 'low_resolution'],
            ['label' => '640px x 640px', 'value' => 'standard_resolution'],
        ];
        return $this->_options;
    }
}
