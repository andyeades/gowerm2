<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2019 Aitoc (https://www.aitoc.com)
 * @package Aitoc_OptionsManagement
 */

/**
 * Copyright © 2018 Aitoc. All rights reserved.
 */
namespace Aitoc\OptionsManagement\Block\Product\View\Options\Type;
/**
 * Product options text type block
 *
 * @api
 * @since 100.0.2
 */
class Select extends \Magento\Catalog\Block\Product\View\Options\AbstractOptions
{
    /**
     * @var \Aitoc\OptionsManagement\Helper\Data
     */
    protected $helper;

    /**
     * Select constructor.
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\Pricing\Helper\Data $pricingHelper
     * @param \Magento\Catalog\Helper\Data $catalogData
     * @param \Aitoc\OptionsManagement\Helper\Data $helper
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Pricing\Helper\Data $pricingHelper,
        \Magento\Catalog\Helper\Data $catalogData,
        \Aitoc\OptionsManagement\Helper\Data $helper,
        array $data = []
    ) {
        parent::__construct($context, $pricingHelper, $catalogData, $data);
        $this->helper = $helper;
    }

    /**
     * @param string $template
     * @return $this
     */
    public function setTemplate($template)
    {
        if ($template == 'product/view/options/type/select.phtml') {
            $template = 'Magento_Catalog::product/view/options/type/select.phtml';
        }
        return parent::setTemplate($template);
    }

    /**
     * Return html for control element
     *
     * @return string
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function getValuesHtml()
    {
        $_option = $this->getOption();

        $configValue = $this->getProduct()->getPreconfiguredValues()->getData('options/' . $_option->getId());
        $store = $this->getProduct()->getStore();


        $isDefaultValueEnabled = $this->helper->isDefaultValueEnabled();


        $this->setSkipJsReloadPrice(1);
        // Remove inline prototype onclick and onchange events

        if ($_option->getType() == 'drop_down' ||
            $_option->getType() == 'multiple'
        ) {
            $require = $_option->getIsRequire() ? ' required' : '';
            $extraParams = '';
            $select = $this->getLayout()->createBlock(
                \Magento\Framework\View\Element\Html\Select::class
            )->setData(
                [
                    'id' => 'select_' . $_option->getId(),
                    'class' => $require . ' product-custom-option admin__control-select'
                ]
            );
            if ($_option->getType() == 'drop_down') {
                $select->setName('options[' . $_option->getid() . ']')->addOption('', __('-- Please Select --'));
            } else {
                $select->setName('options[' . $_option->getid() . '][]');
                $select->setClass('multiselect admin__control-multiselect' . $require . ' product-custom-option');
            }
            foreach ($_option->getValues() as $_value) {
                $priceStr = $this->_formatPrice(
                    [
                        'is_percent' => $_value->getPriceType() == 'percent',
                        'pricing_value' => $_value->getPrice($_value->getPriceType() == 'percent'),
                    ],
                    false
                );
                $select->addOption(
                    $_value->getOptionTypeId(),
                    $_value->getTitle() . ' ' . strip_tags($priceStr) . '',
                    ['price' => $this->pricingHelper->currencyByStore($_value->getPrice(true), $store, false)]
                );
            }
            if ($_option->getType() == 'multiple') {
                $extraParams = ' multiple="multiple"';
            }
            if (!$this->getSkipJsReloadPrice()) {
                $extraParams .= ' onchange="opConfig.reloadPrice()"';
            }
            $extraParams .= ' data-selector="' . $select->getName() . '"';
            $select->setExtraParams($extraParams);

            if ($configValue) {
                $select->setValue($configValue);
            }

            // set default value
            if ($isDefaultValueEnabled && !$configValue) {
                $configValue = [];
                foreach ($_option->getValues() as $_value) {
                    if ($_value->getDefaultValue()) {
                        $configValue[] = $_value->getId();
                    }
                }
                $select->setValue($configValue);
            }

            return $select->getHtml();
        }

        if ($_option->getType() == 'radio' || $_option->getType() == 'checkbox') {
            $selectHtml = '<div class="options-list nested" id="options-' . $_option->getId() . '-list">';
            $require = $_option->getIsRequire() ? ' required' : '';
            $arraySign = '';
            switch ($_option->getType()) {
                case 'radio':
                    $type = 'radio';
                    $class = 'radio admin__control-radio';
                    if (!$_option->getIsRequire()) {
                        $selectHtml .= '<div class="field choice admin__field admin__field-option">' .
                            '<input type="radio" id="options_' .
                            $_option->getId() .
                            '" class="' .
                            $class .
                            ' product-custom-option" name="options[' .
                            $_option->getId() .
                            ']"' .
                            ' data-selector="options[' . $_option->getId() . ']"' .
                            ($this->getSkipJsReloadPrice() ? '' : ' onclick="opConfig.reloadPrice()"') .
                            ' value="" checked="checked" /><label class="label admin__field-label" for="options_' .
                            $_option->getId() .
                            '"><span>' .
                            __('None') . '</span></label></div>';
                    }
                    break;
                case 'checkbox':
                    $type = 'checkbox';
                    $class = 'checkbox admin__control-checkbox';
                    $arraySign = '[]';
                    break;
            }
            $count = 1;
            foreach ($_option->getValues() as $_value) {
                $count++;

                $priceStr = $this->_formatPrice(
                    [
                        'is_percent' => $_value->getPriceType() == 'percent',
                        'pricing_value' => $_value->getPrice($_value->getPriceType() == 'percent'),
                    ]
                );

                $htmlValue = $_value->getOptionTypeId();
                if ($arraySign) {
                    $checked = is_array($configValue) && in_array($htmlValue, $configValue) ? 'checked' : '';
                } else {
                    $checked = $configValue == $htmlValue ? 'checked' : '';
                }

                // set default value
                if ($isDefaultValueEnabled && !$configValue) {
                    $checked = ($_value->getDefaultValue() ? 'checked'  : '');
                }

                $dataSelector = 'options[' . $_option->getId() . ']';
                if ($arraySign) {
                    $dataSelector .= '[' . $htmlValue . ']';
                }

                $selectHtml .= '<div class="field choice admin__field admin__field-option' .
                    $require .
                    '">' .
                    '<input type="' .
                    $type .
                    '" class="' .
                    $class .
                    ' ' .
                    $require .
                    ' product-custom-option"' .
                    ($this->getSkipJsReloadPrice() ? '' : ' onclick="opConfig.reloadPrice()"') .
                    ' name="options[' .
                    $_option->getId() .
                    ']' .
                    $arraySign .
                    '" id="options_' .
                    $_option->getId() .
                    '_' .
                    $count .
                    '" value="' .
                    $htmlValue .
                    '" ' .
                    $checked .
                    ' data-selector="' . $dataSelector . '"' .
                    ' price="' .
                    $this->pricingHelper->currencyByStore($_value->getPrice(true), $store, false) .
                    '" />' .
                    '<label class="label admin__field-label" for="options_' .
                    $_option->getId() .
                    '_' .
                    $count .
                    '"><span>' .
                    $_value->getTitle() .
                    '</span> ' .
                    $priceStr .
                    '</label>';
                $selectHtml .= '</div>';
            }
            $selectHtml .= '</div>';

            return $selectHtml;
        }
    }
}
