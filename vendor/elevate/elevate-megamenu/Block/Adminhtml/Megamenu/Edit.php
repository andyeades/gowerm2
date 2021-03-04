<?php
namespace Elevate\Megamenu\Block\Adminhtml\Megamenu;

class Edit extends \Magento\Backend\Block\Widget\Form\Container
{
  /**
   * Core registry
   *
   * @var \Magento\Framework\Registry
   */
  protected $_coreRegistry = null;

  /**
   * @param \Magento\Backend\Block\Widget\Context $context
   * @param \Magento\Framework\Registry $registry
   * @param array $data
   */
  public function __construct(
    \Magento\Backend\Block\Widget\Context $context,
    \Magento\Framework\Registry $registry,
    array $data = []
  ) {
    $this->_coreRegistry = $registry;
    parent::__construct($context, $data);
  }

  /**
   * Initialize blog post edit block
   *
   * @return void
   */
  protected function _construct()
  {
    $this->_objectId = 'entity_id'; // Primary Key?
    $this->_blockGroup = 'Elevate_Megamenu';
    $this->_controller = 'adminhtml_megamenu';

    parent::_construct();

    if ($this->_isAllowedAction('Elevate_Megamenu::save')) {
      $this->buttonList->update('save', 'label', __('Save Megamenu Item'));
      $this->buttonList->add(
        'saveandcontinue',
        [
          'label' => __('Save and Continue Edit'),
          'class' => 'save',
          'data_attribute' => [
            'mage-init' => [
              'button' => ['event' => 'saveAndContinueEdit', 'target' => '#edit_form'],
            ],
          ]
        ],
        -100
      );
    } else {
      $this->buttonList->remove('save');
    }

    if ($this->_isAllowedAction('Elevate_Megamenu::delete')) {
      $this->buttonList->update('delete', 'label', __('Delete Megamenu Item'));
    } else {
      $this->buttonList->remove('delete');
    }
  }

  /**
   * Retrieve text for header element depending on loaded post
   *
   * @return \Magento\Framework\Phrase
   */
  public function getHeaderText()
  {
    if ($this->_coreRegistry->registry('megamenu')->getId()) {
      return __("Edit Megamenu Item '%1'", $this->escapeHtml($this->_coreRegistry->registry('megamenu')->getTitle()));
    } else {
      return __('New Megamenu Item');
    }
  }

  /**
   * Check permission for passed action
   *
   * @param string $resourceId
   * @return bool
   */
  protected function _isAllowedAction($resourceId)
  {
    return $this->_authorization->isAllowed($resourceId);
  }

  /**
   * Getter of url for "Save and Continue" button
   * tab_id will be replaced by desired by JS later
   *
   * @return string
   */
  protected function _getSaveAndContinueUrl()
  {
    return $this->getUrl('elevate_megamenu/*/save', ['_current' => true, 'back' => 'edit', 'active_tab' => '']);
  }
}