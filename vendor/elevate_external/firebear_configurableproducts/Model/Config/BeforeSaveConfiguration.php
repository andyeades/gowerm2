<?php

namespace Firebear\ConfigurableProducts\Model\Config;

class BeforeSaveConfiguration extends \Magento\Framework\App\Config\Value
{
    public function beforeSave()
    {
        $matrixXaxis = $this->getData('fieldset_data/x_axis');
        $matrixYaxis = $this->getData('fieldset_data/y_axis');
        if ($matrixXaxis == $matrixYaxis) {
            throw new \Magento\Framework\Exception\ValidatorException(
                __('\'Attribute code for matrix X axis\' and \'Attribute code for matrix Y axis\' shouldn\'t be equal.')
            );
        }
        parent::beforeSave();
    }
}
