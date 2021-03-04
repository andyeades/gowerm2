<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Elevate\ProductIcons\Controller\Adminhtml;

use Elevate\ProductIcons\Api\ProducticonsRepositoryInterface;

abstract class Index extends \Magento\Backend\App\Action
{
  /**
   * Core registry
   *
   * @var \Magento\Framework\Registry
   */
  protected $coreRegistry = null;

  /**
   * @var \Magento\Framework\View\Result\PageFactory
   */
  protected $resultPageFactory;

  /**
   * @var \Elevate\ProductIcons\Model\ProducticonsRepository
   */
  protected $producticonsRepository;

  /**
   * @var \Elevate\ProductIcons\Api\ProducticonsRepositoryInterface
   */
  protected $producticonsRepositoryInterface;

  /**
   * Constructor
   *
   * @param \Magento\Backend\App\Action\Context $context
   * @param \Magento\Framework\Registry $coreRegistry
   * @param \Elevate\ProductIcons\Model\ProducticonsRepository $producticonsRepository
   * @param \Elevate\ProductIcons\Api\ProducticonsRepositoryInterface $producticonsRepositoryInterface
   * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory

   * @SuppressWarnings(PHPMD.ExcessiveParameterList)
   */
  public function __construct(
    \Magento\Backend\App\Action\Context $context,
    \Magento\Framework\Registry $coreRegistry,
    \Elevate\ProductIcons\Model\ProducticonsRepository $producticonsRepository,
    \Elevate\ProductIcons\Api\ProducticonsRepositoryInterface $producticonsRepositoryInterface,
    \Magento\Framework\View\Result\PageFactory $resultPageFactory
  ) {
    $this->coreRegistry = $coreRegistry;
    $this->producticonsRepository = $producticonsRepository;
    $this->producticonsRepositoryInterface = $producticonsRepositoryInterface;
    $this->resultPageFactory = $resultPageFactory;
    parent::__construct($context);
  }
}