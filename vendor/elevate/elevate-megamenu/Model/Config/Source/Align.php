<?php
namespace Elevate\Megamenu\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class Status
 */
class Align implements OptionSourceInterface
{
  /**
   * Get options
   *
   * @return array
   */
  public function toOptionArray()
  {
    return [
      ['label' => __('Left'), 'value' => '1'],
      ['label' => __('Center'), 'value' => '2'],
      ['label' => __('Right'), 'value' => '3']
    ];
}
}