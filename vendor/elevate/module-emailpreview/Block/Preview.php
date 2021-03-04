<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Adminhtml system template preview block
 *
 * @author     Magento Core Team <core@magentocommerce.com>
 */
namespace Elevate\EmailPreview\Block;

/**
 * Email template preview block.
 *
 * @api
 * @since 100.0.2
 */
class Preview extends \Magento\Backend\Block\Widget
{

    /**
     * @var \Magento\Sales\Model\Order\Address\Renderer
     */
    protected $addressRenderer;

    /**
     * @var Emulation
     */
    protected $_appEmulation;
    /**
     * @var \Magento\Payment\Helper\Data
     */
    protected $paymentHelper;

    protected $resultPageFactory;
    /**
     * @var \Magento\Framework\Filter\Input\MaliciousCode
     */
    protected $_maliciousCode;
    protected $layoutFactory;
    /**
     * @var \Magento\Email\Model\TemplateFactory
     */
    protected $_emailFactory;
    /**
     * @var \Magento\Email\Model\TemplateFactory
     */

    /**
     * @var \Magento\Sales\Model\OrderRepository
     */
    protected $_orderRepository;

    /**
     * @var \Elevate\EmailPreview\Model\Mail\Template\ExtendedTransportBuilderFactory
     */
    private $_extendedTransportBuilder;


