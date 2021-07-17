<?php

namespace Elevate\CartAssignments\Controller\Shipping;

use Magento\Framework\App\Action\Context;
use Magento\Framework\Json\Helper\Data;
use Psr\Log\LoggerInterface;
use stdClass;


class Get extends \Magento\Framework\App\Action\Action
{


    /**
     * @var Session
     */
    private $session;
    /**
     * @var StockItemRepository
     */

    /**
     * @var Data
     */
    protected $_jsonHelper;

    /**
     * @var LoggerInterface
     */
    protected $_logger;




    /**
     * Index constructor.
     * @param Context $context
     * @param Data $jsonHelper
     * @param LoggerInterface $logger
     */
    public function __construct(
        Context $context,
        Data $jsonHelper,
        LoggerInterface $logger,
        \Magento\Customer\Model\Session $session
    ) {
        parent::__construct($context);
        $this->_jsonHelper = $jsonHelper;
        $this->_logger = $logger;
        $this->session = $session;


    }

    /**
     * Execute view action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {


$html = '';
$om = \Magento\Framework\App\ObjectManager::getInstance();
$customerSession = $om->get('Magento\Customer\Model\Session');
//$customerData = $customerSession->getCustomer()->getData(); //get all data of customerData
$NAV_shipping_code = $customerSession->getCustomer()->getData('nav_shipping_method_code');//get id of customer
         if($NAV_shipping_code == ''){
 $NAV_shipping_code = 'FLAT500';
 }
   
        $standard_shipping_table =
            '<table class="table table-striped">
	<thead>
		<tr>
			<td><strong>Product</strong></td>
			<td class="text-right"><strong>Price (ex VAT)</strong></td>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>All goods*</td>
			<td class="text-right">&pound;9.95</td>
		</tr>
		<tr>
			<td>Assessments &amp; training</td>
			<td class="text-right">&pound;0.00</td>
		</tr>
		<tr>
			<td>Downloadable software</td>
			<td class="text-right">&pound;0.00</td>
		</tr>
		<tr>
			<td>Sit-stand platform (each)</td>
			<td class="text-right">&pound;12.95</td>
		</tr>
		<tr>
			<td>Chair (each)</td>
			<td class="text-right">&pound;21.95</td>
		</tr>
		<tr>
			<td>Desk (each)</td>
			<td class="text-right">&pound;29.95</td>
		</tr>
	</tbody>
</table>
<p><em>*Excludes assessments & training, downloadable software, sit-stand platforms, chairs and desks</em></p>';

        $bespoke2_shipping_table =
            '<table class="table table-striped">
	<thead>
		<tr>
			<td><strong>Product</strong></td>
			<td class="text-right"><strong>Price (ex VAT)</strong></td>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>All goods*</td>
			<td class="text-right">&pound;9.95</td>
		</tr>
		<tr>
			<td>Assessments &amp; training</td>
			<td class="text-right">&pound;0.00</td>
		</tr>
		<tr>
			<td>Downloadable software</td>
			<td class="text-right">&pound;0.00</td>
		</tr>
		<tr>
			<td>Chair (each)</td>
			<td class="text-right">&pound;0.00</td>
		</tr>
		<tr>
			<td>Sit-stand platform (each)</td>
			<td class="text-right">&pound;12.95</td>
		</tr>
		<tr>
			<td>Desk (each)</td>
			<td class="text-right">&pound;29.95</td>
		</tr>
	</tbody>
</table>
<p><em>*Excludes assessments & training, downloadable software, chairs, sit-stand platforms and desks</em></p>';
        $bespoke3_shipping_table =
            '<table class="table table-striped">
	<thead>
		<tr>
			<td><strong>Product</strong></td>
			<td class="text-right"><strong>Price (ex VAT)</strong></td>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>All goods*</td>
			<td class="text-right">&pound;5.00</td>
		</tr>
		<tr>
			<td>Assessments &amp; training</td>
			<td class="text-right">&pound;0.00</td>
		</tr>
		<tr>
			<td>Downloadable software</td>
			<td class="text-right">&pound;0.00</td>
		</tr>
		<tr>
			<td>Chair (each)</td>
			<td class="text-right">&pound;10.00</td>
		</tr>
		<tr>
			<td>Sit-stand platform (each)</td>
			<td class="text-right">&pound;10.00</td>
		</tr>
		<tr>
			<td>Desk (each)</td>
			<td class="text-right">&pound;25.00</td>
		</tr>
	</tbody>
</table>
<p><em>*Excludes assessments & training, downloadable software, chairs, sit-stand platforms and desks</em></p>';
        $bespoke4_shipping_table =
            '<table class="table table-striped">
	<thead>
		<tr>
			<td><strong>Product</strong></td>
			<td class="text-right"><strong>Price (ex VAT)</strong></td>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>All goods*</td>
			<td class="text-right">&pound;5.00</td>
		</tr>
		<tr>
			<td>Assessments &amp; training</td>
			<td class="text-right">&pound;0.00</td>
		</tr>
		<tr>
			<td>Downloadable software</td>
			<td class="text-right">&pound;0.00</td>
		</tr>
		<tr>
			<td>Sit-stand platform (each)</td>
			<td class="text-right">&pound;12.95</td>
		</tr>
		<tr>
			<td>Chair (each)</td>
			<td class="text-right">&pound;21.95</td>
		</tr>
		<tr>
			<td>Desk (each)</td>
			<td class="text-right">&pound;29.95</td>
		</tr>
	</tbody>
</table>
<p><em>*Excludes assessments & training, downloadable software, sit-stand platforms, chairs and desks</em></p>';
        $bespoke5_shipping_table =
            '<table class="table table-striped">
	<thead>
		<tr>
			<td><strong>Product</strong></td>
			<td class="text-right"><strong>Price (ex VAT)</strong></td>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>All goods*</td>
			<td class="text-right">&pound;0.00</td>
		</tr>
		<tr>
			<td>Chair (each)</td>
			<td class="text-right">&pound;21.95</td>
		</tr>
		<tr>
			<td>Desk (each)</td>
			<td class="text-right">&pound;29.95</td>
		</tr>
	</tbody>
</table>
<p><em>*Excludes chairs and desks</em></p>';
        // echo $NAV_shipping_code;
       
                         
            if($NAV_shipping_code == 'FLAT500' || $NAV_shipping_code == 'STANDARD_W' ){
                $Shippimg_price = "<td class='text-right'>&pound;5.00*</td>";
            }elseif($NAV_shipping_code == 'FLAT750'){
                $Shippimg_price = "<td class='text-right'>&pound;7.50*</td>";
            }elseif($NAV_shipping_code == 'FLAT1000'){
                $Shippimg_price = "<td class='text-right'>&pound;10.00*</td>";
            }elseif($NAV_shipping_code == 'FOC'){
                $Shippimg_price = "<td class='text-right'>&pound;0.00</td>";
            }else{
                $Shippimg_price = "<td class='text-right'>&pound;5.00*</td>";

            }
            if($NAV_shipping_code == 'STANDARD'){
                $html .=
                    '<p><strong>UK Mainland &amp; Northern Ireland:</strong></p>
<table class="table table-striped">
	<thead>
		<tr>
			<td><strong>Product</strong></td>
			<td class="text-right"><strong>Price (ex VAT)</strong></td>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>All goods*</td>
			<td class="text-right">&pound;9.95</td>
		</tr>
		<tr>
			<td>Assessments &amp; training</td>
			<td class="text-right">&pound;0.00</td>
		</tr>
		<tr>
			<td>Downloadable software</td>
			<td class="text-right">&pound;0.00</td>
		</tr>
		<tr>
			<td>Sit-stand platform (each)</td>
			<td class="text-right">&pound;12.95</td>
		</tr>
		<tr>
			<td>Chair (each)</td>
			<td class="text-right">&pound;21.95</td>
		</tr>
		<tr>
			<td>Desk (each)</td>
			<td class="text-right">&pound;29.95</td>
		</tr>
	</tbody>
</table>
<p><em>*Excludes assessments & training, downloadable software, sit-stand platforms, chairs and desks</em></p>';

            }elseif($NAV_shipping_code == 'BESPOKE2'){
                $html .=
                    '<p><strong>UK Mainland &amp; Northern Ireland:</strong></p>
<table class="table table-striped">
	<thead>
		<tr>
			<td><strong>Product</strong></td>
			<td class="text-right"><strong>Price (ex VAT)</strong></td>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>All goods*</td>
			<td class="text-right">&pound;9.95</td>
		</tr>
		<tr>
			<td>Assessments &amp; training</td>
			<td class="text-right">&pound;0.00</td>
		</tr>
		<tr>
			<td>Downloadable software</td>
			<td class="text-right">&pound;0.00</td>
		</tr>
		<tr>
			<td>Chair (each)</td>
			<td class="text-right">&pound;0.00</td>
		</tr>
		<tr>
			<td>Sit-stand platform (each)</td>
			<td class="text-right">&pound;12.95</td>
		</tr>
		<tr>
			<td>Desk (each)</td>
			<td class="text-right">&pound;29.95</td>
		</tr>
	</tbody>
</table>
<p><em>*Excludes assessments & training, downloadable software, chairs, sit-stand platforms and desks</em></p>';

            }elseif($NAV_shipping_code == 'BESPOKE3'){
                $html .=
                    '<p><strong>UK Mainland &amp; Northern Ireland:</strong></p>
<table class="table table-striped">
	<thead>
		<tr>
			<td><strong>Product</strong></td>
			<td class="text-right"><strong>Price (ex VAT)</strong></td>
		</tr>
	</thead>
	<tbody>											
		<tr>
			<td>All goods*</td>
			<td class="text-right">&pound;5.00</td>
		</tr>
		<tr>
			<td>Assessments &amp; training</td>
			<td class="text-right">&pound;0.00</td>
		</tr>
		<tr>
			<td>Downloadable software</td>
			<td class="text-right">&pound;0.00</td>
		</tr>
		<tr>
			<td>Chair (each)</td>
			<td class="text-right">&pound;10.00</td>
		</tr>
		<tr>
			<td>Sit-stand platform (each)</td>
			<td class="text-right">&pound;10.00</td>
		</tr>
		<tr>
			<td>Desk (each)</td>
			<td class="text-right">&pound;25.00</td>
		</tr>
	</tbody>
</table>
<p><em>*Excludes assessments & training, downloadable software, chairs, sit-stand platforms and desks</em></p>';

            }elseif($NAV_shipping_code == 'BESPOKE4'){
                $html .=
                    '<p><strong>UK Mainland &amp; Northern Ireland:</strong></p>
<table class="table table-striped">
	<thead>
		<tr>
			<td><strong>Product</strong></td>
			<td class="text-right"><strong>Price (ex VAT)</strong></td>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>All goods*</td>
			<td class="text-right">&pound;5.00</td>
		</tr>
		<tr>
			<td>Assessments &amp; training</td>
			<td class="text-right">&pound;0.00</td>
		</tr>
		<tr>
			<td>Downloadable software</td>
			<td class="text-right">&pound;0.00</td>
		</tr>
		<tr>
			<td>Sit-stand platform (each)</td>
			<td class="text-right">&pound;12.95</td>
		</tr>
		<tr>
			<td>Chair (each)</td>
			<td class="text-right">&pound;21.95</td>
		</tr>
		<tr>
			<td>Desk (each)</td>
			<td class="text-right">&pound;29.95</td>
		</tr>
	</tbody>
</table>
<p><em>*Excludes assessments & training, downloadable software, sit-stand platforms, chairs and desks</em></p>';

            }elseif($NAV_shipping_code == 'BESPOKE5'){
                $html .=
                    '<p><strong>UK Mainland &amp; Northern Ireland:</strong></p>
<table class="table table-striped">
	<thead>
		<tr>
			<td><strong>Product</strong></td>
			<td class="text-right"><strong>Price (ex VAT)</strong></td>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>All goods*</td>
			<td class="text-right">&pound;0.00</td>
		</tr>
		<tr>
			<td>Chair (each)</td>
			<td class="text-right">&pound;21.95</td>
		</tr>
		<tr>
			<td>Desk (each)</td>
			<td class="text-right">&pound;29.95</td>
		</tr>
	</tbody>
</table>
<p><em>*Excludes chairs and desks</em></p>';

            }
            else{

                $html .=
                    '<p><strong>UK Mainland &amp; Northern Ireland:</strong></p>
<table class="table table-striped">
	<thead>
		<tr>
			<td><strong>Product</strong></td>
			<td class="text-right"><strong>Price (ex VAT)</strong></td>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>All goods</td>
			'.$Shippimg_price.'
		</tr>
	</tbody>
</table>';


if($NAV_shipping_code != 'FOC'){
$html .= '<p><em>*Excludes assesments & training and downloadable software (&pound;0.00)</em></p>';
}
            }

           
       $reponse['html'] = $html;
      
             echo json_encode($reponse);
    
        exit;
  

//if we have requirement - we can also get the billing details
      //  $billingAddress = $cart->getQuote()->getBillingAddress();
      //  echo '<pre>'; print_r($billingAddress->getData()); echo '</pre>';

      //  $shippingAddress = $cart->getQuote()->getShippingAddress();
      //  echo '<pre>'; print_r($shippingAddress->getData()); echo '</pre>';

        $responseData = [
            'errors' => false,
            'has_basket' => $hasBasket,
            'total_items' => $totalItems,
            'total_quantity' => $totalQuantity,
            'sub_total' => $subTotal,
            'grand_total' => $grandTotal,
            'item_data' => $itemData
        ];
        echo json_encode($responseData);


    }

    /**
     * Create json response
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function jsonResponse($response = '')
    {
        return $this->getResponse()->representJson(
            $this->_jsonHelper->jsonEncode($response)
        );
    }


}