<?php
declare(strict_types=1);
/**
 * Copyright © 2017 Firebear Studio. All rights reserved.
 */

namespace Firebear\ConfigurableProducts\Plugin\Controller\Checkout\Cart;

use Exception;
use Firebear\ConfigurableProducts\Helper\Data as IcpHelper;
use Firebear\ConfigurableProducts\Model\ProcessProductOptions;
use Firebear\ConfigurableProducts\Model\ProductOptionsRepository;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Product\Option;
use Magento\Catalog\Model\Product\Option\Repository;
use Magento\Catalog\Model\ProductRepository;
use Magento\Checkout\Helper\Cart;
use Magento\Checkout\Model\Cart as CustomerCart;
use Magento\Checkout\Model\CartFactory;
use Magento\Checkout\Model\Session;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Data\Form\FormKey\Validator;
use Magento\Framework\Escaper;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Locale\ResolverInterface;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;
use Zend_Filter_LocalizedToNormalized;

/**
 * @deprecated
 * Class Add
 * @package Firebear\ConfigurableProducts\Plugin\Controller\Checkout\Cart
 */
class Add extends \Magento\Checkout\Controller\Cart\Add
{

    /**
     * @var IcpHelper
     */
    public $icpHelper;
    /**
     * @var Configurable
     */
    public $configurableProducts;
    /**
     * Serializer interface instance.
     *
     * @var Json
     * @since 101.1.0
     */
    protected $serializer;
    /**
     * @var CartFactory
     */
    private $cartFactory;
    /**
     * @var Repository
     */
    private $optionRepository;
    /**
     * @var ProductOptionsRepository
     */
    private $productOptionsRepository;
    /**
     * @var Option
     */
    private $optionModel;

    /**
     * Add constructor.
     * @param Context $context
     * @param ScopeConfigInterface $scopeConfig
     * @param Session $checkoutSession
     * @param StoreManagerInterface $storeManager
     * @param Validator $formKeyValidator
     * @param CustomerCart $cart
     * @param ProductRepository $productRepository
     * @param CartFactory $cartFactory
     * @param Option $optionModel
     * @param Repository $optionRepository
     * @param IcpHelper $icpHelper
     * @param Configurable $configurableProducts
     * @param ProductOptionsRepository $productOptionsRepository
     * @param Json|null $serializer
     */
    public function __construct(
        Context $context,
        ScopeConfigInterface $scopeConfig,
        Session $checkoutSession,
        StoreManagerInterface $storeManager,
        Validator $formKeyValidator,
        CustomerCart $cart,
        ProductRepository $productRepository,
        CartFactory $cartFactory,
        Option $optionModel,
        Repository $optionRepository,
        IcpHelper $icpHelper,
        Configurable $configurableProducts,
        ProductOptionsRepository $productOptionsRepository,
        Json $serializer = null
    ) {
        $this->cartFactory = $cartFactory;
        $this->optionRepository = $optionRepository;
        $this->optionModel = $optionModel;
        $this->icpHelper = $icpHelper;
        $this->serializer = $serializer
            ?: ObjectManager::getInstance()
                ->get(Json::class);
        $this->configurableProducts = $configurableProducts;
        $this->productOptionsRepository = $productOptionsRepository;

        parent::__construct(
            $context,
            $scopeConfig,
            $checkoutSession,
            $storeManager,
            $formKeyValidator,
            $cart,
            $productRepository
        );
    }

