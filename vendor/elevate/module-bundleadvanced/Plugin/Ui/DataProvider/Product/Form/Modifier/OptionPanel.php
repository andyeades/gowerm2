<?php

namespace Elevate\BundleAdvanced\Plugin\Ui\DataProvider\Product\Form\Modifier;

use Magento\Ui\Component\Form;;

class OptionPanel
{
    /**
     * @param \Magento\Bundle\Ui\DataProvider\Product\Form\Modifier\BundlePanel $subject
     * @param $meta
     * @return mixed
     */
    public function afterModifyMeta(\Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\CustomOptions $subject, $meta)
    {
        $fieldSet = [
   
            'option_tooltip' => [
                'dataType' => Form\Element\DataType\Number::NAME,
                'formElement'   => Form\Element\Input::NAME,
                'label' => 'Tooltip',
                'dataScope' => 'option_tooltip',
                'sortOrder' => 40
            ],
            'option_comment' => [
                'dataType' => Form\Element\DataType\Number::NAME,
                'formElement'   => Form\Element\Input::NAME,
                'label' => 'Comments',
                'dataScope' => 'option_comment',
                'sortOrder' => 40
            ]
        ];
        
        foreach ($fieldSet as $filed => $fieldOptions)
        {
        
          
            $meta['custom_options']['children']['options']['children']['record']['children']['container_option']['children']['container_common']['children'][$filed] = $this->getSelectionCustomText($fieldOptions);
        }

        return $meta;
    }

    /**
     * @param $fieldOptions
     * @return array
     */
    protected function getSelectionCustomText($fieldOptions)
    {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'componentType' => Form\Field::NAME,
                        'dataType'      => $fieldOptions['dataType'],
                        'formElement'   => $fieldOptions['formElement'],
                        'label'         => __($fieldOptions['label']),
                        'dataScope'     => $fieldOptions['dataScope'],
                        'sortOrder'     => $fieldOptions['sortOrder'],
                        'options'       => array_key_exists('options', $fieldOptions) ? $fieldOptions['options']: "",
                    ]
                ]
            ]
        ];
    }
}