<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2019 Aitoc (https://www.aitoc.com)
 * @package Aitoc_OptionsManagement
 */

/**
 * Copyright © 2018 Aitoc. All rights reserved.
 */
namespace Aitoc\OptionsManagement\Model\Template;

use Aitoc\OptionsManagement\Api\Data\TemplateOptionInterface;
use Aitoc\OptionsManagement\Model\ResourceModel\Template\Option as ResourceOption;
use Aitoc\OptionsManagement\Model\ResourceModel\Template\Option\CollectionFactory;
use Aitoc\OptionsManagement\Api\Data\TemplateOptionSearchResultInterfaceFactory;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\EntityManager\MetadataPool;
use Magento\Framework\App\ObjectManager;

class OptionRepository implements \Aitoc\OptionsManagement\Api\TemplateOptionRepositoryInterface
{
    /**
     * @var OptionFactory
     */
    protected $optionFactory;

    /**
     * @var ResourceOption
     */
    protected $optionResource;

    /**
     * @var CollectionFactory;
     */
    protected $collectionFactory;

    /**
     * @var TemplateOptionSearchResultInterfaceFactory
     */
    protected $searchResultFactory;

    /**
     * @var MetadataPool
     */
    protected $metadataPool;

    /**
     * @var Converter
     */
    protected $converter;

    /**
     * Constructor
     *
     * @param OptionFactory|null $optionFactory
     * @param ResourceOption $optionResource
     * @param CollectionFactory|null $collectionFactory
     * @param \Magento\Catalog\Model\Product\Option\Converter $converter
     * @param \Magento\Framework\EntityManager\MetadataPool|null $metadataPool
     */
    public function __construct(
        OptionFactory $optionFactory = null,
        ResourceOption $optionResource,
        CollectionFactory $collectionFactory = null,
        \Magento\Catalog\Model\Product\Option\Converter $converter,
        \Magento\Framework\EntityManager\MetadataPool $metadataPool = null
    ) {
        $this->optionFactory = $optionFactory ?: ObjectManager::getInstance()->get(OptionFactory::class);
        $this->optionResource = $optionResource;
        $this->collectionFactory = $collectionFactory ?: ObjectManager::getInstance()->get(CollectionFactory::class);
        $this->converter = $converter;
        $this->metadataPool = $metadataPool ?: ObjectManager::getInstance()->get(\Magento\Framework\EntityManager\MetadataPool::class);
    }

    /**
     * {@inheritdoc}
     */
    public function getById($optionId)
    {
        $template = $this->optionFactory->create();
        $this->optionFactory->load($template, $optionId);
        if (!$template->getId()) {
            throw new NoSuchEntityException(__('Template option with id "%1" does not exist.', $optionId));
        }
        return $template;
    }

    /**
     * Retrieve empty option.
     *
     * @return TemplateOptionInterface
     */
    public function getEmpty()
    {
        return $this->optionFactory->create();
    }

    /**
     * {@inheritdoc}
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria)
    {
        /** @var \Aitoc\OptionsManagement\Model\ResourceModel\Template\Option\Collection $collection */
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

        /** @var \Aitoc\OptionsManagement\Api\Data\TemplateOptionSearchResultInterface $searchResult */
        $searchResult = $this->searchResultFactory->create();
        $searchResult->setSearchCriteria($searchCriteria);
        $searchResult->setItems($collection->getItems());
        $searchResult->setTotalCount($collection->getSize());
        return $searchResult;
    }

    public function getTemplateOptions($templateId, $storeId = 0)
    {
        /** @var \Aitoc\OptionsManagement\Model\ResourceModel\Template\Option\Collection $collection */
        $collection = $this->collectionFactory->create();
        return $collection->getOptionsByTemplate($templateId, $storeId);
    }

    /**
     * {@inheritdoc}
     */
    public function delete(TemplateOptionInterface $option)
    {
        try {
            $this->removeRelatedProductOption($option);
            $this->optionResource->delete($option);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
        return true;
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
    public function save(TemplateOptionInterface $option)
    {
        try {
            $this->optionResource->save($option);
        } catch (\Exception $e) {
            throw new CouldNotSaveException(__('Unable to save template option %1', $option->getOptionId()));
        }
        return $option;
    }

    /**
     * @param \Magento\Catalog\Model\Product\Option $productOption
     * @return int
     * @throws CouldNotSaveException
     */
    public function saveRelation(\Magento\Catalog\Model\Product\Option $productOption)
    {
        try {
            return $this->optionResource->saveRelation($productOption);
        } catch (\Exception $e) {
            throw new CouldNotSaveException(__('Unable to save option relation ID %1', $productOption->getId()));
        }
    }

    /**
     * @param TemplateOptionInterface $option
     * @return int
     * @throws CouldNotDeleteException
     */
    public function removeRelatedProductOption(TemplateOptionInterface $option)
    {
        try {
            return $this->optionResource->removeRelatedProductOption($option);
        } catch (\Exception $e) {
            throw new CouldNotDeleteException(__('Unable to remove related product option ID %1', $option->getId()));
        }
    }
}
