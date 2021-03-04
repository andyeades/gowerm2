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


namespace Mirasvit\Kb\Controller\Adminhtml\Articlesections;

use Magento\Framework\Controller\ResultFactory;

class MassDelete extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Backend\App\Action\Context
     */
    private $context;
    /**
     * @var \Mirasvit\Kb\Model\ResourceModel\Articlesections\CollectionFactory
     */
    private $collectionFactory;
    /**
     * @var \Magento\Ui\Component\MassAction\Filter
     */
    private $filter;

    /**
     * MassDelete constructor.
     * @param \Mirasvit\Kb\Model\ResourceModel\Articlesections\CollectionFactory $collectionFactory
     * @param \Magento\Ui\Component\MassAction\Filter $filter
     * @param \Magento\Backend\App\Action\Context $context
     */
    public function __construct(
        \Mirasvit\Kb\Model\ResourceModel\Articlesections\CollectionFactory $collectionFactory,
        \Magento\Ui\Component\MassAction\Filter $filter,
        \Magento\Backend\App\Action\Context $context
    ) {
        $this->filter            = $filter;
        $this->context           = $context;
        $this->collectionFactory = $collectionFactory;

        parent::__construct($context);
    }

    /**
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

        if (!$this->getRequest()->getParams()) {
            return $resultRedirect->setPath('*/*/');
        }

        $collection = $this->filter->getCollection($this->collectionFactory->create());
        $collectionSize = $collection->getSize();

        foreach ($collection as $object) {
            $object->delete();
        }

        $this->messageManager->addSuccessMessage(__('A total of %1 record(s) have been deleted.', $collectionSize));


        return $resultRedirect->setPath('*/*/');
    }

    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->context->getAuthorization()->isAllowed('Mirasvit_Kb:kb_articlesection');
    }
}
