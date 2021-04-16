<?php

namespace Elevate\BundleAdvanced\Plugin\Bundle\Ui\DataProvider\Product\Form\Modifier;

use Magento\Ui\Component\Form;;

class BundlePanel
{
    /**
     * @param \Magento\Bundle\Ui\DataProvider\Product\Form\Modifier\BundlePanel $subject
     * @param $meta
     * @return mixed
     */
     
     //types in here  - /vendor/magento/module-ui/Component/Form/Element
    public function afterModifyMeta(\Magento\Bundle\Ui\DataProvider\Product\Form\Modifier\BundlePanel $subject, $meta)
    {                
        $fieldSet = [
            'default_option_text' => [
                'dataType' => Form\Element\DataType\Number::NAME,
                'formElement'   => Form\Element\Input::NAME,
                'label' => 'Default Text',
                'dataScope' => 'default_option_text',
                'sortOrder' => 40
            ],
            'option_tooltip' => [
                'dataType' => Form\Element\DataType\Number::NAME,
                'formElement'   => Form\Element\Textarea::NAME,
                'label' => 'Tooltip',
                'dataScope' => 'option_tooltip',
                'sortOrder' => 40
            ],
            'min_qty' => [
                'dataType' => Form\Element\DataType\Number::NAME,
                'formElement'   => Form\Element\Input::NAME,
                'label' => 'Min Qty',
                'dataScope' => 'min_qty',
                'sortOrder' => 40
            ],
            'max_qty' => [
                'dataType' => Form\Element\DataType\Number::NAME,
                'formElement'   => Form\Element\Input::NAME,
                'label' => 'Max Qty',
                'dataScope' => 'max_qty',
                'sortOrder' => 45
            ],
            'is_lease_machine' => [
                'dataType' => Form\Element\DataType\Boolean::NAME,
                'formElement'   => Form\Element\Select::NAME,
                'label' => 'Highlight',
                'dataScope' => 'is_lease_machine',
                'sortOrder' => 50,
                'options' => [
                    [
                        'label' => __('No'),
                        'value' => 0
                    ],
                    [
                        'label' => __('Yes'),
                        'value' => 1
                    ],
                ],
            ],
        ];

        foreach ($fieldSet as $filed => $fieldOptions)
        {
            $meta['bundle-items']['children']['bundle_options']['children']
            ['record']['children']['product_bundle_container']['children']['option_info']['children'][$filed] = $this->getSelectionCustomText($fieldOptions);
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