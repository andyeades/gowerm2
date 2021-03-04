<?php
declare(strict_types=1);

namespace Firebear\ConfigurableProducts\Model;

use Exception;
use Firebear\ConfigurableProducts\Api\Data;
use Firebear\ConfigurableProducts\Api\ProductOptionsRepositoryInterface;
use Magento\Framework\Config\Dom\ValidationException;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\NotFoundException;

class ProductOptionsRepository implements ProductOptionsRepositoryInterface
{
    private $productOptionsResource;
    private $productOptionsFactory;
    private $entities = [];

    /**
     * ProductOptionsRepository constructor.
     *
     * @param ResourceModel\ProductOptions $productOptionsResource
     * @param ProductOptionsFactory $productOptionsFactory
     */
    public function __construct(
        \Firebear\ConfigurableProducts\Model\ResourceModel\ProductOptions $productOptionsResource,
        ProductOptionsFactory $productOptionsFactory
    ) {
        $this->productOptionsResource = $productOptionsResource;
        $this->productOptionsFactory = $productOptionsFactory;
    }

    /**
     * @param Data\ProductOptionsInterface $productOptionsInterface
     *
     * @return Data\ProductOptionsInterface
     * @throws CouldNotSaveException
     */
    public function save(Data\ProductOptionsInterface $productOptionsInterface)
    {
        if ($productOptionsInterface->getItemId()) {
            $productOptionsInterface = $this->get($productOptionsInterface->getItemId())
                ->addData($productOptionsInterface->getData());
        }
        try {
            $this->productOptionsResource->save($productOptionsInterface);
            unset($this->entities);
        } catch (ValidationException $e) {
            throw new CouldNotSaveException(__($e->getMessage()));
        } catch (Exception $e) {
            throw new CouldNotSaveException(__('Unable to save model %1', $productOptionsInterface->getItemId()));
        }

        return $productOptionsInterface;
    }

    /**
     * @param int $itemId
     *
     * @return mixed
     * @throws NoSuchEntityException
     */
    public function get($itemId)
    {
        if (!isset($this->entities[$itemId])) {
            $productOptionsInterface = $this->productOptionsFactory->create();
            $this->productOptionsResource->load($productOptionsInterface, $itemId);
            if (!$productOptionsInterface->getItemId()) {
                throw new NoSuchEntityException(__('Entity with specified ID "%1" not found.', $itemId));
            }
            $this->entities[$itemId] = $productOptionsInterface;
        }

        return $this->entities[$itemId];
    }

    /**
     * @param Data\ProductOptionsInterface $productOptionsInterface
     * @param string $productId
     * @return Data\ProductOptionsInterface
     * @throws NotFoundException
     * @throws ValidationException
     * @throws CouldNotSaveException
     */
    public function update(Data\ProductOptionsInterface $productOptionsInterface, $productId)
    {
        $oldOptions = $this->getByProductId($productId);
        if ($oldOptions->getItemId()) {
            $productOptionsInterface->setItemId($oldOptions->getItemId());
            $productOptionsInterface = $oldOptions->addData($productOptionsInterface->getData());
            try {
                $this->productOptionsResource->save($productOptionsInterface);
                unset($this->entities);
            } catch (ValidationException $e) {
                throw new CouldNotSaveException(__($e->getMessage()));
            } catch (Exception $e) {
                throw new CouldNotSaveException(__('Unable to save model %1', $productOptionsInterface->getItemId()));
            }
        } else {
            throw new NotFoundException(__('Unable to save model. Product ID %1 not found', $productId));
        }
        return $productOptionsInterface;
    }

    /**
     * @param $productId
     *
     * @return ProductOptions
     */
    public function getByProductId($productId)
    {
        $model = $this->productOptionsFactory->create();
        $this->productOptionsResource->load($model, $productId, 'product_id');

        return $model;
    }

    /**
     * @param $name
     *
     * @return ProductOptions
     */
    public function getByName($name)
    {
        $model = $this->productOptionsFactory->create();
        $this->productOptionsResource->load($model, 2);

        return $model;
    }

    /**
     * @param int $itemId
     *
     * @return bool
     */
    public function deleteById($itemId)
    {
        $model = $this->get($itemId);
        $this->delete($model);

        return true;
    }

    /**
     * @param Data\ProductOptionsInterface $productOptionsInterface
     *
     * @return bool
     * @throws CouldNotSaveException
     * @throws CouldNotDeleteException
     */
    public function delete(Data\ProductOptionsInterface $productOptionsInterface)
    {
        try {
            $this->productOptionsResource->delete($productOptionsInterface);
        } catch (ValidationException $e) {
            throw new CouldNotSaveException(__($e->getMessage()));
        } catch (Exception $e) {
            throw new CouldNotDeleteException(
                __('Unable to remove entity with ID%', $productOptionsInterface->getItemId())
            );
        }

        return true;
    }

    public function deleteByProductId($productId)
    {
        $model = $this->productOptionsFactory->create();
        $this->productOptionsResource->load($model, $productId, 'product_id');
        $this->delete($model);
        return true;
    }
}
