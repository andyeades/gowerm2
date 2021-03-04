<?php
namespace Elevate\ProductKeyFacts\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\Api\Filter;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\FilterGroupBuilder;
use Magento\Framework\Data\Collection;

class Data extends AbstractHelper {

  protected $_producticonsFactory;
  protected $_producticonsRepositoryInterface;
  protected $_searchCriteriaBuilder;
  protected $_sortOrderBuilder;
  protected $_filter;
  protected $_filterBuilder;
  protected $_filterGroup;
  protected $_filterGroupBuilder;
  protected $_stdTimeZone;


  public function __construct(
    \Magento\Framework\App\Helper\Context $context,
    \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
    \Magento\Store\Model\StoreManagerInterface $storeManager,
    \Magento\Framework\Stdlib\DateTime\Timezone $stdTimeZone,

    array $data = []
  ) {
    $this->_scopeConfig = $scopeConfig;
    $this->_storeManager = $storeManager;
    $this->_stdTimeZone = $stdTimeZone;
    parent::__construct($context);
  }

  public function getHtml() {
    $html_array = array("Bob","Johnson");
    return $html_array;
  }

  /**
   * Get Item Output
   *
   * @param array  $items
   * @param string $output_type
   * @param object $product
   * @param string $item_wrapper_class
   *
   * @return string
   */
  public function getItemOutput(array $items, string $output_type, object $product, string $item_wrapper_class) {
    $html_output = '';



    if (strcmp($output_type, 'Long') === 0) {
      $item_count = count($items);
      foreach ($items as $item) {

        $html_output .= '<div class="' . $item_wrapper_class .' '. $item_wrapper_class . '-' . strtolower($output_type) . ' ev-kf-items-'.$item_count.'">';

        $html_output .= '<div class="'. $item_wrapper_class .'-single">';
        $html_output .= '<span class="'. $item_wrapper_class .'-top-text">' . $item . '</span>';
        $html_output .= '</div>';

        $html_output .= '</div>'; //Close Wrapper
      }

    }

    if (strcmp($output_type, 'Short') === 0) {

      foreach ($items as $item) {

        $item = explode("|", $item);

        if (!isset($item[0])) {
          $item[0] = NULL;
        }
        if (!isset($item[1])) {
          $item[1] = NULL;
        }
        if (!isset($item[2])) {
          $item[2] = NULL;
        }

        $html_output .= '<div class="' . $item_wrapper_class .' '. $item_wrapper_class . '-' . strtolower($output_type) . '">';

        $html_output .= '<div class="'. $item_wrapper_class .'-top">';
        $html_output .= '<span class="'. $item_wrapper_class .'-top-number">' . $item[1] . '</span>';
        $html_output .= '<span class="'. $item_wrapper_class .'-top-text">' . $item[2] . '</span>';
        $html_output .= '</div>';
        $html_output .= '<div class="'. $item_wrapper_class .'-bottom">';
        $html_output .= '<span class="'. $item_wrapper_class .'-bottom-text">' . $item[0] . '</span>';
        $html_output .= '</div>';

        $html_output .= "</div>"; //Close Wrapper
      }
      return $html_output;
    }
    return $html_output;
  }

}