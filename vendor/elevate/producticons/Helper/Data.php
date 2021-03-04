<?php
namespace Elevate\ProductIcons\Helper;

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
    \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
    \Magento\Framework\Api\SortOrderBuilder $sortOrderBuilder,
    \Magento\Framework\Api\Filter $filter,
    \Magento\Framework\Api\FilterBuilder $filterBuilder,
    \Magento\Framework\Api\Search\FilterGroup $filterGroup,
    \Magento\Framework\Api\Search\FilterGroupBuilder $filterGroupBuilder,
    \Elevate\ProductIcons\Api\ProducticonsRepositoryInterface $producticonsRepositoryInterface,
    \Elevate\ProductIcons\Model\ProducticonsFactory $producticonsFactory,
    \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
    \Magento\Store\Model\StoreManagerInterface $storeManager,
    \Magento\Framework\Stdlib\DateTime\Timezone $stdTimeZone,

    array $data = []
  ) {
    $this->_producticonsFactory = $producticonsFactory;
    $this->_producticonsRepositoryInterface = $producticonsRepositoryInterface;
    $this->_searchCriteriaBuilder = $searchCriteriaBuilder;
    $this->_sortOrderBuilder = $sortOrderBuilder;
    $this->_filterBuilder = $filterBuilder;
    $this->_filter = $filter;
    $this->_filterGroup = $filterGroup;
    $this->_filterGroupBuilder = $filterGroupBuilder;
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
   * @param array $icon_ids
   *
   * @return array
   * @throws \Magento\Framework\Exception\NoSuchEntityException
   */
  public function getIconsForProduct(array $icon_ids) {
    $html_array = array();
    $producticonsFactory = $this->_producticonsFactory->create();

    $sortOrder = $this->_sortOrderBuilder->setField('icon_id')->setDirection("ASC")->create();
    $currentDate = $this->_stdTimeZone->date()->format('Y-m-d H:i:s');
    //echo $currentDate;


    // Don't Show if item is not meant to be shown yet.
   $filter1 = $this->_filterBuilder->setField('icon_start_date')->setValue($currentDate)->setConditionType('lteq')->create();

    // Don't Show if not Enabled
    //$filter2 = $this->_filterBuilder->create()->setField('enabled')->setValue('1')->setConditionType('eq');

    // Don't Show if End date Has Passed
    $filter2 = $this->_filterBuilder
      ->setField('icon_end_date')
      ->setValue($currentDate)
      ->setConditionType('gt')
      ->create();

    // Limit to Icons
    $filter3 = $this->_filterBuilder
      ->setField('icon_id')
      ->setValue($icon_ids)
      ->setConditionType('in')
      ->create();


    // If you want to have each filter applied in an AND operation you need to insert each filter into a seperate filtergroup, otherwise they would be applied in an OR fashion

    $filterGroup1 = $this->_filterGroupBuilder
      ->addFilter($filter1)
      ->create();
    $filterGroup2 = $this->_filterGroupBuilder->addFilter($filter2)->create();
    $filterGroup3 = $this->_filterGroupBuilder->addFilter($filter3)->create();

    $searchCriteria = $this->_searchCriteriaBuilder
      ->setFilterGroups([$filterGroup1, $filterGroup2, $filterGroup3])
      ->setSortOrders([$sortOrder])
      ->create();

    $icons_for_product = $this->_producticonsRepositoryInterface->getList($searchCriteria);

    $currentStore = $this->_storeManager->getStore();
    $mediaUrl = $currentStore->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);

    $html = "";

    $icons = $icons_for_product->getItems();

    foreach ($icons as $icon) {
      $icon_image = $icon->getIconUrl();
      $icon_title = $icon->getIconTitle();


      $html .= '<div class="ev-product-icon-outer">';
      $html .= '<div class="ev-product-icon-inner">';
      $html .= '<img src="'.$mediaUrl.'elevate/tmp/producticons/'.$icon_image.'" alt="'.$icon_title.'">';
      $html .= '</div>'; // End .ev-product-icon-inner
      $html .= '</div>'; // End .ev-product-icon-outer
    }


    $html_array[1] = $html;

    return $html_array;
  }

  public function getMediaUrl() {
    return $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA );
  }

}