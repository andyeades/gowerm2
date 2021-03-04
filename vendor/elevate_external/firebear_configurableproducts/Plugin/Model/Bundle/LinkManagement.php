<?php
declare(strict_types=1);

namespace Firebear\ConfigurableProducts\Plugin\Model\Bundle;

use Exception;
use Magento\Bundle\Api\Data\LinkInterface;
use Magento\Bundle\Api\Data\LinkInterfaceFactory;
use Magento\Bundle\Model\ResourceModel\Bundle;
use Magento\Bundle\Model\ResourceModel\BundleFactory;
use Magento\Bundle\Model\ResourceModel\Option\CollectionFactory;
use Magento\Bundle\Model\Selection;
use Magento\Bundle\Model\SelectionFactory;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Product\Type;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\EntityManager\MetadataPool;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\InputException;
use Magento\Store\Model\StoreManagerInterface;

class LinkManagement
{

    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var LinkInterfaceFactory
     */
    protected $linkFactory;

    /**
     * @var BundleFactory
     */
    protected $bundleFactory;

    /**
     * @var SelectionFactory
     */
    protected $bundleSelection;

    /**
     * @var CollectionFactory
     */
    protected $optionCollection;

    /**
     * @var DataObjectHelper
     */
    protected $dataObjectHelper;

