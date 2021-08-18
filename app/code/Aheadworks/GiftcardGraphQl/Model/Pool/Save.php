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
 * @package    GiftcardGraphQl
 * @version    1.0.0
 * @copyright  Copyright (c) 2021 Aheadworks Inc. (https://aheadworks.com/)
 * @license    https://aheadworks.com/end-user-license-agreement/
 */
namespace Aheadworks\GiftcardGraphQl\Model\Pool;

use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Aheadworks\Giftcard\Api\Data\PoolInterface;
use Aheadworks\Giftcard\Api\Data\PoolInterfaceFactory;
use Aheadworks\Giftcard\Api\PoolRepositoryInterface;

/**
 * Class Save
 *
 * @package Aheadworks\GiftcardGraphQl\Model\Pool
 */
class Save
{
    /**
     * @var PoolRepositoryInterface
     */
    private $poolRepository;

    /**
     * @var PoolInterfaceFactory
     */
    private $poolFactory;

    /**
     * @var DataObjectHelper
     */
    private $dataObjectHelper;

    /**
     * @param PoolRepositoryInterface $poolRepository
     * @param PoolInterfaceFactory $poolFactory
     * @param DataObjectHelper $dataObjectHelper
     */
    public function __construct(
        PoolRepositoryInterface $poolRepository,
        PoolInterfaceFactory $poolFactory,
        DataObjectHelper $dataObjectHelper
    ) {
        $this->poolRepository = $poolRepository;
        $this->poolFactory = $poolFactory;
        $this->dataObjectHelper = $dataObjectHelper;
    }

    /**
     * Save pool
     *
     * @param array $data
     * @return PoolInterface
     * @throws GraphQlInputException
     */
    public function execute(array $data)
    {
        try {
            $pool = $this->createPool($data);
            return $this->poolRepository->save($pool);
        } catch (LocalizedException $e) {
            throw new GraphQlInputException(__($e->getMessage()));
        }
    }

    /**
     * Create pool
     *
     * @param array $data
     * @return PoolInterface
     * @throws LocalizedException
     */
    private function createPool(array $data)
    {
        $id = $data[PoolInterface::ID] ?? null;
        $pool = $id
            ? $this->poolRepository->get($id)
            : $this->poolFactory->create();

        $this->dataObjectHelper->populateWithArray(
            $pool,
            $data,
            PoolInterface::class
        );

        return $pool;
    }
}
