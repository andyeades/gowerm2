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
namespace Aheadworks\Giftcard\Ui\Component\Listing\Column\Pool;

use Magento\Ui\Component\Listing\Columns\Column;

/**
 * Class Name
 *
 * @package Aheadworks\Giftcard\Ui\Component\Listing\Column
 */
class Name extends Column
{
    /**
     * {@inheritdoc}
     */
    public function prepareDataSource(array $dataSource)
    {
        $fieldName = $this->getData('name');
        foreach ($dataSource['data']['items'] as & $item) {
            $item[$fieldName . '_label'] = $item['name'];
            $item[$fieldName . '_url'] = $this->context->getUrl(
                'aw_giftcard_admin/pool/edit',
                ['id' => $item['id']]
            );
        }

        return $dataSource;
    }
}
