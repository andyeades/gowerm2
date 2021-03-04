<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2019 Aitoc (https://www.aitoc.com)
 * @package Aitoc_OptionsManagement
 */

namespace Aitoc\OptionsManagement\Plugin\Framework\EntityManager\Operation\Update;

class UpdateMain
{
    /**
     * @param \Magento\Framework\EntityManager\Operation\Update\UpdateMain $updateMain
     * @param object $entity
     * @param array $arguments
     * @return array
     */
    public function beforeExecute($updateMain, $entity, $arguments = [])
    {
        if ($entity->getSaveOnlyOptions()) {
            $data = $entity->getData();
            foreach($data as $key => $value) {
                if ($key == 'entity_id'
                    || $key == 'sku'
                    || $key == 'store_id'
                    || $key == 'is_replace_product_sku'
                    || strpos($key, 'option') !== false
                ) {
                    continue;
                }

                unset($data[$key]);
            }

            $entity->setData($data);
        }

        return [$entity, $arguments];
    }
}
