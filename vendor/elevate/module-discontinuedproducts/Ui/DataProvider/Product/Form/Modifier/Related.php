<?php

namespace Elevate\Discontinuedproducts\Ui\DataProvider\Product\Form\Modifier;

use Magento\Ui\Component\Form\Fieldset;

class Related extends \Elevate\LinkedProducts\Ui\DataProvider\Product\Form\Modifier\Related
{

    const DATA_SCOPE_DISCONTINUEDPRODUCTS = 'discontinuedproducts';
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
                        $this->scopePrefix . static::DATA_SCOPE_LINKEDPRODUCTS => $this->getLinkedProductsFieldset(),
                        $this->scopePrefix . static::DATA_SCOPE_DISCONTINUEDPRODUCTS => $this->getDiscontinuedproductsFieldset()
                    ],
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'label' => __('Related Products, Up-Sells, Cross-Sells, Linked Products and Discontinued Products'),
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
    protected function getDiscontinuedproductsFieldset()
    {
        $content = __(
            'Something products variations together in collections.'
        );

        return [
            'children' => [
                'button_set' => $this->getButtonSet(
                    $content,
                    __('Add Discontinued Products'),
                    $this->scopePrefix . static::DATA_SCOPE_DISCONTINUEDPRODUCTS
                ),
                'modal' => $this->getGenericModal(
                    __('Add Discontinued Products'),
                    $this->scopePrefix . static::DATA_SCOPE_DISCONTINUEDPRODUCTS
                ),
                static::DATA_SCOPE_DISCONTINUEDPRODUCTS => $this->getGrid($this->scopePrefix . static::DATA_SCOPE_DISCONTINUEDPRODUCTS),
            ],
            'arguments' => [
                'data' => [
                    'config' => [
                        'additionalClasses' => 'admin__fieldset-section',
                        'label' => __('Discontinued Products'),
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
            static::DATA_SCOPE_DISCONTINUEDPRODUCTS,
            static::DATA_SCOPE_LINKEDPRODUCTS
        ];
    }
}
