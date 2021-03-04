<?php
namespace Elevate\ProductIcons\Block\Adminhtml;
class Producticons extends \Magento\Backend\Block\Widget\Grid\Container {
  protected function _construct() {
    $this->_controller = 'adminhtml_producticons';
    $this->_blockGroup = 'Elevate_Producticons';
    $this->_headerText = __('Manage Elevate Product Icon Items');
    parent::_construct();

    if ($this->_isAllowedAction('Elevate_ProductIcons::save')) {
      $this->buttonList->update('add', 'label', __('Add New Product Icon Item'));
    } else {
      $this->buttonList->remove('add');
    }

  }

  protected function _isAllowedAction($resourceId) {
    return $this->_authorization->isAllowed($resourceId);
  }
}