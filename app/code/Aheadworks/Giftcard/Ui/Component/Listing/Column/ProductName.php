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
namespace Aheadworks\Giftcard\Ui\Component\Listing\Column;

use Magento\Ui\Component\Listing\Columns\Column;

/**
 * Class ProductName
 *
 * @package Aheadworks\Giftcard\Ui\Component\Listing\Column
 */
class ProductName extends Column
{
    /**
     * {@inheritdoc}
     * phpcs:disable Magento2.Performance.ForeachArrayMerge
     */
    public function prepareDataSource(array $dataSource)
    {
        $fieldName = $this->getData('config/fieldName');
        $awgcBackUrlParam = $this->getData('config/awgcBackUrlParam')
            ? ['awgcBack' => $this->getData('config/awgcBackUrlParam')]
            : [];
        $columnName = $this->getData('name');
        foreach ($dataSource['data']['items'] as &$item) {
            if ($productId = $item[$fieldName]) {
                if ($productName = $item[$columnName]) {
                    $item[$columnName . '_url'] = $this->context->getUrl(
                        'aw_giftcard_admin/product/edit',
                        array_merge(['id' => $productId], $awgcBackUrlParam)
                    );
                    $item[$columnName . '_label'] = $productName;
                } else {
                    $item[$columnName . '_label'] = $productId;
                }
            }
        }
        return $dataSource;
    }
}
