<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Elevate\Themeoptions\Block\CheckoutAgreements\Adminhtml\Agreement\Edit;

class Form extends \Magento\CheckoutAgreements\Block\Adminhtml\Agreement\Edit\Form
{
    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;

    /**
     * @var \Magento\CheckoutAgreements\Model\AgreementModeOptions
     */
    protected $agreementModeOptions;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Store\Model\System\Store $systemStore
     * @param \Magento\CheckoutAgreements\Model\AgreementModeOptions $agreementModeOptions
     * @param array $data
     * @codeCoverageIgnore
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Store\Model\System\Store $systemStore,
        \Magento\CheckoutAgreements\Model\AgreementModeOptions $agreementModeOptions,
        array $data = []
    ) {
        $this->_systemStore = $systemStore;
        $this->agreementModeOptions = $agreementModeOptions;
        parent::__construct($context, $registry, $formFactory, $systemStore, $agreementModeOptions, $data);
    }

    /**
     * @return $this
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareForm()
    {
        /** @var $model \Elevate\Themeoptions\Model\CheckoutAgreements\Agreement
         */
        $model = $this->_coreRegistry->registry('checkout_agreement');
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create(
            ['data' => ['id' => 'edit_form', 'action' => $this->getData('action'), 'method' => 'post']]
        );
        $seperatelinkText = $model->getSeperateLinktext();

        $fieldset = $form->addFieldset(
            'base_fieldset',
            ['legend' => __('Terms and Conditions Information'), 'class' => 'fieldset-wide']
        );

        if ($model->getId()) {
            $fieldset->addField('agreement_id', 'hidden', ['name' => 'agreement_id']);
        }
        $fieldset->addField(
            'name',
            'text',
            [
                'name' => 'name',
                'label' => __('Condition Name'),
                'title' => __('Condition Name'),
                'required' => true
            ]
        );

        $fieldset->addField(
            'is_active',
            'select',
            [
                'label' => __('Status'),
                'title' => __('Status'),
                'name' => 'is_active',
                'required' => true,
                'options' => ['1' => __('Enabled'), '0' => __('Disabled')]
            ]
        );

        $fieldset->addField(
            'is_html',
            'select',
            [
                'label' => __('Show Content as'),
                'title' => __('Show Content as'),
                'name' => 'is_html',
                'required' => true,
                'options' => [0 => __('Text'), 1 => __('HTML')]
            ]
        );

        $fieldset->addField(
            'mode',
            'select',
            [
                'label' => __('Applied'),
                'title' => __('Applied'),
                'name' => 'mode',
                'required' => true,
                'options' => $this->agreementModeOptions->getOptionsArray()
            ]
        );

        if (!$this->_storeManager->isSingleStoreMode()) {
            $field = $fieldset->addField(
                'stores',
                'multiselect',
                [
                    'name' => 'stores[]',
                    'label' => __('Store View'),
                    'title' => __('Store View'),
                    'required' => true,
                    'values' => $this->_systemStore->getStoreValuesForForm(false, true)
                ]
            );
            $renderer = $this->getLayout()->createBlock(
                \Magento\Backend\Block\Store\Switcher\Form\Renderer\Fieldset\Element::class
            );
            $field->setRenderer($renderer);
        } else {
            $fieldset->addField(
                'stores',
                'hidden',
                ['name' => 'stores[]', 'value' => $this->_storeManager->getStore(true)->getId()]
            );
            $model->setStoreId($this->_storeManager->getStore(true)->getId());
        }

        $fieldset->addField(
            'checkbox_text',
            'editor',
            [
                'name' => 'checkbox_text',
                'label' => __('Checkbox Text'),
                'title' => __('Checkbox Text'),
                'rows' => '5',
                'cols' => '30',
                'wysiwyg' => false,
                'required' => true
            ]
        );

        $fieldset->addField(
            'content',
            'editor',
            [
                'name' => 'content',
                'label' => __('Content'),
                'title' => __('Content'),
                'style' => 'height:24em;',
                'wysiwyg' => false,
                'required' => true
            ]
        );
        $fieldset->addField(
            'seperate_linktext',
            'checkbox',
            [
                'name' => 'seperate_linktext',
                'label' => __('Show Checkbox and Link Separately'),
                'onclick' => 'this.value = this.checked ? 1 : 0;',
                'checked' => isset($seperatelinkText) ? $seperatelinkText : 0
            ]
        );
        $fieldset->addField(
            'link_text',
            'editor',
            [
                'name' => 'link_text',
                'label' => __('Seperate Link Text'),
                'title' => __('Seperate Link Text'),
                'rows' => '5',
                'cols' => '30',
                'wysiwyg' => false,
                'required' => true
            ]
        );

        $fieldset->addField(
            'content_height',
            'text',
            [
                'name' => 'content_height',
                'label' => __('Content Height (css)'),
                'title' => __('Content Height'),
                'maxlength' => 25,
                'class' => 'validate-css-length'
            ]
        );

        $form->setValues($model->getData());
        $form->setUseContainer(true);
        $this->setForm($form);

        return $this;
    }
}
