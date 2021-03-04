<?php
namespace Elevate\Megamenu\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class Status
 */
class Type implements OptionSourceInterface
{
  /**
   * Get options
   *
   * @return array
   */
  public function toOptionArray()
  {
    return [
      ['label' => __('Menu Type 1'), 'value' => '1'],
      ['label' => __('Menu Type 2'), 'value' => '2'],
      ['label' => __('Menu Type 3'), 'value' => '3']
    ];
}
}