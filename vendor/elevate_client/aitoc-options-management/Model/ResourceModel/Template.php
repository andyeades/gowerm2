<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2019 Aitoc (https://www.aitoc.com)
 * @package Aitoc_OptionsManagement
 */

/**
 * Copyright © 2018 Aitoc. All rights reserved.
 */
namespace Aitoc\OptionsManagement\Model\ResourceModel;

/**
 * Class Template
 */
class Template extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Define main table
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('aitoc_optionsmanagement_template', 'template_id');
    }

    /**
     * @param \Magento\Framework\Model\AbstractModel|\Magento\Framework\DataObject $object
     * @return $this
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function _afterSave(\Magento\Framework\Model\AbstractModel $object)
    {
        $this->saveOptions($object);
        $this->saveProducts($object);
        return parent::_afterSave($object);
    }

    /**
     * @return string
     */
    public function getRelationProductTable()
    {
        return $this->getTable('aitoc_optionsmanagement_template_product');
    }

    /**
     * Retrieve array of product id's for template
     *
     * @param \Aitoc\OptionsManagement\Model\Template $template
     * @return array
     */
    public function getProducts($template)
    {
        $select = $this->getConnection()->select()
            ->distinct(true)
            ->from($this->getRelationProductTable(), ['product_id'])
            ->where('template_id = ?', (int)$template->getId());

        return $this->getConnection()->fetchCol($select);
    }

    /**
     * Save template products relation
     *
     * @param \Aitoc\OptionsManagement\Model\Template $template
     * @return $this
     */
    protected function saveProducts($template)
    {
        $products = $template->getPostedProducts();
        /** @var \Aitoc\OptionsManagement\Model\TemplateRepository $templateRepository */
        $templateRepository = $template->getTemplateRepository();

        if ($products === null) {
            $update = $template->getProducts();
        } else {
            /**
             * old template-product relationships
             */
            $oldProducts = $template->getProducts();

            $insert = array_diff($products, $oldProducts);
            $delete = array_diff($oldProducts, $products);
            $update = array_diff($oldProducts, $delete, $insert);

            $id = $template->getId();
            $connection = $this->getConnection();

            /**
             * Delete products from template
             */
            if (!empty($delete)) {
                foreach ($delete as $productId) {
                    $templateRepository->removeOptionsFromProduct($template, $productId);
                }
            }

            /**
             * Add products to template
             */
            if (!empty($insert)) {
                foreach ($insert as $productId) {
                    $templateRepository->assignOptionsToProduct($template, $productId);
                }
            }
        }

        /**
         * Update products to template
         */
        if (!empty($update)) {
            foreach ($update as $productId) {
                $templateRepository->updateOptionsToProduct($template, $productId);
            }
        }

        return $this;
    }

    /**
     * Save template options
     *
     * @param $template
     * @return $this
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    protected function saveOptions($template)
    {
        $options = $template->getData('options');
        $origOptions = $template->getOptions();
        if (!is_array($origOptions)) {
            $origOptions = [];
        }
        $optionRepository = $template->getOptionRepository();

        $newOptions = [];
        if (!$options) {
            throw new \Magento\Framework\Exception\CouldNotSaveException(
                __('Unable to save template ID: %1. Options are missing.', $template->getTemplateId())
            );
        }

        foreach($options as $option) {
            if (isset($option['is_delete']) && $option['is_delete']) {
                continue;
            } elseif (isset($option['option_id']) && $option['option_id'] > 0) {
                // update option
                foreach($origOptions as $origKey => $origOption) {
                    if ($origOption->getId() == $option['option_id']) {
                        $origOption->addData($option);
                        $newOptions[] = $origOption;
                        unset($origOptions[$origKey]);
                        break;
                    }
                }
            } else {
                // add new option
                $option['option_id'] = null;
                $option['template_id'] = $template->getId();
                $newOptions[] = $optionRepository->getEmpty()->addData($option);
            }
        }

        // remove options
        foreach($origOptions as $option) {
            $optionRepository->delete($option);
        }

        if (!$newOptions) {
            return $this;
        }

        foreach($newOptions as $option) {
            $optionRepository->save($option);
        }

        $template->resetOptions();

        return $this;
    }

    /**
     * Add product relation
     *
     * @param \Aitoc\OptionsManagement\Model\Template $template
     * @param int $productId
     * @return int
     */
    public function addProductRelation($template, $productId)
    {
        $connection = $this->getConnection();
        $data = ['template_id' => (int)$template->getId(), 'product_id' => (int)$productId];
        return $connection->insert($this->getRelationProductTable(), $data);
    }

    /**
     * Remove product option relation
     *
     * @param \Aitoc\OptionsManagement\Model\Template $template
     * @param \Magento\Catalog\Model\Product $product
     * @return int
     */
    public function removeProductOptionRelation($template, $product)
    {
        $connection = $this->getConnection();

        $productOptionIds = [];
        $productOptionValueIds = [];
        $options = $product->getOptions();

        foreach($options as $option) {
            if ($option->getTemplateId() && $option->getTemplateId() == $template->getId()) {
                $productOptionIds[] = $option->getId();
                $values = $option->getValues();
                if ($values) {
                    foreach($values as $value) {
                        $productOptionValueIds[] = $value->getOptionTypeId();
                    }
                }
            }
        }

        $connection->delete(
            $this->getTable('aitoc_optionsmanagement_template_product_option_type_value'),
            ['product_option_type_id IN (?)' => $productOptionValueIds]
        );

        $connection->delete(
            $this->getTable('aitoc_optionsmanagement_template_product_option'),
            ['product_option_id IN (?)' => $productOptionIds]
        );

        return $connection->delete(
            $this->getRelationProductTable(),
            ['template_id = ?' => $template->getId(), 'product_id = ?' => $product->getId()]
        );
    }

    /**
     * Remove related product option
     *
     * @param \Aitoc\OptionsManagement\Model\Template $template
     * @return int
     */
    public function removeRelatedProductOption(\Aitoc\OptionsManagement\Model\Template $template)
    {
        $productIds = $this->getProducts($template);
        $connection = $this->getConnection();

        $select = $connection->select()
            ->from(['template_option' => $this->getTable('aitoc_optionsmanagement_template_option')], [])
            ->joinLeft(
                ['template_product_option' => $this->getTable('aitoc_optionsmanagement_template_product_option')],
                'template_option.option_id = template_product_option.template_option_id',
                'product_option_id')
            ->where('template_option.template_id = ?', $template->getId());

        $productOptionIds = $connection->fetchCol($select);

        if ($productOptionIds) {
            $connection->delete(
                $this->getTable('catalog_product_option'),
                ['option_id IN (?)' => $productOptionIds]
            );
        }


        // update has_options flag
        $select = $connection->select()->distinct(true)
            ->from($this->getTable('catalog_product_option'), ['product_id'])
            ->where('product_id IN (?)', $productIds);
        $hasOptionProductIds = $connection->fetchCol($select);

        $noOptionProductIds = array_diff($productIds, $hasOptionProductIds);
        if ($noOptionProductIds) {
            $connection->update(
                $this->getTable('catalog_product_entity'),
                ['has_options' => 0],
                ['entity_id IN (?)' => $noOptionProductIds]
            );
        }

        // update required_options flag
        $select = $connection->select()->distinct(true)
            ->from($this->getTable('catalog_product_option'), ['product_id'])
            ->where('product_id IN (?)', $productIds)
            ->where('is_require = 1');
        $requireOptionProductIds = $connection->fetchCol($select);

        $noRequireOptionProductIds = array_diff($productIds, $requireOptionProductIds);
        if ($noRequireOptionProductIds) {
            $connection->update(
                $this->getTable('catalog_product_entity'),
                ['required_options' => 0],
                ['entity_id IN (?)' => $noRequireOptionProductIds]
            );
        }

        return $productIds;
    }

    /**
     * Retrieve array of used store ids in template
     *
     * @param int $templateId
     * @param bool $useDefaultTable
     * @param bool $useIsEnableTable
     * @return array
     */
    public function getUsedStoreIds($templateId, $useDefaultTable = false, $useIsEnableTable = false)
    {
        $select = $this->getConnection()->select()
            ->distinct(true)
            ->from(
                ['template_option_title' => $this->getTable('aitoc_optionsmanagement_template_option_title')],
                ['store_id']
            )
            ->join(
                ['template_option' => $this->getTable('aitoc_optionsmanagement_template_option')],
                'template_option.option_id = template_option_title.option_id',
                [])
            ->where('template_option.template_id = ?', (int)$templateId)
            ->where('template_option_title.store_id > 0');
        $titleStoreIds = $this->getConnection()->fetchCol($select);

        if ($useDefaultTable) {
            $select = $this->getConnection()->select()
                ->distinct(true)
                ->from(
                    ['template_option_default' => $this->getTable('aitoc_optionsmanagement_template_option_default')],
                    ['store_id']
                )
                ->join(
                    ['template_option' => $this->getTable('aitoc_optionsmanagement_template_option')],
                    'template_option.option_id = template_option_default.option_id',
                    [])
                ->where('template_option.template_id = ?', (int)$templateId)
                ->where('template_option_default.store_id > 0');
            $defaultStoreIds = $this->getConnection()->fetchCol($select);
        } else {
            $defaultStoreIds = [];
        }

        if ($useIsEnableTable) {
            $select = $this->getConnection()->select()
                ->distinct(true)
                ->from(
                    ['template_option_is_enable' => $this->getTable('aitoc_optionsmanagement_template_option_is_enable')],
                    ['store_id']
                )
                ->join(
                    ['template_option' => $this->getTable('aitoc_optionsmanagement_template_option')],
                    'template_option.option_id = template_option_is_enable.option_id',
                    [])
                ->where('template_option.template_id = ?', (int)$templateId)
                ->where('template_option_is_enable.store_id > 0');
            $isEnableStoreIds = $this->getConnection()->fetchCol($select);
        } else {
            $isEnableStoreIds = [];
        }

        $select = $this->getConnection()->select()
            ->distinct(true)
            ->from(
                ['template_option_type_title' => $this->getTable('aitoc_optionsmanagement_template_option_type_title')],
                ['store_id']
            )
            ->join(
                ['template_option_type_value' => $this->getTable('aitoc_optionsmanagement_template_option_type_value')],
                'template_option_type_value.option_type_id = template_option_type_title.option_type_id',
                [])
            ->join(
                ['template_option' => $this->getTable('aitoc_optionsmanagement_template_option')],
                'template_option.option_id = template_option_type_value.option_id',
                [])
            ->where('template_option.template_id = ?', (int)$templateId)
            ->where('template_option_type_title.store_id > 0');
        $titleValueStoreIds = $this->getConnection()->fetchCol($select);

        $storeIds = array_merge($titleStoreIds, $defaultStoreIds, $isEnableStoreIds, $titleValueStoreIds);
        $storeIds = array_unique($storeIds);

        return $storeIds;
    }
}
