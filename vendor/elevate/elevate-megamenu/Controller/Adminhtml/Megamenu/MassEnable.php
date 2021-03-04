<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Elevate\Megamenu\Controller\Adminhtml\Megamenu;

use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;
use Elevate\Megamenu\Model\ResourceModel\Megamenu\CollectionFactory;
use Magento\Framework\Controller\ResultFactory;

/**
 * Class MassDisable
 */
class MassEnable extends \Elevate\Megamenu\Controller\Adminhtml\Index
{
    /**
     * @var Filter
     */
    protected $filter;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

  /**
   * @param \Magento\Backend\App\Action\Context $context
   * @param \Magento\Framework\Registry $coreRegistry
   * @param \Elevate\Megamenu\Model\MegamenuRepository $megamenuRepository
   * @param \Elevate\Megamenu\Api\MegamenuRepositoryInterface $megamenuRepositoryInterface
   * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
   * @param Filter $filter
   * @param CollectionFactory $collectionFactory
   *
   * @codeCoverageIgnore
   */
  public function __construct(
    \Magento\Backend\App\Action\Context $context,
    \Magento\Framework\Registry $coreRegistry,
    \Elevate\Megamenu\Model\MegamenuRepository $megamenuRepository,
    \Elevate\Megamenu\Api\MegamenuRepositoryInterface $megamenuRepositoryInterface,
    \Magento\Framework\View\Result\PageFactory $resultPageFactory,
    Filter $filter,
    CollectionFactory $collectionFactory
  ) {
    $this->filter = $filter;
    $this->collectionFactory = $collectionFactory;
    parent::__construct($context,
      $coreRegistry,
      $megamenuRepository,
      $megamenuRepositoryInterface,
      $resultPageFactory);
  }

    /**
     * Execute action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     * @throws \Magento\Framework\Exception\LocalizedException|\Exception
     */
    public function execute()
    {
        $collection = $this->filter->getCollection($this->collectionFactory->create());

        foreach ($collection as $item) {
            $item->setEnabled(1);
            $item->save();
        }

        $this->messageManager->addSuccessMessage(__('A total of %1 menu item(s) have been enabled.', $collection->getSize()));

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('elevate_megamenu/index/managemegamenu');
    }
}
