<?php
/**
 * Aheadworks Inc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://aheadworks.com/end-user-license-agreement/
 *
 * @package    Giftcard
 * @version    1.4.6
 * @copyright  Copyright (c) 2021 Aheadworks Inc. (https://aheadworks.com/)
 * @license    https://aheadworks.com/end-user-license-agreement/
 */
namespace Aheadworks\Giftcard\Model\Source\Entity\Attribute;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;
use Magento\Framework\Data\Collection;
use Magento\Eav\Model\Entity\Collection\AbstractCollection;
use Magento\Config\Model\Config\Source\Email\Template as SourceEmailTemplate;

/**
 * Class GiftcardEmailTemplate
 *
 * @package Aheadworks\Giftcard\Model\Source\Entity\Attribute
 */
class GiftcardEmailTemplate extends AbstractSource
{
    /**
     * @var SourceEmailTemplate
     */
    private $emailTemplates;

    /**
     * @param SourceEmailTemplate $emailTemplates
     */
    public function __construct(
        SourceEmailTemplate $emailTemplates
    ) {
        $this->emailTemplates = $emailTemplates;
    }

    /**
     * {@inheritdoc}
     */
    public function getAllOptions()
    {
        if ($this->_options === null) {
            $this->_options = $this->emailTemplates
                ->setPath('aw_giftcard_email_template')
                ->toOptionArray();
        }
        return $this->_options;
    }

    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        return $this->getAllOptions();
    }

    /**
     * {@inheritdoc}
     */
    public function getOptionText($value)
    {
        $options = $this->getAllOptions();
        foreach ($options as $option) {
            if ($option['value'] == $value) {
                return $option['label'];
            }
        }
        return false;
    }

    /**
     * Add Value Sort To Collection Select
     *
     * @param AbstractCollection $collection
     * @param string $dir direction
     * @return $this
     */
    public function addValueSortToCollection($collection, $dir = Collection::SORT_ORDER_DESC)
    {
        $attributeCode = $this->getAttribute()->getAttributeCode();
        $attributeId = $this->getAttribute()->getId();
        $attributeTable = $this->getAttribute()->getBackend()->getTable();

        $valueTable1 = $attributeCode . '_t1';
        $valueTable2 = $attributeCode . '_t2';
        $collection->getSelect()
            ->joinLeft(
                [$valueTable1 => $attributeTable],
                "e.entity_id={$valueTable1}.entity_id" .
                " AND {$valueTable1}.attribute_id='{$attributeId}'" .
                " AND {$valueTable1}.store_id='0'",
                []
            )
            ->joinLeft(
                [$valueTable2 => $attributeTable],
                "e.entity_id={$valueTable2}.entity_id" .
                " AND {$valueTable2}.attribute_id='{$attributeId}'" .
                " AND {$valueTable2}.store_id='{$collection->getStoreId()}'",
                []
            );
        $valueExpr = $collection->getConnection()
            ->getCheckSql(
                $valueTable2 . '.value_id > 0',
                $valueTable2 . '.value',
                $valueTable1 . '.value'
            );
        $collection->getSelect()->order($valueExpr . ' ' . $dir);
        return $this;
    }
}
