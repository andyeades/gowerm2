<?php

namespace Elevate\LinkedProducts\Ui\DataProvider\Product\Form\Modifier;

use Magento\Ui\Component\Form\Fieldset;

class Related extends \Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\Related
{
    const DATA_SCOPE_LINKEDPRODUCTS = 'linkedproducts';

    /**
     * @var string
     */
    private static $previousGroup = 'search-engine-optimization';

    /**
     * @var int
     */
    private static $sortOrder = 90;

    /**
     * {@inheritdoc}
     */
    public function modifyMeta(array $meta)
    {
        $meta = array_replace_recursive(
            $meta,
            [
                static::GROUP_RELATED => [
                    'children' => [
                        $this->scopePrefix . static::DATA_SCOPE_RELATED => $this->getRelatedFieldset(),
                        $this->scopePrefix . static::DATA_SCOPE_UPSELL => $this->getUpSellFieldset(),
                        $this->scopePrefix . static::DATA_SCOPE_CROSSSELL => $this->getCrossSellFieldset(),
                        $this->scopePrefix . static::DATA_SCOPE_LINKEDPRODUCTS => $this->getLinkedProductsFieldset()
                    ],
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'label' => __('Related Products, Up-Sells, Cross-Sells and Linked Products'),
                                'collapsible' => true,
                                'componentType' => Fieldset::NAME,
                                'dataScope' => static::DATA_SCOPE,
                                'sortOrder' =>
                                    $this->getNextGroupSortOrder(
                                        $meta,
                                        self::$previousGroup,
                                        self::$sortOrder
                                    ),
                            ],
                        ],

                    ],
                ],
            ]
        );

        return $meta;
    }

    /**
     * Prepares config for the Linked products fieldset
     *
     * @return array
     */
    protected function getLinkedProductsFieldset()
    {
        $content = __(
            'Linked products variations together in collections.'
        );

        return [
            'children' => [
                'button_set' => $this->getButtonSet(
                    $content,
                    __('Add Linked Products'),
                    $this->scopePrefix . static::DATA_SCOPE_LINKEDPRODUCTS
                ),
                'modal' => $this->getGenericModal(
                    __('Add Linked Products'),
                    $this->scopePrefix . static::DATA_SCOPE_LINKEDPRODUCTS
                ),
                static::DATA_SCOPE_LINKEDPRODUCTS => $this->getGrid($this->scopePrefix . static::DATA_SCOPE_LINKEDPRODUCTS),
            ],
            'arguments' => [
                'data' => [
                    'config' => [
                        'additionalClasses' => 'admin__fieldset-section',
                        'label' => __('Linked Products'),
                        'collapsible' => false,
                        'componentType' => Fieldset::NAME,
                        'dataScope' => '',
                        'sortOrder' => 90,
                    ],
                ],
            ]
        ];
    }

    /**
     * Retrieve all data scopes
     *
     * @return array
     */
    protected function getDataScopes()
    {
        return [
            static::DATA_SCOPE_RELATED,
            static::DATA_SCOPE_CROSSSELL,
            static::DATA_SCOPE_UPSELL,
            static::DATA_SCOPE_LINKEDPRODUCTS
        ];
    }
}