    /**
     * @see \Firebear\ConfigurableProducts\Plugin\Model\Quote\Quote::aroundAddProduct()
     * @deprecated
     * @param \Magento\Checkout\Controller\Cart\Add $subject
     * @param callable $proceed
     * @return Add|\Magento\Framework\Controller\Result\Redirect
     * @throws NoSuchEntityException
     */
    public function aroundExecute(\Magento\Checkout\Controller\Cart\Add $subject, callable $proceed)
    {
        $product = null;
        if (!$subject->_formKeyValidator->validate($subject->getRequest())) {
            return $subject->resultRedirectFactory->create()->setPath('*/*/');
        }
        $params = $this->getRequest()->getParams();

        $product = $this->productRepository->getById($this->getRequest()->getParam('product'));
        $displayMatrixForCurrentProduct =
            $this->productOptionsRepository->getByProductId($product->getId())->getDisplayMatrix();

        $matrixSwatch = $this->_scopeConfig->getValue(
            'firebear_configurableproducts/matrix/matrix_swatch',
            ScopeInterface::SCOPE_STORE
        );
        if (!$displayMatrixForCurrentProduct) {
            $displayMatrixForCurrentProduct = $matrixSwatch;
        }
        $matrixSwatch = ($matrixSwatch && $displayMatrixForCurrentProduct == 1) ? 1 : 0;
        if ($product->getTypeId() == 'bundle') {
            if (!$this->_formKeyValidator->validate($this->getRequest())) {
                return $this->resultRedirectFactory->create()->setPath('*/*/');
            }

            $params = $this->getRequest()->getParams();
            try {
                if (isset($params['qty'])) {
                    $filter = new Zend_Filter_LocalizedToNormalized(
                        [
                            'locale' => $this->_objectManager->get(
                                ResolverInterface::class
                            )->getLocale()
                        ]
                    );
                    $params['qty'] = $filter->filter($params['qty']);
                }

                $product = $this->_initProduct();
                $related = $this->getRequest()->getParam('related_product');

                /**
                 * Check product availability
                 */
                if (!$product) {
                    return $this->goBack();
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

                if (!$this->_checkoutSession->getNoCartRedirect(true)) {
                    if (!$this->cart->getQuote()->getHasError()) {
                        $message = __(
                            'You added %1 to your shopping cart.',
                            $product->getName()
                        );
                        $this->messageManager->addSuccessMessage($message);
                    }
                    return $this->goBack(null, $product);
                }
            } catch (LocalizedException $e) {
                if ($this->_checkoutSession->getUseNotice(true)) {
                    $this->messageManager->addNotice(
                        $this->_objectManager->get(Escaper::class)->escapeHtml($e->getMessage())
                    );
                } else {
                    $messages = array_unique(explode("\n", $e->getMessage()));
                    foreach ($messages as $message) {
                        $this->messageManager->addError(
                            $this->_objectManager->get(Escaper::class)->escapeHtml($message)
                        );
                    }
                }

                $url = $this->_checkoutSession->getRedirectUrl(true);

                if (!$url) {
                    $cartUrl = $this->_objectManager->get(Cart::class)->getCartUrl();
                    $url = $this->_redirect->getRedirectUrl($cartUrl);
                }

                return $this->goBack($url);

            } catch (Exception $e) {
                $this->messageManager->addException($e, __('We can\'t add this item to your shopping cart right now.'));
                $this->_objectManager->get(LoggerInterface::class)->critical($e);

                return $this->goBack();
            }
        }
        if ($product->getTypeId() == 'simple' || $product->getTypeId() == 'virtual'
            || $product->getTypeId() == 'downloadable'
            || $product->getTypeId() == 'grouped'
            || (!isset($params['qty_matrix_product'])
                && !isset($params['options'])
                && !$matrixSwatch)) {
            return $proceed();

        } elseif ($product->getTypeId() == 'simple' || $product->getTypeId() == 'virtual'
            || $product->getTypeId() == 'downloadable'
            || $product->getTypeId() == 'grouped'
            || (!isset($params['qty_matrix_product'])
                && !isset($params['options'])
                && $matrixSwatch)) {
            return $proceed();

        } elseif (isset($params['options']) && !isset($params['qty_matrix_product']) && !$matrixSwatch) {
            try {
                if (isset($params['qty'])) {
                    $filter = new Zend_Filter_LocalizedToNormalized(
                        [
                            'locale' => $this->_objectManager->get(
                                ResolverInterface::class
                            )->getLocale()
                        ]
                    );
                    $params['qty'] = $filter->filter($params['qty']);
                }

                $product = $this->_initProduct();
                $related = $this->getRequest()->getParam('related_product');

                /**
                 * Check product availability
                 */
                if (!$product) {
                    return $this->goBack();
                }
                if (isset($params['options'])) {
                    $this->addCustomizibleOpionsToProduct($params, $product);
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

                if (!$this->_checkoutSession->getNoCartRedirect(true)) {
                    if (!$this->cart->getQuote()->getHasError()) {
                        $message = __(
                            'You added %1 to your shopping cart.',
                            $product->getName()
                        );
                        $this->messageManager->addSuccessMessage($message);
                    }

                    return $this->goBack(null, $product);
                }
            } catch (LocalizedException $e) {
                if ($this->_checkoutSession->getUseNotice(true)) {
                    $this->messageManager->addNotice(
                        $this->_objectManager->get(Escaper::class)->escapeHtml($e->getMessage())
                    );
                } else {
                    $messages = array_unique(explode("\n", $e->getMessage()));
                    foreach ($messages as $message) {
                        $this->messageManager->addError(
                            $this->_objectManager->get(Escaper::class)->escapeHtml($message)
                        );
                    }
                }

                $url = $this->_checkoutSession->getRedirectUrl(true);

                if (!$url) {
                    $cartUrl = $this->_objectManager->get(Cart::class)->getCartUrl();
                    $url = $this->_redirect->getRedirectUrl($cartUrl);
                }

                return $this->goBack($url);
            } catch (Exception $e) {
                $this->messageManager->addException($e, __('We can\'t add this item to your shopping cart right now.'));
                $this->_objectManager->get(LoggerInterface::class)->critical($e);

                return $this->goBack();
            }
        } elseif ($product->getTypeId() != 'bundle') {
            try {
                $cartModel = $this->cartFactory->create();
                $errorFlag = true;
                foreach ($params['qty_matrix_product'] as $optionId => $matrixProductValue) {
                    foreach ($matrixProductValue as $valueId => $qtyProduct) {
                        if ($qtyProduct <= 0) {
                            continue;
                        } else {
                            $errorFlag = false;
                        }

                        $filter = new Zend_Filter_LocalizedToNormalized(
                            [
                                'locale' => $subject->_objectManager->get('Magento\Framework\Locale\ResolverInterface')
                                    ->getLocale()
                            ]
                        );

                        $params['qty'] = $filter->filter($qtyProduct);
                        $params['super_attribute'][$optionId] = '' . $valueId . '';
                        $storeId = $this->_objectManager->get('Magento\Store\Model\StoreManagerInterface')->getStore()->getId();
                        $product = $this
                            ->_objectManager
                            ->create('Magento\Catalog\Model\Product')
                            ->setStoreId($storeId)
                            ->load($params['product']);
                        $idSuperAttributesArray = [];
                        $childProduct = $this->configurableProducts->getProductByAttributes(
                            $params['super_attribute'],
                            $product
                        );
                        foreach ($params['super_attribute'] as $key => $value) {
                            $idSuperAttributesArray[] = $key;
                        }
                        arsort($params['super_attribute']);

                        $this->getRequest()->setParams($params);

                        /**
                         * Check product availability
                         */
                        if (!$product) {
                            return $subject->goBack();
                        }

                        if (isset($params['options'])) {
                            if (!isset($paramsForNextProduct)) {
                                $paramsForNextProduct = $params;
                            }
                            $paramsForNextProduct = $this->addCustomizibleOpionsToProduct(
                                $paramsForNextProduct,
                                $product,
                                $childProduct->getId(),
                                $matrixSwatch
                            );
                        }
                        $this->cart->addProduct($product, $params);

                        /**
                         * @todo remove wishlist observer \Magento\Wishlist\Observer\AddToCart
                         */
                        $subject->_eventManager->dispatch(
                            'checkout_cart_add_product_complete',
                            [
                                'product' => $product,
                                'request' => $subject->getRequest(),
                                'response' => $subject->getResponse()
                            ]
                        );

                        if (!$subject->_checkoutSession->getNoCartRedirect(true)) {
                            if (!$subject->cart->getQuote()->getHasError()) {
                                $message = __(
                                    'You added %1 to your shopping cart.',
                                    $product->getName()
                                );
                                $subject->messageManager->addSuccessMessage($message);
                            }
                        }
                    }
                }
                $cartModel->save();
            } catch (LocalizedException $e) {
                if ($subject->_checkoutSession->getUseNotice(true)) {
                    $subject->messageManager->addNotice(
                        $subject->_objectManager->get('Magento\Framework\Escaper')->escapeHtml($e->getMessage())
                    );
                } else {
                    $messages = array_unique(explode("\n", $e->getMessage()));
                    foreach ($messages as $message) {
                        $subject->messageManager->addError(
                            $subject->_objectManager->get('Magento\Framework\Escaper')->escapeHtml($message)
                        );
                    }
                }

                $url = $subject->_checkoutSession->getRedirectUrl(true);

                if (!$url) {
                    $cartUrl = $subject->_objectManager->get('Magento\Checkout\Helper\Cart')->getCartUrl();
                    $url = $subject->_redirect->getRedirectUrl($cartUrl);
                }

                return $subject->goBack($url);

            } catch (Exception $e) {
                $subject->messageManager->addException(
                    $e,
                    __('We can\'t add this item to your shopping cart right now.') . $e->getMessage()
                );
                $subject->_objectManager->get('Psr\Log\LoggerInterface')->critical($e);

                return $subject->goBack();
            }
            if (!$product || $errorFlag) {
                $subject->messageManager->addWarning(
                    __('Qty not specified for any product.')
                );

                return $subject->goBack();
            }

            return $subject->goBack(null, $product);
        }
    }

    /**
     * @param $params
     * @param Product $product
     * @param $simpleProductId
     * @param bool $matrixSwatch
     * @return
     * @throws NoSuchEntityException
     */
    protected function addCustomizibleOpionsToProduct(
        &$params,
        &$product,
        $simpleProductId = null,
        $matrixSwatch = false
    ) {
        /** @var ProcessProductOptions $process */
        $process = $this->_objectManager->create(ProcessProductOptions::class);
        return $process->addCustomizibleOpionsToProduct($params, $product, $simpleProductId, $matrixSwatch);
    }
}
