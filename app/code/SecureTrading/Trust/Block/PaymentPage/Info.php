<?php

namespace SecureTrading\Trust\Block\PaymentPage;

use Magento\Framework\Phrase;
use Magento\Payment\Block\ConfigurableInfo;

class Info extends ConfigurableInfo
{
	/**
	 * Returns label
	 *
	 * @param string $field
	 * @return Phrase
	 */
	protected function getLabel($field)
	{
		switch ($field) {
			case 'errorcode':
				return __("Error Code");
			case 'orderreference':
				return __("Order Reference");
			case 'paymenttypedescription':
				return __("Payment Type Description");
			case 'requestreference':
				return __("Request Reference");
			case 'settlestatus':
				return __("Settle Status");
			case 'sitereference':
				return __("Site Reference");
			case 'transactionreference':
				return __("Transaction Reference");
			case 'notificationreference':
				return __("Notification Reference");
			case 'accounttypedescription':
				return __("Account Type Description");
			case 'enrolled':
				return __("3D Enrolled");
			case 'status':
				return __("3D Status");
			case 'maskedpan':
				return __("Card Digits");
			case 'authcode':
				return __("Auth Code");
			case 'securityresponsepostcode':
				return __("Security Response Postcode");
			case 'securityresponseaddress':
				return __("Security Response Address");
			case 'securityresponsesecuritycode':
				return __("Security Response Security Code");
			case 'walletid':
				return __("Wallet Id");
			case 'walletsource':
				return __("Wallet Source");
			case 'parentorderid':
				return __("Parent Order Id");
			case 'parenttransactionid':
				return __("Parent Transaction Id");
			case 'subscriptionid':
				return __("Subscription Id");
			case 'subscriptiontype':
				return __("Subscription Type");
			case 'subscriptionunit':
				return __("Subscription Unit");
			case 'subscriptionfrequency':
				return __("Subscription Frequency");
			case 'subscriptionfinalnumber':
				return __("Subscription Finalnumber");
			case 'subscriptionamount':
				return __("Subscription Amount");
			case 'paypalemail':
				return __("PayPal Email");
			case 'paypalpayerid':
				return __("PayPal Payer ID");
			default:
				return __($field);
		}
	}

	/**
	 * Returns value view
	 *
	 * @param string $field
	 * @param string $value
	 * @return string | Phrase
	 */
	protected function getValueView($field, $value)
	{
		if($field == 'maskedpan'){
			if($this->getIsSecureMode()){
				return "############".substr($value,12,4);
			}
		}
		return (string)parent::getValueView($field, $value); // TODO: Change the autogenerated stub
	}

	public function getValueAsArray($value, $escapeHtml = false)
	{
		if (empty($value) && $value !== "0") {
			return [];
		}
		if (!is_array($value)) {
			$value = [$value];
		}
		if ($escapeHtml) {
			foreach ($value as $_key => $_val) {
				$value[$_key] = $this->escapeHtml($_val);
			}
		}
		return $value;
	}

	public function getIsSecureMode(){
		if($this->_appState->getAreaCode() == \Magento\Framework\App\Area::AREA_ADMINHTML){
			return false;
		}
		return true;
	}
}