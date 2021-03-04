<?php


namespace Elevate\BundleAdvanced\Ui\DataProvider\Product\Form\Modifier;

use Elevate\BundleAdvanced\Api\Data\ProductAttributeInterface;
use Elevate\BundleAdvanced\Model\Config;
use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier;
use Magento\Catalog\Model\Locator\LocatorInterface;
use Magento\Framework\Stdlib\ArrayManager;
use Magento\Bundle\Model\Product\Type as BundleProduct;

/**
 * Class BundlePanel
 * @package Elevate\BundleAdvanced\Ui\DataProvider\Product\Form\Modifier
 */
class BundlePanel extends AbstractModifier
{
    /**
     * @var LocatorInterface
     */
    private $locator;

    /**
     * @var ArrayManager
     */
    private $arrayManager;

    /**
     * @var Config
     */
    private $config;

    /**
     * @param LocatorInterface $locator
     * @param ArrayManager $arrayManager
     * @param Config $config
     */
    public function __construct(
        LocatorInterface $locator,
        ArrayManager $arrayManager,
        Config $config
    ) {
        $this->locator = $locator;
        $this->arrayManager = $arrayManager;
        $this->config = $config;
    }

    /**
     * {@inheritdoc}
     */
    public function modifyMeta(array $meta)
    {
        if ($this->locator->getProduct()->getTypeId() === BundleProduct::TYPE_CODE) {
            $this
                ->modifySwitchToSimpleBundle($meta)
                ->modifyBundleSelections($meta);
        }

        return $meta;
    }

    /**
     * {@inheritdoc}
     */
    public function modifyData(array $data)
    {
        return $data;
    }

    /**
     * Modify switch to simple bundle
     *
     * @param array $meta
     * @return $this
     */
    private function modifySwitchToSimpleBundle(&$meta)
    {
        $sbpBundleProductTypePath = $this->arrayManager
            ->findPath(ProductAttributeInterface::CODE_ELEVATE_BUNDLEADVANCED_BUNDLE_PRODUCT_TYPE, $meta, null, 'children');
        if (!$sbpBundleProductTypePath) {
            return $this;
        }

        $isEditable = $this->isEditable();
        $addOptionButtonComponent = 'product_form.product_form.bundle-items.bundle_header.add_button';
        $optionInfoComponent = 'product_form.product_form.bundle-items.bundle_options.0.'
            . 'product_bundle_container.option_info';
        $meta = $this->arrayManager->merge(
            $sbpBundleProductTypePath,
            $meta,
            [
                'arguments' => ['data' => ['config' => [
                    'component' => 'Elevate_BundleAdvanced/js/ui/form/product/bundle/to_simple_bundle_switcher',
                    'confirmText' => __('Are you sure want to switch view? All bundle options will be permanently '
                        . 'deleted!'),
                    'isEditable' => $isEditable,
                    'disabled' => !$isEditable,
                    'switcherConfig' => [
                        'enabled' => true,
                        'rules' => [
                            [
                                'value' => 1,
                                'actions' => [
                                    [
                                        'target' => $addOptionButtonComponent,
                                        'callback' => 'visible',
                                        'params' => [false]
                                    ],
                                    [
                                        'target' => $optionInfoComponent . '.type',
                                        'callback' => 'visible',
                                        'params' => [false]
                                    ],
                                    [
                                        'target' => $optionInfoComponent . '.required',
                                        'callback' => 'visible',
                                        'params' => [false]
                                    ]
                                ]
                            ],
                            [
                                'value' => 0,
                                'actions' => [
                                    [
                                        'target' => $addOptionButtonComponent,
                                        'callback' => 'visible',
                                        'params' => [true]
                                    ],
                                    [
                                        'target' => $optionInfoComponent . '.type',
                                        'callback' => 'visible',
                                        'params' => [true]
                                    ],
                                    [
                                        'target' => $optionInfoComponent . '.required',
                                        'callback' => 'visible',
                                        'params' => [true]
                                    ]
                                ]
                            ]
                        ]
                    ],
                    'toSimpleBundleSwitcherConfig' => $this->getToSimpleBundleSwitcherConfig()
                ]]]
            ]
        );

        return $this;
    }

    /**
     * Retrieve config for simple bundle switcher
     *
     * @return array
     */
    private function getToSimpleBundleSwitcherConfig()
    {
        $optionInfoComponent = 'product_form.product_form.bundle-items.bundle_options.0.'
            . 'product_bundle_container.option_info';
        $config = [
            'rules' => [
                [
                    'target' => $optionInfoComponent . '.type',
                    'actions' => [
                        [
                            'callback' => 'value',
                            'params' => ['checkbox']
                        ],
                        [
                            'callback' => 'visible',
                            'params' => [false]
                        ]
                    ]
                ],
                [
                    'target' => $optionInfoComponent . '.required',
                    'actions' => [
                        [
                            'callback' => 'visible',
                            'params' => [false]
                        ],
                        [
                            'callback' => 'value',
                            'params' => ['0']
                        ]
                    ]
                ]
            ]
        ];

        if ($this->isEditable()) {
            $config['rules'][] = [
                'target' => $optionInfoComponent . '.title',
                'actions' => [
                    [
                        'callback' => 'value',
                        'params' => [$this->config->getDefaultTitleForListOfBundleProducts()]
                    ]
                ]
            ];
        }

        return $config;
    }

    /**
     * Check if is editable
     *
     * @return bool
     */
    private function isEditable()
    {
        return $this->locator->getProduct()->getId() == null;
    }

    /**
     * Modify bundle selections
     *
     * @param array $meta
     * @return $this
     */
    private function modifyBundleSelections(&$meta)
    {
        $bundleSelectionsPath = $this->arrayManager->findPath('bundle_selections', $meta, null, 'children');
        if (!$bundleSelectionsPath) {
            return $this;
        }

        $meta = $this->arrayManager->merge(
            $bundleSelectionsPath . '/children/record',
            $meta,
            [
                'arguments' => ['data' => ['config' => [
                    'component' => 'Elevate_BundleAdvanced/js/ui/dynamic-rows/record',
                ]]]
            ]
        );
        $meta = $this->arrayManager->merge(
            $bundleSelectionsPath . '/children/record/children/is_default',
            $meta,
            [
                'arguments' => ['data' => ['config' => [
                    'imports' => [
                        'visible' => 'product_form.product_form.product-details.container_elevate_bundleadvanced_bundle_product_type'
                            . '.elevate_bundleadvanced_bundle_product_type:invertedChecked'
                    ]
                ]]]
            ]
        );

        return $this;
    }
}
