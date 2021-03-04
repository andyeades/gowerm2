<?php
namespace Elevate\Megamenu\Block\Adminhtml\Megamenu\Edit;

/**
 * Adminhtml blog post edit form
 */
class Form extends \Magento\Backend\Block\Widget\Form\Generic
{

  /**
   * @var \Magento\Store\Model\System\Store
   */
  protected $_systemStore;

  /**
   * @param \Magento\Backend\Block\Template\Context $context
   * @param \Magento\Framework\Registry $registry
   * @param \Magento\Framework\Data\FormFactory $formFactory
   * @param \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig
   * @param \Magento\Store\Model\System\Store $systemStore
   * @param array $data
   */
  public function __construct(
    \Magento\Backend\Block\Template\Context $context,
    \Magento\Framework\Registry $registry,
    \Magento\Framework\Data\FormFactory $formFactory,
    \Magento\Store\Model\System\Store $systemStore,
    array $data = []
  ) {
    $this->_systemStore = $systemStore;
    parent::__construct($context, $registry, $formFactory, $data);
  }

  /**
   * Init form
   *
   * @return void
   */
  protected function _construct()
  {
    parent::_construct();
    $this->setId('megamenu_form');
    $this->setTitle(__('Megamenu Item Information'));
  }

  /**
   * Prepare form
   *
   * @return $this
   */
  protected function _prepareForm()
  {
    /** @var \Elevate\Megamenu\Model\Megamenu $model */
    $model = $this->_coreRegistry->registry('megamenu');

    /** @var \Magento\Framework\Data\Form $form */
    $form = $this->_formFactory->create(
      ['data' => ['id' => 'edit_form', 'action' => $this->getData('action'), 'method' => 'post']]
    );

    $form->setHtmlIdPrefix('megamenu_');

    $fieldset = $form->addFieldset(
      'base_fieldset',
      ['legend' => __('Megamenu Item Details'), 'class' => 'fieldset-wide']
    );

    if ($model->getId()) {
      $fieldset->addField('entity_id', 'hidden', ['name' => 'entity_id']);
    }

    $fieldset->addField(
      'menu_name',
      'text',
      ['name' => 'menu_name', 'label' => __('Menu Name'), 'title' => __('Menu Name'), 'required' => true]
    );

    $fieldset->addField(
      'menu_link',
      'text',
      [
        'name' => 'menu_link',
        'label' => __('Menu Link'),
        'title' => __('Menu Link'),
        'required' => true,
      ]
    );

    $fieldset->addField(
      'menu_icon',
      'text',
      [
        'name' => 'menu_icon',
        'label' => __('Menu Icon'),
        'title' => __('Menu Icon'),
        'required' => false,
      ]
    );

    $fieldset->addField(
      'enabled',
      'checkbox',
      [
        'label' => __('Enabled'),
        'name' => 'enabled',
        'data-form-part' => $this->getData('megamenu_form'),
        'checked' => true,
        'value' => 1,
        'onchange' => 'this.value = this.checked ? 1 : 0;',
      ]
    );

    $fieldset->addField(
      'menu_type',
      'text',
      [
        'name' => 'menu_type',
        'label' => __('Menu Type'),
        'title' => __('Menu Type'),
        'maxlength' => 1,
        'class' => 'validate-digits',
        'required' => true,
      ]
    );

    $fieldset->addField(
      'position',
      'text',
      [
        'name' => 'position',
        'label' => __('Position'),
        'title' => __('Position'),
        'maxlength' => 4,
        'class' => 'validate-digits',
        'default' => '0',
        'required' => true,
      ]
    );
    $fieldset->addField(
      'show_type',
      'text',
      [
        'name' => 'show_type',
        'label' => __('Show Type'),
        'title' => __('Show Type'),
        'maxlength' => 1,
        'class' => 'validate-digits',
        'required' => true,
      ]
    );

    $dateFormat = $this->_localeDate->getDateFormat(\IntlDateFormatter::SHORT);
    $fieldset->addField(
      'start_date',
      'date',
      [
        'label' => __('Start Date'),
        'title' => __('Start Date'),
        'name' => 'start_date',
        'date_format' => $dateFormat
        //'required' => true
      ]
    );

    $dateFormat = $this->_localeDate->getDateFormat(\IntlDateFormatter::SHORT);
    $fieldset->addField(
      'end_date',
      'date',
      [
        'label' => __('End Date'),
        'title' => __('End Date'),
        'name' => 'end_date',
        'date_format' => $dateFormat
        //'required' => true
      ]
    );

    /*
    if (!$model->getId()) {
      $model->setData('is_active', '1');
    }
    */

    $fieldset->addField(
      'menu_content',
      'editor',
      [
        'name' => 'menu_content',
        'label' => __('Content'),
        'title' => __('Content'),
        'style' => 'height:36em',
        'required' => true
      ]
    );

    $form->setValues($model->getData());
    $form->setUseContainer(true);
    $this->setForm($form);

    return parent::_prepareForm();
  }
}