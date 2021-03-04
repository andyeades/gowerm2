<?php

namespace Elevate\PrintLabels\Controller\Adminhtml\Order;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
//use PrintNode\Credentials;

use \Elevate\PrintLabels\Controller\Adminhtml\Edit\DPDAuthorisation;

/**
 * Class Info
 *
 * @category Elevate
 * @package  Elevate\PrintLabels\Controller\Adminhtml\Edit
 * @author   Richard Jones <richard.jones@elevateweb.co.uk>
 */
class View extends \Magento\Sales\Controller\Adminhtml\Order {

  protected $fileFactory;
  protected $translateInline;
  protected $resultPageFactory;
  protected $resultLayoutFactory;
  protected $resultRawFactory;
  protected $logger;

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
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry = null;

    /**
     * Index constructor.
     *
     * @param Context                                             $context
     *
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
     * @param \Magento\Framework\Registry $coreRegistry
     */
    public function __construct(
        Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Sales\Api\OrderRepositoryInterface $orderRepository,
        \Magento\Customer\Api\AccountManagementInterface $accountManagement,
        \Magento\Sales\Api\OrderCustomerManagementInterface $orderCustomerService,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Framework\Encryption\EncryptorInterface $encryptorInterface,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory,
        \Magento\Framework\Translate\InlineInterface $translateInline,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\View\Result\LayoutFactory $resultLayoutFactory,
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory,
        \Magento\Sales\Api\OrderManagementInterface $orderManagement,
        \Psr\Log\LoggerInterface $logger,
        \PrintNode\Credentials $printNodeCredentials,
        \Magento\Customer\Api\AddressRepositoryInterface $addressRepository,
        \Magento\Sales\Model\Order\AddressRepository $orderAddressRepository,
        \Magento\Sales\Api\OrderAddressRepositoryInterface $orderAddressRepo,
        \Magento\Sales\Model\Order $orderModel,
        \Elevate\PrintLabels\Controller\Adminhtml\Edit\DPDAuthorisation $dpdAuthorisation
    ) {
      $this->coreRegistry = $coreRegistry;
      $this->orderRepository = $orderRepository;
      $this->orderCustomerService = $orderCustomerService;
      $this->resultJsonFactory = $resultJsonFactory;
      $this->searchCriteriaBuilder = $searchCriteriaBuilder;
      $this->accountManagement = $accountManagement;
      $this->encryptorInterface = $encryptorInterface;
      $this->scopeConfig = $scopeConfig;
      $this->fileFactory = $fileFactory;
      $this->translateInline = $translateInline;
      $this->resultPageFactory = $resultPageFactory;
      $this->resultLayoutFactory = $resultLayoutFactory;
      $this->resultRawFactory = $resultRawFactory;
      $this->printNodeCredentials = $printNodeCredentials;
      $this->dpdAuthorisation = $dpdAuthorisation;
      $this->addressRepository = $addressRepository;
      $this->orderAddressRepository = $orderAddressRepository;
      $this->searchCriteriaBuilder = $searchCriteriaBuilder;
      $this->orderModel = $orderModel;
      $this->orderAddressRepo = $orderAddressRepo;
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
      parent::__construct($context, $coreRegistry, $fileFactory, $translateInline, $resultPageFactory, $resultJsonFactory, $resultLayoutFactory, $resultRawFactory, $orderManagement, $orderRepository, $logger);
    }

    /**
     * Index action
     *
     * @return \Magento\Framework\Controller\Result\Json
     * @throws \Exception
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
