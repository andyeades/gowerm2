<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Braintree\Gateway\Http\Client;

use Braintree\Result\Error;
use Braintree\Result\Successful;
use Exception;
use Magento\Braintree\Model\Adapter\BraintreeAdapter;
use Magento\Payment\Gateway\Http\ClientException;
use Magento\Payment\Gateway\Http\ClientInterface;
use Magento\Payment\Gateway\Http\TransferInterface;
use Magento\Payment\Model\Method\Logger;
use Psr\Log\LoggerInterface;

/**
 * Class AbstractTransaction
 */
abstract class AbstractTransaction implements ClientInterface
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var Logger
     */
    protected $customLogger;

    /**
     * @var BraintreeAdapter
     */
    protected $adapter;

    /**
     * Constructor
     *
     * @param LoggerInterface $logger
     * @param Logger $customLogger
     * @param BraintreeAdapter $adapter
     */
    public function __construct(LoggerInterface $logger, Logger $customLogger, BraintreeAdapter $adapter)
    {
        $this->logger = $logger;
        $this->customLogger = $customLogger;
        $this->adapter = $adapter;
    }

    /**
     * @inheritdoc
     */
    public function placeRequest(TransferInterface $transferObject)
    {
        $data = $transferObject->getBody();
        $log = [
            'request' => $data,
            'client' => static::class
        ];
        $response['object'] = [];

        try {
            $response['object'] = $this->process($data);
        } catch (Exception $e) {
        
        
               $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/andy_paymentaz.log');
$logger = new \Zend\Log\Logger();
$logger->addWriter($writer);
$logger->info($e->getMessage());
$logger->info(print_r($data, true));
     
        
            $message = __($e->getMessage() ?: 'Sorry, but something went wrong');
            $this->logger->critical($message);
            throw new ClientException($message);
        } finally {
            $log['response'] = (array) $response['object'];
            $this->customLogger->debug($log);
        }

        return $response;
    }

    /**
     * Process http request
     * @param array $data
     * @return Error|Successful
     */
    abstract protected function process(array $data);
}
