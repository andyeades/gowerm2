<?php

namespace Elevate\CartAssignments\Controller\Shipping;

use Magento\Framework\App\Action\Context;
use Magento\Framework\Json\Helper\Data;
use Psr\Log\LoggerInterface;
use stdClass;


class International extends \Magento\Framework\App\Action\Action
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
         

$html = '

<section>
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
				<!--Start Row-->
                <div class="row row-padding">
                    <div class="col-sm-12">
					<!--International charges-->
						<h3>International shipping charges</h3>
						<table class="table table-striped">
                            <thead>
							    <tr>
                                    <th>&nbsp;</th>
                                    <th colspan="8" class="text-center"><strong>Zone - Prices (ex VAT)</strong></th>
                                </tr>
                                <tr>
                                    <th><strong>Product</strong></th>
                                    <th class="text-right"><strong>1</strong></th>
									<th class="text-right"><strong>2</strong></th>
									<th class="text-right"><strong>3</strong></th>
									<th class="text-right"><strong>4</strong></th>
									<th class="text-right"><strong>5</strong></th>
									<th class="text-right"><strong>6</strong></th>
									<th class="text-right"><strong>7</strong></th>
									<th class="text-right"><strong>8</strong></th>
									<th class="text-right"><strong>9</strong></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>All goods**</td>
                                    <td class="text-right">&pound;20.95</td>
                                    <td class="text-right">&pound;24.95</td>
                                    <td class="text-right">&pound;24.95</td>
                                    <td class="text-right">&pound;29.95</td>
                                    <td class="text-right">&pound;29.95</td>
                                    <td class="text-right">&pound;25.95</td>
                                    <td class="text-right">&pound;39.95</td>
                                    <td class="text-right">&pound;45.95</td>
                                    <td class="text-right">&pound;49.95</td>
                                </tr>
								<tr>
                                    <td>Assessments & training</td>
                                    <td class="text-right">&pound;0.00</td>
                                    <td class="text-right">&pound;0.00</td>
                                    <td class="text-right">&pound;0.00</td>
                                    <td class="text-right">&pound;0.00</td>
                                    <td class="text-right">&pound;0.00</td>
                                    <td class="text-right">&pound;0.00</td>
                                    <td class="text-right">&pound;0.00</td>
                                    <td class="text-right">&pound;0.00</td>
                                    <td class="text-right">&pound;0.00</td>
                                </tr>
                                <tr>
                                    <td>Downloadable software</td>
                                    <td class="text-right">&pound;0.00</td>
                                    <td class="text-right">&pound;0.00</td>
                                    <td class="text-right">&pound;0.00</td>
                                    <td class="text-right">&pound;0.00</td>
                                    <td class="text-right">&pound;0.00</td>
                                    <td class="text-right">&pound;0.00</td>
                                    <td class="text-right">&pound;0.00</td>
                                    <td class="text-right">&pound;0.00</td>
                                    <td class="text-right">&pound;0.00</td>
                                </tr>
								<tr>
                                    <td>Sit-stand platform (each)</td>
                                    <td class="text-right">&pound;24.95</td>
                                    <td class="text-right">&pound;64.95</td>
                                    <td class="text-right">&pound;84.95</td>
                                    <td class="text-right">&pound;109.95</td>
                                    <td class="text-right">&pound;134.95</td>
                                    <td class="text-right">&pound;104.95</td>
                                    <td class="text-right">&pound;174.95</td>
                                    <td class="text-right">&pound;199.95</td>
                                    <td class="text-right">&pound;214.95</td>
                                </tr>
								<tr>
                                    <td>Chair (each)</td>
                                    <td class="text-right">&pound;29.95</td>
                                    <td class="text-right">&pound;94.95</td>
                                    <td class="text-right">&pound;129.95</td>
                                    <td class="text-right">&pound;174.95</td>
                                    <td class="text-right">&pound;209.95</td>
                                    <td class="text-right">&pound;154.95</td>
                                    <td class="text-right">&pound;244.95</td>
                                    <td class="text-right">&pound;274.95</td>
                                    <td class="text-right">&pound;329.95</td>
                                </tr>
								<tr>
                                    <td>Desk (each)</td>
                                    <td class="text-right">&pound;34.95</td>
                                    <td class="text-right">&pound;114.95</td>
                                    <td class="text-right">&pound;154.95</td>
                                    <td class="text-right">&pound;214.95</td>
                                    <td class="text-right">&pound;254.95</td>
                                    <td class="text-right">&pound;179.95</td>
                                    <td class="text-right">&pound;289.95</td>
                                    <td class="text-right">&pound;319.95</td>
                                    <td class="text-right">&pound;399.95</td>
                                </tr>
                            </tbody>
                        </table>
						<p><em>**Excludes assessments & training, downloadable software, sit-stand platforms, chairs and desks</em></p>
                    	<!--End international charges-->
					</div>
                </div>     
                <!--End Row-->
				<!--Start Row-->
                <div class="row row-padding">
                    <div class="col-sm-12">
						<!--International zones-->
                        <h3>International zones</h3>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th><strong>Zone</strong></th>
                                    <th><strong>Destination</strong></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>Austria, Belgium, Bulgaria, Czech Republic, Denmark, Estonia, Finland, France, Germany, Hungary, Italy, Latvia, Lithuania, Luxembourg, Poland, Romania, Slovakia, Slovenia, Spain, Sweden</td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Ireland, Netherlands</td>
                                </tr>
								<tr>
                                    <td>3</td>
                                    <td>Monaco</td>
                                </tr>
								<tr>
                                    <td>4</td>
                                    <td>Greece, Portugal</td>
                                </tr> 
								<tr>
                                    <td>5</td>
                                    <td>Albania, Andorra, Belarus, Bosnia and Herzegovina, Croatia, Cyprus, Faroe Islands, Gibraltar, Greenland, Guernsey, Iceland, Jersey, Liechtenstein, Macedonia, Malta, Moldova, Montenegro, Northern Mariana Islands, Norway, San Marino, Switzerland, Turkey, Ukraine</td>
                                </tr>
								<tr>
                                    <td>6</td>
                                    <td>Canada, Mexico, Puerto Rico, United States</td>
                                </tr>
								<tr>
                                    <td>7</td>
                                    <td>Australia, China, Hong Kong SAR China, Indonesia, Japan, Macau SAR China, Malaysia, Philippines, Russia, Singapore, South Africa, South Korea, Taiwan, Thailand</td>
                                </tr>   
								<tr>
                                    <td>8</td>
                                    <td>American Samoa, Anguilla, Antigua and Barbuda, Armenia, Aruba, Azerbaijan, Bahamas, Bahrain, Barbados, Bermuda, British Virgin Islands, Brunei, Cayman Islands, Cook Islands, Dominica, Dominican Republic, Egypt, Fiji, French Polynesia, Georgia, Grenada, Guam, India, Iraq, Israel, Jamaica, Kazakhstan, Kenya, Kiribati, Kuwait, Kyrgyzstan, Marshall Islands, Martinique, Micronesia, Mongolia, Morocco, Netherlands Antilles, New Caledonia, New Zealand, Nigeria, Oman, Pakistan, Papua New Guinea, Qatar, Saint Barthelemy, Saint Kitts and Nevis, Saint Lucia, Saint Vincent and the Grenadines, Samoa, Saudi Arabia, Solomon Islands, Sri Lanka, Tajikistan, Tonga, Trinidad and Tobago, Turkmenistan, Turks and Caicos Islands, United Arab Emirates, U.S. Virgin Islands, Uzbekistan, Vanuatu, Vietnam, Wallis and Futuna, Zimbabwe</td>
                                </tr>
								<tr>
                                    <td>9</td>
                                    <td>Afghanistan, Aland Islands, Algeria, Angola, Antarctica, Argentina, Bangladesh, Belize, Benin, Bhutan, Bolivia, Botswana, Bouvet Island, Brazil, British Indian Ocean Territory, Burkina Faso, Burundi, Cambodia, Cameroon, Cape Verde, Central African Republic, Chad, Chile, Christmas Island, Cocos (Keeling) Islands, Colombia, Comoros, Congo - Brazzaville, Congo - Kinshasa, Costa Rica, Cote d\'Ivoire, Cuba, Djibouti, Ecuador, El Salvador, Equatorial Guinea, Eritrea, Ethiopia, Falkland Islands, French Guiana, French Southern Territories, Gabon, Gambia, Ghana, Guadeloupe, Guatemala, Guinea, Guinea-Bissau, Guyana, Haiti, Heard Island and McDonald Islands, Honduras, Iran, Jordan, Laos, Lebanon, Lesotho, Liberia, Libya, Madagascar, Malawi, Maldives, Mali, Mauritania, Mauritius, Mayotte, Montserrat, Mozambique, Myanmar (Burma), Namibia, Nauru, Nepal, Nicaragua, Niger, Niue, Norfolk Island, North Korea, Palau, Palestinian Territories, Panama, Paraguay, Peru, Pitcairn Islands, Reunion, Rwanda, Saint Helena, Saint Martin, Saint Pierre and Miquelon, Sao Tome and Principe, Senegal, Serbia, Seychelles, Sierra Leone, Somalia, South Georgia and the South Sandwich Islands, Sudan, Suriname, Svalbard and Jan Mayen, Swaziland, Syria, Tanzania, Timor-Leste, Togo, Tokelau, Tunisia, Tuvalu, Uganda, Uruguay, U.S. Minor Outlying Islands, Vatican City, Venezuela, Western Sahara, Yemen, Zambia</td>
                                </tr>
                            </tbody>
                        </table>
					
                    </div>
                </div>
             
                
            </div>    
        </div>
    </div>
</section>
';

            
       $response['html'] = $html;
               
             echo json_encode($response);
    
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