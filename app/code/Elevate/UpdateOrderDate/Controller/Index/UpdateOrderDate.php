<?php

namespace Elevate\UpdateOrderDate\Controller\Index;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Checkout\Model\Cart as CustomerCart;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class UpdateOrderDate extends \Magento\Checkout\Controller\Cart
{
    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;


    /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator
     * @param CustomerCart $cart
     * @param ProductRepositoryInterface $productRepository
     * @codeCoverageIgnore
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator,
        CustomerCart $cart,
        ProductRepositoryInterface $productRepository
    ) {
        parent::__construct(
            $context,
            $scopeConfig,
            $checkoutSession,
            $storeManager,
            $formKeyValidator,
            $cart
        );
        $this->productRepository = $productRepository;
    }

    /**
     * Initialize product instance from request data
     *
     * @return \Magento\Catalog\Model\Product|false
     */
    protected function _initProduct()
    {
        $productId = (int)$this->getRequest()->getParam('product');
        if ($productId) {
            $storeId = $this->_objectManager->get('Magento\Store\Model\StoreManagerInterface')->getStore()->getId();
            try {
                return $this->productRepository->getById($productId, false, $storeId);
            } catch (NoSuchEntityException $e) {
                return false;
            }
        }
        return false;
    }

    /**
     * Add product to shopping cart action
     *
     * @return \Magento\Framework\Controller\Result\Redirect
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function execute()
    {




$orderId = $this->getRequest()->getParam('order_id');


$orderId = 94791;
$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
$order = $objectManager->create('Magento\Sales\Api\Data\OrderInterface')->load($orderId);

$allowed_update = true;

if($allowed_update){


$date_input_cm = strtotime('2019-11-23');
$date_input = date('Y-m-d', $date_input_cm);
$nice_date_input = date('l jS F Y', $date_input_cm);

$order->setData('delivery_date_selected', $date_input);






$order->setData('detailed_delivery_info_dates', $nice_date_input);

try{
$order->save();


}
catch(Exception $e){

print_r($e->getMessage());

}


}
else{

$message = 'You cannot update an order 1 hour after the order was placed, please contact customer service for any changes required.';
}

exit;
    }

    /**
     * Cart response
     *
     * @param array $result
     * @return $this
     */
    protected function cartResponse($result = '')
    {
     
    }
}