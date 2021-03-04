<?php

namespace Firebear\ConfigurableProducts\Api;

use Firebear\ConfigurableProducts\Api\Data\DefaultProductOptionsInterface;
use Magento\Framework\Exception\NotFoundException;

interface DefaultProductOptionsRepositoryInterface
{
    /**
     * @param \Firebear\ConfigurableProducts\Api\Data\DefaultProductOptionsInterface $defaultProductOptionsInterface
     *
     * @return \Firebear\ConfigurableProducts\Api\Data\DefaultProductOptionsInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(DefaultProductOptionsInterface $defaultProductOptionsInterface);

    /**
     * @param int $linkId
     *
     * @return \Firebear\ConfigurableProducts\Api\Data\DefaultProductOptionsInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get($linkId);

    /**
     * @param int $parentId
     *
     * @return \Firebear\ConfigurableProducts\Api\Data\DefaultProductOptionsInterface
     */
    public function getByParentId($parentId);

    /**
     * @param \Firebear\ConfigurableProducts\Api\Data\DefaultProductOptionsInterface $defaultProductOptionsInterface
     *
     * @return bool
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function delete(DefaultProductOptionsInterface $defaultProductOptionsInterface);


    /**
     * @param int $linkId
     *
     * @return bool
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function deleteById($linkId);

    /**
     * @param int $parentId
     *
     * @return bool
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function deleteByParentId($parentId);
}
