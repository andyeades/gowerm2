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
namespace Aheadworks\Giftcard\Model\Pool;

use Aheadworks\Giftcard\Api\Data\Pool\CodeInterface;
use Magento\Framework\Model\AbstractModel;
use Aheadworks\Giftcard\Model\ResourceModel\Pool\Code as ResourceCode;

/**
 * Class Code
 *
 * @package Aheadworks\Giftcard\Model
 */
class Code extends AbstractModel implements CodeInterface
{
    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init(ResourceCode::class);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getData(self::ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setId($id)
    {
        return $this->setData(self::ID, $id);
    }

    /**
     * {@inheritdoc}
     */
    public function getPoolId()
    {
        return $this->getData(self::POOL_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setPoolId($poolId)
    {
        return $this->setData(self::POOL_ID, $poolId);
    }

    /**
     * {@inheritdoc}
     */
    public function getCode()
    {
        return $this->getData(self::CODE);
    }

    /**
     * {@inheritdoc}
     */
    public function setCode($code)
    {
        return $this->setData(self::CODE, $code);
    }

    /**
     * {@inheritdoc}
     */
    public function isUsed()
    {
        return $this->getData(self::USED);
    }

    /**
     * {@inheritdoc}
     */
    public function setUsed($used)
    {
        return $this->setData(self::USED, $used);
    }

    /**
     * {@inheritdoc}
     */
    public function getExtensionAttributes()
    {
        return $this->getData(self::EXTENSION_ATTRIBUTES_KEY);
    }

    /**
     * {@inheritdoc}
     */
    public function setExtensionAttributes(
        \Aheadworks\Giftcard\Api\Data\Pool\CodeExtensionInterface $extensionAttributes
    ) {
        return $this->setData(self::EXTENSION_ATTRIBUTES_KEY, $extensionAttributes);
    }
}
