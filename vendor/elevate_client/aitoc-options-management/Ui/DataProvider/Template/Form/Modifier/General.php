<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2019 Aitoc (https://www.aitoc.com)
 * @package Aitoc_OptionsManagement
 */

/**
 * Copyright © 2018 Aitoc. All rights reserved.
 */
namespace Aitoc\OptionsManagement\Ui\DataProvider\Template\Form\Modifier;

use Aitoc\OptionsManagement\Api\Data\TemplateInterface;
use Magento\Framework\Registry;
use Magento\Ui\Component\Form;

/**
 * Data provider for general panel of template page
 *
 * @api
 */
class General extends AbstractModifier
{

    const GROUP_GENERAL_NAME = 'general';
    
    /**
     * @var Registry
     */
    private $registry;

    /**
     * @param Registry $registry
     */
    public function __construct(Registry $registry) {
        $this->registry = $registry;
    }

    /**
     * {@inheritdoc}
     */
    public function modifyData(array $data)
    {
        $model = $this->registry->registry('current_template');
        $id = $model->getId();

        $data[$id] = [
            TemplateInterface::TEMPLATE_ID => $model->getId(),
            TemplateInterface::TITLE => $model->getTitle(),
            TemplateInterface::IS_REPLACE_PRODUCT_SKU => $model->getIsReplaceProductSku(),
            TemplateInterface::SORT_ORDER => $model->getSortOrder(),
            'store_id' => $model->getStoreId()
        ];

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function modifyMeta(array $meta)
    {
        $meta = $this->prepareFirstPanel($meta);
        $meta = $this->addTitleField($meta);
        $meta = $this->addIsReplaceProductSkuField($meta);
        $meta = $this->addSortOrderField($meta);
        return $meta;
    }

    /**
     * @param array $meta
     * @return array
     */
    protected function prepareFirstPanel(array $meta)
    {
        $meta[static::GROUP_GENERAL_NAME] = [
            'arguments' => [
                'data' => [
                    'config' => [
                        'componentType' => 'fieldset',
                        'label' => __('General'),
                        'collapsible' => false,
                        'dataScope' => self::DATA_SCOPE,
                        'sortOrder' => 10
                    ]
                ]
            ],
            'children' => []
        ];

        return $meta;
    }

    /**
     * Add title field
     *
     * @param array $meta
     * @return array
     */
    protected function addTitleField(array $meta)
    {
        $code = TemplateInterface::TITLE;
        $meta[static::GROUP_GENERAL_NAME]['children'][static::CONTAINER_PREFIX . $code] = [
            'arguments' => [
                'data' => [
                    'config' => [
                        'formElement' => 'container',
                        'componentType' => 'container',
                        'label' => __('Title'),
                        'required' => 1,
                        'sortOrder' => 10
                    ]
                ]
            ],
            'children' => [
                $code => [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'dataType' => Form\Element\DataType\Text::NAME,
                                'formElement' => Form\Element\Input::NAME,
                                'visible' => 1,
                                'required' => 1,
                                'default' => '',
                                'notice' => '',
                                'label' => __('Title'),
                                'code' => $code,
                                'source' => static::GROUP_GENERAL_NAME,
                                'scopeLabel' => '',
                                'sortOrder' => 0,
                                'componentType' => Form\Field::NAME,
                                'validation' => [
                                    'required-entry' => 1,
                                    'max_text_length' => 64
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        return $meta;
    }

    /**
     * Add sort order field
     *
     * @param array $meta
     * @return array
     */
    protected function addSortOrderField(array $meta)
    {
        $code = TemplateInterface::SORT_ORDER;
        $meta[static::GROUP_GENERAL_NAME]['children'][static::CONTAINER_PREFIX . $code] = [
            'arguments' => [
                'data' => [
                    'config' => [
                        'formElement' => 'container',
                        'componentType' => 'container',
                        'label' => __('Sort Order'),
                        'required' => 0,
                        'sortOrder' => 30
                    ]
                ]
            ],
            'children' => [
                $code => [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'formElement' => Form\Element\Input::NAME,
                                'componentType' => \Magento\Ui\Component\Form\Field::NAME,
                                'dataType' => Form\Element\DataType\Number::NAME,
                                'label' => __('Sort Order'),
                                'code' => $code,
                                'source' => static::GROUP_GENERAL_NAME,
                                'sortOrder' => 0,
                                'validation' => [
                                    'required-entry' => true,
                                    'validate-digits' => true,
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        return $meta;
    }

    protected function addIsReplaceProductSkuField($meta)
    {
        $code = TemplateInterface::IS_REPLACE_PRODUCT_SKU;
        $meta[static::GROUP_GENERAL_NAME]['children'][static::CONTAINER_PREFIX . $code] = [
            'arguments' => [
                'data' => [
                    'config' => [
                        'formElement' => 'container',
                        'componentType' => 'container',
                        'label' => __('Replace Product SKU in Order'),
                        'required' => 0,
                        'sortOrder' => 20
                    ]
                ]
            ],
            'children' => [
                $code => [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'dataType' => \Magento\Ui\Component\Form\Element\DataType\Number::NAME,
                                'formElement' => \Magento\Ui\Component\Form\Element\Checkbox::NAME,
                                'visible' => 1,
                                'required' => 0,
                                'default' => 0,
                                'notice' => '',
                                'label' => __('Replace Product SKU in Order'),
                                'code' => $code,
                                'scopeLabel' => '',
                                'sortOrder' => 7,
                                'options' => [
                                    [
                                        'value' => 1,
                                        'label' => __('Yes')
                                    ],
                                    [
                                        'value' => 0,
                                        'label' => __('No')
                                    ]

                                ],
                                'componentType' => \Magento\Ui\Component\Form\Field::NAME,
                                'prefer' => 'toggle',
                                'valueMap' => [
                                    'true' => '1',
                                    'false' => '0'
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        return $meta;
    }
}
