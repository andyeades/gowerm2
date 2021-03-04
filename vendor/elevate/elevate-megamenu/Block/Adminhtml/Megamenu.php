<?php

namespace Elevate\Megamenu\Block\Adminhtml;
class Megamenu extends \Magento\Backend\Block\Widget\Grid\Container {
    protected function _construct() {
        $this->_controller = 'adminhtml_megamenu';
        $this->_blockGroup = 'Elevate_Megamenu';
        $this->_headerText = __('Manage Elevate Megamenu Items');
        parent::_construct();

        if ($this->_isAllowedAction('Elevate_Megamenu::save')) {
            $this->buttonList->update('add', 'label', __('Add New Megamenu Item'));
        } else {
            $this->buttonList->remove('add');
        }

    }

    protected function _isAllowedAction($resourceId) {
        return $this->_authorization->isAllowed($resourceId);
    }
}