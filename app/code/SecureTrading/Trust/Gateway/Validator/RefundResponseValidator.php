<?php

namespace SecureTrading\Trust\Gateway\Validator;

use Magento\Payment\Gateway\Validator\ResultInterface;

/**
 * Class RefundResponseValidator
 * @package SecureTrading\Trust\Gateway\Validator
 */
class RefundResponseValidator extends AbstractResponseValidator
{

    /**
     * Performs validation of result code
     *
     * @param array $validationSubject
     * @return ResultInterface
     */
    public function validate(array $validationSubject)
    {
        if (empty($validationSubject['response'])) {
            throw new \InvalidArgumentException('Response does not exist');
        }

        $response = $validationSubject['response'];

        if ($this->getErrorCode($response)) {
            return $this->createResult(
                true,
                []
            );
        } else {
            return $this->createResult(
                false,
                [__($this->getErrorMessage($response))]
            );
        }
    }

	/**
	 * @param $response
	 * @return bool
	 */
	public function getErrorCode($response){
        $this->logger->debug('--- Error Code: '.$response['responses'][0]['errorcode'].' ---');
        if(isset($response['responses'][0]['errorcode'])){
            if($response['responses'][0]['errorcode'] == 0){
                return true;
            }
            return false;
        }
        return false;
    }

	/**
	 * @param $response
	 * @return string
	 */
	public function getErrorMessage($response){
        $this->logger->debug('--- Error Message: '.$response['responses'][0]['errormessage'].' ---');
        if(!empty($response['responses'][0]['errormessage'])) {
            return $response['responses'][0]['errormessage'];
        }
        return 'Something went wrong.';
    }
}