<?php

namespace Elevate\TrackOrder\Controller\Index;

class getOrder extends \Magento\Framework\App\Action\Action {
    protected $_pageFactory;
    protected $_addressConfig;
    protected $_orderFactory;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Customer\Model\Address\Config $addressConfig,
        \Magento\Sales\Model\OrderFactory $orderFactory,
        \Magento\Framework\View\Result\PageFactory $pageFactory
    ) {
        $this->_pageFactory = $pageFactory;
        $this->_addressConfig = $addressConfig;
        $this->_orderFactory = $orderFactory;

        return parent::__construct($context);
    }

    public function execute() {
           $output = '';
        $data = $this->getRequest()->getParams();

        $email_address = $data['email_address'];
        $increment_id = $data['increment_id'];

        if (!$email_address || !$increment_id) {
            $response['html'] = "Invalid Details";
            echo json_encode($response);
            exit;
        }
        $order = false;
        //$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        //$order = $objectManager->create('Magento\Sales\Model\Order')->load(121387); //122336

        $ordersCollection = $this->_orderFactory->create()->getCollection()->addFieldToFilter('customer_email', $email_address)->addFieldToFilter('increment_id', $increment_id);

        foreach ($ordersCollection as $orders) {
            $order = $orders;
            break;
        }

        $shipTrack = array();
        if ($order) {
            $shipments = $order->getShipmentsCollection();
            foreach ($shipments as $shipment) {
                $increment_id = $shipment->getIncrementId();
                $tracks = $shipment->getTracksCollection();

                $trackingInfos = array();
                foreach ($tracks as $track) {
                    $trackingInfos[] = $track->getData();
                }
                $shipTrack[$increment_id] = $trackingInfos;
            }
      

        $_results = $shipTrack;

        $output = '

<style>

.track-order-chosen-delservice {
  text-align: center;
  align-items: center;
  display: flex;

}
.track-order-chosen-delservice > h2,
.track-order-chosen-delservice > i {
  flex: 0 1 auto;
}
.track-order-chosen-delservice i {
    width: 50px;
    font-size: 50px;
    margin-right: 0.875rem;
}

.bottom-btn-row {
  padding-top: 1rem;
}

.delivery-time-date {
  margin-bottom: 0.5rem;
}
</style>



<div>

<div class="page-title title-buttons">

</div>
<div class="row" style="
    display: flex;
    align-items: stretch;
">
<div class="col-md-6" style="    flex: 0 1 auto;">
<div style="
    background-color: #f6f8fb;
    padding: 26px 35px 47px 48px;
    height: 100%;
"> <h2 style="
    margin-top: 0px;
    margin-bottom: 42px;
">Your Order Progress</h2>
    <ul class="StepProgress">
      <li class="StepProgress-item is-done"><strong>';

        $output .= $order->getCreatedAt();
        $output .= ' - Order Placed</strong></li>
      <li class="StepProgress-item is-done"><strong>Order Processing</strong>
        Your order is still processing and you will receive an email notification on dispatch.
      </li>';
        $is_done_class = '';
        $is_done = strtolower($order->getStatus()) == 'complete' ? true: false;
        if ($is_done) {

            $is_done_class = 'is-done';
        }

        $output .= '   <li class="StepProgress-item ' . $is_done_class . '" style="min-height: initial;"><strong>Order Dispatched</strong></li>

    </ul>
</div>
</div>



 <div class="col-md-6" style="

    background-color: #006b7f;
    color: #fff;
    padding: 20px;
    flex: 0 1 auto;

">
<div class="track-order-chosen-delservice">
    <i class="fa fa-info-circle"></i>
<h2>Your Chosen Delivery Service</h2></div>
     <div class="box-content">';

        $shipping_address = $order->getShippingAddress();
        $detailed_delivery_info = $shipping_address->getDetailedDeliveryInfo();
        $detailed_delivery_info_dates = $shipping_address->getDetailedDeliveryInfoDates();
        $detailed_delivery_teamnumber = $shipping_address->getDetailedDeliveryTeamnumber();
        $detailed_delivery_beforetime = $shipping_address->getDetailedDeliveryBeforeTime();
        $detailed_delivery_starttime = $shipping_address->getDetailedDeliveryStartTime();
        $detailed_delivery_endtime = $shipping_address->getDetailedDeliveryEndTime();
        $ship_date = explode(',', $detailed_delivery_info_dates);
        $ship_date = array_unique($ship_date);
        $output_time = '';


        if (!empty($detailed_delivery_beforetime)) {
            // before time
            $output_time .= "before ";
            $output_time .= "<strong>";
            $output_time .= $detailed_delivery_beforetime;
        } else {

            // Betweeen

            $output_time .= "between ";
            $output_time .= "<strong>";
            $output_time .= $detailed_delivery_starttime;
            $output_time .= ' - '.$detailed_delivery_endtime;
        }


        $output .= '</div>
            <div style="
    margin-top: 17px;
    background-color: #f0f0f0;
    color: #000;
    padding: 20px;
">';



        if (count($ship_date) > 1) {
            $output_string = 'on one of the following days:';
        }  else {
            $output_string = 'on:';
        }

        $output .= '<p class="delivery-time-date">Your delivery time will be  ';

        $output .= $output_time;
        $output .= '</strong> '.$output_string.'</p>';
        $output .= '<ul>';

          foreach ($ship_date as $key => $val) {

              $us_date = $val;
              $parts = explode("/", $us_date, 3);

              if (isset($parts[2])) {
                  $val = $parts[2] . "-" . $parts[1] . "-" . $parts[0];
              }
              $dt = new \DateTime($val);
              $output .= "<li><strong>";
              $output .= $dt->format('l jS F Y');

              $output .= "</strong></li>";
          }



        $output .= '</ul><br />
<p>The delivery company will confirm the specific day by giving you 24 hours prior notice.</p>

   </div>

<div class="bottom-btn-row">
  <a class="btn action primary" href="/knowledge-base?category=amend-or-cancel-a-delivery">Change Delivery Date</a>
  <a class="btn action primary" href="/knowledge-base?category=question-about-delivery">Delivery Enquiry</a>
</div>
</div>



  <div>





<div>
<h2 style="
    text-align: center;
    margin-top: 50px;
    font-size: 28px;
">Delivery Details</h2><hr style="
    border-top: 2px solid #0e1936;
    margin-top: 15px;
    width: 73%;
    margin-bottom: 50px;
">
<div class="page-title title-buttons">
';

        // if(!Mage::getSingleton('customer/session')->isLoggedIn()){
        //not logged in
        // }else{
        //     $output .=  $this->getChildHtml('buttons');
        //}

        $output .= '</div>';

        //  $output .=  $this->getStatusHistoryRssUrl($order);

        if (!$order->getIsVirtual()) {
            $output .= '<div class="row">
    <div class="col-md-12" style="text-align:center;">
        <div class="box">
            <div class="box-title">
                <h2>Shipping Address</h2>
            </div>
            <div class="box-content">
                <address>';
            //  $output .=  $order->getShippingAddress()->format('html');
            $address = $order->getShippingAddress();
            $renderer = $this->_addressConfig->getFormatByCode('html')->getRenderer();
            $output .= $renderer->renderArray($address);
            $output .= '</address>
            </div>
        </div>
    </div>

</div>';
        }

        $output .= '</div>





  </div>
</div>
';
  }
        $response['html'] = $output;
        echo json_encode($response);
    }
}
