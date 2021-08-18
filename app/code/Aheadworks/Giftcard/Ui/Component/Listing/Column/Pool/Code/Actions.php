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
namespace Aheadworks\Giftcard\Ui\Component\Listing\Column\Pool\Code;

use Magento\Ui\Component\Listing\Columns\Column;

/**
 * Class Actions
 *
 * @package Aheadworks\Giftcard\Ui\Component\Listing\Column\Pool\Code
 */
class Actions extends Column
{
    /**
     * {@inheritdoc}
     */
    public function prepareDataSource(array $dataSource)
    {
        foreach ($dataSource['data']['items'] as & $item) {
            $item[$this->getData('name')] = [
                'delete' => [
                    'href' => $this->context->getUrl(
                        'aw_giftcard_admin/pool/code_delete',
                        ['id' => $item['id']]
                    ),
                    'label' => __('Delete'),
                    'confirm' => [
                        'title' => __('Delete "${ $.$data.code }"'),
                        'message' => __('Are you sure you want to delete a "${ $.$data.code }" code?'),
                        '__disableTmpl' => [
                            'title' => false,
                            'message' => false,
                        ],
                    ]
                ]
            ];
        }

        return $dataSource;
    }
}
