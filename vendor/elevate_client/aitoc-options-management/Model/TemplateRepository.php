<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2019 Aitoc (https://www.aitoc.com)
 * @package Aitoc_OptionsManagement
 */

/**
 * Copyright © 2018 Aitoc. All rights reserved.
 */
namespace Aitoc\OptionsManagement\Model;

use Magento\Catalog\Model\ProductRepository;
use Magento\Framework\EntityManager\EntityManager;
use Magento\Catalog\Model\ProductFactory;
use Aitoc\OptionsManagement\Api\Data\TemplateInterface;
use Aitoc\OptionsManagement\Model\TemplateFactory;
use Aitoc\OptionsManagement\Model\ResourceModel\Template as ResourceTemplate;
use Aitoc\OptionsManagement\Model\ResourceModel\Template\CollectionFactory;
use Aitoc\OptionsManagement\Api\Data\TemplateSearchResultInterfaceFactory;
use Aitoc\OptionsManagement\Api\Data\TemplateOptionInterface;
use Aitoc\OptionsManagement\Api\Data\TemplateOptionValueInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\App\CacheInterface;

class TemplateRepository implements \Aitoc\OptionsManagement\Api\TemplateRepositoryInterface
{
    /**
     * @var \Aitoc\OptionsManagement\Helper\Data
     */
    protected $helper;

    /**
     * @var TemplateFactory
     */
    protected $templateFactory;

    /**
     * @var ResourceTemplate
     */
    protected $templateResource;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var ProductRepository
     */
    protected $productRepository;

    /**
     * @var ProductFactory
     */
    protected $productFactory;

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var TemplateSearchResultInterfaceFactory
     */
    protected $searchResultFactory;

    /**
     * @var CacheInterface
     */
    protected $cacheManager;

    /**
     * @var \Magento\Framework\Event\ManagerInterface
     */
    protected $eventManager;

    /**
     * @var array
     */
    protected $usedStoreIds = [];

    /**
     * TemplateRepository constructor.
     *
     * @param \Aitoc\OptionsManagement\Helper\Data $helper
     * @param \Aitoc\OptionsManagement\Model\TemplateFactory $templateFactory
     * @param ResourceTemplate $templateResource
     * @param CollectionFactory $collectionFactory
     * @param ProductRepository $productRepository
     * @param ProductFactory $productFactory
     * @param EntityManager $entityManager
     * @param TemplateSearchResultInterfaceFactory $searchResultFactory
     * @param CacheInterface $cacheManager
     * @param \Magento\Framework\Event\ManagerInterface $eventManager
     */
    public function __construct(
        \Aitoc\OptionsManagement\Helper\Data $helper,
        TemplateFactory $templateFactory,
        ResourceTemplate $templateResource,
        CollectionFactory $collectionFactory,
        ProductRepository $productRepository,
        ProductFactory $productFactory,
        EntityManager $entityManager,
        TemplateSearchResultInterfaceFactory $searchResultFactory,
        CacheInterface $cacheManager,
        \Magento\Framework\Event\ManagerInterface $eventManager
    ) {
        $this->helper = $helper;
        $this->templateResource = $templateResource;
        $this->templateFactory = $templateFactory;
        $this->collectionFactory = $collectionFactory;
        $this->productRepository = $productRepository;
        $this->productFactory = $productFactory;
        $this->entityManager = $entityManager;
        $this->searchResultFactory = $searchResultFactory;
        $this->cacheManager = $cacheManager;
        $this->eventManager = $eventManager;
    }

    /**
     * {@inheritdoc}
     */
    public function getById($templateId, $storeId = 0)
    {
        $template = $this->getEmpty($storeId);
        $this->templateResource->load($template, $templateId);
        if (!$template->getId()) {
            throw new NoSuchEntityException(__('Template with ID "%1" does not exist.', $templateId));
        }

        $template->setStoreId($storeId);
        return $template;
    }

    /**
     * {@inheritdoc}
     */
    public function getEmpty($storeId = 0)
    {
        return $this->templateFactory->create()->setStoreId($storeId);
    }

    /**
     * {@inheritdoc}
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria)
    {
        /** @var \Aitoc\OptionsManagement\Model\ResourceModel\Template\Collection $collection */
        $collection = $this->collectionFactory->create();

        /** @var SortOrder $sortOrder */
        foreach ((array)$searchCriteria->getSortOrders() as $sortOrder) {
            $field = $sortOrder->getField();
            $collection->addOrder(
                $field,
                ($sortOrder->getDirection() == SortOrder::SORT_ASC) ? 'ASC' : 'DESC'
            );
        }
        $collection->setCurPage($searchCriteria->getCurrentPage());
        $collection->setPageSize($searchCriteria->getPageSize());
        $collection->load();

        /** @var \Aitoc\OptionsManagement\Api\Data\TemplateSearchResultInterface $searchResult */
        $searchResult = $this->searchResultFactory->create();
        $searchResult->setSearchCriteria($searchCriteria);
        $searchResult->setItems($collection->getItems());
        $searchResult->setTotalCount($collection->getSize());
        return $searchResult;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(TemplateInterface $template)
    {
        try {
            $this->removeRelatedProductOption($template);
            $this->templateResource->delete($template);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
        return true;
    }

    /**
     * @param TemplateInterface $template
     * @return int
     * @throws CouldNotDeleteException
     */
    public function removeRelatedProductOption(TemplateInterface $template)
    {
        try {
            $productIds = $this->templateResource->removeRelatedProductOption($template);

            // clean product cache
            if ($productIds) {
                $cleanTags = [];
                $product = $this->productFactory->create();
                foreach($productIds as $productId) {
                    $cleanTags[] = 'catalog_product_' . $productId;
                    $product->setId($productId);
                    $this->eventManager->dispatch('clean_cache_by_tags', ['object' => $product]);
                }
                $this->cacheManager->clean($cleanTags);
            }

            return $productIds;

        } catch (\Exception $e) {
            throw new CouldNotDeleteException(__('Unable to remove related product option of template ID: %1', $template->getId()));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($templateId)
    {
        return $this->delete($this->getById($templateId));
    }

    /**
     * {@inheritdoc}
     */
    public function save(TemplateInterface $template)
    {
        try {
            $this->templateResource->save($template);
        } catch (CouldNotSaveException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new CouldNotSaveException(__('Unable to save template ID: %1', $template->getTemplateId()));
        }
        return $template;
    }

    /**
     * {@inheritdoc}
     */
    public function assignOptionsToProduct(TemplateInterface $template, $productId)
    {
        // only default store
        if ($template->getStoreId() > 0) {
            /** @var $template \Aitoc\OptionsManagement\Model\Template */
            $template = $this->getById($template->getId());
        }

        $options = $template->getOptions();
        try {
            /** @var $product \Magento\Catalog\Model\Product */
            $product = $this->productRepository->getById($productId, true, 0);

            $productOptions = $product->getOptions();
            $newProductOptions = [];
            $sortOrder = 0;
            $index = 0;

            // correct sort order for primary options
            if ($template->getSortOrder() > 0) {
                foreach($productOptions as $index => $option) {
                    if ($option->getTemplateId()) {
                        $checkTemplate = $this->getById($option->getTemplateId());
                        if ($checkTemplate) {
                            if ($checkTemplate->getSortOrder() > $template->getSortOrder()) {
                                break;
                            }
                        }
                    }
                    $sortOrder++;
                    $option->setSortOrder($sortOrder);
                }
            }

            // add new options
            foreach($options as $option) {
                $newProductOption = clone $product->getOptionInstance();

                $newProductOption
                    ->setData($option->getData())
                    ->setOptionId(null)
                    ->setTemplateOptionId($option->getOptionId());

                $sortOrder++;
                $newProductOption->setSortOrder($sortOrder);

                $values = $option->getValues();
                if (is_array($values)) {
                    $newProductOptionValues = [];
                    foreach($values as $value) {
                        $newProductOptionValue = clone $newProductOption->getValueInstance();
                        $newProductOptionValue->setData($value->getData())
                            ->setOptionTypeId(null)
                            ->setTemplateOptionTypeId($value->getOptionTypeId());
                        $newProductOptionValues[] = $newProductOptionValue;
                    }

                    $newProductOption->setValues($newProductOptionValues);
                }

                $newProductOptions[] = $newProductOption;
            }

            if ($template->getSortOrder() > 0) {
                // correct sort order for last options
                if ($index) {
                    foreach($productOptions as $i => $option) {
                        if ($i <= $index) {
                            continue;
                        }
                        $sortOrder++;
                        $option->setSortOrder($sortOrder);
                    }
                }
            } else {
                // set sort order options after options of current template
                foreach($productOptions as $option) {
                    $sortOrder++;
                    $option->setSortOrder($sortOrder);
                }
            }

            // update is_replace_product_sku
            if (!$product->getIsReplaceProductSku() && $template->getIsReplaceProductSku()) {
                $product->setIsReplaceProductSku(1);
            }

            $this->saveСorrectOptionsToProduct($product, array_merge($productOptions, $newProductOptions), 0);

            $this->templateResource->addProductRelation($template, $productId);

            // update options store data
            $this->updateStoreOptionsToProduct($template, $productId);

        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function updateOptionsToProduct(TemplateInterface $template, $productId)
    {
        // only default store
        if ($template->getStoreId() > 0) {
            /** @var $template \Aitoc\OptionsManagement\Model\Template */
            $template = $this->getById($template->getId());
        }

        $templateOptions = $template->getOptions();

        try {
            /** @var $product \Magento\Catalog\Model\Product */
            $product = $this->productRepository->getById($productId, true, 0);

            $options = $product->getOptions();
            $remainingOptions = [];

            $sortOrder = 0;

            foreach($options as $option) {

                if ($option->getTemplateId() && $option->getTemplateId() == $template->getId()) {

                    $templateOptionId = $option->getTemplateOptionId();
                    $templateOption = $this->getOptionById($templateOptions, $templateOptionId);

                    // delete options
                    if (is_null($templateOption)) {
                        $option->delete();
                        continue;
                    }

                    // update options
                    $optionId = $option->getOptionId();

                    $option->setData($templateOption->getData())
                        ->setOptionId($optionId)
                        ->setTemplateOptionId($templateOptionId);

                    $values = $option->getValues();

                    if (!is_array($values) && $option->getGroupByType($option->getType()) == 'select') {
                        $values = [];
                    }

                    if (is_array($values)) {
                        $templateValues = $templateOption->getValues();
                        $remainingValues = [];

                        foreach($values as $value) {
                            $templateValueId = $value->getTemplateOptionTypeId();
                            $templateValue = $this->getOptionValueById($templateValues, $templateValueId);

                            // delete values
                            if (is_null($templateValue)) {
                                $value->delete();
                                continue;
                            }

                            // update values
                            $valueId = $value->getOptionTypeId();
                            $value->setData($templateValue->getData())
                                ->setOptionTypeId($valueId)
                                ->setTemplateOptionTypeId($templateValueId);
                            $remainingValues[] = $value;
                        }

                        // insert values
                        foreach($templateValues as $templateValue) {
                            $value =
                                $this->getOptionValueByTemplateOptionTypeId($remainingValues, $templateValue->getId());
                            if (is_null($value)) {
                                $newProductOptionValue = clone $option->getValueInstance();

                                $newProductOptionValue->setData($templateValue->getData())
                                    ->setOptionTypeId(null)
                                    ->setTemplateOptionTypeId($templateValue->getId());

                                $remainingValues[] = $newProductOptionValue;
                            }
                        }

                        $option->setValues($remainingValues);
                    }
                }

                $option->setSortOrder(0);
                $remainingOptions[] = $option;
            }

            // insert options
            foreach($templateOptions as $templateOption) {
                $option = $this->getOptionByTemplateOptionId($remainingOptions, $templateOption->getId());
                if (is_null($option)) {
                    $newProductOption = clone $product->getOptionInstance();
                    $newProductOption
                        ->setData($templateOption->getData())
                        ->setOptionId(null)
                        ->setTemplateOptionId($templateOption->getOptionId());

                    $templateValues = $templateOption->getValues();
                    if (is_array($templateValues)) {
                        $newOptionValues = [];
                        foreach($templateValues as $value) {
                            $newOptionValue = clone $newProductOption->getValueInstance();
                            $newOptionValue->setData($value->getData())
                                ->setOptionTypeId(null)
                                ->setTemplateOptionTypeId($value->getOptionTypeId());
                            $newOptionValues[] = $newOptionValue;
                        }

                        $newProductOption->setValues($newOptionValues);
                    }

                    $remainingOptions[] = $newProductOption;
                }
            }

            // correct sort order for options
            if ($template->getSortOrder() > 0) {
                // correct sort order for primary options
                foreach($remainingOptions as $option) {
                    if ($option->getTemplateId() && $option->getTemplateId() != $template->getId()) {
                        $checkTemplate = $this->getById($option->getTemplateId());
                        if ($checkTemplate) {
                            if ($checkTemplate->getSortOrder() > $template->getSortOrder()) {
                                break;
                            }
                        }
                    }
                    $sortOrder++;
                    $option->setSortOrder($sortOrder);
                }

                // set sort order for current template options
                foreach($templateOptions as $templateOption) {
                    foreach($remainingOptions as $option) {
                        if ($option->getTemplateOptionId() == $templateOption->getId()) {
                            $sortOrder++;
                            $option->setSortOrder($sortOrder);
                            break;
                        }
                    }
                }

                // correct sort order for last options
                foreach($remainingOptions as $option) {
                    if ($option->getSortOrder()) {
                        continue;
                    }
                    $sortOrder++;
                    $option->setSortOrder($sortOrder);
                }

            } else {
                // set sort order for current template options
                foreach($templateOptions as $templateOption) {
                    foreach($remainingOptions as $option) {
                        if ($option->getTemplateOptionId() == $templateOption->getId()) {
                            $sortOrder++;
                            $option->setSortOrder($sortOrder);
                            break;
                        }
                    }
                }

                // set sort order options after options of current template
                foreach($remainingOptions as $option) {
                    if ($option->getTemplateId() != $template->getId()) {
                        $sortOrder++;
                        $option->setSortOrder($sortOrder);
                    }
                }
            }

            // update is_replace_product_sku
            if (!$product->getIsReplaceProductSku() && $template->getIsReplaceProductSku()) {
                $product->setIsReplaceProductSku(1);
            }

            $this->saveСorrectOptionsToProduct($product, $remainingOptions, 0);

            // update options store data
            $this->updateStoreOptionsToProduct($template, $productId);

        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function updateStoreOptionsToProduct(TemplateInterface $template, $productId)
    {
        // get used store IDs
        $usedStoreIds = $this->getUsedStoreIds($template->getId());

        foreach($usedStoreIds as $storeId) {
            try {
                /** @var $template \Aitoc\OptionsManagement\Model\Template */
                $template = $this->getById($template->getId(), $storeId);
                /** @var $product \Magento\Catalog\Model\Product */
                $product = $this->productRepository->getById($productId, true, $storeId);

                $templateOptions = $template->getOptions();
                $productOptions = $product->getOptions();

                foreach($productOptions as $option) {

                    if ($option->getTemplateId() && $option->getTemplateId() == $template->getId()) {

                        $templateOptionId = $option->getTemplateOptionId();

                        $templateOption = $this->getOptionById($templateOptions, $templateOptionId);

                        if (is_null($templateOption)) {
                            continue;
                        }

                        // update options
                        $optionId = $option->getOptionId();

                        $option->setData($templateOption->getData())
                            ->setOptionId($optionId)
                            ->setSortOrder(null)
                            ->setTemplateOptionId($templateOptionId);

                        $productValues = $option->getValues();

                        if (is_array($productValues)) {
                            $templateValues = $templateOption->getValues();

                            foreach($productValues as $value) {
                                $templateValueId = $value->getTemplateOptionTypeId();

                                $templateValue = $this->getOptionValueById($templateValues, $templateValueId);

                                if (is_null($templateValue)) {
                                    continue;
                                }

                                // update values
                                $valueId = $value->getOptionTypeId();
                                $value->setData($templateValue->getData())
                                    ->setOptionTypeId($valueId)
                                    ->setTemplateOptionTypeId($templateValueId);
                            }
                        }
                    } else {
                        $option->setIsSaveFromTemplate(true);
                    }
                }

                $this->saveСorrectOptionsToProduct($product, $productOptions, $storeId);

            } catch (\Exception $exception) {
                throw new CouldNotSaveException(__($exception->getMessage()));
            }
        }

        return $this;
    }

    /**
     * @param array $options
     * @param int $optionId
     * @return TemplateOptionInterface|null
     */
    public function getOptionById($options, $optionId)
    {
        foreach($options as $option) {
            if ($option->getId() == $optionId) {
                return $option;
            }
        }

        return null;
    }

    /**
     * @param array $values
     * @param int $valueId
     * @return TemplateOptionValueInterface|null
     */
    public function getOptionValueById($values, $valueId)
    {
        foreach($values as $value) {
            if ($value->getId() == $valueId) {
                return $value;
            }
        }

        return null;
    }

    /**
     * @param array $options
     * @param int $templateOptionId
     * @return TemplateOptionInterface|null
     */
    public function getOptionByTemplateOptionId($options, $templateOptionId)
    {
        foreach($options as $option) {
            if ($option->getTemplateOptionId() == $templateOptionId) {
                return $option;
            }
        }
        return null;
    }

    /**
     * @param $values
     * @param $templateOptionTypeId
     * @return TemplateOptionValueInterface|null
     */
    public function getOptionValueByTemplateOptionTypeId($values, $templateOptionTypeId)
    {
        foreach($values as $value) {
            if ($value->getTemplateOptionTypeId() == $templateOptionTypeId) {
                return $value;
            }
        }
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function removeOptionsFromProduct(TemplateInterface $template, $productId, $keepOptionsOnUnlink = 0)
    {
        try {
            /** @var $product \Magento\Catalog\Model\Product */
            $product = $this->productRepository->getById($productId, true, 0);

            if (!$keepOptionsOnUnlink) {
                $options = $product->getOptions();
                $remainingOptions = [];
                $sortOrder = 0;
                foreach($options as $option) {
                    if ($option->getTemplateId() && $option->getTemplateId() == $template->getId()) {
                        $option->delete();
                    } else {
                        $sortOrder++;
                        $option->setSortOrder($sortOrder);
                        $remainingOptions[] = $option;
                    }
                }
                $this->saveСorrectOptionsToProduct($product, $remainingOptions, 0);
            }

            $this->templateResource->removeProductOptionRelation($template, $product);

        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }

        return $this;
    }

    /**
     * @param \Magento\Catalog\Model\Product $product
     * @param array $options
     * @param int $storeId
     * @throws \Exception
     */
    protected function saveСorrectOptionsToProduct($product, array $options, $storeId = 0)
    {
        // fix for m2.1.x (null price and price_type)
        if ($this->helper->isMagento21x()) {
            foreach($options as $option) {
                if ($option->getGroupByType() == 'select') {
                    $values = $option->getValues();
                    if (is_array($values)) {
                        foreach($values as $value) {
                            if (!$value->getPrice()) {
                                $value->setPrice(0);
                                $value->setPriceType('fixed');
                            }
                        }
                    }
                }
            }
        }

        $saveProduct = $this->productFactory->create();
        $saveProduct
            ->setId($product->getId())
            ->setSku($product->getSku())
            ->setStoreId($storeId)
            ->setCanSaveCustomOptions(true)
            ->setOptions($options)
            ->setIsReplaceProductSku($product->getIsReplaceProductSku())
            ->setSaveOnlyOptions(true);

        $this->entityManager->save($saveProduct);
        $this->productRepository->cleanCache();
    }

    /**
     * {@inheritdoc}
     */
    public function duplicate(TemplateInterface $oldTemplate)
    {
        try {
            // get template only by default store
            if ($oldTemplate->getStoreId() > 0) {
                $oldTemplate = $this->getById($oldTemplate->getId(), 0);
            }

            $template = clone $oldTemplate;
            $options = $template->getOptions();

            $template
                ->setTemplateId(null)
                ->setTitle($template->getTitle() . __('_duplicate'))
                ->setProducts([])
                ->setPostedProducts([]);

            $template->setOptions([]);

            $optionsData = [];
            foreach($options as $option) {

                $option->setOptionId(null);
                $data = $option->getData();

                $values = $option->getValues();
                if ($values) {
                    $valuesData = [];
                    foreach($values as $value) {
                        $value->setOptionTypeId(null);
                        $valuesData[] = $value->getData();
                    }
                    $data['values'] = $valuesData;
                }

                $optionsData[] = $data;
            }

            $template->setData('options', $optionsData);

            $newTemplate = $this->save($template);

            $this->duplicateStoreData($oldTemplate, $newTemplate);
            return $newTemplate;

        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
    }

    /**
     * @param TemplateInterface $oldTemplate
     * @param TemplateInterface $newTemplate
     * @return $this
     * @throws CouldNotSaveException
     */
    public function duplicateStoreData(TemplateInterface $oldTemplate, TemplateInterface $newTemplate)
    {
        // get used store IDs
        $usedStoreIds = $this->getUsedStoreIds($oldTemplate->getId());

        foreach($usedStoreIds as $storeId) {
            try {
                /** @var $oldTemplateByStore \Aitoc\OptionsManagement\Model\Template */
                $oldTemplateByStore = $this->getById($oldTemplate->getId(), $storeId);
                $oldStoreOptions = array_values($oldTemplateByStore->getOptions());

                /** @var $newTemplateByStore \Aitoc\OptionsManagement\Model\Template */
                $newTemplateByStore = $this->getById($newTemplate->getId(), $storeId);
                $newStoreOptions = array_values($newTemplateByStore->getOptions());

                $optionsData = [];
                foreach($oldStoreOptions as $index => $oldOption) {
                    $newOption = $newStoreOptions[$index];

                    $data = $newOption->getData();

                    if (is_null($oldOption->getStoreTitle())) {
                        $data['is_delete_store_title'] = 1;
                    } else {
                        $data['title'] = $oldOption->getStoreTitle();
                    }

                    if (is_null($oldOption->getStoreDefaultText())) {
                        $data['is_delete_store_default_text'] = 1;
                    } else {
                        $data['default_text'] = $oldOption->getStoreDefaultText();
                    }

                    if (is_null($oldOption->getStoreIsEnable())) {
                        $data['is_delete_store_is_enable'] = 1;
                    } else {
                        $data['is_enable'] = $oldOption->getStoreIsEnable();
                    }

                    //is_delete_store_title

                    $oldValues = $oldOption->getValues();
                    if ($oldValues) {
                        $oldValues = array_values($oldValues);
                        $newValues = array_values($newOption->getValues());

                        $valuesData = [];
                        foreach($oldValues as $i => $oldValue) {
                            $newValue = $newValues[$i];
                            $valueData = $newValue->getData();

                            if (is_null($oldValue->getStoreTitle())) {
                                $valueData['is_delete_store_title'] = 1;
                            } else {
                                $valueData['title'] = $oldValue->getStoreTitle();
                            }

                            $valuesData[] = $valueData;
                        }
                        $data['values'] = $valuesData;
                    }

                    $optionsData[] = $data;
                }

                $newTemplateByStore->setData('options', $optionsData);
                $this->save($newTemplateByStore);

            } catch (\Exception $exception) {
                throw new CouldNotSaveException(__($exception->getMessage()));
            }
        }

        return $this;
    }

    /**
     * Retrieve array of used store ids in template
     *
     * @param int $templateId
     * @return array
     */
    public function getUsedStoreIds($templateId)
    {
        // get used store IDs
        if (!isset($this->usedStoreIds[$templateId])) {
            $this->usedStoreIds[$templateId] = $this->templateResource->getUsedStoreIds(
                $templateId,
                $this->helper->isDefaultValueEnabled(),
                $this->helper->isEnabledPerOptionEnabled()
            );
        }
        return $this->usedStoreIds[$templateId];
    }
}
