<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2019 Aitoc (https://www.aitoc.com)
 * @package Aitoc_OptionsManagement
 */

/**
 * Copyright © 2018 Aitoc. All rights reserved.
 */
namespace Aitoc\OptionsManagement\Model\ResourceModel\Template\Option;

/**
 * Template custom option resource model
 *
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Value extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Store manager
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * Currency factory
     *
     * @var \Magento\Directory\Model\CurrencyFactory
     */
    protected $_currencyFactory;

    /**
     * Core config model
     *
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_config;

    /**
     * @var \Magento\Framework\Locale\FormatInterface
     */
    private $localeFormat;

    /**
     * Class constructor
     *
     * @param \Magento\Framework\Model\ResourceModel\Db\Context $context
     * @param \Magento\Directory\Model\CurrencyFactory $currencyFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $config
     * @param string $connectionName
     */
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        \Magento\Directory\Model\CurrencyFactory $currencyFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\Config\ScopeConfigInterface $config,
        $connectionName = null
    ) {
        $this->_currencyFactory = $currencyFactory;
        $this->_storeManager = $storeManager;
        $this->_config = $config;
        parent::__construct($context, $connectionName);
    }

    /**
     * Define main table and initialize connection
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('aitoc_optionsmanagement_template_option_type_value', 'option_type_id');
    }

    /**
     * Proceed operations after object is saved
     * Save options store data
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return \Magento\Framework\Model\ResourceModel\Db\AbstractDb
     */
    protected function _afterSave(\Magento\Framework\Model\AbstractModel $object)
    {
        $this->_saveValuePrices($object);
        $this->_saveValueTitles($object);

        return parent::_afterSave($object);
    }

    /**
     * Save option value price data
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return void
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _saveValuePrices(\Magento\Framework\Model\AbstractModel $object)
    {
        $priceTable = $this->getTable('aitoc_optionsmanagement_template_option_type_price');
        $formattedPrice = $this->getLocaleFormatter()->getNumber($object->getPrice());

        $price = (double)sprintf('%F', $formattedPrice);
        $priceType = $object->getPriceType();

        if (!is_null($object->getPrice()) && $priceType) {
            //save for store_id = 0
            $select = $this->getConnection()->select()->from(
                $priceTable,
                'option_type_id'
            )->where(
                'option_type_id = ?',
                (int)$object->getId()
            )->where(
                'store_id = ?',
                \Magento\Store\Model\Store::DEFAULT_STORE_ID
            );
            $optionTypeId = $this->getConnection()->fetchOne($select);

            if ($optionTypeId) {
                if ($object->getStoreId() == '0') {
                    $bind = ['price' => $price, 'price_type' => $priceType];
                    $where = [
                        'option_type_id = ?' => $optionTypeId,
                        'store_id = ?' => \Magento\Store\Model\Store::DEFAULT_STORE_ID,
                    ];

                    $this->getConnection()->update($priceTable, $bind, $where);
                }
            } else {
                $bind = [
                    'option_type_id' => (int)$object->getId(),
                    'store_id' => \Magento\Store\Model\Store::DEFAULT_STORE_ID,
                    'price' => $price,
                    'price_type' => $priceType,
                ];
                $this->getConnection()->insert($priceTable, $bind);
            }
        }

        $scope = (int)$this->_config->getValue(
            \Magento\Store\Model\Store::XML_PATH_PRICE_SCOPE,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

        if ($scope == \Magento\Store\Model\Store::PRICE_SCOPE_WEBSITE
            && $priceType
            && !is_null($object->getPrice())
            && $object->getStoreId() != \Magento\Store\Model\Store::DEFAULT_STORE_ID
        ) {
            $baseCurrency = $this->_config->getValue(
                \Magento\Directory\Model\Currency::XML_PATH_CURRENCY_BASE,
                'default'
            );

            $storeIds = $this->_storeManager->getStore($object->getStoreId())->getWebsite()->getStoreIds();
            if (is_array($storeIds)) {
                foreach ($storeIds as $storeId) {
                    if ($priceType == 'fixed') {
                        $storeCurrency = $this->_storeManager->getStore($storeId)->getBaseCurrencyCode();
                        /** @var $currencyModel \Magento\Directory\Model\Currency */
                        $currencyModel = $this->_currencyFactory->create();
                        $currencyModel->load($baseCurrency);
                        $rate = $currencyModel->getRate($storeCurrency);
                        if (!$rate) {
                            $rate = 1;
                        }
                        $newPrice = $price * $rate;
                    } else {
                        $newPrice = $price;
                    }

                    $select = $this->getConnection()->select()->from(
                        $priceTable,
                        'option_type_id'
                    )->where(
                        'option_type_id = ?',
                        (int)$object->getId()
                    )->where(
                        'store_id = ?',
                        (int)$storeId
                    );
                    $optionTypeId = $this->getConnection()->fetchOne($select);

                    if ($optionTypeId) {
                        $bind = ['price' => $newPrice, 'price_type' => $priceType];
                        $where = ['option_type_id = ?' => (int)$optionTypeId, 'store_id = ?' => (int)$storeId];

                        $this->getConnection()->update($priceTable, $bind, $where);
                    } else {
                        $bind = [
                            'option_type_id' => (int)$object->getId(),
                            'store_id' => (int)$storeId,
                            'price' => $newPrice,
                            'price_type' => $priceType,
                        ];

                        $this->getConnection()->insert($priceTable, $bind);
                    }
                }
            }
        } else {
            if ($scope == \Magento\Store\Model\Store::PRICE_SCOPE_WEBSITE
                && is_null($object->getPrice())
                && !$priceType
            ) {
                $storeIds = $this->_storeManager->getStore($object->getStoreId())->getWebsite()->getStoreIds();
                foreach ($storeIds as $storeId) {
                    $where = [
                        'option_type_id = ?' => (int)$object->getId(),
                        'store_id = ?' => $storeId,
                    ];
                    $this->getConnection()->delete($priceTable, $where);
                }
            }
        }
    }

    /**
     * Save option value title data
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return void
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    protected function _saveValueTitles(\Magento\Framework\Model\AbstractModel $object)
    {
        foreach ([\Magento\Store\Model\Store::DEFAULT_STORE_ID, $object->getStoreId()] as $storeId) {
            $titleTable = $this->getTable('aitoc_optionsmanagement_template_option_type_title');
            $select = $this->getConnection()->select()->from(
                $titleTable,
                ['option_type_id']
            )->where(
                'option_type_id = ?',
                (int)$object->getId()
            )->where(
                'store_id = ?',
                (int)$storeId
            );
            $optionTypeId = $this->getConnection()->fetchOne($select);
            $existInCurrentStore = $this->getOptionIdFromOptionTable($titleTable, (int)$object->getId(), (int)$storeId);

            if ($storeId != \Magento\Store\Model\Store::DEFAULT_STORE_ID && $object->getData('is_delete_store_title')) {
                $object->unsetData('title');
            }

            if ($object->getTitle()) {
                if ($existInCurrentStore) {
                    if ($storeId == $object->getStoreId()) {
                        $where = [
                            'option_type_id = ?' => (int)$optionTypeId,
                            'store_id = ?' => $storeId,
                        ];
                        $bind = ['title' => $object->getTitle()];
                        $this->getConnection()->update($titleTable, $bind, $where);
                    }
                } else {
                    $existInDefaultStore = $this->getOptionIdFromOptionTable(
                        $titleTable,
                        (int)$object->getId(),
                        \Magento\Store\Model\Store::DEFAULT_STORE_ID
                    );
                    // we should insert record into not default store only of if it does not exist in default store
                    if (($storeId == \Magento\Store\Model\Store::DEFAULT_STORE_ID && !$existInDefaultStore)
                        || ($storeId != \Magento\Store\Model\Store::DEFAULT_STORE_ID && !$existInCurrentStore)
                    ) {
                        $bind = [
                            'option_type_id' => (int)$object->getId(),
                            'store_id' => $storeId,
                            'title' => $object->getTitle(),
                        ];
                        $this->getConnection()->insert($titleTable, $bind);
                    }
                }
            } else {
                if ($storeId
                    && $optionTypeId
                    && $object->getStoreId() > \Magento\Store\Model\Store::DEFAULT_STORE_ID
                ) {
                    $where = [
                        'option_type_id = ?' => (int)$optionTypeId,
                        'store_id = ?' => $storeId,
                    ];
                    $this->getConnection()->delete($titleTable, $where);
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
    protected function getOptionIdFromOptionTable($tableName, $optionId, $storeId)
    {
        $connection = $this->getConnection();
        $select = $connection->select()->from(
            $tableName,
            ['option_type_id']
        )->where(
            'option_type_id = ?',
            $optionId
        )->where(
            'store_id = ?',
            (int)$storeId
        );
        return $connection->fetchOne($select);
    }

    /**
     * Delete values by option id
     *
     * @param int $optionId
     * @return $this
     */
    public function deleteValue($optionId)
    {
        $statement = $this->getConnection()->select()->from(
            $this->getTable('aitoc_optionsmanagement_template_option_type_value')
        )->where(
            'option_id = ?',
            $optionId
        );

        $rowSet = $this->getConnection()->fetchAll($statement);

        foreach ($rowSet as $optionType) {
            $this->deleteValues($optionType['option_type_id']);
        }

        $this->getConnection()->delete($this->getMainTable(), ['option_id = ?' => $optionId]);

        return $this;
    }

    /**
     * Delete values by option type
     *
     * @param int $optionTypeId
     * @return void
     */
    public function deleteValues($optionTypeId)
    {
        $condition = ['option_type_id = ?' => $optionTypeId];

        $this->getConnection()->delete($this->getTable('aitoc_optionsmanagement_template_option_type_price'), $condition);

        $this->getConnection()->delete($this->getTable('aitoc_optionsmanagement_template_option_type_title'), $condition);
    }

    /**
     * Get FormatInterface to convert price from string to number format
     *
     * @return \Magento\Framework\Locale\FormatInterface
     * @deprecated
     */
    private function getLocaleFormatter()
    {
        if ($this->localeFormat === null) {
            $this->localeFormat = \Magento\Framework\App\ObjectManager::getInstance()
                ->get(\Magento\Framework\Locale\FormatInterface::class);
        }
        return $this->localeFormat;
    }

    /**
     * @return string
     */
    public function getRelationProductOptionValueTable()
    {
        return $this->getTable('aitoc_optionsmanagement_template_product_option_type_value');
    }

    /**
     * Save template products relation
     *
     * @param \Magento\Catalog\Model\Product\Option\Value $productOptionValue
     * @return int The number of affected rows.
     */
    public function saveRelation(\Magento\Catalog\Model\Product\Option\Value $productOptionValue)
    {
        $connection = $this->getConnection();
        return $connection->insertOnDuplicate(
            $this->getRelationProductOptionValueTable(),
            [
                'template_option_type_id' => $productOptionValue->getTemplateOptionTypeId(),
                'product_option_type_id' => $productOptionValue->getOptionTypeId()
            ],
            ['product_option_type_id']
        );
    }
}
