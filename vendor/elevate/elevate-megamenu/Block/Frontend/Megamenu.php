<?php

namespace Elevate\Megamenu\Block\Frontend;

use Magento\Framework\Api\Filter;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\FilterGroupBuilder;
use Magento\Framework\Data\Collection;

class Megamenu extends \Magento\Framework\View\Element\Template {

  protected $_megamenuFactory;
  protected $_megamenuRepositoryInterface;
  protected $_searchCriteriaBuilder;
  protected $_sortOrderBuilder;
  protected $_filter;
  protected $_filterBuilder;
  protected $_filterGroup;
  protected $_filterGroupBuilder;
  protected $_stdTimeZone;
  protected $filterProvider;


  public function __construct(
    \Magento\Framework\View\Element\Template\Context $context,
    \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
    \Magento\Framework\Api\SortOrderBuilder $sortOrderBuilder,
    \Magento\Framework\Api\Filter $filter,
    \Magento\Framework\Api\FilterBuilder $filterBuilder,
    \Magento\Framework\Api\Search\FilterGroup $filterGroup,
    \Magento\Framework\Api\Search\FilterGroupBuilder $filterGroupBuilder,
    \Elevate\Megamenu\Api\MegamenuRepositoryInterface $megamenuRepositoryInterface,
    \Elevate\Megamenu\Model\MegamenuFactory $megamenuFactory,
    \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
    \Magento\Store\Model\StoreManagerInterface $storeManager,
    \Magento\Framework\Stdlib\DateTime\Timezone $stdTimeZone,
    \Magento\Cms\Model\Template\FilterProvider $filterProvider,
    array $data = []
  ) {
    $this->_megamenuFactory = $megamenuFactory;
    $this->_megamenuRepositoryInterface = $megamenuRepositoryInterface;
    $this->_searchCriteriaBuilder = $searchCriteriaBuilder;
    $this->_sortOrderBuilder = $sortOrderBuilder;
    $this->_filterBuilder = $filterBuilder;
    $this->_filter = $filter;
    $this->_filterGroup = $filterGroup;
    $this->_filterGroupBuilder = $filterGroupBuilder;
    $this->_scopeConfig = $scopeConfig;
    $this->_storeManager = $storeManager;
    $this->_stdTimeZone = $stdTimeZone;
    $this->filterProvider = $filterProvider;
    parent::__construct($context, $data);
  }

