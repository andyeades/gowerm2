<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Elevate\Megamenu\Controller\Adminhtml;

use Elevate\Megamenu\Api\MegamenuRepositoryInterface;

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
   * @var \Elevate\Megamenu\Model\MegamenuRepository
   */
  protected $megamenuRepository;

  /**
   * @var \Elevate\Megamenu\Api\MegamenuRepositoryInterface
   */
  protected $megamenuRepositoryInterface;

  /**
   * Constructor
   *
   * @param \Magento\Backend\App\Action\Context $context
   * @param \Magento\Framework\Registry $coreRegistry
   * @param \Elevate\Megamenu\Model\MegamenuRepository $megamenuRepository
   * @param \Elevate\Megamenu\Api\MegamenuRepositoryInterface $megamenuRepositoryInterface
   * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory

   * @SuppressWarnings(PHPMD.ExcessiveParameterList)
   */
  public function __construct(
    \Magento\Backend\App\Action\Context $context,
    \Magento\Framework\Registry $coreRegistry,
    \Elevate\Megamenu\Model\MegamenuRepository $megamenuRepository,
    \Elevate\Megamenu\Api\MegamenuRepositoryInterface $megamenuRepositoryInterface,
    \Magento\Framework\View\Result\PageFactory $resultPageFactory
  ) {
    $this->coreRegistry = $coreRegistry;
    $this->megamenuRepository = $megamenuRepository;
    $this->megamenuRepositoryInterface = $megamenuRepositoryInterface;
    $this->resultPageFactory = $resultPageFactory;
    parent::__construct($context);
  }
}