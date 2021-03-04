<?php
namespace Elevate\Promotions\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class Status
 */
class SitewideType implements OptionSourceInterface
{
  /**
   * Get options
   *
   * @return array
   */
  public function toOptionArray()
  {
    return [
      ['label' => __('Text Bar'), 'value' => 'text_bar'],
      ['label' => __('Text Bar + Countdown'), 'value' => 'text_bar_countdown'],
      ['label' => __('USP Only'), 'value' => 'usp_bar'],
      ['label' => __('USP + countdown'), 'value' => 'countdown_bar'],
      ['label' => __('USP + Promo'), 'value' => 'usp_promo_bar']
    ];
}
}