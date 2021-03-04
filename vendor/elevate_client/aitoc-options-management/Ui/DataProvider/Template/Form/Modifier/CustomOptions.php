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

use Magento\Store\Model\StoreManagerInterface;
use Magento\Catalog\Model\ProductOptions\ConfigInterface;
use Magento\Catalog\Model\Config\Source\Product\Options\Price as ProductOptionsPrice;
use Magento\Framework\UrlInterface;
use Magento\Framework\Stdlib\ArrayManager;
use Magento\Ui\Component\Modal;
use Magento\Ui\Component\Container;
use Magento\Ui\Component\DynamicRows;
use Magento\Ui\Component\Form\Fieldset;
use Magento\Ui\Component\Form\Field;
use Magento\Ui\Component\Form\Element\Input;
use Magento\Ui\Component\Form\Element\Select;
use Magento\Ui\Component\Form\Element\Checkbox;
use Magento\Ui\Component\Form\Element\ActionDelete;
use Magento\Ui\Component\Form\Element\DataType\Text;
use Magento\Ui\Component\Form\Element\Textarea;
use Magento\Ui\Component\Form\Element\DataType\Number;
use Magento\Framework\Locale\CurrencyInterface;
use Magento\Framework\Registry;
use Aitoc\OptionsManagement\Helper\Data;

