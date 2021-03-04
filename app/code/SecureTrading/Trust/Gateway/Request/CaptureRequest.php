<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace SecureTrading\Trust\Gateway\Request;

use Magento\Payment\Gateway\ConfigInterface;
use Magento\Payment\Gateway\Data\PaymentDataObjectInterface;
use Magento\Payment\Gateway\Request\BuilderInterface;
use SecureTrading\Trust\Helper\Data;
use SecureTrading\Trust\Helper\Logger\Logger;


/**
 * Class CaptureRequest
 * @package SecureTrading\Trust\Gateway\Request
 */
class CaptureRequest implements BuilderInterface
{

    /**
     * @var ConfigInterface
     */
    private $config;

    /**
     * @var Logger
     */
    private $logger;
    /**
     * CaptureRequest constructor.
     * @param ConfigInterface $config
     * @param Logger $logger
     */
    public function __construct(
        ConfigInterface $config,
        Logger $logger
    ) {
        $this->logger = $logger;
        $this->config = $config;
    }

    /**
     * @param array $buildSubject
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function build(array $buildSubject)
    {
        if (!isset($buildSubject['payment'])
            || !$buildSubject['payment'] instanceof PaymentDataObjectInterface
        ) {
            throw new \InvalidArgumentException('Payment data object should be provided');
        }

        /** @var PaymentDataObjectInterface $paymentDO */
        $paymentDO = $buildSubject['payment'];

        $payment = $paymentDO->getPayment();
        $data = [];
		if ($this->config->getValue(Data::BACK_OFFICE) == 0) {
			throw new \Magento\Framework\Exception\LocalizedException(__('Back-Office is required.'));
		}
        $data['configData'] = array(
            'username' => $this->config->getValue(Data::USER_NAME),
            'password' => $this->config->getValue(Data::PASSWORD),
        );

        $data['requestData'] = array(
            'requesttypedescriptions' => array('TRANSACTIONUPDATE'),
            'filter' => array(
                'sitereference' => array(array('value' => $this->config->getValue(Data::SITE_REFERENCE))),
                'transactionreference' => array(array('value' => $payment->getAdditionalInformation('transactionreference')))
            ),
            'updates' => array('settlestatus' => '0')
        );
        $this->logger->debug('--- ORDER INCREMENT ID: '. $payment->getOrder()->getIncrementId() .'---');
        $this->logger->debug('--- PREPARE DATA TO CAPTURE:', $data['requestData']);
        return $data;
    }
}