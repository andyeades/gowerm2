<?php
/**
 * Aheadworks Inc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://aheadworks.com/end-user-license-agreement/
 *
 * @package    Giftcard
 * @version    1.4.6
 * @copyright  Copyright (c) 2021 Aheadworks Inc. (https://aheadworks.com/)
 * @license    https://aheadworks.com/end-user-license-agreement/
 */
namespace Aheadworks\Giftcard\Api;

/**
 * Interface PoolManagementInterface
 * @api
 */
interface PoolManagementInterface
{
    /**
     * Generate codes for pool
     *
     * @param int $poolId
     * @param int $qty
     * @return \Aheadworks\Giftcard\Api\Data\Pool\CodeInterface[]
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function generateCodesForPool($poolId, $qty);

    /**
     * Import codes to pool
     *
     * @param int $poolId
     * @param mixed $codesRawData
     * @return \Aheadworks\Giftcard\Api\Data\Pool\CodeInterface[]
     * @throws \Aheadworks\Giftcard\Api\Exception\ImportValidatorExceptionInterface
     */
    public function importCodesToPool($poolId, $codesRawData);

    /**
     * Pull code from pool
     *
     * @param int $poolId
     * @param bool $generateNew
     * @return string|null
     */
    public function pullCodeFromPool($poolId, $generateNew = true);
}
