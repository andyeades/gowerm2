<?php

namespace Elevate\Assignments\Controller\Addons;

use Magento\Framework\App\Action\Context;
use Magento\Framework\Json\Helper\Data;
use Psr\Log\LoggerInterface;
use stdClass;




class Getaddons extends \Magento\Framework\App\Action\Action
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

    protected $has_assigned_html;

    protected $validation_errors = [];

    /**
     * Error Logging when $_GET['debug'] = 1
     */
    public function addValidationError($attribute_id, $message){
        $this->validation_errors[$attribute_id] = $message;
    }
    public function getValidationErrors(){
        return $this->validation_errors;
    }
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
     * //get_all_addons

    //get all current addons

    //any update to the cart quantity is run through this function.
    //Everytime a cart change happens - increasing a quantity, adding a product, removing a product.
    //This has the potential to cause other products to be recalculated
    //we can ajax as much of this into a single request.


    //what do we want to do

    //increase main quantity

    //increase addon_qty

    //assign_addon

    //remove_addon

    //remove_product

    //add_product


    //Response

    //current_addons
    //cart_addons

    /*return output of addons

* Execute view action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */

    public function execute()
    {
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