    /**
     * @var MetadataPool
     */
    private $metadataPool;
    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @param ProductRepositoryInterface $productRepository
     * @param LinkInterfaceFactory $linkFactory
     * @param SelectionFactory $bundleSelection
     * @param BundleFactory $bundleFactory
     * @param CollectionFactory $optionCollection
     * @param StoreManagerInterface $storeManager
     * @param DataObjectHelper $dataObjectHelper
     */
    public function __construct(
        ProductRepositoryInterface $productRepository,
        LinkInterfaceFactory $linkFactory,
        SelectionFactory $bundleSelection,
        BundleFactory $bundleFactory,
        CollectionFactory $optionCollection,
        StoreManagerInterface $storeManager,
        DataObjectHelper $dataObjectHelper
    ) {
        $this->productRepository = $productRepository;
        $this->linkFactory = $linkFactory;
        $this->bundleFactory = $bundleFactory;
        $this->bundleSelection = $bundleSelection;
        $this->optionCollection = $optionCollection;
        $this->storeManager = $storeManager;
        $this->dataObjectHelper = $dataObjectHelper;
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function aroundSaveChild(
        \Magento\Bundle\Model\LinkManagement $subject,
        callable $proceed,
        $sku,
        LinkInterface $linkedProduct
    ) {
        $product = $this->productRepository->get($sku, true);
        if ($product->getTypeId() != Type::TYPE_BUNDLE) {
            throw new InputException(
                __('Product with specified sku: "%1" is not a bundle product', [$product->getSku()])
            );
        }
        /** @var Product $linkProductModel */
        $linkProductModel = $this->productRepository->get($linkedProduct->getSku());

        if (!$linkedProduct->getId()) {
            throw new InputException(__('Id field of product link is required'));
        }

        /** @var Selection $selectionModel */
        $selectionModel = $this->bundleSelection->create();
        $selectionModel->load($linkedProduct->getId());
        if (!$selectionModel->getId()) {
            throw new InputException(__('Can not find product link with id "%1"', [$linkedProduct->getId()]));
        }
        $linkField = $this->getMetadataPool()->getMetadata(ProductInterface::class)->getLinkField();
        $selectionModel = $this->mapProductLinkToSelectionModel(
            $selectionModel,
            $linkedProduct,
            $linkProductModel->getId(),
            $product->getData($linkField)
        );

        try {
            $selectionModel->save();
        } catch (Exception $e) {
            throw new CouldNotSaveException(__('Could not save child: "%1"', $e->getMessage()), $e);
        }

        return true;
    }

    /**
     * Get MetadataPool instance
     * @return MetadataPool
     */
    private function getMetadataPool()
    {
        if (!$this->metadataPool) {
            $this->metadataPool = ObjectManager::getInstance()->get(MetadataPool::class);
        }
        return $this->metadataPool;
    }

    /**
     * @param Selection $selectionModel
     * @param LinkInterface $productLink
     * @param string $linkedProductId
     * @param string $parentProductId
     * @return Selection
     */
    protected function mapProductLinkToSelectionModel(
        Selection $selectionModel,
        LinkInterface $productLink,
        $linkedProductId,
        $parentProductId
    ) {
        $selectionModel->setProductId($linkedProductId);
        $selectionModel->setParentProductId($parentProductId);
        if (($productLink->getOptionId() !== null)) {
            $selectionModel->setOptionId($productLink->getOptionId());
        }
        if ($productLink->getPosition() !== null) {
            $selectionModel->setPosition($productLink->getPosition());
        }
        if ($productLink->getQty() !== null) {
            $selectionModel->setSelectionQty($productLink->getQty());
        }
        if ($productLink->getPriceType() !== null) {
            $selectionModel->setSelectionPriceType($productLink->getPriceType());
        }
        if ($productLink->getPrice() !== null) {
            $selectionModel->setSelectionPriceValue($productLink->getPrice());
        }
        if ($productLink->getCanChangeQuantity() !== null) {
            $selectionModel->setSelectionCanChangeQty($productLink->getCanChangeQuantity());
        }
        if ($productLink->getIsDefault() !== null) {
            $selectionModel->setIsDefault($productLink->getIsDefault());
        }

        return $selectionModel;
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function aroundAddChild(
        \Magento\Bundle\Model\LinkManagement $subject,
        callable $proceed,
        ProductInterface $product,
        $optionId,
        LinkInterface $linkedProduct
    ) {
        if ($product->getTypeId() != Type::TYPE_BUNDLE) {
            throw new InputException(
                __('Product with specified sku: "%1" is not a bundle product', $product->getSku())
            );
        }

        $options = $this->optionCollection->create();
        $options->setIdFilter($optionId);
        $existingOption = $options->getFirstItem();

        if (!$existingOption->getId()) {
            throw new InputException(
                __(
                    'Product with specified sku: "%1" does not contain option: "%2"',
                    [$product->getSku(), $optionId]
                )
            );
        }

        $linkField = $this->getMetadataPool()->getMetadata(ProductInterface::class)->getLinkField();
        /* @var $resource Bundle */
        $resource = $this->bundleFactory->create();
        $selections = $resource->getSelectionsData($product->getData($linkField));
        /** @var Product $linkProductModel */
        $linkProductModel = $this->productRepository->get($linkedProduct->getSku());
        /*if ($linkProductModel->isComposite()) {
            throw new InputException(__('Bundle product could not contain another composite product'));
        }*/

        if ($selections) {
            foreach ($selections as $selection) {
                if ($selection['option_id'] == $optionId &&
                    $selection['product_id'] == $linkProductModel->getEntityId()) {
                    if (!$product->getCopyFromView()) {
                        throw new CouldNotSaveException(
                            __(
                                'Child with specified sku: "%1" already assigned to product: "%2"',
                                [$linkedProduct->getSku(), $product->getSku()]
                            )
                        );
                    } else {
                        return $this->bundleSelection->create()->load($linkProductModel->getEntityId());
                    }
                }
            }
        }

        $selectionModel = $this->bundleSelection->create();
        $selectionModel = $this->mapProductLinkToSelectionModel(
            $selectionModel,
            $linkedProduct,
            $linkProductModel->getEntityId(),
            $product->getData($linkField)
        );
        $selectionModel->setOptionId($optionId);

        try {
            $selectionModel->save();
            $resource->addProductRelation($product->getData($linkField), $linkProductModel->getEntityId());
        } catch (Exception $e) {
            throw new CouldNotSaveException(__('Could not save child: "%1"', $e->getMessage()), $e);
        }

        return $selectionModel->getId();
    }
}
