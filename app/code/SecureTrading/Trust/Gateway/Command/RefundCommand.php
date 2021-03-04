<?php

namespace SecureTrading\Trust\Gateway\Command;

use Magento\Framework\App\ObjectManager;
use Magento\Payment\Gateway\Command\CommandPoolInterface;
use Magento\Payment\Gateway\ConfigInterface;
use Magento\Payment\Gateway\ErrorMapper\ErrorMessageMapperInterface;
use Magento\Payment\Gateway\Http\TransferFactoryInterface;
use Magento\Payment\Gateway\Request\BuilderInterface;
use Magento\Payment\Gateway\Response\HandlerInterface;
use Magento\Payment\Gateway\Validator\ValidatorInterface;
use SecureTrading\Trust\Helper\Logger\Logger;
/**
 * Class RefundCommand
 *
 * @package SecureTrading\Trust\Gateway\Command
 */
class RefundCommand extends AbstractCommand
{
    /**
     * @var CommandPoolInterface
     */
    protected $commandPool;

    /**
     * CaptureCommand constructor.
     * @param BuilderInterface $requestBuilder
     * @param TransferFactoryInterface $transferFactory
     * @param Logger $logger
     * @param HandlerInterface $handler
     * @param ValidatorInterface $validator
     * @param ErrorMessageMapperInterface|null $errorMessageMapper
     * @param ConfigInterface $config
     * @param CommandPoolInterface $commandPool
     */
    public function __construct(
        BuilderInterface $requestBuilder,
        TransferFactoryInterface $transferFactory,
        Logger $logger,
        ConfigInterface $config,
        CommandPoolInterface $commandPool,
        HandlerInterface $handler = null,
        ValidatorInterface $validator = null,
        ErrorMessageMapperInterface $errorMessageMapper = null
    ) {
        parent::__construct($requestBuilder,$transferFactory,$logger,$config,$handler,$validator,$errorMessageMapper);
        $this->commandPool = $commandPool;
    }

    public function execute(array $commandSubject)
    {
        $data =  $this->requestBuilder->build($commandSubject);

        $transactionDetail = $this->transferFactory->create($data['detail']);
        $transactionDetailData = $transactionDetail->getSingle('responses')->getSingle(0)->getSingle('records')->getSingle(0)->getAll();
        $this->logger->debug('--- RESPONSE TRANSACTION DETAIL :', array($transactionDetailData));
        $this->logger->debug('--- RESPONSE SETTLE STATUS : '.$transactionDetailData['settlestatus'].' ---');
        if(isset($transactionDetailData['settlestatus']) && $transactionDetailData['settlestatus'] == 100){
            $response = $this->transferFactory->create($data['refund']);
            //Validate error code
            if ($this->validator !== null) {
                $result = $this->validator->validate(
                    array_merge($commandSubject, ['response' => $response])
                );
                if (!$result->isValid()) {
                    $this->processErrors($result);
                }
            }

            if ($this->handler) {
                $this->handler->handle(
                    $commandSubject,
                    ['data' => $response['responses'][0]]
                );
            }
        }
        elseif(isset($transactionDetailData['settlestatus']) && in_array($transactionDetailData['settlestatus'],[0,1,10])){
              $this->voidTransaction($commandSubject);
        }
    }


    /**
     * @param $commandSubject
     * @throws \Magento\Framework\Exception\NotFoundException
     * @throws \Magento\Payment\Gateway\Command\CommandException
     * @return void
     */
    protected function voidTransaction($commandSubject){
        $paymentPO = $commandSubject['payment'];
        $payment = $paymentPO->getPayment();
        $order   = $payment->getOrder();

        $this->commandPool->get('void')->execute($commandSubject);
        $order->addStatusHistoryComment(__('We voided the transaction because it has not settled yet.'));

        $order->save();

    }
}