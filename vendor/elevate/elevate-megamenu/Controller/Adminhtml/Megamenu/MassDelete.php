<?php

namespace Elevate\Megamenu\Controller\Adminhtml\Megamenu;

use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;
use Elevate\Megamenu\Model\ResourceModel\Megamenu\CollectionFactory;
use Magento\Framework\Controller\ResultFactory;

/**
 * Class MassDisable
 */
class MassDelete extends \Elevate\Megamenu\Controller\Adminhtml\Index
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
   * @return \Magento\Backend\Model\View\Result\Redirect
   * @throws \Magento\Framework\Exception\LocalizedException
   */
  public function execute()
  {
    $collection = $this->filter->getCollection($this->collectionFactory->create());
    $itemDeleted = 0;
    /** @var \Elevate\Megamenu\Model\Megamenu $item */
    foreach ($collection->getItems() as $item) {
      $this->megamenuRepositoryInterface->delete($item);
      $itemDeleted++;
    }
    $this->messageManager->addSuccessMessage(__('A total of %1 record(s) have been deleted.', $itemDeleted));

    return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setPath('elevate_megamenu/index/managemegamenu');
  }
}