<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace SecureTrading\Trust\Gateway\Http;

use Magento\Payment\Gateway\Http\TransferFactoryInterface;

/**
 * Class TransactionUpdateTransferFactory
 * @package SecureTrading\Trust\Gateway\Http
 */
class TransactionUpdateTransferFactory implements TransferFactoryInterface
{
    /**
     * @param array $request
     * @return \Magento\Payment\Gateway\Http\TransferInterface
     */
    public function create(array $request)
    {
        $api = \Securetrading\api($request['configData']);
        return  $api->process($request['requestData']);
    }
}
