<?php
/**
 * Mirasvit
 *
 * This source file is subject to the Mirasvit Software License, which is available at https://mirasvit.com/license/.
 * Do not edit or add to this file if you wish to upgrade the to newer versions in the future.
 * If you wish to customize this module for your needs.
 * Please refer to http://www.magentocommerce.com for more information.
 *
 * @category  Mirasvit
 * @package   mirasvit/module-kb
 * @version   1.0.69
 * @copyright Copyright (C) 2020 Mirasvit (https://mirasvit.com/)
 */



namespace Mirasvit\Kb\Controller\Adminhtml\Articlesubsections;

use Magento\Framework\Controller\ResultFactory;

class Delete extends \Mirasvit\Kb\Controller\Adminhtml\Articlesubsections
{

    /**
     *
     */
    public function execute()
    {
        if ($this->getRequest()->getParam('id') > 0) {
            try {
                $model = $this->articlesubsectionFactory->create();

                $model->setId($this->getRequest()
                    ->getParam('id'))
                    ->delete();

                $this->messageManager->addSuccess(
                    __('Article was successfully deleted')
                );
                $this->_redirect('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                $this->_redirect('*/*/edit', ['id' => $this->getRequest()
                    ->getParam('id'), ]);
            }
        }
        $this->_redirect('*/*/');
    }
}
