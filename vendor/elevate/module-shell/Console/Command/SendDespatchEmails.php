<?php

namespace Elevate\Shell\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Magento\Payment\Helper\Data as PaymentHelper;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Email\Container\ShipmentIdentity;
use Magento\Sales\Model\Order\Email\Container\Template;
use Magento\Sales\Model\Order\Email\Sender;
use Magento\Sales\Model\Order\Shipment;

use Magento\Sales\Model\Order\Address\Renderer;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\DataObject;

use Magento\Sales\Model\Order\Email\Container\IdentityInterface;


class SendDespatchEmails extends Command
{

    /**
     * @var PaymentHelper
     */
    protected $paymentHelper;

    /**
     * @var ShipmentResource
     */
    protected $shipmentResource;

    /**
     * Global configuration storage.
     *
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $globalConfig;

    /**
     * @var Renderer
     */
    protected $addressRenderer;

    /**
     * Application Event Dispatcher
     *
     * @var ManagerInterface
     */
    protected $eventManager;
    /**
     * @var \Magento\Sales\Model\Order\Email\SenderBuilderFactory
     */
    protected $senderBuilderFactory;

    /**
     * @var Template
     */
    protected $templateContainer;

    /**
     * @var IdentityInterface
     */
    protected $identityContainer;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;
    private $objectManager;
    protected $log_file = 'sale_flag.log';
    protected $pageSize = 10;
    protected $fileDelimiter = ',';
    protected $dryrun = false;

    /* Debug Info */
    protected $limitCollectionSku = '';
    // protected $limitCollectionSku = 'AMERICAN_WHITE_FINISH_SOLID_PI.854';



    protected $dateFromOverride = '2019-12-03 00:00:00';
    protected $dateToOverride = '2020-05-11 23:59:59';

    //protected $dateToOverride = '2019-12-03 23:59:59';
    protected $genRun = false;
    protected $salesRun = true;

    /*end debug info*/

