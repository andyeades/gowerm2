<?php

namespace SecureTrading\Trust\Block\PaymentPage;

use SecureTrading\Trust\Helper\Data;

class Raw extends Iframe
{
    public function getRedirectUrl()
    {   if(!$this->config->getValue(Data::SKIP_CHOICE_PAGE)) {
        return $this->config->getValue(Data::CHOICE_PAGE);
    }
        return $this->config->getValue(Data::DETAILS_PAGE);
    }

	public function getRedirectData()
	{
		$orderId = $this->getRequest()->getParam('orderId', null);
		$order   = $this->orderFactory->create()->load($orderId);
            if(!$order || !$order->getId()){
            
            $this->_redirect('checkout#payment');
    
  
    }
		$info    = $order->getPayment()->getAdditionalInformation('secure_trading_data');

		return (array)$info;
	}
}