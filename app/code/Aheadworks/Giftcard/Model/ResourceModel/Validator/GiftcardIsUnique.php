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
namespace Aheadworks\Giftcard\Model\ResourceModel\Validator;

use Aheadworks\Giftcard\Api\Data\GiftcardInterface;
use Aheadworks\Giftcard\Api\Data\Pool\CodeInterface as PoolCodeInterface;
use Aheadworks\Giftcard\Model\Source\YesNo;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\EntityManager\MetadataPool;

/**
 * Class GiftcardIsUnique
 *
 * @package Aheadworks\Giftcard\Model\ResourceModel\Validator
 */
class GiftcardIsUnique
{
    /**
     * @var MetadataPool
     */
    private $metadataPool;

    /**
     * @var ResourceConnection
     */
    private $resourceConnection;

    /**
     * @param MetadataPool $metadataPool
     * @param ResourceConnection $resourceConnection
     */
    public function __construct(
        MetadataPool $metadataPool,
        ResourceConnection $resourceConnection
    ) {
        $this->metadataPool = $metadataPool;
        $this->resourceConnection = $resourceConnection;
    }

    /**
     * Check unique Gift Card code
     *
     * @param string $code
     * @return bool
     */
    public function validate($code)
    {
        $giftcardMetaData = $this->metadataPool->getMetadata(GiftcardInterface::class);
        $connection = $this->resourceConnection->getConnectionByName($giftcardMetaData->getEntityConnectionName());

        $bind = ['code' => $code];
        $select = $connection->select()
            ->from($this->resourceConnection->getTableName($giftcardMetaData->getEntityTable()))
            ->where('code = :code');
        if ($connection->fetchRow($select, $bind)) {
            return false;
        }

        $bind = [
            'code' => $code,
            'used' => YesNo::NO
        ];
        $poolCodeMetaData = $this->metadataPool->getMetadata(PoolCodeInterface::class);
        $connection = $this->resourceConnection->getConnectionByName($poolCodeMetaData->getEntityConnectionName());
        $select = $connection->select()
            ->from($this->resourceConnection->getTableName($poolCodeMetaData->getEntityTable()))
            ->where('code = :code')
            ->where('used = :used');
        if ($connection->fetchRow($select, $bind)) {
            return false;
        }
        return true;
    }
}
