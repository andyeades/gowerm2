<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2019 Aitoc (https://www.aitoc.com)
 * @package Aitoc_OptionsManagement
 */

/**
 * Copyright © 2018 Aitoc. All rights reserved.
 */

namespace Aitoc\OptionsManagement\Controller\Adminhtml\Template;

use Magento\Backend\App\Action\Context;
use Aitoc\OptionsManagement\Model\ResourceModel\Template\CollectionFactory;
use Magento\Eav\Model\Entity\Collection\AbstractCollection;
use Magento\Ui\Component\MassAction\Filter;
use Aitoc\OptionsManagement\Api\TemplateRepositoryInterface;
use Magento\Framework\Controller\ResultFactory;

/**
 * Class MassDelete
 */
class MassDelete extends \Magento\Backend\App\Action
{

    const ADMIN_RESOURCE = 'Aitoc_OptionsManagement::templates';

    /**
     * @var Filter
     */
    protected $filter;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var TemplateRepositoryInterface
     */
    protected $templateRepository;

    /**
     * @param Context $context
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     * @param TemplateRepositoryInterface $templateRepository
     */
    public function __construct(
        Context $context,
        Filter $filter,
        CollectionFactory $collectionFactory,
        TemplateRepositoryInterface $templateRepository
    ) {
        parent::__construct($context);
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        $this->templateRepository = $templateRepository;
    }


    /**
     * Delete action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        $collectionSize = $collection->getSize();
        $templatesDeleted = 0;
        foreach ($collection as $template) {
            $this->templateRepository->delete($template);
            $templatesDeleted++;
        }

        $this->messageManager->addSuccess(__('A total of %1 record(s) were deleted.', $templatesDeleted));

        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

        return $resultRedirect->setPath('*/*/');
    }

    /**
     * @param AbstractCollection $collection
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    protected function XmassAction(AbstractCollection $collection)
    {
        $templatesDeleted = 0;
        foreach ($collection->getAllIds() as $templateId) {
            $this->templateRepository->deleteById($templateId);
            $templatesDeleted++;
        }

        if ($templatesDeleted) {
            $this->messageManager->addSuccess(__('A total of %1 record(s) were deleted.', $templatesDeleted));
        }
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setPath($this->getComponentRefererUrl());

        return $resultRedirect;
    }
}