    protected $_storeManager;
    protected $_appState;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Filter\Input\MaliciousCode $maliciousCode
     * @param \Magento\Email\Model\TemplateFactory $emailFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Filter\Input\MaliciousCode $maliciousCode,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Email\Model\TemplateFactory $emailFactory,
        \Elevate\EmailPreview\Model\Mail\Template\ExtendedTransportBuilderFactory $extendedTransportBuilder,
        \Magento\Framework\App\State $appState,
        \Magento\Sales\Model\Order\Address\Renderer $addressRenderer,
        \Magento\Payment\Helper\Data $paymentHelper,
        \Magento\Sales\Model\OrderRepository $orderRepository,
        \Magento\Framework\View\Layout $layoutFactory,
        array $data = []
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->_maliciousCode = $maliciousCode;
        $this->_emailFactory = $emailFactory;
        $this->_storeManager = $storeManager;
        $this->_extendedTransportBuilder = $extendedTransportBuilder;
        $this->_appState = $appState;
        $this->addressRenderer = $addressRenderer;
        $this->layoutFactory = $layoutFactory;
        $this->paymentHelper = $paymentHelper;
        $this->_orderRepository = $orderRepository;
        parent::__construct($context, $data);
    }

    /**
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
     * @param Order $order
     * @return string|null
     */
    protected function getFormattedBillingAddress($order)
    {
        return $this->addressRenderer->format($order->getBillingAddress(), 'html');
    }

    /**
     * Get payment info block as html
     *
     * @param Order $order
     * @return string
     */
    protected function getPaymentHtml($order)
    {
        return $this->paymentHelper->getInfoBlockHtml(
            $order->getPayment(),
            $this->_storeManager->getStore()->getId()
        );
    }

    public function getProductPriceHtml(
        \Magento\Catalog\Model\Product $product,
        $priceType,
        $renderZone = \Magento\Framework\Pricing\Render::ZONE_ITEM_LIST,
        array $arguments = []
    ) {
        if (!isset($arguments['zone'])) {
            $arguments['zone'] = $renderZone;
        }

        /** @var \Magento\Framework\Pricing\Render $priceRender */
        $priceRender = $this->getPriceRender();
        $price = '';

        if ($priceRender) {
            $price = $priceRender->render(
                $priceType,
                $product,
                $arguments
            );
        }
        return $price;
    }


    protected function getAlertGridBlock(){




        $storeId = $this->getStoreId() ?: (int) $this->_customer->getStoreId();
        $store = $this->getStore($storeId);

        $this->_appEmulation->startEnvironmentEmulation($storeId);

        $block = $this->getBlock();
        $block->setStore($store)->reset();

        // Add products to the block
        foreach ($products as $product) {
            $product->setCustomerGroupId($this->_customer->getGroupId());
            $block->addProduct($product);
        }

        $templateId = $this->_scopeConfig->getValue(
            $templateConfigPath,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );

        $alertGrid = $this->_appState->emulateAreaCode(
            Area::AREA_FRONTEND,
            [$block, 'toHtml']
        );
        $this->_appEmulation->stopEnvironmentEmulation();

    }

    protected function getPriceRender()
    {
        return $this->layoutFactory->createBlock(
            \Magento\Framework\Pricing\Render::class,
            '',
            ['data' => ['price_render_handle' => 'catalog_product_prices']]
        );
    }
    /**
     * Prepare html output
     *
     * @return string
     */
    protected function _toHtml()
    {
        $template_code = $this->getRequest()->getParam('template_code');
        $order_id = $this->getRequest()->getParam('order_id');

        if(empty($template_code)){

            echo "No Template";
            exit;
        }

///instead of name - number for custom admin email



        $storeId = (int)$this->getRequest()->getParam('store');
        $storeId = (is_numeric ($storeId)) ? $storeId : $this->getAnyStoreView()->getId();


        $extendedTransportBuilder = $this->_extendedTransportBuilder->create();

        $sender = [
            'name' => "andy",
            'email' => "andy.eades@elevateweb.co.uk"
        ];
        $templateOptions = [
            'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
            'store' => $storeId
        ];
        $templateVars = [];
        //  $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        $orderId = 197767;
if(!empty($order_id)){

    $incrId = $order_id;
    $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

    $collection = $objectManager->create('Magento\Sales\Model\Order');
    $order = $collection->loadByIncrementId($incrId);
    $orderId = $order ->getId();

}
        $order = $this->_orderRepository->get($orderId);
        $templateVars['order'] = $order;

        $shipmenttotal = $order->getShipmentsCollection();
        foreach ($shipmenttotal as $shipment) {

            $templateVars['shipment'] = $shipment;
            break;
        }



        foreach ($order->getInvoiceCollection() as $invoice)
        {
            $templateVars['invoice'] = $invoice;
            break;
        }

        foreach ($order->getCreditmemosCollection() as $creditmemo)
        {
            $templateVars['creditmemo'] = $creditmemo;
            break;
        }
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        $CustomerModel = $objectManager->create('Magento\Customer\Model\Customer');
        $CustomerModel->setWebsiteId($storeId); //Here 1 means Store ID**
        $CustomerModel->loadByEmail('andy.eades@elevateweb.co.uk');


        $templateVars['customerName'] = "andy";

        $product = $objectManager->create('Magento\Catalog\Model\Product')->load(176);
        $alertGrid = $this->getProductPriceHtml(
            $product, \Magento\Catalog\Pricing\Price\FinalPrice::PRICE_CODE, \Magento\Framework\Pricing\Render::ZONE_EMAIL, [
                        'display_label' => __('Price:')
                    ]
        );
        $templateVars['alertGrid'] = $alertGrid;


        $templateVars['product'] = $product;
        $templateVars['store'] = $order->getStore();
        
       // if($shipment){
       // $templateVars['comment'] = '';// $shipment->getCustomerNoteNotify() ? $shipment->getCustomerNote() : '';
         //                        }
           //                      else{
                                  $templateVars['comment'] = '';
             //                    }
        $templateVars['billing'] = $order->getBillingAddress();
        $templateVars['payment_html'] = $this->getPaymentHtml($order);
        $templateVars['formattedShippingAddress'] = $this->getFormattedShippingAddress($order);
        $templateVars['formattedBillingAddress'] = $this->getFormattedBillingAddress($order);


        try {

            $html = $extendedTransportBuilder
                ->setTemplateIdentifier($template_code)
                ->setTemplateOptions($templateOptions)
                ->setTemplateVars($templateVars)
                ->setFrom($sender)
                ->addTo('andy.eades@elevateweb.co.uk', 'Receiver Name')

                ->getTransportHtml();

        } catch (\Exception $e) {
            echo "No Template";
            exit;
        }
        return $html;


    }

    /**
     * Get either default or any store view
     *
     * @return \Magento\Store\Model\Store|null
     */
    protected function getAnyStoreView()
    {
        $store = $this->_storeManager->getDefaultStoreView();
        if ($store) {
            return $store;
        }
        foreach ($this->_storeManager->getStores() as $store) {
            return $store;
        }
        return null;
    }
}
