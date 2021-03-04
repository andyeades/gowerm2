<?php
namespace Elevate\Megamenu\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class Status
 */
class ShowType implements OptionSourceInterface
{
  /**
   * Get options
   *
   * @return array
   */
  public function toOptionArray()
  {
    return [
      ['label' => __('Mobile & Desktop'), 'value' => '1'],
      ['label' => __('Mobile Only'), 'value' => '2'],
      ['label' => __('Desktop Only'), 'value' => '3']
    ];
}
}