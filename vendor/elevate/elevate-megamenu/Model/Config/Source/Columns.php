<?php

//Location: magento2_root/app/code/Vendorname/Extensionname/Model/Config/Source/Custom.php
namespace Elevate\Megamenu\Model\Config\Source;

class Columns implements \Magento\Framework\Option\ArrayInterface
{
  /**
   * @return array
   */
  public function toOptionArray()
  {

    return [
      ['value' => 1, 'label' => __('One Column')],
      ['value' => 2, 'label' => __('Two Columns')],
      ['value' => 3, 'label' => __('Three Columns')],
        ['value' => 4, 'label' => __('Four Columns')],
        ['value' => 5, 'label' => __('Five Columns')],
        ['value' => 6, 'label' => __('Six Columns')],
        ['value' => 'auto', 'label' => ('Auto Width Columns')],
    ];
  }
}
