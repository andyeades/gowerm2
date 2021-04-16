<?php

namespace Elevate\AddToCart\Controller\Cart;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Checkout\Model\Cart as CustomerCart;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Add extends \Magento\Checkout\Controller\Cart
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


        if (!$this->_formKeyValidator->validate($this->getRequest())) {
           // return $this->resultRedirectFactory->create()->setPath('*/*/');
        }
                  
        $params = $this->getRequest()->getParams();
        $result = [];
        try {
            if (isset($params['qty'])) {
                $filter = new \Zend_Filter_LocalizedToNormalized(
                    ['locale' => $this->_objectManager->get('Magento\Framework\Locale\ResolverInterface')->getLocale()]
                );
                $params['qty'] = $filter->filter($params['qty']);
            }

            $product = $this->_initProduct();
            $related = $this->getRequest()->getParam('related_product');

            /**
             * Check product availability
             */
            if (!$product) {
                $result['message'] =  __('Product Error');
                $result['error'] = 1;
                return $this->cartResponse($result);
            }

            $this->cart->addProduct($product, $params);
            if (!empty($related)) {
                $this->cart->addProductsByIds(explode(',', $related));
            }

            $this->cart->save();

            /**
             * @todo remove wishlist observer \Magento\Wishlist\Observer\AddToCart
             */
            $this->_eventManager->dispatch(
                'checkout_cart_add_product_complete',
                ['product' => $product, 'request' => $this->getRequest(), 'response' => $this->getResponse()]
            );


            
            //TODO: potential speed optimisation here - no idea why magento team was assessing the stock before outputting the reponse
            //the item had already been added to the quote with no error!
            //needs investigation & update of another overcomplicated magento implementation.
            
                if (!$this->cart->getQuote()->getHasError()) {


                    $imageUrl = $product->getData('image');
                    $message = '<div class="row"><div class="col-3"><img src="/media/catalog/product'.$imageUrl.'" /></div>';
                    $message .= __(
                        '<div class="col-9" style="display: flex;align-items: center;">You added %1 to your shopping basket.</div></div>',
                        $product->getName()
                    );
                 //   $this->messageManager->addSuccessMessage($message);

                    $result['message'] =  $message;
                    $result['success'] = 1;

                }
                 else{
                  $result['message'] =  'This item cannot be added to the basket';
                $result['error'] = 1;
                 }
                
                
           //lets carry on doing this - why did magento decide to do this - did they hit some sort of issue or race condition??     
            if ($product && !$product->getIsSalable()) {
                $result['message'] =  __('Out of stock');
                $result['error'] = 1;
            }

             
                return $this->cartResponse($result);

        } catch (\Magento\Framework\Exception\LocalizedException $e) {

            $result['message'] =  $e->getMessage();
            $result['error'] = 1;

            if ($this->_checkoutSession->getUseNotice(true)) {
                $this->messageManager->addNotice(
                    $this->_objectManager->get('Magento\Framework\Escaper')->escapeHtml($e->getMessage())
                );
            } else {
                $messages = array_unique(explode("\n", $e->getMessage()));
                foreach ($messages as $message) {
                    $this->messageManager->addError(
                        $this->_objectManager->get('Magento\Framework\Escaper')->escapeHtml($message)
                    );
                }
            }

//            $url = $this->_checkoutSession->getRedirectUrl(true);

  //          if (!$url) {
    //            $cartUrl = $this->_objectManager->get('Magento\Checkout\Helper\Cart')->getCartUrl();
      //          $url = $this->_redirect->getRedirectUrl($cartUrl);
      //      }

            return $this->cartResponse($result);

        } catch (\Exception $e) {
         //   $this->messageManager->addException($e, __('We can\'t add this item to your shopping cart right now.'));
         //   $this->_objectManager->get('Psr\Log\LoggerInterface')->critical($e);
            $result['message'] =  __('We cannot add this item to your shopping cart right now.'.$e->getMessage());
            $result['error'] = 1;
            return $this->cartResponse($result);
        }
    }

    /**
     * Cart response
     *
     * @param array $result
     * @return $this
     */
    protected function cartResponse($result = '')
    {
        $this->getResponse()->representJson(
            $this->_objectManager->get('Magento\Framework\Json\Helper\Data')->jsonEncode($result)
        );
    }
}