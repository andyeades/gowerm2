<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2019 Aitoc (https://www.aitoc.com)
 * @package Aitoc_OptionsManagement
 */

/**
 * Copyright © 2018 Aitoc. All rights reserved.
 */
namespace Aitoc\OptionsManagement\Plugin\Catalog\Ui\DataProvider\Product\Form\Modifier;

use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\CustomOptions as CustomOptionsModifier;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Aitoc\OptionsManagement\Model\TemplateRepository;
use Magento\Framework\Convert\DataObject;
use Magento\Catalog\Model\Locator\LocatorInterface;
use Magento\Framework\UrlInterface;
use Aitoc\OptionsManagement\Helper\Data;
use Magento\Ui\Component\Container;
use Magento\Ui\Component\Form\Field;
use Magento\Ui\Component\Form\Element\DataType\Text;
use Magento\Ui\Component\Form\Element\Textarea;
use Magento\Ui\Component\Form\Element\Input;

class CustomOptions
{
    const FIELD_DEFAULT_VALUE = 'default_value';

    const CONTAINER_DEFAULT_TEXT = 'container_default_text';
    const FIELD_DEFAULT_TEXT = 'default_text';
    const FIELD_STORE_DEFAULT_TEXT_NAME = 'store_default_text';
    const FIELD_IS_USE_DEFAULT_TEXT = 'is_use_default_text';

    const FIELD_IS_ENABLE_NAME = 'is_enable';
    const FIELD_STORE_IS_ENABLE_NAME = 'store_is_enable';
    const FIELD_IS_USE_IS_ENABLE = 'is_use_default_is_enable';

    /**
     * @var TemplateRepository
     */
    protected $templateRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    /**
     * @var DataObject
     */
    protected $objectConverter;

    /**
     * @var LocatorInterface
     */
    protected $locator;

    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    /**
     * @var Data
     */
    private $helper;

    /**
     * CustomOptions constructor.
     * @param TemplateRepository $templateRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param DataObject $objectConverter
     * @param UrlInterface $urlBuilder
     */
    public function __construct(
        TemplateRepository $templateRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        DataObject $objectConverter,
        LocatorInterface $locator,
        UrlInterface $urlBuilder,
        Data $helper
    ) {
        $this->templateRepository = $templateRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->objectConverter = $objectConverter;
        $this->locator = $locator;
        $this->urlBuilder = $urlBuilder;
        $this->helper = $helper;
    }

    /**
     * @param CustomOptionsModifier $modifier
     * @param array $data
     * @return array
     */
    public function afterModifyData($modifier, array $data)
    {
        $templateIds = [];
        $product = $this->locator->getProduct();

        // set is_use_default_text flag
        if ($product->getStoreId() > 0
            && $this->helper->isDefaultValueEnabled()
            && isset($data[$product->getId()]['product']['options'])
        ) {
            $options = $data[$product->getId()]['product']['options'];
            foreach($options as $index => $option) {
                $options[$index][static::FIELD_IS_USE_DEFAULT_TEXT] = !$option[static::FIELD_STORE_DEFAULT_TEXT_NAME];
            }
            $data[$product->getId()]['product']['options'] = $options;
        }

        // set is_use_default_is_enable flag
        if ($product->getStoreId() > 0
            && $this->helper->isEnabledPerOptionEnabled()
            && isset($data[$product->getId()]['product']['options'])
        ) {
            $options = $data[$product->getId()]['product']['options'];
            foreach($options as $index => $option) {
                $options[$index][static::FIELD_IS_USE_IS_ENABLE] = is_null($option[static::FIELD_STORE_IS_ENABLE_NAME]);
            }
            $data[$product->getId()]['product']['options'] = $options;
        }

        // add assigned_templates data
        $productOptions = $product->getOptions() ?: [];
        foreach($productOptions as $option) {
            if ($option->getTemplateId()) {
                $templateIds[] = $option->getTemplateId();
            }
        }

        $templateIds = array_unique($templateIds);

        return array_replace_recursive(
            $data,
            [
                $product->getId() => [
                    'product' => [
                        'assigned_templates' => implode(',', $templateIds),
                        'is_replace_product_sku' => $product->getIsReplaceProductSku()
                    ]
                ]
            ]
        );
    }

    /**
     * @param CustomOptionsModifier $modifier
     * @param array $meta
     * @return array
     */
    public function afterModifyMeta($modifier, array $meta)
    {
        $meta = $this->addAssignedTemplatesField($meta);
        $meta = $this->addKeepOptionsOnUnlinkField($meta);
        $meta = $this->addIsReplaceProductSkuField($meta);

        if ($this->helper->isDefaultValueEnabled()) {
            $meta = $this->addDefaultValueField($meta);
            $meta = $this->addDefaultTextContainer($meta);
        }

        if ($this->helper->isEnabledPerOptionEnabled()) {
            $meta = $this->addIsEnabledField($meta);
        }

        if (!isset($meta[CustomOptionsModifier::GROUP_CUSTOM_OPTIONS_NAME]['children']['options']['children']
            ['record']['children']['container_option']['children']['container_common']['children'])
        ) {
            return $meta;
        }
        
        // change delete to edit button from template
        $config = $meta[CustomOptionsModifier::GROUP_CUSTOM_OPTIONS_NAME]['children']['options']
            ['arguments']['data']['config'];

        $config['component'] = 'Aitoc_OptionsManagement/js/product/dynamic-rows-import-custom-options';
        $config['template'] = 'Aitoc_OptionsManagement/dynamic-rows/templates/collapsible';
        $config['templateUrl'] = $this->urlBuilder->getUrl('optionsmanagement/template/edit');

        $meta[CustomOptionsModifier::GROUP_CUSTOM_OPTIONS_NAME]['children']['options']['arguments']['data']['config'] =
            $config;

        // disable option with template
        $option = $meta[CustomOptionsModifier::GROUP_CUSTOM_OPTIONS_NAME]['children']['options']['children']
            ['record']['children']['container_option']['children']['container_common']['children'];

        foreach($option as &$data) {
            if (!isset($data['arguments']['data']['config']['label'])) {
                continue;
            }

            if (!isset($data['arguments']['data']['config']['imports'])) {
                $data['arguments']['data']['config']['imports'] = [];
            }

            $data['arguments']['data']['config']['imports']['disabled']
                = '${ $.provider }:${ $.parentScope }.template_id';
        }

        // update js (title element)
        $option['title']['arguments']['data']['config']['component']
            = 'Aitoc_OptionsManagement/js/product/static-type-input';

        // update service template (title element)
        if (isset($option['title']['arguments']['data']['config']['service']['template'])) {
            $option['title']['arguments']['data']['config']['service']['template']
                = 'Aitoc_OptionsManagement/form/element/helper/custom-option-service';
        }

        // update js template (fix disable element)
        $option['type']['arguments']['data']['config']['elementTmpl']
            = 'Aitoc_OptionsManagement/grid/filters/elements/ui-select';

        $meta[CustomOptionsModifier::GROUP_CUSTOM_OPTIONS_NAME]['children']['options']['children']
            ['record']['children']['container_option']['children']['container_common']['children'] = $option;


        // disable static with template
        $static = $meta[CustomOptionsModifier::GROUP_CUSTOM_OPTIONS_NAME]['children']['options']['children']
            ['record']['children']['container_option']['children']['container_type_static']['children'];

        foreach($static as &$data) {
            if (!isset($data['arguments']['data']['config']['label'])) {
                continue;
            }

            if (!isset($data['arguments']['data']['config']['imports'])) {
                $data['arguments']['data']['config']['imports'] = [];
            }

            $data['arguments']['data']['config']['imports']['disabled']
                = '${ $.provider }:${ $.parentScope }.template_id';
        }

        $meta[CustomOptionsModifier::GROUP_CUSTOM_OPTIONS_NAME]['children']['options']['children']
            ['record']['children']['container_option']['children']['container_type_static']['children'] = $static;


        // disable values with template
        $values = $meta[CustomOptionsModifier::GROUP_CUSTOM_OPTIONS_NAME]['children']['options']['children']
            ['record']['children']['container_option']['children']['values']['children']['record']['children'];

        foreach($values as &$data) {
            if (!isset($data['arguments']['data']['config']['label'])) {
                continue;
            }

            if (!isset($data['arguments']['data']['config']['imports'])) {
                $data['arguments']['data']['config']['imports'] = [];
            }

            $data['arguments']['data']['config']['imports']['disabled']
                = '${ $.provider }:${ $.parentScope }.template_option_id';
        }

        // update service template (title value field)
        if (isset($values['title']['arguments']['data']['config']['service']['template'])) {
            $values['title']['arguments']['data']['config']['service']['template']
                = 'Aitoc_OptionsManagement/form/element/helper/custom-option-type-service';
            $values['title']['arguments']['data']['config']['imports']['templateOptionId']
                = '${ $.provider }:${ $.parentScope }.template_option_id';
        }

        // hide delete button
        if (!isset($values['is_delete']['arguments']['data']['config']['imports'])) {
            $values['is_delete']['arguments']['data']['config']['imports'] = [];
        }
        $values['is_delete']['arguments']['data']['config']['imports']['visible']
            = '!${ $.provider }:${ $.dataScope}.template_option_id';


        $meta[CustomOptionsModifier::GROUP_CUSTOM_OPTIONS_NAME]['children']['options']['children']['record']['children']
            ['container_option']['children']['values']['children']['record']['children'] = $values;


        // hide add value button
        if (!isset($meta[CustomOptionsModifier::GROUP_CUSTOM_OPTIONS_NAME]['children']['options']['children']['record']
            ['children']['container_option']['children']['values']['arguments']['data']['config']['imports'])) {
            $meta[CustomOptionsModifier::GROUP_CUSTOM_OPTIONS_NAME]['children']['options']['children']
            ['record']['children']['container_option']['children']['values']['arguments']['data']['config']['imports']
                = [];
        }
        $meta[CustomOptionsModifier::GROUP_CUSTOM_OPTIONS_NAME]['children']['options']['children']['record']['children']
            ['container_option']['children']['values']['arguments']['data']['config']['imports']['addButton']
            = '!${ $.provider }:${ $.dataScope }.template_option_id';

        return $meta;
    }

    protected function addAssignedTemplatesField($meta)
    {
        if (isset($meta[CustomOptionsModifier::GROUP_CUSTOM_OPTIONS_NAME]['children'])) {
            $templates = $this->templateRepository->getList($this->searchCriteriaBuilder->create())->getItems();
            $options = $this->objectConverter->toOptionArray($templates, 'id', 'title');

            $meta[CustomOptionsModifier::GROUP_CUSTOM_OPTIONS_NAME]['children']['assigned_templates'] =
                [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'label' => __('Assigned Templates'),
                                'componentType' => Field::NAME,
                                'formElement' => \Magento\Ui\Component\Form\Element\MultiSelect::NAME,
                                'dataScope' => 'assigned_templates',
                                'dataType' => \Magento\Ui\Component\Form\Element\DataType\Text::NAME,
                                'sortOrder' => 5,
                                'options' => $options
                            ]
                        ]
                    ]
                ];
        }

        return $meta;
    }

    protected function addKeepOptionsOnUnlinkField($meta)
    {
        if (isset($meta[CustomOptionsModifier::GROUP_CUSTOM_OPTIONS_NAME]['children'])) {

            $meta[CustomOptionsModifier::GROUP_CUSTOM_OPTIONS_NAME]['children']['keep_options_on_unlink'] =
                [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'dataType' => \Magento\Ui\Component\Form\Element\DataType\Number::NAME,
                                'formElement' => \Magento\Ui\Component\Form\Element\Checkbox::NAME,
                                'visible' => 1,
                                'required' => 0,
                                'default' => 0,
                                'notice' => '',
                                'label' => __('Keep Options after Unlinking Templates'),
                                'code' => 'keep_options_on_unlink',
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
                                'componentType' => Field::NAME,
                                'prefer' => 'toggle',
                                'valueMap' => [
                                    'true' => '1',
                                    'false' => '0'
                                ]

                            ]
                        ]
                    ]
                ];
        }

        return $meta;
    }

    protected function addIsReplaceProductSkuField($meta)
    {
        if (isset($meta[CustomOptionsModifier::GROUP_CUSTOM_OPTIONS_NAME]['children'])) {

            $meta[CustomOptionsModifier::GROUP_CUSTOM_OPTIONS_NAME]['children']['is_replace_product_sku'] =
                [
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
                                'code' => 'is_replace_product_sku',
                                'scopeLabel' => '',
                                'sortOrder' => 6,
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
                                'componentType' => Field::NAME,
                                'prefer' => 'toggle',
                                'valueMap' => [
                                    'true' => '1',
                                    'false' => '0'
                                ]

                            ]
                        ]
                    ]
                ];
        }

        return $meta;
    }

    protected function addDefaultValueField($meta)
    {
        if (!isset($meta[CustomOptionsModifier::GROUP_CUSTOM_OPTIONS_NAME]['children']['options']['children']['record']
            ['children']['container_option']['children']['values']['children']['record']['children'])) {
            return $meta;
        }

        // update js (to control type of default_value element)
        $meta[CustomOptionsModifier::GROUP_CUSTOM_OPTIONS_NAME]['children']['options']['children']['record']['children']
            ['container_option']['children']['container_common']['children']['type']
            ['arguments']['data']['config']['component'] = 'Aitoc_OptionsManagement/js/component/custom-options-type';

        // add default_value element
        $fields = $meta[CustomOptionsModifier::GROUP_CUSTOM_OPTIONS_NAME]['children']['options']['children']['record']
            ['children']['container_option']['children']['values']['children']['record']['children'];

        $fields = $this->arrayInsertAfter(
            'sort_order',
            $fields,
            static::FIELD_DEFAULT_VALUE,
            [
                'arguments' => [
                    'data' => [
                        'config' => [
                            'label' => __('Default'),
                            'component' => 'Aitoc_OptionsManagement/js/component/default-value',
                            'elementTmpl' => 'Aitoc_OptionsManagement/form/components/single/default-value-checkbox',
                            'componentType' => Field::NAME,
                            'formElement' => \Magento\Ui\Component\Form\Element\Checkbox::NAME,
                            'dataScope' => static::FIELD_DEFAULT_VALUE,
                            'dataType' => \Magento\Ui\Component\Form\Element\DataType\Text::NAME,
                            'sortOrder' => 59,
                            'value' => '0',
                            'valueMap' => [
                                'true' => '1',
                                'false' => '0'
                            ],
                            'imports' => [
                                'optionTypeUpdated' =>
                                    '${ $.parentName.replace(/\.values\.[0-9]+$/g, \'\') }.container_common.type:value'
                            ]
                        ]
                    ]
                ]
            ]
        );

        $meta[CustomOptionsModifier::GROUP_CUSTOM_OPTIONS_NAME]['children']['options']['children']['record']
            ['children']['container_option']['children']['values']['children']['record']['children'] = $fields;

        return $meta;
    }

    protected function addDefaultTextContainer($meta)
    {
        if (!isset($meta[CustomOptionsModifier::GROUP_CUSTOM_OPTIONS_NAME]['children']['options']['children']
            ['record']['children']['container_option']['children'])) {
            return $meta;
        }

        // add default_text element
        $containers = $meta[CustomOptionsModifier::GROUP_CUSTOM_OPTIONS_NAME]['children']['options']['children']
            ['record']['children']['container_option']['children'];

        $containers = $this->arrayInsertAfter(
            'container_type_static',
            $containers,
            static::CONTAINER_DEFAULT_TEXT,
            [
                'arguments' => [
                    'data' => [
                        'config' => [
                            'componentType' => Container::NAME,
                            'formElement' => Container::NAME,
                            'component' => 'Magento_Ui/js/form/components/group',
                            'breakLine' => false,
                            'showLabel' => false,
                            'additionalClasses' => 'admin__field-group-columns admin__control-group-equal',
                            'sortOrder' => 25
                        ],
                    ],
                ],
                'children' => [
                    static::FIELD_DEFAULT_TEXT => $this->getDefaultTextFieldConfig(10),
                    static::FIELD_DEFAULT_TEXT . '_area' => $this->getDefaultTextFieldConfig(10, true),
                ]
            ]
        );

        $meta[CustomOptionsModifier::GROUP_CUSTOM_OPTIONS_NAME]['children']['options']['children']
            ['record']['children']['container_option']['children'] = $containers;


        // change text type groups config
        $groupsConfig = $meta[CustomOptionsModifier::GROUP_CUSTOM_OPTIONS_NAME]['children']['options']['children']
            ['record']['children']['container_option']['children']['container_common']['children']['type']
            ['arguments']['data']['config']['groupsConfig'];


        $groupsConfig['area'] = $groupsConfig['text'];

        $groupsConfig['text']['values'] = ['field'];
        $groupsConfig['text']['indexes'][] = static::CONTAINER_DEFAULT_TEXT;
        $groupsConfig['text']['indexes'][] = static::FIELD_DEFAULT_TEXT;

        $groupsConfig['area']['values'] = ['area'];
        $groupsConfig['area']['indexes'][] = static::CONTAINER_DEFAULT_TEXT;
        $groupsConfig['area']['indexes'][] = static::FIELD_DEFAULT_TEXT . '_area';

        $meta[CustomOptionsModifier::GROUP_CUSTOM_OPTIONS_NAME]['children']['options']['children']
            ['record']['children']['container_option']['children']['container_common']['children']['type']
            ['arguments']['data']['config']['groupsConfig'] = $groupsConfig;


        return $meta;
    }

    /**
     * Get config for "Default Text" field
     *
     * @param int $sortOrder
     * @return array
     */
    protected function getDefaultTextFieldConfig($sortOrder, $isTextArea = false)
    {
        $data = [
            'arguments' => [
                'data' => [
                    'config' => [
                        'label' => __('Default Text'),
                        'componentType' => Field::NAME,
                        'formElement' => ($isTextArea ? Textarea::NAME : Input::NAME),
                        'dataScope' => static::FIELD_DEFAULT_TEXT,
                        'dataType' => Text::NAME,
                        'sortOrder' => $sortOrder,
                        'imports' => [
                            'optionId' => '${ $.provider }:${ $.parentScope }.option_id',
                            'isUseDefault' => '${ $.provider }:${ $.parentScope }.is_use_default_text',
                            'disabled' => '${ $.provider }:${ $.parentScope }.template_id',
                            'templateId' => '${ $.provider }:${ $.parentScope }.template_id'
                        ]
                    ],
                ],
            ],
        ];

        if ($this->locator->getProduct()->getStoreId()) {
            $data['arguments']['data']['config']['service'] = [
                'template' => 'Aitoc_OptionsManagement/form/element/helper/custom-option-service',
            ];
        }

        return $data;
    }

    protected function addIsEnabledField($meta)
    {
        if (!isset($meta[CustomOptionsModifier::GROUP_CUSTOM_OPTIONS_NAME]['children']['options']['children']
            ['record']['children']['container_option']['children']['container_common']['children'])) {
            return $meta;
        }

        // add is_enabled element
        $fields = $meta[CustomOptionsModifier::GROUP_CUSTOM_OPTIONS_NAME]['children']['options']['children']
            ['record']['children']['container_option']['children']['container_common']['children'];


        $fields[static::FIELD_IS_ENABLE_NAME] = [
            'arguments' => [
                'data' => [
                    'config' => [
                        'dataType' => \Magento\Ui\Component\Form\Element\DataType\Number::NAME,
                        'formElement' => \Magento\Ui\Component\Form\Element\Checkbox::NAME,
                        'visible' => 1,
                        'required' => 0,
                        'default' => 1,
                        'notice' => '',
                        'label' => __('Enabled'),
                        'code' => static::FIELD_IS_ENABLE_NAME,
                        'scopeLabel' => '',
                        'sortOrder' => 50,
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
                        'componentType' => Field::NAME,
                        'prefer' => 'toggle',
                        'valueMap' => [
                            'true' => '1',
                            'false' => '0'
                        ],
                        'value' => '1',
                        'imports' => [
                            'optionId' => '${ $.provider }:${ $.parentScope }.option_id',
                            'isUseDefault' => '${ $.provider }:${ $.parentScope }.is_use_default_is_enable',
                            'disabled' => '${ $.provider }:${ $.parentScope }.template_id',
                            'templateId' => '${ $.provider }:${ $.parentScope }.template_id'
                        ]
                    ],
                ],
            ]
        ];

        if ($this->locator->getProduct()->getStoreId()) {
            $fields[static::FIELD_IS_ENABLE_NAME]['arguments']['data']['config']['service'] = [
                'template' => 'Aitoc_OptionsManagement/form/element/helper/custom-option-service'
            ];
        }

        $meta[CustomOptionsModifier::GROUP_CUSTOM_OPTIONS_NAME]['children']['options']['children']
            ['record']['children']['container_option']['children']['container_common']['children'] = $fields;

        return $meta;
    }

    protected function arrayInsertAfter($key, array $array, $newKey, $newValue)
    {
        if (array_key_exists($key, $array)) {
            $new = [];
            foreach ($array as $k => $value) {
                $new[$k] = $value;
                if ($k === $key) {
                    $new[$newKey] = $newValue;
                }
            }
            return $new;
        }
        return $array;
    }
}
