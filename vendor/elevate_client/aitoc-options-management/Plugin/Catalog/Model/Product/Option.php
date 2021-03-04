<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2019 Aitoc (https://www.aitoc.com)
 * @package Aitoc_OptionsManagement
 */

/**
 * Copyright © 2018 Aitoc. All rights reserved.
 */
namespace Aitoc\OptionsManagement\Plugin\Catalog\Model\Product;

class Option
{
    /**
     * @var \Aitoc\OptionsManagement\Model\Template\OptionRepository
     */
    protected $optionRepository;

    /**
     * @var \Aitoc\OptionsManagement\Helper\Data
     */
    protected $helper;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\Option
     */
    protected $optionResource;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry = null;

    /**
     * @var \Magento\Catalog\Model\Product
     */
    protected $product = null;

    /**
     * Option constructor.
     * @param \Aitoc\OptionsManagement\Model\Template\OptionRepository $optionRepository
     * @param \Aitoc\OptionsManagement\Helper\Data $helper
     */
    public function __construct(
        \Aitoc\OptionsManagement\Model\Template\OptionRepository $optionRepository,
        \Aitoc\OptionsManagement\Helper\Data $helper,
        \Magento\Catalog\Model\ResourceModel\Product\Option $optionResource,
        \Magento\Framework\Registry $coreRegistry
    ) {
        $this->optionRepository = $optionRepository;
        $this->helper = $helper;
        $this->optionResource = $optionResource;
        $this->coreRegistry = $coreRegistry;
    }

    /**
     * @param \Magento\Catalog\Model\Product\Option $option
     * @param \Magento\Catalog\Model\Product $product
     * @return array
     */
    public function beforeGetProductOptionCollection($option, \Magento\Catalog\Model\Product $product)
    {
        $this->product = $product;
        return [$product];
    }

    /**
     * @param \Magento\Catalog\Model\Product\Option $option
     * @param \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection $result
     * @return \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
     */
    public function afterGetProductOptionCollection($option, $result)
    {
        if ($result && !$this->product->getData('_edit_mode') && $this->helper->isEnabledPerOptionEnabled()
            && !$this->coreRegistry->registry('option_edit_mode')
        ) {
            $options = $result;
            $result = [];
            foreach($options as $option) {
                if ($option->getIsEnable()) {
                    $result[] = $option;
                }
            }
        }
        return $result;
    }

    /**
     * Fix magento bug with store_id
     *
     * @param \Magento\Catalog\Model\Product\Option $option
     * @param \Magento\Catalog\Model\Product\Option $result
     * @return \Magento\Catalog\Model\Product\Option
     */
    public function afterBeforeSave($option, $result)
    {
        $product = $option->getProduct();

        if ($product && is_object($product)) {
            $option->setStoreId($product->getStoreId());
        }

        if ($option->getStoreId() == 0 || (!$option->getTemplateId() && !$option->getIsSaveFromTemplate()) ) {
            return $result;
        }

        if (!$option->getData('store_title')) {
            $option->setData('is_delete_store_title', 1);
        } else {
            if ($option->getData('title') != $option->getData('store_title')) {
                $option->setData('title', $option->getData('store_title'));
                $option->setData('is_delete_store_title', 0);
            }
        }

        if ($values = $option->getValues()) {
            foreach($values as $value) {
                if (!$value->getData('store_title')) {
                    $value->setData('is_delete_store_title', 1);
                } else {
                    if ($value->getData('title') != $value->getData('store_title')) {
                        $value->setData('title', $value->getData('store_title'));
                        $value->setData('is_delete_store_title', 0);
                    }
                }
            }
        } elseif ($values = $option->getData('values')) {
            foreach($values as &$value) {
                if (!isset($value['store_title']) || !$value['store_title']) {
                    $value['is_delete_store_title'] = 1;
                } else {
                    if ($value['title'] != $value['store_title']) {
                        $value['title'] = $value['store_title'];
                        $value['is_delete_store_title'] = 0;
                    }
                }
            }
            $option->setData('values', $values);
        }

        if ($this->helper->isDefaultValueEnabled()) {
            if (!$option->getData('store_default_text')) {
                $option->setData('is_delete_store_default_text', 1);
            } else {
                if ($option->getData('default_text') != $option->getData('store_default_text')) {
                    $option->setData('default_text', $option->getData('store_default_text'));
                    $option->setData('is_delete_store_default_text', 0);
                }
            }
        }

        if ($this->helper->isEnabledPerOptionEnabled()) {
            if (is_null($option->getData('store_is_enable'))) {
                $option->setData('is_delete_store_is_enable', 1);
            } else {
                if ($option->getData('is_enable') != $option->getData('store_is_enable')) {
                    $option->setData('is_enable', $option->getData('store_is_enable'));
                    $option->setData('is_delete_store_is_enable', 0);
                }
            }
        }

        return $result;
    }

    /**
     * @param \Magento\Catalog\Model\Product\Option $option
     * @param \Magento\Catalog\Model\Product\Option $result
     * @return \Magento\Catalog\Model\Product\Option
     */
    public function afterAfterSave($option, $result)
    {
        if ($option->getTemplateOptionId()) {
            $this->optionRepository->saveRelation($option);
        }

        if ($this->helper->isDefaultValueEnabled()) {
            $this->saveDefaultText($option);
        }

        if ($this->helper->isEnabledPerOptionEnabled()) {
            $this->saveIsEnable($option);
        }

        return $result;
    }

    /**
     * Save Default Text
     *
     * @param \Magento\Catalog\Model\Product\Option $option
     */
    protected function saveDefaultText(\Magento\Catalog\Model\Product\Option $option)
    {
        $connection = $this->optionResource->getConnection();
        $defaultTableName = $this->optionResource->getTable('catalog_product_option_default');
        foreach ([\Magento\Store\Model\Store::DEFAULT_STORE_ID, $option->getStoreId()] as $storeId) {
            $existInCurrentStore = $this->getColFromOptionTable($defaultTableName, (int)$option->getId(), (int)$storeId);
            $existInDefaultStore = (int)$storeId == \Magento\Store\Model\Store::DEFAULT_STORE_ID ?
                $existInCurrentStore :
                $this->getColFromOptionTable(
                    $defaultTableName,
                    (int)$option->getId(),
                    \Magento\Store\Model\Store::DEFAULT_STORE_ID
                );

            if (!is_null($option->getDefaultText())) {
                $isDeleteStoreDefaultText = (bool)$option->getData('is_delete_store_default_text');
                if ($existInCurrentStore) {
                    if ($isDeleteStoreDefaultText && (int)$storeId != \Magento\Store\Model\Store::DEFAULT_STORE_ID) {
                        $connection->delete($defaultTableName, ['option_default_id = ?' => $existInCurrentStore]);
                    } elseif ($option->getStoreId() == $storeId) {
                        $data = ['default_text' => $option->getDefaultText()];
                        $connection->update(
                            $defaultTableName,
                            $data,
                            [
                                'option_id = ?' => $option->getId(),
                                'store_id  = ?' => $storeId,
                            ]
                        );
                    }
                } else {
                    // we should insert record into not default store only of if it does not exist in default store
                    if (($storeId == \Magento\Store\Model\Store::DEFAULT_STORE_ID && !$existInDefaultStore) ||
                        (
                            $storeId != \Magento\Store\Model\Store::DEFAULT_STORE_ID &&
                            !$existInCurrentStore &&
                            !$isDeleteStoreDefaultText
                        )
                    ) {
                        $data = [
                            'option_id' => $option->getId(),
                            'store_id' => $storeId,
                            'default_text' => $option->getDefaultText(),
                        ];
                        $connection->insert($defaultTableName, $data);
                    }
                }
            } else {
                if ($option->getId() && $option->getStoreId() > \Magento\Store\Model\Store::DEFAULT_STORE_ID
                    && $storeId
                ) {
                    $connection->delete(
                        $defaultTableName,
                        [
                            'option_id = ?' => $option->getId(),
                            'store_id  = ?' => $option->getStoreId(),
                        ]
                    );
                }
            }
        }
    }

    /**
     * Save Is Enable
     *
     * @param \Magento\Catalog\Model\Product\Option $option
     */
    protected function saveIsEnable(\Magento\Catalog\Model\Product\Option $option)
    {
        $connection = $this->optionResource->getConnection();
        $isEnableTableName = $this->optionResource->getTable('catalog_product_option_is_enable');
        foreach ([\Magento\Store\Model\Store::DEFAULT_STORE_ID, $option->getStoreId()] as $storeId) {
            $existInCurrentStore = $this->getColFromOptionTable($isEnableTableName, (int)$option->getId(), (int)$storeId);
            $existInDefaultStore = (int)$storeId == \Magento\Store\Model\Store::DEFAULT_STORE_ID ?
                $existInCurrentStore :
                $this->getColFromOptionTable(
                    $isEnableTableName,
                    (int)$option->getId(),
                    \Magento\Store\Model\Store::DEFAULT_STORE_ID
                );




            if (!is_null($option->getIsEnable())) {
                $isDeleteStoreIsEnable = (bool)$option->getData('is_delete_store_is_enable');

                if ($existInCurrentStore) {
                    if ($isDeleteStoreIsEnable && (int)$storeId != \Magento\Store\Model\Store::DEFAULT_STORE_ID) {
                        $connection->delete($isEnableTableName, ['option_is_enable_id = ?' => $existInCurrentStore]);
                    } elseif ($option->getStoreId() == $storeId) {
                        $data = ['is_enable' => (int)$option->getIsEnable()];
                        $connection->update(
                            $isEnableTableName,
                            $data,
                            [
                                'option_id = ?' => $option->getId(),
                                'store_id  = ?' => $storeId,
                            ]
                        );
                    }
                } else {
                    // we should insert record into not default store only of if it does not exist in default store
                    if (($storeId == \Magento\Store\Model\Store::DEFAULT_STORE_ID && !$existInDefaultStore) ||
                        (
                            $storeId != \Magento\Store\Model\Store::DEFAULT_STORE_ID &&
                            !$existInCurrentStore &&
                            !$isDeleteStoreIsEnable
                        )
                    ) {
                        $data = [
                            'option_id' => $option->getId(),
                            'store_id' => $storeId,
                            'is_enable' => (int)$option->getIsEnable(),
                        ];
                        $connection->insert($isEnableTableName, $data);
                    }
                }
            } else {
                if ($option->getId() && $option->getStoreId() > \Magento\Store\Model\Store::DEFAULT_STORE_ID
                    && $storeId
                ) {
                    $connection->delete(
                        $isEnableTableName,
                        [
                            'option_id = ?' => $option->getId(),
                            'store_id  = ?' => $option->getStoreId(),
                        ]
                    );
                }
            }
        }
    }

    /**
     * Get first col from from first row for option table
     *
     * @param string $tableName
     * @param int $optionId
     * @param int $storeId
     * @return string
     */
    protected function getColFromOptionTable($tableName, $optionId, $storeId)
    {
        $connection = $this->optionResource->getConnection();
        $statement = $connection->select()->from(
            $tableName
        )->where(
            'option_id = ?',
            $optionId
        )->where(
            'store_id  = ?',
            $storeId
        );

        return $connection->fetchOne($statement);
    }
}
