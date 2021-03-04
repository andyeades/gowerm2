<?php

namespace SecureTrading\Trust\Plugin\Controller\Adminhtml\Order\Create;

/**
 * Class Save
 * @package SecureTrading\Trust\Plugin\Controller\Adminhtml\Order\Create
 */
class Save
{
    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $_request;
    /**
     * @var \Magento\Framework\Registry
     */
    protected $_registry;

    /**
     * Save constructor.
     * @param \Magento\Framework\App\RequestInterface $request
     * @param \Magento\Framework\Registry $registry
     */
    public function __construct(\Magento\Framework\App\RequestInterface $request,
                                \Magento\Framework\Registry $registry)
    {
        $this->_request = $request;
        $this->_registry = $registry;
    }

    /**
     * @param \Magento\Sales\Controller\Adminhtml\Order\Create\Save $subject
     * @param $result
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function afterExecute(\Magento\Sales\Controller\Adminhtml\Order\Create\Save $subject, $result){
        $tempPaymentData = $this->getRequest()->getParam('payment');

        $orderId = $this->_registry->registry('order_id');
        $this->_registry->unregister('order_id');

        if($result instanceof \Magento\Framework\Controller\Result\Redirect && !$result instanceof \Magento\Framework\Controller\Result\Forward){
            if ($tempPaymentData['method'] == 'secure_trading') {
                return $result->setPath('securetrading/paymentpage/adminredirect', ['order_id' => $orderId]);
            }
            return $result;
        }
        return $result;
    }

    /**
     * @return \Magento\Framework\App\RequestInterface
     */
    public function getRequest()
    {
        return $this->_request;
    }
}