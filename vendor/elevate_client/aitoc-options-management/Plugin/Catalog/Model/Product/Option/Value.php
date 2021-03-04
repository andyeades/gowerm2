<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2019 Aitoc (https://www.aitoc.com)
 * @package Aitoc_OptionsManagement
 */

/**
 * Copyright © 2018 Aitoc. All rights reserved.
 */
namespace Aitoc\OptionsManagement\Plugin\Catalog\Model\Product\Option;

class Value
{
    /**
     * @var \Aitoc\OptionsManagement\Model\Template\Option\ValueRepository
     */
    protected $valueRepository;

    /**
     * Option constructor.
     * @param \Aitoc\OptionsManagement\Model\Template\Option\ValueRepository $valueRepository
     */
    public function __construct(\Aitoc\OptionsManagement\Model\Template\Option\ValueRepository $valueRepository)
    {
        $this->valueRepository = $valueRepository;
    }

    /**
     * @param \Magento\Catalog\Model\Product\Option\Value $value
     * @param \Magento\Catalog\Model\Product\Option\Value $result
     * @return \Magento\Catalog\Model\Product\Option\Value
     */
    public function afterAfterSave($value, $result)
    {
        if ($value->getTemplateOptionTypeId()) {
            $this->valueRepository->saveRelation($value);
        }
        return $result;
    }

    /**
     * Fix magento bugs with storedData
     *
     * @param \Magento\Catalog\Model\Product\Option\Value $valueModel
     * @param string|array $key
     * @param mixed $value
     * @return array
     */
    public function beforeSetData($valueModel, $key, $value = null)
    {
        if (is_array($key)) {
            $valueModel->afterDelete();
        }
        return [$key, $value];
    }

}
