<?php

namespace Elevate\PrintLabels\Controller\Adminhtml\Index;


class Index extends \Magento\Backend\App\Action
{
    public function execute()
    {
        $this->_view->loadLayout();
        $this->_view->getLayout()->initMessages();
        $this->_view->renderLayout();
    }

    /**
     * Check if admin has permissions to visit slider pages.
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Elevate_PrintLabels::index');
    }
}

?>