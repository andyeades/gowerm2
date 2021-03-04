<?php

namespace Elevate\PrintLabels\Block\Adminhtml\Index;

use Elevate\PrintLabels\Controller\Adminhtml\Edit\DPDAuthorisation;
use Elevate\PrintLabels\Helper\Data;
use Magento\Framework\Message\ManagerInterface;


class Index extends \Magento\Backend\Block\Widget\Container
{

    /**
     * @var ManagerInterface
     */
    private $messageManager;

    protected $_backendUrl;

    protected $_orderCollectionFactory;

    protected $orderRepository;

    protected $addressRepository;

    protected $searchCriteriaBuilder;

    protected $orderAddressRepository;

    protected $orderAddressRepo;

    protected $orderModel;

    protected $dpdAuthorisation;

    protected $orderClass;

    /**
     * @var \Elevate\PrintLabels\Helper\Data $helper,
     */
    protected $helper;

    /**
     * @var \Elevate\PrintLabels\Helper\OrderData $orderHelper,
     */
    protected $orderHelper;

    /**
     * Index constructor.
     *
     * @param \Magento\Backend\Block\Widget\Context                      $context
     * @param \Magento\Backend\Model\UrlInterface                        $backendUrl
     * @param \Elevate\PrintLabels\Helper\Data                           $helper
     * @param ManagerInterface                                           $messageManager
     * @param \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory
     * @param \Magento\Sales\Api\OrderRepositoryInterface                $orderRepository
     * @param \Magento\Framework\Api\SearchCriteriaBuilder               $searchCriteriaBuilder
     * @param \Magento\Customer\Api\AddressRepositoryInterface           $addressRepository
     * @param \Magento\Sales\Model\Order\AddressRepository               $orderAddressRepository
     * @param \Magento\Sales\Api\OrderAddressRepositoryInterface         $orderAddressRepo
     * @param \Magento\Sales\Model\Order                                 $orderModel
     * @param \Elevate\PrintLabels\Controller\Adminhtml\Edit\DPDAuthorisation  $dpdAuthorisation
     * @param \Elevate\PrintLabels\Controller\Adminhtml\Orders\GetOrders $orderClass
     * @param array                                                      $data
     * @param \Elevate\PrintLabels\Helper\OrderData $orderHelper
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Backend\Model\UrlInterface $backendUrl, \Elevate\PrintLabels\Helper\Data $helper,

        ManagerInterface $messageManager,
        \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory,
        \Magento\Sales\Api\OrderRepositoryInterface $orderRepository,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
        \Magento\Customer\Api\AddressRepositoryInterface $addressRepository,
        \Magento\Sales\Model\Order\AddressRepository $orderAddressRepository,
        \Magento\Sales\Api\OrderAddressRepositoryInterface $orderAddressRepo,
        \Magento\Sales\Model\Order $orderModel,
        \Elevate\PrintLabels\Controller\Adminhtml\Edit\DPDAuthorisation  $dpdAuthorisation,
        \Elevate\PrintLabels\Controller\Adminhtml\Orders\GetOrders $orderClass,
        \Elevate\PrintLabels\Helper\OrderData $orderHelper,
        array $data = [])
    {
        parent::__construct($context, $data);
        $this->messageManager = $messageManager;
        $this->_backendUrl = $backendUrl;
        $this->helper = $helper;
        $this->_orderCollectionFactory = $orderCollectionFactory;
        $this->orderRepository = $orderRepository;
        $this->addressRepository = $addressRepository;
        $this->orderAddressRepository = $orderAddressRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->orderModel = $orderModel;
        $this->orderAddressRepo = $orderAddressRepo;
        $this->dpdAuthorisation = $dpdAuthorisation;
        $this->orderClass = $orderClass;
        $this->orderHelper = $orderHelper;
    }

    public function getOrders() {
       return $this->orderHelper->getOrders();
    }

    public function getPostUrl()
    {
        $params = $this->getRequest()->getParams();

        $params = array('name' => '');
        //$params = array('some'=>$params);

        $url = $this->_backendUrl->getUrl("*/*/shiporder");

        return $url;
    }

    public function shipOrder($order_id) {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        // Load the order increment ID
        $order = $objectManager->create('Magento\Sales\Model\Order')->loadByIncrementID($order_id);


        // Check if order can be shipped or has already shipped
        if (! $order->canShip()) {
            $this->messageManager->addSuccessMessage("Can't Ship Order %1",$order_id);

            /*
             * throw new \Magento\Framework\Exception\LocalizedException(
                __('You can\'t create a shipment.')
            );
            */
        }

        // Initialize the order shipment object
        $convertOrder = $objectManager->create('Magento\Sales\Model\Convert\Order');
        $shipment = $convertOrder->toShipment($order);

        // Loop through order items
        foreach ($order->getAllItems() AS $orderItem) {
            // Check if order item has qty to ship or is virtual
            if (! $orderItem->getQtyToShip() || $orderItem->getIsVirtual()) {
                continue;
            }

            $qtyShipped = $orderItem->getQtyToShip();

            // Create shipment item with qty
            $shipmentItem = $convertOrder->itemToShipmentItem($orderItem)->setQty($qtyShipped);

            // Add shipment item to shipment
            $shipment->addItem($shipmentItem);
        }

        // Register shipment
        $shipment->register();

        $shipment->getOrder()->setIsInProcess(true);

        try {
            // Save created shipment and order
            $shipment->save();
            $this->messageManager->addSuccessMessage(__('Shipped Order ID: %1', $order_id));

            $shipment->getOrder()->save();

            // Send email
            //$objectManager->create('Magento\Shipping\Model\ShipmentNotifier')->notify($shipment);


            $shipment->save();

        } catch (\Exception $e) {
            throw new \Magento\Framework\Exception\LocalizedException(
                __($e->getMessage())
            );
        }
    }
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Elevate_PrintLabels::index');
    }
    protected function _isAllowedAction($resourceId) {
        return $this->_authorization->isAllowed($resourceId);
    }
}

