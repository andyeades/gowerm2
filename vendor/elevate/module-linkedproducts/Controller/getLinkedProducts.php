<?php

namespace Elevate\Framework\Controller\Customer;

use Magento\Framework\App\Action\Context;
use Magento\Framework\Json\Helper\Data;
use Psr\Log\LoggerInterface;
use stdClass;


class Details extends \Magento\Framework\App\Action\Action
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


        $customerData = [];
        $responseData = [];
        $isLoggedIn = false;

        if($this->session->isLoggedIn()) {
            $isLoggedIn = true;
            $customerData = [
                'id' => $this->session->getCustomer()->getId(),
                'name' => $this->session->getCustomer()->getName(),
                'email' => $this->session->getCustomer()->getEmail(),
                'group_id' => $this->session->getCustomer()->getGroupId(),
            ];
        }



        $responseData = [
            'errors' => false,
            'is_logged_in' => $isLoggedIn,
            'customer_data' => $customerData
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