/**
 * Data provider for "Customizable Options" panel
 *
 * @api
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class CustomOptions extends AbstractModifier
{
    /**#@+
     * Group values
     */
    const GROUP_CUSTOM_OPTIONS_NAME = 'custom_options';
    /**#@-*/

    /**#@+
     * Button values
     */
    const BUTTON_ADD = 'button_add';

    /**#@-*/

    /**#@+
     * Container values
     */
    const CONTAINER_HEADER_NAME = 'container_header';
    const CONTAINER_OPTION = 'container_option';
    const CONTAINER_COMMON_NAME = 'container_common';
    const CONTAINER_TYPE_STATIC_NAME = 'container_type_static';

    const CONTAINER_DEFAULT_TEXT = 'container_default_text';
    /**#@-*/

    /**#@+
     * Grid values
     */
    const GRID_OPTIONS_NAME = 'options';
    const GRID_TYPE_SELECT_NAME = 'values';
    /**#@-*/

    /**#@+
     * Field values
     */
    const FIELD_ENABLE = 'affect_product_custom_options';
    const FIELD_OPTION_ID = 'option_id';
    const FIELD_TITLE_NAME = 'title';
    const FIELD_STORE_TITLE_NAME = 'store_title';
    const FIELD_TYPE_NAME = 'type';
    const FIELD_IS_REQUIRE_NAME = 'is_require';

    const FIELD_IS_ENABLE_NAME = 'is_enable';
    const FIELD_STORE_IS_ENABLE_NAME = 'store_is_enable';
    const FIELD_IS_USE_IS_ENABLE = 'is_use_default_is_enable';

    const FIELD_SORT_ORDER_NAME = 'sort_order';
    const FIELD_PRICE_NAME = 'price';
    const FIELD_PRICE_TYPE_NAME = 'price_type';
    const FIELD_SKU_NAME = 'sku';
    const FIELD_MAX_CHARACTERS_NAME = 'max_characters';
    const FIELD_FILE_EXTENSION_NAME = 'file_extension';
    const FIELD_IMAGE_SIZE_X_NAME = 'image_size_x';
    const FIELD_IMAGE_SIZE_Y_NAME = 'image_size_y';
    const FIELD_IS_DELETE = 'is_delete';
    const FIELD_IS_USE_DEFAULT = 'is_use_default';

    const FIELD_DEFAULT_TEXT = 'default_text';
    const FIELD_STORE_DEFAULT_TEXT_NAME = 'store_default_text';
    const FIELD_IS_USE_DEFAULT_TEXT = 'is_use_default_text';

    const FIELD_DEFAULT_VALUE = 'default_value';
    /**#@-*/

    /**#@+
     * Import options values
     */
    const IMPORT_OPTIONS_MODAL = 'import_options_modal';
    const CUSTOM_OPTIONS_LISTING = 'product_custom_options_listing';
    /**#@-*/

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Catalog\Model\ProductOptions\ConfigInterface
     */
    protected $productOptionsConfig;

    /**
     * @var \Magento\Catalog\Model\Config\Source\Product\Options\Price
     */
    protected $productOptionsPrice;

    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    /**
     * @var ArrayManager
     */
    protected $arrayManager;

    /**
     * @var array
     */
    protected $meta = [];

    /**
     * @var CurrencyInterface
     */
    private $localeCurrency;

    /**
     * @var Registry
     */
    private $registry;

    /**
     * @var Data
     */
    private $helper;

    /**
     * CustomOptions constructor.
     * @param StoreManagerInterface $storeManager
     * @param ConfigInterface $productOptionsConfig
     * @param ProductOptionsPrice $productOptionsPrice
     * @param UrlInterface $urlBuilder
     * @param ArrayManager $arrayManager
     * @param Registry $registry
     * @param Data $helper
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        ConfigInterface $productOptionsConfig,
        ProductOptionsPrice $productOptionsPrice,
        UrlInterface $urlBuilder,
        ArrayManager $arrayManager,
        Registry $registry,
        Data $helper
    ) {
        $this->storeManager = $storeManager;
        $this->productOptionsConfig = $productOptionsConfig;
        $this->productOptionsPrice = $productOptionsPrice;
        $this->urlBuilder = $urlBuilder;
        $this->arrayManager = $arrayManager;
        $this->registry = $registry;
        $this->helper = $helper;
    }

    /**
     * {@inheritdoc}
     */
    public function modifyData(array $data)
    {
        $options = [];

        $model = $this->getCurrentTemplate();
        $templateOptions = $model->getOptions();

        $isEnabledPerOptionEnabled = $this->helper->isEnabledPerOptionEnabled();

        /** @var \Aitoc\OptionsManagement\Model\Template\Option $option */
        $index = 0;
        foreach ($templateOptions as $option) {
            $optionData = $option->getData();

            $optionData[static::FIELD_IS_USE_DEFAULT] = !$option->getData(static::FIELD_STORE_TITLE_NAME);

            if ($this->getCurrentTemplate()->getStoreId() > 0) {
                $optionData[static::FIELD_IS_USE_DEFAULT_TEXT] = !$option->getData(static::FIELD_STORE_DEFAULT_TEXT_NAME);
            }

            if ($isEnabledPerOptionEnabled) {
                if ($this->getCurrentTemplate()->getStoreId() > 0) {
                    $optionData[static::FIELD_IS_USE_IS_ENABLE] = is_null($option->getData(static::FIELD_STORE_IS_ENABLE_NAME));
                } else {
                    $optionData[static::FIELD_IS_USE_IS_ENABLE] = 0;
                }
            }

            $options[$index] = $this->formatPriceByPath(static::FIELD_PRICE_NAME, $optionData);
            $values = $option->getValues() ?: [];

            foreach ($values as $value) {
                $value->setData(static::FIELD_IS_USE_DEFAULT, !$value->getData(static::FIELD_STORE_TITLE_NAME));
            }
            /** @var \Aitoc\OptionsManagement\Model\Template\Option\Value $value */
            foreach ($values as $value) {
                $options[$index][static::GRID_TYPE_SELECT_NAME][] = $this->formatPriceByPath(
                    static::FIELD_PRICE_NAME,
                    $value->getData()
                );
            }
            $index++;
        }

        $id = $model->getId();
        $data[$id][static::FIELD_ENABLE] = 1;
        $data[$id][static::GRID_OPTIONS_NAME] = $options;

        return $data;
    }

    /**
     * Format float number to have two digits after delimiter
     *
     * @param string $path
     * @param array $data
     * @return array
     */
    protected function formatPriceByPath($path, array $data)
    {
        $value = $this->arrayManager->get($path, $data);

        if (is_numeric($value)) {
            $data = $this->arrayManager->replace($path, $data, $this->formatPrice($value));
        }

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function modifyMeta(array $meta)
    {
        $this->meta = $meta;
        $this->createCustomOptionsPanel();
        return $this->meta;
    }

    /**
     * Create "Customizable Options" panel
     *
     * @return $this
     */
    protected function createCustomOptionsPanel()
    {
        $this->meta = array_replace_recursive(
            $this->meta,
            [
                static::GROUP_CUSTOM_OPTIONS_NAME => [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'label' => __('Customizable Options'),
                                'componentType' => Fieldset::NAME,
                                'dataScope' => static::DATA_SCOPE,
                                'collapsible' => false,
                                'sortOrder' => 20,
                            ],
                        ],
                    ],
                    'children' => [
                        static::CONTAINER_HEADER_NAME => $this->getHeaderContainerConfig(10),
                        static::FIELD_ENABLE => $this->getEnableFieldConfig(20),
                        static::GRID_OPTIONS_NAME => $this->getOptionsGridConfig(30)
                    ]
                ]
            ]
        );

        $this->meta = array_merge_recursive(
            $this->meta,
            [
                static::IMPORT_OPTIONS_MODAL => $this->getImportOptionsModalConfig()
            ]
        );

        return $this;
    }

    /**
     * Get config for header container
     *
     * @param int $sortOrder
     * @return array
     */
    protected function getHeaderContainerConfig($sortOrder)
    {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'label' => null,
                        'formElement' => Container::NAME,
                        'componentType' => Container::NAME,
                        'template' => 'ui/form/components/complex',
                        'sortOrder' => $sortOrder,
                        'content' => __('Custom options let customers choose the product variations they want.'),
                    ],
                ],
            ],
            'children' => [
                static::BUTTON_ADD => [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'title' => __('Add Option'),
                                'formElement' => Container::NAME,
                                'componentType' => Container::NAME,
                                'component' => 'Magento_Ui/js/form/components/button',
                                'sortOrder' => 20,
                                'actions' => [
                                    [
                                        'targetName' => 'ns = ${ $.ns }, index = ' . static::GRID_OPTIONS_NAME,
                                        'actionName' => 'processingAddChild',
                                    ]
                                ]
                            ]
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Get config for the whole grid
     *
     * @param int $sortOrder
     * @return array
     */
    protected function getOptionsGridConfig($sortOrder)
    {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'addButtonLabel' => __('Add Option'),
                        'componentType' => DynamicRows::NAME,
                        'component' => 'Magento_Catalog/js/components/dynamic-rows-import-custom-options',
                        'template' => 'ui/dynamic-rows/templates/collapsible',
                        'additionalClasses' => 'admin__field-wide',
                        'deleteProperty' => static::FIELD_IS_DELETE,
                        'deleteValue' => '1',
                        'addButton' => false,
                        'renderDefaultRecord' => false,
                        'columnsHeader' => false,
                        'collapsibleHeader' => true,
                        'sortOrder' => $sortOrder,
                        'dataProvider' => static::CUSTOM_OPTIONS_LISTING,
                        'imports' => ['insertData' => '${ $.provider }:${ $.dataProvider }'],
                    ],
                ],
            ],
            'children' => [
                'record' => [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'headerLabel' => __('New Option'),
                                'componentType' => Container::NAME,
                                'component' => 'Magento_Ui/js/dynamic-rows/record',
                                'positionProvider' => static::CONTAINER_OPTION . '.' . static::FIELD_SORT_ORDER_NAME,
                                'isTemplate' => true,
                                'is_collection' => true,
                            ],
                        ],
                    ],
                    'children' => [
                        static::CONTAINER_OPTION => [
                            'arguments' => [
                                'data' => [
                                    'config' => [
                                        'componentType' => Fieldset::NAME,
                                        'collapsible' => true,
                                        'label' => null,
                                        'sortOrder' => 10,
                                        'opened' => true,
                                    ],
                                ],
                            ],
                            'children' => [
                                static::FIELD_SORT_ORDER_NAME => $this->getPositionFieldConfig(40),
                                static::CONTAINER_COMMON_NAME => $this->getCommonContainerConfig(10),
                                static::CONTAINER_TYPE_STATIC_NAME => $this->getStaticTypeContainerConfig(20),
                                static::CONTAINER_DEFAULT_TEXT => $this->getDefaultTextContainerConfig(25),
                                static::GRID_TYPE_SELECT_NAME => $this->getSelectTypeGridConfig(30)
                            ]
                        ],
                    ]
                ]
            ]
        ];
    }

    /**
     * Get config for hidden field responsible for enabling custom options processing
     *
     * @param int $sortOrder
     * @return array
     */
    protected function getEnableFieldConfig($sortOrder)
    {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'formElement' => Field::NAME,
                        'componentType' => Input::NAME,
                        'dataScope' => static::FIELD_ENABLE,
                        'dataType' => Number::NAME,
                        'visible' => false,
                        'sortOrder' => $sortOrder,
                    ],
                ],
            ],
        ];
    }

    /**
     * Get config for modal window "Import Options"
     *
     * @return array
     */
    protected function getImportOptionsModalConfig()
    {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'componentType' => Modal::NAME,
                        'dataScope' => '',
                        'provider' => static::FORM_NAME . '.product_form_data_source',
                        'options' => [
                            'title' => __('Select Product'),
                            'buttons' => [
                                [
                                    'text' => __('Import'),
                                    'class' => 'action-primary',
                                    'actions' => [
                                        [
                                            'targetName' => 'index = ' . static::CUSTOM_OPTIONS_LISTING,
                                            'actionName' => 'save'
                                        ],
                                        'closeModal'
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'children' => [
                static::CUSTOM_OPTIONS_LISTING => [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'autoRender' => false,
                                'componentType' => 'insertListing',
                                'dataScope' => static::CUSTOM_OPTIONS_LISTING,
                                'externalProvider' => static::CUSTOM_OPTIONS_LISTING . '.'
                                    . static::CUSTOM_OPTIONS_LISTING . '_data_source',
                                'selectionsProvider' => static::CUSTOM_OPTIONS_LISTING . '.'
                                    . static::CUSTOM_OPTIONS_LISTING . '.product_columns.ids',
                                'ns' => static::CUSTOM_OPTIONS_LISTING,
                                'render_url' => $this->urlBuilder->getUrl('mui/index/render'),
                                'realTimeLink' => true,
                                'externalFilterMode' => false,
                                'currentProductId' => $this->getCurrentTemplate()->getId(),
                                'dataLinks' => [
                                    'imports' => false,
                                    'exports' => true
                                ],
                                'exports' => [
                                    'currentProductId' => '${ $.externalProvider }:params.current_product_id'
                                ]
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Get config for container with common fields for any type
     *
     * @param int $sortOrder
     * @return array
     */
    protected function getCommonContainerConfig($sortOrder)
    {
        $commonContainer = [
            'arguments' => [
                'data' => [
                    'config' => [
                        'componentType' => Container::NAME,
                        'formElement' => Container::NAME,
                        'component' => 'Magento_Ui/js/form/components/group',
                        'breakLine' => false,
                        'showLabel' => false,
                        'additionalClasses' => 'admin__field-group-columns admin__control-group-equal',
                        'sortOrder' => $sortOrder,
                    ],
                ],
            ],
            'children' => [
                static::FIELD_OPTION_ID => $this->getOptionIdFieldConfig(10),
                static::FIELD_TITLE_NAME => $this->getTitleFieldConfig(
                    20,
                    [
                        'arguments' => [
                            'data' => [
                                'config' => [
                                    'label' => __('Option Title'),
                                    'component' => 'Magento_Catalog/component/static-type-input',
                                    'valueUpdate' => 'input',
                                    'imports' => [
                                        'optionId' => '${ $.provider }:${ $.parentScope }.option_id',
                                        'isUseDefault' => '${ $.provider }:${ $.parentScope }.is_use_default'
                                    ]
                                ],
                            ],
                        ],
                    ]
                ),
                static::FIELD_TYPE_NAME => $this->getTypeFieldConfig(30),
                static::FIELD_IS_REQUIRE_NAME => $this->getIsRequireFieldConfig(40)
            ]
        ];

        if ($this->helper->isEnabledPerOptionEnabled()) {
            $commonContainer['children'][static::FIELD_IS_ENABLE_NAME] = $this->getIsEnabledFieldConfig(50);
        }

        if ($this->getCurrentTemplate()->getStoreId()) {
            $useDefaultConfig = [
                'service' => [
                    'template' => 'Magento_Catalog/form/element/helper/custom-option-service',
                ]
            ];
            $titlePath = $this->arrayManager->findPath(static::FIELD_TITLE_NAME, $commonContainer, null)
                . static::META_CONFIG_PATH;
            $commonContainer = $this->arrayManager->merge($titlePath, $commonContainer, $useDefaultConfig);
        }

        return $commonContainer;
    }

    /**
     * Get config for container with fields for all types except "select"
     *
     * @param int $sortOrder
     * @return array
     */
    protected function getStaticTypeContainerConfig($sortOrder)
    {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'componentType' => Container::NAME,
                        'formElement' => Container::NAME,
                        'component' => 'Magento_Ui/js/form/components/group',
                        'breakLine' => false,
                        'showLabel' => false,
                        'additionalClasses' => 'admin__field-group-columns admin__control-group-equal',
                        'sortOrder' => $sortOrder,
                    ],
                ],
            ],
            'children' => [
                static::FIELD_PRICE_NAME => $this->getPriceFieldConfig(10),
                static::FIELD_PRICE_TYPE_NAME => $this->getPriceTypeFieldConfig(20),
                static::FIELD_SKU_NAME => $this->getSkuFieldConfig(30),
                static::FIELD_MAX_CHARACTERS_NAME => $this->getMaxCharactersFieldConfig(40),
                static::FIELD_FILE_EXTENSION_NAME => $this->getFileExtensionFieldConfig(50),
                static::FIELD_IMAGE_SIZE_X_NAME => $this->getImageSizeXFieldConfig(60),
                static::FIELD_IMAGE_SIZE_Y_NAME => $this->getImageSizeYFieldConfig(70)
            ]
        ];
    }

    /**
     * Get config for container2 with fields for all types except "select"
     *
     * @param int $sortOrder
     * @return array
     */
    protected function getDefaultTextContainerConfig($sortOrder)
    {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'componentType' => Container::NAME,
                        'formElement' => Container::NAME,
                        'component' => 'Magento_Ui/js/form/components/group',
                        'breakLine' => false,
                        'showLabel' => false,
                        'additionalClasses' => 'admin__field-group-columns admin__control-group-equal',
                        'sortOrder' => $sortOrder
                    ],
                ],
            ],
            'children' => [
                static::FIELD_DEFAULT_TEXT => $this->getDefaultTextFieldConfig(10),
                static::FIELD_DEFAULT_TEXT . '_area' => $this->getDefaultTextFieldConfig(10, true),
            ]
        ];
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
                            'isUseDefault' => '${ $.provider }:${ $.parentScope }.is_use_default_text'
                        ]
                    ],
                ],
            ],
        ];

        if ($this->getCurrentTemplate()->getStoreId()) {
            $data['arguments']['data']['config']['service'] = [
                'template' => 'Magento_Catalog/form/element/helper/custom-option-service',
            ];
        }

        return $data;
    }

    /**
     * Get config for grid for "select" types
     *
     * @param int $sortOrder
     * @return array
     */
    protected function getSelectTypeGridConfig($sortOrder)
    {
        $options = [
            'arguments' => [
                'data' => [
                    'config' => [
                        'imports' => [
                            'optionId' => '${ $.provider }:${ $.parentScope }.option_id',
                            'optionTypeId' => '${ $.provider }:${ $.parentScope }.option_type_id',
                            'isUseDefault' => '${ $.provider }:${ $.parentScope }.is_use_default'
                        ],
                        'service' => [
                            'template' => 'Aitoc_OptionsManagement/form/element/helper/custom-option-type-service',
                        ],
                    ],
                ],
            ],
        ];

        $data = [
            'arguments' => [
                'data' => [
                    'config' => [
                        'addButtonLabel' => __('Add Value'),
                        'componentType' => DynamicRows::NAME,
                        'component' => 'Magento_Ui/js/dynamic-rows/dynamic-rows',
                        'additionalClasses' => 'admin__field-wide',
                        'deleteProperty' => static::FIELD_IS_DELETE,
                        'deleteValue' => '1',
                        'renderDefaultRecord' => false,
                        'sortOrder' => $sortOrder,
                    ]
                ]
            ],
            'children' => [
                'record' => [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'componentType' => Container::NAME,
                                'component' => 'Magento_Ui/js/dynamic-rows/record',
                                'positionProvider' => static::FIELD_SORT_ORDER_NAME,
                                'isTemplate' => true,
                                'is_collection' => true,
                            ],
                        ],
                    ],
                    'children' => [
                        static::FIELD_TITLE_NAME => $this->getTitleFieldConfig(
                            10,
                            $this->getCurrentTemplate()->getStoreId() ? $options : []
                        ),
                        static::FIELD_PRICE_NAME => $this->getPriceFieldConfig(20),
                        static::FIELD_PRICE_TYPE_NAME => $this->getPriceTypeFieldConfig(30, ['fit' => true]),
                        static::FIELD_SKU_NAME => $this->getSkuFieldConfig(40),
                        static::FIELD_SORT_ORDER_NAME => $this->getPositionFieldConfig(50)
                    ]
                ]
            ]
        ];


        if ($this->helper->isDefaultValueEnabled()) {
            $data['children']['record']['children'][static::FIELD_DEFAULT_VALUE]
                = $this->getDefaultValueFieldConfig(59);
        }

        $data['children']['record']['children'][static::FIELD_IS_DELETE] = $this->getIsDeleteFieldConfig(60);

        return $data;
    }

    /**
     * Get config for hidden id field
     *
     * @param int $sortOrder
     * @return array
     */
    protected function getOptionIdFieldConfig($sortOrder)
    {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'formElement' => Input::NAME,
                        'componentType' => Field::NAME,
                        'dataScope' => static::FIELD_OPTION_ID,
                        'sortOrder' => $sortOrder,
                        'visible' => false,
                    ],
                ],
            ],
        ];
    }

    /**
     * Get config for "Title" fields
     *
     * @param int $sortOrder
     * @param array $options
     * @return array
     */
    protected function getTitleFieldConfig($sortOrder, array $options = [])
    {
        return array_replace_recursive(
            [
                'arguments' => [
                    'data' => [
                        'config' => [
                            'label' => __('Title'),
                            'componentType' => Field::NAME,
                            'formElement' => Input::NAME,
                            'dataScope' => static::FIELD_TITLE_NAME,
                            'dataType' => Text::NAME,
                            'sortOrder' => $sortOrder,
                            'validation' => [
                                'required-entry' => true
                            ],
                        ],
                    ],
                ],
            ],
            $options
        );
    }

    /**
     * Get config for "Option Type" field
     *
     * @param int $sortOrder
     * @return array
     */
    protected function getTypeFieldConfig($sortOrder)
    {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'label' => __('Option Type'),
                        'componentType' => Field::NAME,
                        'formElement' => Select::NAME,
                        'component' => 'Aitoc_OptionsManagement/js/component/custom-options-type',
                        'elementTmpl' => 'ui/grid/filters/elements/ui-select',
                        'selectType' => 'optgroup',
                        'dataScope' => static::FIELD_TYPE_NAME,
                        'dataType' => Text::NAME,
                        'sortOrder' => $sortOrder,
                        'options' => $this->getProductOptionTypes(),
                        'disableLabel' => true,
                        'multiple' => false,
                        'selectedPlaceholders' => [
                            'defaultPlaceholder' => __('-- Please select --'),
                        ],
                        'validation' => [
                            'required-entry' => true
                        ],
                        'groupsConfig' => [
                            'text' => [
                                'values' => ['field'],
                                'indexes' => [
                                    static::CONTAINER_TYPE_STATIC_NAME,
                                    static::CONTAINER_DEFAULT_TEXT,
                                    static::FIELD_PRICE_NAME,
                                    static::FIELD_PRICE_TYPE_NAME,
                                    static::FIELD_SKU_NAME,
                                    static::FIELD_MAX_CHARACTERS_NAME,
                                    static::FIELD_DEFAULT_TEXT
                                ]
                            ],
                            'area' => [
                                'values' => ['area'],
                                'indexes' => [
                                    static::CONTAINER_TYPE_STATIC_NAME,
                                    static::CONTAINER_DEFAULT_TEXT,
                                    static::FIELD_PRICE_NAME,
                                    static::FIELD_PRICE_TYPE_NAME,
                                    static::FIELD_SKU_NAME,
                                    static::FIELD_MAX_CHARACTERS_NAME,
                                    static::FIELD_DEFAULT_TEXT . '_area'
                                ]
                            ],
                            'file' => [
                                'values' => ['file'],
                                'indexes' => [
                                    static::CONTAINER_TYPE_STATIC_NAME,
                                    static::FIELD_PRICE_NAME,
                                    static::FIELD_PRICE_TYPE_NAME,
                                    static::FIELD_SKU_NAME,
                                    static::FIELD_FILE_EXTENSION_NAME,
                                    static::FIELD_IMAGE_SIZE_X_NAME,
                                    static::FIELD_IMAGE_SIZE_Y_NAME
                                ]
                            ],
                            'select' => [
                                'values' => ['drop_down', 'radio', 'checkbox', 'multiple'],
                                'indexes' => [
                                    static::GRID_TYPE_SELECT_NAME
                                ]
                            ],
                            'data' => [
                                'values' => ['date', 'date_time', 'time'],
                                'indexes' => [
                                    static::CONTAINER_TYPE_STATIC_NAME,
                                    static::FIELD_PRICE_NAME,
                                    static::FIELD_PRICE_TYPE_NAME,
                                    static::FIELD_SKU_NAME
                                ]
                            ]
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Get config for "Required" field
     *
     * @param int $sortOrder
     * @return array
     */
    protected function getIsRequireFieldConfig($sortOrder)
    {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'label' => __('Required'),
                        'componentType' => Field::NAME,
                        'formElement' => Checkbox::NAME,
                        'dataScope' => static::FIELD_IS_REQUIRE_NAME,
                        'dataType' => Text::NAME,
                        'sortOrder' => $sortOrder,
                        'value' => '1',
                        'valueMap' => [
                            'true' => '1',
                            'false' => '0'
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Get config for "Enabled" field
     *
     * @param int $sortOrder
     * @return array
     */
    protected function getIsEnabledFieldConfig($sortOrder)
    {
        $data =  [
            'arguments' => [
                'data' => [
                    'config' => [
                        'dataType' => Number::NAME,
                        'formElement' => Checkbox::NAME,
                        'visible' => 1,
                        'required' => 0,
                        'default' => 1,
                        'notice' => '',
                        'label' => __('Enabled'),
                        'code' => static::FIELD_IS_ENABLE_NAME,
                        'scopeLabel' => '',
                        'sortOrder' => $sortOrder,
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
                            'isUseDefault' => '${ $.provider }:${ $.parentScope }.is_use_default_is_enable'
                        ]
                    ],
                ],
            ]
        ];

        if ($this->getCurrentTemplate()->getStoreId()) {
            $data['arguments']['data']['config']['service'] = [
                'template' => 'Magento_Catalog/form/element/helper/custom-option-service',
            ];
        }

        return $data;
    }

    /**
     * Get config for hidden field used for sorting
     *
     * @param int $sortOrder
     * @return array
     */
    protected function getPositionFieldConfig($sortOrder)
    {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'componentType' => Field::NAME,
                        'formElement' => Input::NAME,
                        'dataScope' => static::FIELD_SORT_ORDER_NAME,
                        'dataType' => Number::NAME,
                        'visible' => false,
                        'sortOrder' => $sortOrder,
                    ],
                ],
            ],
        ];
    }

    /**
     * Get config for hidden field used for removing rows
     *
     * @param int $sortOrder
     * @return array
     */
    protected function getIsDeleteFieldConfig($sortOrder)
    {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'componentType' => ActionDelete::NAME,
                        'fit' => true,
                        'sortOrder' => $sortOrder
                    ],
                ],
            ],
        ];
    }

    /**
     * Get config for "Price" field
     *
     * @param int $sortOrder
     * @return array
     */
    protected function getPriceFieldConfig($sortOrder)
    {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'label' => __('Price'),
                        'componentType' => Field::NAME,
                        'formElement' => Input::NAME,
                        'dataScope' => static::FIELD_PRICE_NAME,
                        'dataType' => Number::NAME,
                        'addbefore' => $this->getCurrencySymbol(),
                        'sortOrder' => $sortOrder,
                        'validation' => [
                            'validate-zero-or-greater' => true
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Get config for "Price Type" field
     *
     * @param int $sortOrder
     * @param array $config
     * @return array
     */
    protected function getPriceTypeFieldConfig($sortOrder, array $config = [])
    {
        return array_replace_recursive(
            [
                'arguments' => [
                    'data' => [
                        'config' => [
                            'label' => __('Price Type'),
                            'componentType' => Field::NAME,
                            'formElement' => Select::NAME,
                            'dataScope' => static::FIELD_PRICE_TYPE_NAME,
                            'dataType' => Text::NAME,
                            'sortOrder' => $sortOrder,
                            'options' => $this->productOptionsPrice->toOptionArray(),
                        ],
                    ],
                ],
            ],
            $config
        );
    }

    /**
     * Get config for "SKU" field
     *
     * @param int $sortOrder
     * @return array
     */
    protected function getSkuFieldConfig($sortOrder)
    {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'label' => __('SKU'),
                        'componentType' => Field::NAME,
                        'formElement' => Input::NAME,
                        'dataScope' => static::FIELD_SKU_NAME,
                        'dataType' => Text::NAME,
                        'sortOrder' => $sortOrder,
                    ],
                ],
            ],
        ];
    }

    /**
     * Get config for "Max Characters" field
     *
     * @param int $sortOrder
     * @return array
     */
    protected function getMaxCharactersFieldConfig($sortOrder)
    {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'label' => __('Max Characters'),
                        'componentType' => Field::NAME,
                        'formElement' => Input::NAME,
                        'dataScope' => static::FIELD_MAX_CHARACTERS_NAME,
                        'dataType' => Number::NAME,
                        'sortOrder' => $sortOrder,
                        'validation' => [
                            'validate-zero-or-greater' => true
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Get config for "File Extension" field
     *
     * @param int $sortOrder
     * @return array
     */
    protected function getFileExtensionFieldConfig($sortOrder)
    {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'label' => __('Compatible File Extensions'),
                        'componentType' => Field::NAME,
                        'formElement' => Input::NAME,
                        'dataScope' => static::FIELD_FILE_EXTENSION_NAME,
                        'dataType' => Text::NAME,
                        'sortOrder' => $sortOrder,
                    ],
                ],
            ],
        ];
    }

    /**
     * Get config for "Maximum Image Width" field
     *
     * @param int $sortOrder
     * @return array
     */
    protected function getImageSizeXFieldConfig($sortOrder)
    {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'label' => __('Maximum Image Size'),
                        'notice' => __('Please leave blank if it is not an image.'),
                        'addafter' => __('px.'),
                        'componentType' => Field::NAME,
                        'formElement' => Input::NAME,
                        'dataScope' => static::FIELD_IMAGE_SIZE_X_NAME,
                        'dataType' => Number::NAME,
                        'sortOrder' => $sortOrder,
                        'validation' => [
                            'validate-zero-or-greater' => true
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Get config for "Maximum Image Height" field
     *
     * @param int $sortOrder
     * @return array
     */
    protected function getImageSizeYFieldConfig($sortOrder)
    {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'label' => ' ',
                        'addafter' => __('px.'),
                        'componentType' => Field::NAME,
                        'formElement' => Input::NAME,
                        'dataScope' => static::FIELD_IMAGE_SIZE_Y_NAME,
                        'dataType' => Number::NAME,
                        'sortOrder' => $sortOrder,
                        'validation' => [
                            'validate-zero-or-greater' => true
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Get options for drop-down control with product option types
     *
     * @return array
     */
    protected function getProductOptionTypes()
    {
        $options = [];
        $groupIndex = 0;

        foreach ($this->productOptionsConfig->getAll() as $option) {
            $group = [
                'value' => $groupIndex,
                //TODO: Wrap label with __() or remove this TODO after MAGETWO-49771 is closed
                'label' => $option['label'],
                'optgroup' => []
            ];

            foreach ($option['types'] as $type) {
                if ($type['disabled']) {
                    continue;
                }

                //TODO: Wrap label with __() or remove this TODO after MAGETWO-49771 is closed
                $group['optgroup'][] = ['label' => $type['label'], 'value' => $type['name']];
            }

            if (count($group['optgroup'])) {
                $options[] = $group;
                $groupIndex += 1;
            }
        }

        return $options;
    }

    /**
     * Get currency symbol
     *
     * @return string
     */
    protected function getCurrencySymbol()
    {
        return $this->storeManager->getStore()->getBaseCurrency()->getCurrencySymbol();
    }

    /**
     * The getter function to get the locale currency for real application code
     *
     * @return \Magento\Framework\Locale\CurrencyInterface
     *
     * @deprecated
     */
    private function getLocaleCurrency()
    {
        if ($this->localeCurrency === null) {
            $this->localeCurrency = \Magento\Framework\App\ObjectManager::getInstance()->get(CurrencyInterface::class);
        }
        return $this->localeCurrency;
    }

    /**
     * Format price according to the locale of the currency
     *
     * @param mixed $value
     * @return string
     */
    protected function formatPrice($value)
    {
        if (!is_numeric($value)) {
            return null;
        }

        $store = $this->storeManager->getStore();
        $currency = $this->getLocaleCurrency()->getCurrency($store->getBaseCurrencyCode());
        $value = $currency->toCurrency($value, ['display' => \Magento\Framework\Currency::NO_SYMBOL]);

        return $value;
    }

    /**
     * @return \Aitoc\OptionsManagement\Model\Template
     */
    public function getCurrentTemplate()
    {
        return $this->registry->registry('current_template');
    }

    /**
     * Get config for "Default Value" field
     *
     * @param int $sortOrder
     * @return array
     */
    protected function getDefaultValueFieldConfig($sortOrder)
    {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'label' => __('Default'),
                        'component' => 'Aitoc_OptionsManagement/js/component/default-value',
                        'elementTmpl' => 'Aitoc_OptionsManagement/form/components/single/default-value-checkbox',
                        'componentType' => Field::NAME,
                        'formElement' => Checkbox::NAME,
                        'dataScope' => static::FIELD_DEFAULT_VALUE,
                        'dataType' => Text::NAME,
                        'sortOrder' => $sortOrder,
                        'value' => '0',
                        'valueMap' => [
                            'true' => '1',
                            'false' => '0'
                        ],
                        'imports' => [
                            'optionTypeUpdated' => '${ $.parentName.replace(/\.values\.[0-9]+$/g, \'\') }.container_common.type:value'
                        ]
                    ]
                ]
            ]
        ];
    }
}
