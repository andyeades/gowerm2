<?php

namespace Elevate\PrintLabels\Controller\Adminhtml\Orders;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
//use PrintNode\Credentials;

use \Elevate\PrintLabels\Controller\Adminhtml\Edit\DPDAuthorisation;

/**
 * Class GetOrders
 *
 * @category Elevate
 * @package  Elevate\PrintLabels\Controller\Adminhtml\Edit
 * @author   Richard Jones <richard.jones@elevateweb.co.uk>
 */
class GetOrders extends \Magento\Backend\App\Action {
    /**
     * @var \Magento\Sales\Api\OrderRepositoryInterface
     */
    protected $orderRepository;

    /**
     * @var \Magento\Customer\Api\AccountManagementInterface
     */
    protected $accountManagement;

    /**
     * @var \Magento\Sales\Api\OrderCustomerManagementInterface
     */
    protected $orderCustomerService;

    protected $searchCriteriaBuilder;
    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $resultJsonFactory;

    /**
     * @var \Magento\Framework\Encryption\EncryptorInterface
     */
    protected $encryptorInterface;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var \PrintNode\Credentials
     */
    protected $printNodeCredentials;

    /**
     * @var \Elevate\PrintLabels\Controller\Adminhtml\Edit\DPDAuthorisation
     */
    protected $dpdAuthorisation;

    protected $addressRepository;

    protected $orderAddressRepository;

    protected $orderAddressRepo;

    protected $orderModel;

    /**
     * Index constructor.
     *
     * @param Context                                             $context
     * @param \Magento\Sales\Api\OrderRepositoryInterface         $orderRepository
     * @param \Magento\Customer\Api\AccountManagementInterface    $accountManagement
     * @param \Magento\Sales\Api\OrderCustomerManagementInterface $orderCustomerService
     * @param \Magento\Framework\Api\SearchCriteriaBuilder        $searchCriteriaBuilder
     * @param \Magento\Framework\Controller\Result\JsonFactory    $resultJsonFactory
     * @param \Magento\Customer\Api\AddressRepositoryInterface           $addressRepository
     * @param \Magento\Sales\Model\Order\AddressRepository               $orderAddressRepository
     * @param \Magento\Sales\Api\OrderAddressRepositoryInterface         $orderAddressRepo
     * @param \Magento\Sales\Model\Order                                 $orderModel
     * @param \Magento\Framework\Encryption\EncryptorInterface    $encryptorInterface
     * @param \Magento\Framework\App\Config\ScopeConfigInterface  $scopeConfig
     * @param \PrintNode\Credentials                              $printNodeCredentials
     * @param \Elevate\PrintLabels\Controller\Adminhtml\Edit\DPDAuthorisation $dpdAuthorisation
     * @param \Elevate\PrintLabels\Helper\OrderData $orderHelper
     */
    public function __construct(
        Context $context,
        \Magento\Sales\Api\OrderRepositoryInterface $orderRepository,
        \Magento\Customer\Api\AccountManagementInterface $accountManagement,
        \Magento\Sales\Api\OrderCustomerManagementInterface $orderCustomerService,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Framework\Encryption\EncryptorInterface $encryptorInterface,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \PrintNode\Credentials $printNodeCredentials,
        \Magento\Customer\Api\AddressRepositoryInterface $addressRepository,
        \Magento\Sales\Model\Order\AddressRepository $orderAddressRepository,
        \Magento\Sales\Api\OrderAddressRepositoryInterface $orderAddressRepo,
        \Magento\Sales\Model\Order $orderModel,
        \Elevate\PrintLabels\Controller\Adminhtml\Edit\DPDAuthorisation $dpdAuthorisation,
        \Elevate\PrintLabels\Helper\OrderData $orderHelper
    ) {
        parent::__construct($context);

        $this->orderRepository = $orderRepository;
        $this->orderCustomerService = $orderCustomerService;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->accountManagement = $accountManagement;
        $this->encryptorInterface = $encryptorInterface;
        $this->scopeConfig = $scopeConfig;
        $this->printNodeCredentials = $printNodeCredentials;
        $this->dpdAuthorisation = $dpdAuthorisation;
        $this->addressRepository = $addressRepository;
        $this->orderAddressRepository = $orderAddressRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->orderModel = $orderModel;
        $this->orderAddressRepo = $orderAddressRepo;
        $this->orderHelper = $orderHelper;
        $this->printNodeApiKey = $this->scopeConfig->getValue('elevate_printlabels/printnodedetails/printnodeapikey', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $this->printNodePrinterId = $this->scopeConfig->getValue('elevate_printlabels/printnodedetails/printnodeprinterid', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $this->apiAccountNumber = $this->scopeConfig->getValue('elevate_printlabels/details/api_accountnumber', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $this->apiUsername = $this->scopeConfig->getValue('elevate_printlabels/details/api_username', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $this->apiPassword = $this->scopeConfig->getValue('elevate_printlabels/details/api_password', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $this->apiUrl = $this->scopeConfig->getValue('elevate_printlabels/details/api_url', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $this->senderContactName = $this->scopeConfig->getValue('elevate_printlabels/contactdetails/contact_name', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $this->senderTelephone = $this->scopeConfig->getValue('elevate_printlabels/contactdetails/contact_telephone', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $this->senderOrgName = $this->scopeConfig->getValue('elevate_printlabels/contactdetails/organisation_name', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $this->senderOrgStreet = $this->scopeConfig->getValue('elevate_printlabels/contactdetails/organisation_street', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $this->senderOrgStreet2 = $this->scopeConfig->getValue('elevate_printlabels/contactdetails/organisation_locality', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $this->senderOrgTownCity = $this->scopeConfig->getValue('elevate_printlabels/contactdetails/organisation_towncity', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $this->senderOrgCounty = $this->scopeConfig->getValue('elevate_printlabels/contactdetails/organisation_county', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $this->senderOrgPostcode = $this->scopeConfig->getValue('elevate_printlabels/contactdetails/organisation_postcode', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $this->senderOrgCountryCode = $this->scopeConfig->getValue('elevate_printlabels/contactdetails/organisation_countrycode', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $this->collectionTime = $this->scopeConfig->getValue('elevate_printlabels/collection/collection_time', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $this->collectionCutOffTime = $this->scopeConfig->getValue('elevate_printlabels/collection/collection_cutofftime', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $this->collectionDays = $this->scopeConfig->getValue('elevate_printlabels/collection/collection_days', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);


    }

    /**
     * Index action
     *
     * @return \Magento\Framework\Controller\Result\Json
     * @throws \Exception
     */
    public function execute() {
        $orders = $this->orderHelper->getOrders();
        $response = $orders;
        return $this->resultJsonFactory->create()->setData($response);
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
