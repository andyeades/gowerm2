<?php

namespace Punchout2go\Purchaseorder\Controller\Index;

use Magento\Framework\Registry;
use Magento\Framework\App\CsrfAwareActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Request\InvalidRequestException;
use Magento\Framework\App\Action\HttpPostActionInterface as HttpPost;

class Index extends \Magento\Framework\App\Action\Action implements HttpPost,CsrfAwareActionInterface
{
    protected $resultPageFactory;
    protected $orderRequestFactory;

    /** @var \Magento\Framework\Registry $registry */
    protected $registry;

    /** @var \Punchout2go\Purchaseorder\Helper\Data $helper */
    protected $helper;

    /**
     * Index constructor.
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Punchout2go\Purchaseorder\Model\Order\Request $orderRequestFactory
     * @param \Magento\Framework\Registry $registry
     * @param \Punchout2go\Purchaseorder\Helper\Data $helper
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Punchout2go\Purchaseorder\Model\Order\Request $orderRequestFactory,
        \Magento\Framework\Registry $registry,
        \Punchout2go\Purchaseorder\Helper\Data $helper
    ){
        $this->resultFactory = $context->getResultFactory();
        $this->orderRequestFactory = $orderRequestFactory;
        $this->registry = $registry;
        $this->helper = $helper;
        $this->registry->register('is_po2go_purchase_order', true);
        parent::__construct($context);
    }

    public function execute()
    {
        /** @var Punchout2go\Purchaseorder\Helper\Data $helper */
        $helper = $this->helper;

        /** @var \Punchout2go\Purchaseorder\Model\Order\Request $orderRequest */
        $orderRequest = $this->orderRequestFactory;

        /** @var array $post */
        $post = $this->getRequest()->getPostValue();

        $params = null;
        $orderRequestType = null;

        try {
            if (!isset($post['params'])) {
                throw new \Exception('This request requires order data.', 100);
            }
            $helper->debug(print_r($post['params'], true));

            if (null == json_decode($post['params'])) {
                throw new \Exception('This request is not JSON.', 100);
            }
        }
        catch (\Exception $e) {
            $helper->debug("Error with order request // ". $e->getMessage());
            return $this->sendResponse('Error : ' . $e->getMessage(), false);
        }

        $apiKey = null;
        $params = json_decode($post['params'], true);
        /** @var string $apiKey */
        if (array_key_exists('api_key', $params)) {
            $apiKey = $params['api_key'];
        }

        if (null == $apiKey) {
            return $this->sendResponse('Error : no API key provided', false);
        }

        /*
         * Authentication
         */
        if ($helper->isAuthenticatedByApiKey($apiKey) !== true) {
            $response = 'Error : API key is not valid';
            return $this->sendResponse($response, false);
        }

        // load params into orderRequest object
        $orderRequest->setDocument($post['params']);

        try {
            $isValid = $orderRequest->isValid();
            if ($isValid) {
                /** @var \Magento\Sales\Model\Order $order */
                $order = $orderRequest->createOrder();
            }
        } catch (\Exception $e) {
            $helper->debug("Error with order request // ". $e->getMessage());
            return $this->sendResponse('Error : ' . $e->getMessage(), false);
        }

        return $this->sendResponse($order->getRealOrderId());
    }

    /**
     * @param $response
     * @return mixed
     */
    public function sendResponse($response, $success = true)
    {
        $result = $resultRedirect = $this->resultFactory->create(
            \Magento\Framework\Controller\ResultFactory::TYPE_RAW
        );

        $statusCode = '200';
        if ($success == false) {
            $statusCode = \Magento\Framework\Webapi\Exception::HTTP_BAD_REQUEST;
        }

        return $result
            ->setHeader('Content-Type','text/plain')
            ->setHttpResponseCode($statusCode)
            ->setContents($response);
    }

    public function createCsrfValidationException(RequestInterface $request): ?InvalidRequestException
    {
        return null;
    }

    public function validateForCsrf(RequestInterface $request): ?bool
    {
        return true;
    }

}