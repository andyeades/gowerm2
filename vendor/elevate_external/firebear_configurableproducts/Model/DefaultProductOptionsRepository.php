<?php

namespace Firebear\ConfigurableProducts\Model;

use Firebear\ConfigurableProducts\Api\Data;
use Magento\Framework\Config\Dom\ValidationException;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\Webapi\Exception;
use Magento\Catalog\Model\ProductRepository;

class DefaultProductOptionsRepository implements
    \Firebear\ConfigurableProducts\Api\DefaultProductOptionsRepositoryInterface
{
    /**
     * @var ResourceModel\DefaultProductOptions
     */
    private $defaultProductOptionsResource;
    /**
     * @var DefaultProductOptionsFactory
     */
    private $defaultProductOptionsFactory;
    /**
     * @var ProductRepository
     */
    protected $productRepository;
    /**
     * @var array
     */
    private $entities = [];

    /**
     * ProductOptionsRepository constructor.
     *
     * @param ResourceModel\DefaultProductOptions $defaultProductOptionsResource
     * @param DefaultProductOptionsFactory $defaultProductOptionsFactory
     * @param ProductRepository $productRepository
     */
    public function __construct(
        \Firebear\ConfigurableProducts\Model\ResourceModel\DefaultProductOptions $defaultProductOptionsResource,
        \Firebear\ConfigurableProducts\Model\DefaultProductOptionsFactory $defaultProductOptionsFactory,
        ProductRepository $productRepository
    ) {
        $this->defaultProductOptionsResource = $defaultProductOptionsResource;
        $this->defaultProductOptionsFactory = $defaultProductOptionsFactory;
        $this->productRepository = $productRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function save(Data\DefaultProductOptionsInterface $defaultProductOptionsInterface)
    {
        $parentId = $defaultProductOptionsInterface->getParentId();
        $productId = $defaultProductOptionsInterface->getProductId();
        $configurableProduct = $this->productRepository->getById($parentId);
        if ($configurableProduct && $configurableProduct->getTypeId() == 'configurable') {
            $children = $configurableProduct->getTypeInstance()->getUsedProducts($configurableProduct);
            $childrenIds = [];
            foreach ($children as $child) {
                $childrenIds[] = $child->getId();
            }
            if (in_array($productId, $childrenIds)) {
                    $existingParent = $this->getByParentId($defaultProductOptionsInterface->getParentId());
                if ($existingParent->getLinkId()) {
                    $defaultProductOptionsInterface = $defaultProductOptionsInterface
                        ->setLinkId($existingParent->getLinkId());
                    $defaultProductOptionsInterface =
                        $existingParent->addData($defaultProductOptionsInterface->getData());
                }
                try {
                    $this->defaultProductOptionsResource->save($defaultProductOptionsInterface);
                    unset($this->entities);
                } catch (ValidationException $e) {
                    throw new CouldNotSaveException(__($e->getMessage()));
                } catch (\Exception $e) {
                    throw new CouldNotSaveException(
                        __('Unable to save model %1', $defaultProductOptionsInterface->getLinkId())
                    );
                }
                    return $defaultProductOptionsInterface;
            }
        }
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function get($linkId)
    {
        if (!isset($this->entities[$linkId])) {
            $defaultProductOptionsInterface = $this->defaultProductOptionsFactory->create();
            $this->defaultProductOptionsResource->load($defaultProductOptionsInterface, $linkId);
            if (!$defaultProductOptionsInterface->getLinkId()) {
                throw new NoSuchEntityException(__('Entity with specified ID "%1" not found.', $linkId));
            }
            $this->entities[$linkId] = $defaultProductOptionsInterface;
        }

        return $this->entities[$linkId];
    }

    /**
     * {@inheritdoc}
     */
    public function delete(Data\DefaultProductOptionsInterface $defaultProductOptionsInterface)
    {
        try {
            $this->defaultProductOptionsResource->delete($defaultProductOptionsInterface);
        } catch (ValidationException $e) {
            throw new CouldNotSaveException(__($e->getMessage()));
        } catch (\Exception $e) {
            throw new CouldNotDeleteException(
                __('Unable to remove entity with ID%', $defaultProductOptionsInterface->getLinkId())
            );
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getByParentId($parentId)
    {
        $model = $this->defaultProductOptionsFactory->create();
        $this->defaultProductOptionsResource->load($model, $parentId, 'parent_id');

        return $model;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($linkId)
    {
        $model = $this->get($linkId);
        $this->delete($model);

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteByParentId($parentId)
    {
        $model = $this->defaultProductOptionsFactory->create();
        $this->defaultProductOptionsResource->load($model, $parentId, 'parent_id');
        $this->delete($model);
        return true;
    }
}
