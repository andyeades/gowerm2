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
namespace Aheadworks\Giftcard\Api\Data\Pool;

use Magento\Framework\Api\ExtensibleDataInterface;

/**
 * Interface CodeInterface
 * @api
 */
interface CodeInterface extends ExtensibleDataInterface
{
    /**#@+
     * Constants defined for keys of the data array. Identical to the name of the getter in snake case
     */
    const ID = 'id';
    const POOL_ID = 'pool_id';
    const CODE = 'code';
    const USED = 'used';
    /**#@-*/

    /**
     * Get ID
     *
     * @return int
     */
    public function getId();

    /**
     * Set ID
     *
     * @param int $id
     * @return $this
     */
    public function setId($id);

    /**
     * Get pool ID
     *
     * @return int
     */
    public function getPoolId();

    /**
     * Set pool ID
     *
     * @param int $poolId
     * @return $this
     */
    public function setPoolId($poolId);

    /**
     * Get code
     *
     * @return string
     */
    public function getCode();

    /**
     * Set code
     *
     * @param string $code
     * @return $this
     */
    public function setCode($code);

    /**
     * Is used
     *
     * @return bool
     */
    public function isUsed();

    /**
     * Set used
     *
     * @param int $used
     * @return $this
     */
    public function setUsed($used);

    /**
     * Retrieve existing extension attributes object or create a new one
     *
     * @return \Aheadworks\Giftcard\Api\Data\Pool\CodeExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object
     *
     * @param \Aheadworks\Giftcard\Api\Data\Pool\CodeExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Aheadworks\Giftcard\Api\Data\Pool\CodeExtensionInterface $extensionAttributes
    );
}
