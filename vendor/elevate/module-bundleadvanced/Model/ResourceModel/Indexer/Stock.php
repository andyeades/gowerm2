<?php

namespace Elevate\BundleAdvanced\Model\ResourceModel\Indexer;

use Elevate\BundleAdvanced\Api\Data\ProductAttributeInterface;
use Magento\Bundle\Model\ResourceModel\Indexer\Stock as BundleStock;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\CatalogInventory\Model\Indexer\Stock\Action\Full;
use Magento\Bundle\Model\ResourceModel\Indexer\BundleOptionStockDataSelectBuilder;
use Magento\Eav\Api\AttributeRepositoryInterface;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Framework\Indexer\Table\StrategyInterface;
use Magento\Eav\Model\Config;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Catalog\Model\ResourceModel\Indexer\ActiveTableSwitcher;
use Magento\Framework\EntityManager\MetadataPool;

/**
 * Class Stock
 * @package Elevate\BundleAdvanced\Model\ResourceModel\Indexer
 */
class Stock extends BundleStock
{
    /**
     * @var \Magento\Catalog\Model\ResourceModel\Indexer\ActiveTableSwitcher
     */
    private $activeTableSwitcher;

    /**
     * @var \Magento\Bundle\Model\ResourceModel\Indexer\BundleOptionStockDataSelectBuilder
     */
    private $bundleOptionStockDataSelectBuilder;

    /**
     * @var AttributeRepositoryInterface
     */
    protected $attributeRepository;

    /**
     * @var MetadataPool
     */
    protected $metadataPool;

    /**
     * @param Context $context
     * @param StrategyInterface $tableStrategy
     * @param Config $eavConfig
     * @param ScopeConfigInterface $scopeConfig
     * @param null $connectionName
     * @param ActiveTableSwitcher $activeTableSwitcher
     * @param BundleOptionStockDataSelectBuilder $bundleOptionStockDataSelectBuilder
     * @param AttributeRepositoryInterface $attributeRepository
     * @param MetadataPool $metadataPool
     */
    public function __construct(
        Context $context,
        StrategyInterface $tableStrategy,
        Config $eavConfig,
        ScopeConfigInterface $scopeConfig,
        ActiveTableSwitcher $activeTableSwitcher,
        BundleOptionStockDataSelectBuilder $bundleOptionStockDataSelectBuilder,
        AttributeRepositoryInterface $attributeRepository,
        MetadataPool $metadataPool,
        $connectionName = null
    ) {
        parent::__construct($context, $tableStrategy, $eavConfig, $scopeConfig, $connectionName);

        $this->activeTableSwitcher = $activeTableSwitcher;
        $this->bundleOptionStockDataSelectBuilder = $bundleOptionStockDataSelectBuilder;
        $this->attributeRepository = $attributeRepository;
        $this->metadataPool = $metadataPool;
    }

    /**
     * Prepare stock status per Bundle options, website and stock
     *
     * @param int|array $entityIds
     * @param bool $usePrimaryTable use primary or temporary index table
     * @return $this
     */
    protected function _prepareBundleOptionStockData($entityIds = null, $usePrimaryTable = false)
    {
        $this->_cleanBundleOptionStockData();
        $connection = $this->getConnection();
        $table = $this->getActionType() === Full::ACTION_TYPE
            ? $this->activeTableSwitcher->getAdditionalTableName($this->getMainTable())
            : $this->getMainTable();
        $idxTable = $usePrimaryTable ? $table : $this->getIdxTable();
        $select = $this->bundleOptionStockDataSelectBuilder->buildSelect($idxTable);

        $status = new \Zend_Db_Expr(
            'MAX('
            . $connection->getCheckSql('e.required_options = 0', 'i.stock_status', '0')
            . ')'
        );
        $sbpStatus = new \Zend_Db_Expr(
            'MIN('
            . $connection->getCheckSql('e.required_options = 0', 'i.stock_status', '0')
            . ')'
        );

        $select->columns(['status' => $status]);

        if ($entityIds !== null) {
            $select->where('product.entity_id IN(?)', $entityIds);
        }

        // clone select for bundle product without required bundle options
        $selectNonRequired = clone $select;

        $select->where('bo.required = ?', 1);
        $selectNonRequired->where('bo.required = ?', 0)->having($status . ' = 1');
        $query = $select->insertFromSelect($this->_getBundleOptionTable());
        $connection->query($query);

        $metadata = $this->metadataPool->getMetadata(ProductInterface::class);
        $linkField = $metadata->getLinkField();
        $selectNonRequired->columns(['sbp_status' => $sbpStatus, 'row_entity_id' => 'product.' . $linkField]);
        $wrapSelectNonRequired = $connection->select()
            ->from(
                ['r' => new \Zend_Db_Expr(sprintf('(%s)', $selectNonRequired))],
                [
                    'entity_id' => 'r.entity_id',
                    'website_id' => 'r.website_id',
                    'stock_id' => 'r.stock_id',
                    'option_id' => 'r.option_id',
                    'status' => $connection->getCheckSql('eav.value = 1', 'r.`sbp_status`', 'r.`status`')
                ]
            )->joinLeft(
                ['eav' => $this->getTable('catalog_product_entity_int')],
                'r.row_entity_id' . ' = eav.' . $linkField
                . ' and eav.attribute_id = ' . $this->getSbpTypeAttribute()->getId(),
                []
            );

        $query = $wrapSelectNonRequired->insertFromSelect($this->_getBundleOptionTable());
        $connection->query($query);

        return $this;
    }

    /**
     * Retrieve sbp bundle product type attribute
     *
     * @return \Magento\Catalog\Model\ResourceModel\Eav\Attribute|null
     */
    protected function getSbpTypeAttribute()
    {
        try {
            /** @var \Magento\Catalog\Model\ResourceModel\Eav\Attribute $attribute */
            $attribute = $this->attributeRepository->get(
                'catalog_product',
                ProductAttributeInterface::CODE_ELEVATE_BUNDLEADVANCED_BUNDLE_PRODUCT_TYPE
            );
        } catch (\Exception $e) {
            return null;
        }

        return $attribute;
    }
}