    protected $resourceConnection;
    protected $read;
    protected $write;
    protected $table;
    protected $fileHandle;
    protected $boostArray = [];
    protected $_orderCollectionFactory;
   protected $_shipmentCollectionFactory;
    protected $orderRepository;

    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectmanager,
        \Magento\Catalog\Api\ProductRepositoryInterface $productrepository,
        \Magento\Framework\App\State $state,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\App\ResourceConnection $resourceConnection,
        \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory,
        // \Magento\Shipping\Model\Order\Shipment\CollectionFactory $shipmentCollectionFactory,
        \Magento\Sales\Model\OrderRepository $orderRepository,
        Template $templateContainer,
        ShipmentIdentity $identityContainer,
        \Magento\Sales\Model\Order\Email\SenderBuilderFactory $senderBuilderFactory,
        Renderer $addressRenderer,
        PaymentHelper $paymentHelper,
        \Magento\Sales\Model\ResourceModel\Order\Shipment $shipmentResource
        //\Magento\Framework\App\Config\ScopeConfigInterface $globalConfig,
       // ManagerInterface $eventManager
    )            
    {
        $this->objectManager = $objectmanager;
        $this->productRepository = $productrepository;
        $this->state = $state;
        $this->logger = $logger;
        $this->resourceConnection = $resourceConnection;
        $this->_orderCollectionFactory = $orderCollectionFactory;
        $this->orderRepository = $orderRepository;

       $this->templateContainer = $templateContainer;
        $this->identityContainer = $identityContainer;
        $this->senderBuilderFactory = $senderBuilderFactory;
        $this->addressRenderer = $addressRenderer;
        $this->paymentHelper = $paymentHelper;
        $this->shipmentResource = $shipmentResource;
//        $this->globalConfig = $globalConfig;
 //       $this->addressRenderer = $addressRenderer;
   //     $this->eventManager = $eventManager;
      //  $this->_shipmentCollectionFactory = $shipmentCollectionFactory;

        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('elevate:shell:send_despatch_emails')->setDescription('run despatch Emails');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
    
  
        $objectManager = $this->objectManager;

        $state = $objectManager->get('Magento\Framework\App\State');
        $state->setAreaCode('frontend');

        $shipmentCollection = $objectManager->create('Magento\Sales\Model\Order\Shipment')->getCollection();


        //test one order
      //  $shipmentCollection->addAttributeToFilter('entity_id', '159974');


       // $productCollection->addAttributeToFilter('despatch_email_flag', $order->getId())

        //   $productCollection = $this->_shipmentCollectionFactory->create();
        $shipmentCollection->addAttributeToSelect('*');
        //$productCollection->setStoreId(0);
        $shipmentCollection->addAttributeToFilter('despatch_email_flag', array('neq' => '1'));
        $shipmentCollection->load();


        $shipmentCollection->setOrder('entity_id', 'DESC');
        $shipmentCollection->setPageSize($this->pageSize);

        $shipmentCollection->setCurPage(1);  // first page (means limit 0,10)



        foreach($shipmentCollection as $shipment){

        



            $has_tracking = false;
            $trackingnumber = '';
            $comment = '';
            $cansend = false;

            $output = '';
            $sentdispatch = false;

            $order = $this->orderRepository->get($shipment['order_id']);

            $shipmentId = $shipment['entity_id'];


            // $shipmentnew = Mage::getModel('sales/order_shipment')->load($shipmentId);
            // $flag = $shipmentnew->getData('dispatch_email_send_flag');

            $commentscollection = $shipment->getComments();
            foreach($commentscollection as $comment){
                $comment = $comment->getComment();

                echo $comment;
            }



      //      $commentscollection = Mage::getModel('sales/order_shipment_comment')->getCollection()
                //chnage parent_id to use $orderid
        //                              ->addFieldToFilter('parent_id', $shipmentId);

            foreach($commentscollection as $comment){
                $comment = $comment->getComment();
            }

            //echo $comment;
            $comment = strtolower($comment);
            //get tracking number from order shipment track
          //  $trackingnumbercollection = Mage::getModel('sales/order_shipment_track')->getCollection()
                //chnage parent_id to use $orderid
         //                                   ->addFieldToFilter('parent_id', $shipmentId);

            $trackingnumbercollection = $shipment->getAllTracks();

            foreach($trackingnumbercollection as $trackingnumber){
                $check_tracking = $trackingnumber->getTrackNumber();
               $comment .= $trackingnumber->getTitle();
               
               
               
               
                if(!empty($check_tracking)){
                    $trackingnumber = $check_tracking;
                    $has_tracking = true;
                    break;
                }
            }

            if($has_tracking){
                if (strpos($comment, 'bjs') !== false) {
                    $cansend = false;
                }
                else{
                    $cansend = true;

                }
                $cansend = true;
            }
            else{

                if (strpos($comment, 'on time') !== false || strpos($comment, 'get it there') !== false) {

                    $cansend = true;
                }
                if (strpos($comment, 'yahyani') !== false) {
                    $cansend = true;
                }
            }
            // $cansend = true;
            echo  "\n#".$order->getIncrementId() . " SHIP = ".$shipmentId;


            /* dont process
            if (strpos($comment, 'xdp') !== false) {
                $cansend = false;
            }
            if (strpos($comment, 'dx') !== false) {
                $cansend = false;
            }
            if (strpos($comment, 'bjs') !== false) {
                $cansend = false;
            }
*/


            if($cansend){

                echo  " - Can Send" ;
            }
            else{
                echo " - No Send";

            }
            echo  " - C= ".$comment." - T= ".$trackingnumber;



            if(!$cansend){
                continue;

            }
            if(!$this->dryrun) {
                if($cansend){



                    $sentdispatch = $this->_flagDispatchEmails($order, $shipment);

                    if($sentdispatch){

                        $shipment->setData('despatch_email_flag', 1);


                        $shipment->save();

                        echo  " - Dispatch Email sent";

                    }
                    else{
                        echo  " - Dispatch Email ERROR";
                    }
                }

            }


      

          //  echo $output;


           // $shipment = $order->prepareShipment($this->_getItemQtys($order));

            try{
            
               // $shipment->sendEmail2(true, $order);
              
        }
        catch(Exception $e){

            print_r($e->getMessage());
            }
        }

    }



    protected function _getItemQtys($order){

        $qty = array();
        foreach ($order->getAllItems() as $_eachItem) {
            if ($_eachItem->getParentItemId()) {
                $qty[$_eachItem->getParentItemId()] = $_eachItem->getQtyOrdered();
            } else {
                $qty[$_eachItem->getId()] = $_eachItem->getQtyOrdered();
            }
        }
        return $qty;
    }


    protected function _flagDispatchEmails($order, $shipment)
    {



        try{


        //    $shipment = $order->prepareShipment($this->_getItemQtys($order));

            try{
                echo "TEST1";
                $objectManager = $this->objectManager;
              //  $objectManager->create('Magento\Shipping\Model\ShipmentNotifier')
//                              ->notify($shipment);
                $this->sendEmail($order, $shipment);
                echo "TEST2";

            }catch(Exception $e){
                print_r($e->getMessage());

            }

            echo 'Order Dispatched: #' . $order->getIncrementId();
           /// Mage::log('Order Dispatched: #' . $order->getIncrementId(), null, 'dispatch(sent).log');

            return true;
        }catch(Exception $e){
            print_r($e->getMessage());
            echo 'Order Not Dispatched: #' . $order->getIncrementId();
            Mage::log('Order Not Dispatched: #' . $order->getIncrementId(), null, 'dispatch(failed).log');
            return false;
        }
        return true;




    }

    public function sendEmail($order, $shipment){

        //$shipment->setSendEmail($this->identityContainer->isEnabled());



            //$this->identityContainer->setStore($order->getStore());

            $transport = [
                'order' => $order,
                'shipment' => $shipment,
                'comment' => $shipment->getCustomerNoteNotify() ? $shipment->getCustomerNote() : '',
                'billing' => $order->getBillingAddress(),
                'payment_html' => $this->getPaymentHtml($order),
                'store' => $order->getStore(),
                'formattedShippingAddress' => $this->getFormattedShippingAddress($order),
                'formattedBillingAddress' => $this->getFormattedBillingAddress($order),
                'order_data' => [
                    'customer_name' => $order->getCustomerName(),
                    'is_not_virtual' => $order->getIsNotVirtual(),
                    'email_customer_note' => $order->getEmailCustomerNote(),
                    'frontend_status_label' => $order->getFrontendStatusLabel()
                ]
            ];
            $transportObject = new DataObject($transport);

            /**
             * Event argument `transport` is @deprecated. Use `transportObject` instead.
             */
//            $this->eventManager->dispatch(
 //               'email_shipment_set_template_vars_before',
  //              ['sender' => $this, 'transport' => $transportObject->getData(), 'transportObject' => $transportObject]
   //         );

            $this->templateContainer->setTemplateVars($transportObject->getData());

            if ($this->checkAndSend($order)) {
                $shipment->setEmailSent(true);
                $this->shipmentResource->saveAttribute($shipment, ['send_email', 'email_sent']);
                return true;
            }


       // $this->shipmentResource->saveAttribute($shipment, 'send_email');

        return false;
    }
    protected function getTemplateOptions()
    {
        return [
            'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
            'store' => $this->identityContainer->getStore()->getStoreId()
        ];
    }
    /**
     * Send order email if it is enabled in configuration.
     *
     * @param Order $order
     * @return bool
     */
    protected function checkAndSend(Order $order)
    {

        $this->prepareTemplate($order);

        /** @var SenderBuilder $sender */
        $sender = $this->getSender();

        try {
            $sender->send();
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            return false;
        }
        if ($this->identityContainer->getCopyMethod() == 'copy') {
            try {
               // $sender->sendCopyTo();
            } catch (\Exception $e) {
                $this->logger->error($e->getMessage());
            }
        }
        return true;
    }

    /**
     * Populate order email template with customer information.
     *
     * @param Order $order
     * @return void
     */
    protected function prepareTemplate(Order $order)
    {
        $this->templateContainer->setTemplateOptions($this->getTemplateOptions());

        if ($order->getCustomerIsGuest()) {
            $templateId = $this->identityContainer->getGuestTemplateId();
            $customerName = $order->getBillingAddress()->getName();
        } else {
            $templateId = $this->identityContainer->getTemplateId();
            $customerName = $order->getCustomerName();
        }
   //     echo "**".$customerName."((".$order->getCustomerEmail()."))\n\n\n";
        

        $this->identityContainer->setCustomerName($customerName);
        $this->identityContainer->setCustomerEmail($order->getCustomerEmail());
        $this->templateContainer->setTemplateId($templateId);
    }
    /**
     * Get payment info block as html
     *
     * @param Order $order
     * @return string
     * @throws \Exception
     */
    protected function getPaymentHtml(Order $order)
    {
        return $this->paymentHelper->getInfoBlockHtml(
            $order->getPayment(),
            $this->identityContainer->getStore()->getStoreId()
        );
    }

    /**
     * Create Sender object using appropriate template and identity.
     *
     * @return Sender
     */
    protected function getSender()
    {
        return $this->senderBuilderFactory->create(
            [
                'templateContainer' => $this->templateContainer,
                'identityContainer' => $this->identityContainer,
            ]
        );
    }



    /**
     * Render shipping address into html.
     *
     * @param Order $order
     * @return string|null
     */
    protected function getFormattedShippingAddress($order)
    {
        return $order->getIsVirtual()
            ? null
            : $this->addressRenderer->format($order->getShippingAddress(), 'html');
    }

    /**
     * Render billing address into html.
     *
     * @param Order $order
     * @return string|null
     */
    protected function getFormattedBillingAddress($order)
    {
        return $this->addressRenderer->format($order->getBillingAddress(), 'html');
    }
}
