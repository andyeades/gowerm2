<?php

namespace SecureTrading\Trust\Gateway\Command;

use Magento\Framework\Exception\LocalizedException;
use Magento\Payment\Gateway\Command\CommandException;
use SecureTrading\Trust\Helper\Data;
/**
 * Class CancelCommand
 *
 * @package SecureTrading\Trust\Gateway\Command
 */
class CancelCommand extends AbstractCommand
{
    /**
     * @param array $commandSubject
     * @return \Magento\Payment\Gateway\Command\ResultInterface|void|null
     * @throws LocalizedException
     */
    public function execute(array $commandSubject)
    {

        // @TODO implement exceptions catching
        //Send API to Update transaction
        //$response is an object
        $response = $this->transferFactory->create(
            $this->requestBuilder->build($commandSubject)
        );
        //Validate error code
        if ($this->validator !== null) {
            $result = $this->validator->validate(
                array_merge($commandSubject, ['response' => $response])
            );
            if (!$result->isValid()) {
                $this->processErrors($result);
            }
        }
        //Change settle status
        if ($this->handler) {
            $this->handler->handle(
                $commandSubject,
                []
            );
        }

    }
}