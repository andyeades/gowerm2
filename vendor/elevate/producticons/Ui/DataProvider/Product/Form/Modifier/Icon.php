<?php

use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier;

class Icon extends AbstractModifier
{
  public function modifyMeta(array $meta)
  {
    $meta['elevate_productlabels'] = [
      'arguments' => [
        'data' => [
          'config' => [
            'label' => __('Elevate Product Icons'),
            'sortOrder' => 50,
            'collapsible' => true
          ]
        ]
      ],
      'children' => [
        'elevate_producticons_1' => [
          'arguments' => [
            'data' => [
              'config' => [
                'formElement' => 'input',
                'componentType' => 'field',
                'options' => [
                  ['value' => 'test_value_1', 'label' => 'Test Value 1'],
                  ['value' => 'test_value_2', 'label' => 'Test Value 2'],
                  ['value' => 'test_value_3', 'label' => 'Test Value 3'],
                ],
                'visible' => 1,
                'required' => 1,
                'label' => __('Label For Element')
              ]
            ]
          ]
        ]
      ]
    ];

    return $meta;
  }

  /**
   * {@inheritdoc}
   */
  public function modifyData(array $data)
  {
    return $data;
  }
}