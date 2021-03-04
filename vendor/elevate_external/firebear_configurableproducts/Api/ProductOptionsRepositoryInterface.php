<?php



namespace Firebear\ConfigurableProducts\Api;

use Firebear\ConfigurableProducts\Api\Data\ProductOptionsInterface;
use Magento\Framework\Exception\NotFoundException;

/**
 * Interface ProductOptionsRepositoryInterface
 * @package Firebear\ConfigurableProducts\Api
 * @api
 */
interface ProductOptionsRepositoryInterface
{
    /**
     * @param \Firebear\ConfigurableProducts\Api\Data\ProductOptionsInterface $productOptionsInterface
     *
     * @return \Firebear\ConfigurableProducts\Api\Data\ProductOptionsInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(ProductOptionsInterface $productOptionsInterface);

    /**
     * @param \Firebear\ConfigurableProducts\Api\Data\ProductOptionsInterface $productOptionsInterface
     * @param string $productId
     *
     * @return \Firebear\ConfigurableProducts\Api\Data\ProductOptionsInterface
     * @throws NotFoundException
     */
    public function update(ProductOptionsInterface $productOptionsInterface, $productId);

    /**
     * @param int $itemId
     *
     * @return \Firebear\ConfigurableProducts\Api\Data\ProductOptionsInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get($itemId);

    /**
     * @param \Firebear\ConfigurableProducts\Api\Data\ProductOptionsInterface $productOptionsDataInterface
     *
     * @return bool
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function delete(ProductOptionsInterface $productOptionsDataInterface);

    /**
     * @param int $itemId
     *
     * @return bool
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function deleteById($itemId);

    /**
     * @param int $productId
     *
     * @return bool
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function deleteByProductId($productId);
}
