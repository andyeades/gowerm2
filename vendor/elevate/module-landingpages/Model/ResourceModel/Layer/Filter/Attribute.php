<?php
namespace Elevate\LandingPages\Model\ResourceModel\Layer\Filter;


class Attribute extends \Magento\Catalog\Model\ResourceModel\Layer\Filter\Attribute {

    public function getCount(\Magento\Catalog\Model\Layer\Filter\FilterInterface $filter)
    {
        // clone select from collection with filters
        $select = clone $filter->getLayer()->getProductCollection()->getSelect();
        // reset columns, order and limitation conditions
        $select->reset(\Magento\Framework\DB\Select::COLUMNS);
        $select->reset(\Magento\Framework\DB\Select::ORDER);
        $select->reset(\Magento\Framework\DB\Select::LIMIT_COUNT);
        $select->reset(\Magento\Framework\DB\Select::LIMIT_OFFSET);

        $connection = $this->getConnection();
        $attribute = $filter->getAttributeModel();
        $tableAlias = sprintf('%s_idx', $attribute->getAttributeCode());
        $conditions = [
            "{$tableAlias}.entity_id = e.entity_id",
            $connection->quoteInto("{$tableAlias}.attribute_id = ?", $attribute->getAttributeId()),
            $connection->quoteInto("{$tableAlias}.store_id = ?", $filter->getStoreId()),
        ];

        $select->join(
            [$tableAlias => $this->getMainTable()],
            join(' AND ', $conditions),
            ['value', 'count' => new \Zend_Db_Expr("COUNT({$tableAlias}.entity_id)")]
        )->group(
            "{$tableAlias}.value"
        );

        return $connection->fetchPairs($select);
    }
    public function getCountOld(\Magento\Catalog\Model\Layer\Filter\FilterInterface $filter)
    {
    
    
        $attribute_code = ($filter->getRequestVar() != 'cat') ? $filter->getAttributeModel()->getAttributeCode(): 0;

                //filter overide
                if($attribute_code == 'filter_rating'){
                    // clone select from collection with filters
        // clone select from collection with filters
        $layer = $filter->getLayer();
        if($layer instanceof \Magento\Catalog\Model\Layer\Search) {
            $collectionSelect = $layer->getProductCollection()->getSelect();
        } else {
            $collectionSelect = $layer->getCurrentCategory()->getProductCollection()->getSelect();
        }

        $select = clone $collectionSelect;
        // reset columns, order and limitation conditions
        $select->reset(\Magento\Framework\DB\Select::COLUMNS);
        $select->reset(\Magento\Framework\DB\Select::ORDER);
        $select->reset(\Magento\Framework\DB\Select::LIMIT_COUNT);
        $select->reset(\Magento\Framework\DB\Select::LIMIT_OFFSET);

        $connection = $this->getConnection();
        $attribute = $filter->getAttributeModel();
        $tableAlias = sprintf('%s_idx', $attribute->getAttributeCode());
        $conditions = [
            "{$tableAlias}.entity_id = e.entity_id",
            $connection->quoteInto("{$tableAlias}.attribute_id = ?", $attribute->getAttributeId()),
            $connection->quoteInto("{$tableAlias}.store_id = ?", $filter->getStoreId()),
        ];

        $select->join(
            [$tableAlias => $this->getMainTable()],
            join(' AND ', $conditions),
            ['value', 'count' => new \Zend_Db_Expr("COUNT({$tableAlias}.entity_id)")]
        )->group(
            "{$tableAlias}.value"
        );

        return $connection->fetchPairs($select);
    
                }
                
    

        $narrow_results = true;



            if($narrow_results) {
                $collectionSelect = $filter->getLayer()->getProductCollection()->getSelect();

            }
            else{
                // clone select from collection with filters
                $layer = $filter->getLayer();
                if ($layer instanceof \Magento\Catalog\Model\Layer\Search) {
                    $collectionSelect = $layer->getProductCollection()->getSelect();
                } else {
                    $collectionSelect = $layer->getCurrentCategory()->getProductCollection()->getSelect();
                }
            }

        $select = clone $collectionSelect;
        // reset columns, order and limitation conditions
        $select->reset(\Magento\Framework\DB\Select::COLUMNS);
        $select->reset(\Magento\Framework\DB\Select::ORDER);
        $select->reset(\Magento\Framework\DB\Select::LIMIT_COUNT);
        $select->reset(\Magento\Framework\DB\Select::LIMIT_OFFSET);





        $connection = $this->getConnection();
        $attribute = $filter->getAttributeModel();
        $tableAlias = sprintf('%s_idx', $attribute->getAttributeCode());
        $conditions = [
            "{$tableAlias}.entity_id = e.entity_id",
            $connection->quoteInto("{$tableAlias}.attribute_id = ?", $attribute->getAttributeId()),
            $connection->quoteInto("{$tableAlias}.store_id = ?", $filter->getStoreId()),
            $connection->quoteInto("{$tableAlias}.store_id = ?", $filter->getStoreId()),
        ];

        $select->join(
            [$tableAlias => $this->getMainTable()],
            join(' AND ', $conditions),
            ['dis_entity_id' => new \Zend_Db_Expr("DISTINCT({$tableAlias}.entity_id)"), 'value', "{$tableAlias}.entity_id", 'count' => new \Zend_Db_Expr("COUNT({$tableAlias}.entity_id)")]
        )->group(
            "{$tableAlias}.source_id", "{$tableAlias}.value"
        );


            $sql = $connection->select()
                ->from($select, array('value', 'count' => new \Zend_Db_Expr("sum(count)")))
                ->group(
                    "value"
                );
          //  ->from($select, array('*', 'num' => 'COUNT(*)'));

            //  main query
          /*
            $sql = $connection->select()
                      ->from('', array('*',
                          //  NOTE: have to add parentesis around the expression
                          'wrap' => new \Zend_Db_Expr("($select)")
                      ));
          */
                    //  ->where('....')
                     // ->group('brand_id');

        return $connection->fetchPairs($sql);

    }
}