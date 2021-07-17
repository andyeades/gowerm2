<?php

namespace Elevate\PrintLabels\Controller\Adminhtml\Order;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Sales\Controller\Adminhtml\Order;

/**
 * Class Info
 *
 * @category Elevate
 * @package
 * @author   Richard Jones <richard.jones@elevateweb.co.uk>
 */
class Viewer extends \Magento\Backend\App\Action {

    protected $orderController;

    protected $orderRepository;

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry = null;

    /**
     * Index constructor.
     *
     * @param Context                                             $context,
     * @param \Magento\Sales\Controller\Adminhtml\Order           $orderController
     * @param \Magento\Sales\Api\OrderRepositoryInterface         $orderRepository
     * @param \Magento\Framework\Registry $coreRegistry
     *
     */
    public function __construct(
        Context $context,
        \Magento\Sales\Controller\Adminhtml\Order           $orderController,
        \Magento\Sales\Api\OrderRepositoryInterface         $orderRepository,
        \Magento\Framework\Registry $coreRegistry
    ) {
        parent::__construct(
            $context,
            $this->orderController = $orderController,
            $this->orderRepository = $orderRepository,
            $this->coreRegistry = $coreRegistry
        );
    }

    /**
     * Index action
     *
     */
    public function execute() {

        $params = $this->getRequest()->getParams();

        $order_id = $params['order_id'];

        $order = $this->orderRepository->get($order_id);


        $this->coreRegistry->register('sales_order', $order);



        $this->_view->loadLayout();
        $this->_view->getLayout()->initMessages();
        $this->_view->renderLayout();
    }

    /**
     * Is the user allowed to view the blog post grid.
     *
     * @return bool
     */
    protected function _isAllowed() {
        return true;

        return $this->_authorization->isAllowed('Elevate_PrintLabels::elevate_printlabels');
    }
}
