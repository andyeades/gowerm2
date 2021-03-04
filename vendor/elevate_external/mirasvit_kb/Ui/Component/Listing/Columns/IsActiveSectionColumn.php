<?php
/**
 * Mirasvit
 *
 * This source file is subject to the Mirasvit Software License, which is available at https://mirasvit.com/license/.
 * Do not edit or add to this file if you wish to upgrade the to newer versions in the future.
 * If you wish to customize this module for your needs.
 * Please refer to http://www.magentocommerce.com for more information.
 *
 * @category  Mirasvit
 * @package   mirasvit/module-kb
 * @version   1.0.69
 * @copyright Copyright (C) 2020 Mirasvit (https://mirasvit.com/)
 */


namespace Mirasvit\Kb\Ui\Component\Listing\Columns;

class IsActivesectionColumn extends \Magento\Ui\Component\Listing\Columns\Column
{
    /**
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                $item[$this->getData('asec_is_active')] = $this->prepareItem('asec_is_active', $item);
            }
        }

        return $dataSource;
    }


    /**
     * Format data.
     *
     * @param string $fieldName
     * @param array  $item
     * @return string
     */
    protected function prepareItem($fieldName, array $item)
    {
        $status = __('No');
        if (intval($item[$fieldName]) == 1) {
            $status = __('Yes');
        }
        return "<span class='is-active'>".$status.'</span>';
    }
}