  public function getHtml() {
    $html_array = array();
    $menuFactory = $this->_megamenuFactory->create();

    $sortOrder = $this->_sortOrderBuilder->setField('position')->setDirection("ASC")->create();
    $currentDate = $this->_stdTimeZone->date()->format('Y-m-d H:i:s');
    //echo $currentDate;


    // Don't Show if item is not meant to be shown yet.
    $filter1 = $this->_filterBuilder->create()->setField('start_date')->setValue($currentDate)->setConditionType('lt');

    // Don't Show if not Enabled
    $filter2 = $this->_filterBuilder->create()->setField('enabled')->setValue('1')->setConditionType('eq');

    // Don't Show if End date Has Passed
    $filter3 = $this->_filterBuilder->create()->setField('end_date')->setValue($currentDate)->setConditionType('gt');

    // If you want to have each filter applied in an AND operation you need to insert each filter into a seperate filtergroup, otherwise they would be applied in an OR fashion

    $filterGroup1 = $this->_filterGroupBuilder->create()->setFilters([$filter1]);
    $filterGroup2 = $this->_filterGroupBuilder->create()->setFilters([$filter2]);
    $filterGroup3 = $this->_filterGroupBuilder->create()->setFilters([$filter3]);

    $searchCriteria = $this->_searchCriteriaBuilder->setFilterGroups([$filterGroup1,$filterGroup2,$filterGroup3])->setSortOrders([$sortOrder])->create();

    $menuitems = $this->_megamenuRepositoryInterface->getList($searchCriteria);

    $currentStore = $this->_storeManager->getStore();
    $mediaUrl = $currentStore->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
    $logo_image_link = $this->_scopeConfig->getValue('elevatemegamenuconfig/general/logo', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
      $mobilelogo_image_link = $this->_scopeConfig->getValue('elevatemegamenuconfig/general/mobilelogo', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    $logo_alt_text = $this->_scopeConfig->getValue('elevatemegamenuconfig/general/logo_alt_text', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $use_mobilelogo = $this->_scopeConfig->getValue('elevatemegamenuconfig/general/use_mobilelogo', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);


        $mobile_logo_class = '';

        if (empty($use_mobilelogo)) {
            $mobile_logo_class = 'ev-no-mobile-logo';
        }

        $html = "<ul id=\"e-pn\" class=\"e-pn $mobile_logo_class\">";

    $html .= '<a class="cd-nav-trigger-close" href="#cd-primary-nav"></a>';

        $html_array[0] = $html;

        if (!empty($use_mobilelogo)) {
    $html .= '<li class="mobile-logo">';
    $html .= '<div class="mobile-logo-inner">';
    $html .= '<img src="'.$mediaUrl.'logo/'.$mobilelogo_image_link.'" alt="'.$logo_alt_text.'">';
    $html .= '</div>';
    //$html .= '<div style="clear:both;"></div>';
   //$html .= '<div class="search-mob-menu" style="padding: 6px;">';
    $html_array[0] = $html;

    $html = '';



    $html .= '</div>';
    $html .= '</li>';
        }
        $html = '';
    $menuitems_count = $menuitems->getTotalCount();
    $counter = 0;
    $itemcounter = 1;
    foreach ($menuitems->getItems() as $item) {

      $item->setIsFirst($counter == 0);
      $item->setIsLast($counter == $menuitems_count);
      $item->setNavNumber($itemcounter);

      $menu_columns = $item->getMenuColumns();
      $menu_column_class = $this->getMenuColumnClass($menu_columns);
      $classes = $this->getItemClasses($item);
      $classes_output = '';

      foreach ($classes as $class => $classValue) {
        $classes_output .= $classValue.' ';
      }
        //ToDo: - If Has Children ?
        $inner_menu_content = $this->getContentFromStaticBlock($item->getMenuContent());

        if (!empty($inner_menu_content)) {
            $classes_output .= ' has-children ';
        }

        $html .= '<li class="ev_nav_level_1 '.$classes_output.'">';
        if (!empty($item->getCustomToplevelHtmlOn())) {
            $custom_html_output = $item->getCustomToplevelHtml();
            $html .= $custom_html_output;
        } else {
            $a_class_output = '';


            $html .= '<a class="'.$a_class_output.'" href="'.$item->getMenuLink().'">';
            $html .= '<span>'.$item->getMenuName().'</span>';
            $dropdown_icon = $item->getMenuDropdownicon();

            if ($dropdown_icon == 1) {
                $html .= '<span class="dropdown-icon is-hidden"></span>';
            }

            $html .= '</a>';
            if (!empty($inner_menu_content)) {
                // Conditional if menu is Type 1/2/3
                // temp for testing


                $menu_align = $item->getMenuAlign();
                $menu_align_class = '';
                if ($menu_align == 1) {
                    $menu_align_class = "mm-left-align";
                } else if ($menu_align == 2) {
                    $menu_align_class = "mm-center-align";
                } else {
                    $menu_align_class = "mm-right-align";
                }

                $menu_type = $item->getMenuType();
                if ($menu_type == 1) {
                    $html .= '<ul class="cd-secondary-nav ev_nav_level_2 '.$menu_column_class.' hoverable-content menu-style-1 '.$menu_align_class.' is-hidden">';
                    //$html .= '<ul class="cd-secondary-nav ev_nav_level_2 hoverable-content is-hidden">';
                } else if ($menu_type == 3) {
                    $html .= '<ul class="cd-secondary-nav ev_nav_level_2 '.$menu_column_class.' menu-style-3 '.$menu_align_class.' is-hidden">';
                } else {
                    $html .= '<ul class="cd-secondary-nav ev_nav_level_2 '.$menu_column_class.' menu-style-2 '.$menu_align_class.' is-hidden">';
                }
                $html .= '<li class="go-back"><a href="#0"><span class="bk-arrows"></span> Back</a></li>';
                $html .= '<li class="see-all"><a href="'.$item->getMenuLink().'">All '.$item->getMenuName().'</a></li>';
                $html .= $inner_menu_content;
                $html .= '</ul>';
            }
        }
        $html .= '</li>';





      $counter++;
      $itemcounter++;
    }
        $html .= "</ul>";
  $html_array[1] = $html;
return $html_array;
}

  /* FIlters vars out {{config path=”web/unsecure/base_url”}} */
    public function getContentFromStaticBlock($content)
    {
        return $this->filterProvider->getBlockFilter()->filter($content);
    }

  /**
   * Get applicable classes
   *
   * @param object $item
   *
   * @return array
   */

public function getItemClasses($item) {

  $classes = [];

  //$classes[] = 'level' . $item->getLevel();
  //$classes[] = $item->getPositionClass();

  if ($item->getIsFirst()) {
    $classes[] = 'first';
  }

  $showtype = intval($item->getShowType());

  $classes[] = $this->getShowTypeClass($showtype);

  if ($item->getNavNumber()) {
    $classes[] = 'nav-'.$item->getNavNumber();
  }

  /*
  if ($item->getIsActive()) {
    $classes[] = 'active';
  } elseif ($item->getHasActive()) {
    $classes[] = 'has-active';
  }
  */

  if ($item->getIsLast()) {
    $classes[] = 'last';
  }

  /*
  if ($item->getClass()) {
    $classes[] = $item->getClass();
  }

  if ($item->hasChildren()) {
    $classes[] = 'parent';
  }
  */

  return $classes;

}

    /**
     *
     */
    public function getShowTypeClass($showtype) : string {
    $class = '';
        if (intval($showtype) == 3) {
            // Desktop only
            $class = 'ev-mm-desktop-only';
        } else if (intval($showtype) == 2) {
            $class = 'ev-mm-mobile-only';
        } else {
            // Mobile & Desktop;
            $class = '';
        }


        return $class;
    }

  /**
   * Get top menu html
   *
   * @param string $outermostClass
   * @param string $childrenWrapClass
   * @param int $limit
     *
   * @return string
   */
  public function getTopMenuHtml($outermostClass = '', $childrenWrapClass = '', $limit = 0)
  {
    $this->_eventManager->dispatch(
      'page_block_html_topmenu_gethtml_before',
      ['menu' => $this->getMenu(), 'block' => $this, 'request' => $this->getRequest()]
    );

    $this->getMenu()->setOutermostClass($outermostClass);
    $this->getMenu()->setChildrenWrapClass($childrenWrapClass);

    $html = $this->_getTopMenuHtml($this->getMenu(), $childrenWrapClass, $limit);

    $transportObject = new \Magento\Framework\DataObject(['html' => $html]);
    $this->_eventManager->dispatch(
      'page_block_html_topmenu_gethtml_after',
      ['menu' => $this->getMenu(), 'transportObject' => $transportObject]
    );
    $html = $transportObject->getTopMenuHtml();
    return $html;
  }

  /**
   * Count All Subnavigation Items
   *
   * @param \Magento\Backend\Model\Menu $items
     *
   * @return int
   */
  protected function _countItems($items)
  {
    $total = $items->count();
    foreach ($items as $item) {
      /** @var $item \Magento\Backend\Model\Menu\Item */
      if ($item->hasChildren()) {
        $total += $this->_countItems($item->getChildren());
      }
    }
    return $total;
  }

  /**
   * Building Array with Column Brake Stops
   *
   * @param \Magento\Backend\Model\Menu $items
   * @param int $limit
     *
   * @return array|void
   *
   * @todo: Add Depth Level limit, and better logic for columns
   */
  protected function _columnBrake($items, $limit)
  {
    $total = $this->_countItems($items);
    if ($total <= $limit) {
      return;
    }

    $result[] = ['total' => $total, 'max' => (int)ceil($total / ceil($total / $limit))];

    $count = 0;
    $firstCol = true;

    foreach ($items as $item) {
      $place = $this->_countItems($item->getChildren()) + 1;
      $count += $place;

      if ($place >= $limit) {
        $colbrake = !$firstCol;
        $count = 0;
      } elseif ($count >= $limit) {
        $colbrake = !$firstCol;
        $count = $place;
      } else {
        $colbrake = false;
      }

      $result[] = ['place' => $place, 'colbrake' => $colbrake];

      $firstCol = false;
    }

    return $result;
  }

  /**
   * Add sub menu HTML code for current menu item
   *
   * @param \Magento\Framework\Data\Tree\Node $child
   * @param string $childLevel
   * @param string $childrenWrapClass
   * @param int $limit
     *
   * @return string HTML code
   */
  protected function _addSubMenu($child, $childLevel, $childrenWrapClass, $limit)
  {
    $html = '';
    if (!$child->hasChildren()) {
      return $html;
    }

        $colStops = NULL;
    if ($childLevel == 0 && $limit) {
      $colStops = $this->_columnBrake($child->getChildren(), $limit);
    }

    $html .= '<ul class="level' . $childLevel . ' ' . $childrenWrapClass . '">';
    $html .= $this->_getTopMenuHtml($child, $childrenWrapClass, $limit, $colStops);
    $html .= '</ul>';

    return $html;
  }

  protected function getMenuColumnClass($menuColumn) {
      $class = '';

      switch ($menuColumn) {
          case '5':
              $class = 'five-column';
              break;
          case '4':
              $class = 'four-column';
              break;
          case '3':
              $class = 'three-column';
              break;
          case '2':
              $class = 'two-column';
              break;
          case '1':
              $class = 'one-column';
              break;
          case 'auto':
              $class = 'auto-columns';
              break;
          default:
              $class = 'five-column';
              break;

      }


      return $class;
  }

  /**
   * Recursively generates top menu html from data that is specified in $menuTree
   *
   * @param \Magento\Framework\Data\Tree\Node $menuTree
   * @param string $childrenWrapClass
   * @param int $limit
   * @param array $colBrakes
     *
   * @return string
   *
   * @SuppressWarnings(PHPMD.CyclomaticComplexity)
   * @SuppressWarnings(PHPMD.NPathComplexity)
   */
  protected function _getTopMenuHtml(
    \Magento\Framework\Data\Tree\Node $menuTree,
    $childrenWrapClass,
    $limit,
    $colBrakes = []
  ) {
    $html = '';

    $children = $menuTree->getChildren();
    $parentLevel = $menuTree->getLevel();
        $childLevel = $parentLevel === NULL ? 0: $parentLevel + 1;

    $counter = 1;
    $itemPosition = 1;
    $childrenCount = $children->count();

    $parentPositionClass = $menuTree->getPositionClass();
    $itemPositionClassPrefix = $parentPositionClass ? $parentPositionClass . '-' : 'nav-';

    /** @var \Magento\Framework\Data\Tree\Node $child */
    foreach ($children as $child) {
      if ($childLevel === 0 && $child->getData('is_parent_active') === false) {
        continue;
      }
      $child->setLevel($childLevel);
      $child->setIsFirst($counter == 1);
      $child->setIsLast($counter == $childrenCount);
      $child->setPositionClass($itemPositionClassPrefix . $counter);

      $outermostClassCode = '';
      $outermostClass = $menuTree->getOutermostClass();

      if ($childLevel == 0 && $outermostClass) {
        $outermostClassCode = ' class="' . $outermostClass . '" ';
        $child->setClass($outermostClass);
      }

      if (count($colBrakes) && $colBrakes[$counter]['colbrake']) {
        $html .= '</ul></li><li class="column"><ul>';
      }

      $html .= '<li ' . $this->_getRenderedMenuItemAttributes($child) . '>';
      $html .= '<a href="' . $child->getUrl() . '" ' . $outermostClassCode . '><span>' . $this->escapeHtml(
          $child->getName()
        ) . '</span></a>' . $this->_addSubMenu(
          $child,
          $childLevel,
          $childrenWrapClass,
          $limit
        ) . '</li>';
      $itemPosition++;
      $counter++;
    }

    if (count($colBrakes) && $limit) {
      $html = '<li class="column"><ul>' . $html . '</ul></li>';
    }

    return $html;
  }

  /**
   * Generates string with all attributes that should be present in menu item element
   *
   * @param \Magento\Framework\Data\Tree\Node $item
     *
   * @return string
   */
  protected function _getRenderedMenuItemAttributes(\Magento\Framework\Data\Tree\Node $item)
  {
    $html = '';
    $attributes = $this->_getMenuItemAttributes($item);
    foreach ($attributes as $attributeName => $attributeValue) {
      $html .= ' ' . $attributeName . '="' . str_replace('"', '\"', $attributeValue) . '"';
    }
    return $html;
  }

  /**
   * Returns array of menu item's attributes
   *
   * @param \Magento\Framework\Data\Tree\Node $item
     *
   * @return array
   */
  protected function _getMenuItemAttributes(\Magento\Framework\Data\Tree\Node $item)
  {
    $menuItemClasses = $this->_getMenuItemClasses($item);
    return ['class' => implode(' ', $menuItemClasses)];
  }

  /**
   * Returns array of menu item's classes
   *
   * @param \Magento\Framework\Data\Tree\Node $item
     *
   * @return array
   */
  protected function _getMenuItemClasses(\Magento\Framework\Data\Tree\Node $item)
  {
    $classes = [];

    $classes[] = 'level' . $item->getLevel();
    $classes[] = $item->getPositionClass();

    if ($item->getIsFirst()) {
      $classes[] = 'first';
    }

    if ($item->getIsActive()) {
      $classes[] = 'active';
    } elseif ($item->getHasActive()) {
      $classes[] = 'has-active';
    }

    if ($item->getIsLast()) {
      $classes[] = 'last';
    }

    if ($item->getClass()) {
      $classes[] = $item->getClass();
    }

    if ($item->hasChildren()) {
      $classes[] = 'parent';
    }

    return $classes;
  }

  /**
   * Add identity
   *
   * @param array $identity
     *
   * @return void
   */
  public function addIdentity($identity)
  {
    if (!in_array($identity, $this->identities)) {
      $this->identities[] = $identity;
    }
  }

  /**
   * Get identities
   *
   * @return array
   */
  public function getIdentities()
  {
    return $this->identities;
  }


  /**
   * Get menu object.
   *
   * Creates \Magento\Framework\Data\Tree\Node root node object.
   * The creation logic was moved from class constructor into separate method.
   *
   * @return Node
   * @since 100.1.0
   */
  public function getMenu()
  {
    if (!$this->_menu) {
      $this->_menu = $this->nodeFactory->create(
        [
          'data' => [],
          'idField' => 'root',
          'tree' => $this->treeFactory->create()
        ]
      );
    }
    return $this->_menu;
  }

}

?>
