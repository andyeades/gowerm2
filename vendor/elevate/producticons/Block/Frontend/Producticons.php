<?php

namespace Elevate\ProductIcons\Block\Frontend;

use Magento\Framework\Api\Filter;
use Magento\Framework\Api\FilterBuilder;

class Producticons extends \Magento\Framework\View\Element\Template
{
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
      \Magento\Framework\View\Element\Template\Context $context,
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
        parent::__construct($context, $data);
    }

    public function getHtml()
    {
        $html_array = ["Bob","Johnson"];
        return $html_array;
    }

    /**
     * @param array $icon_ids
     *
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getIconsForProduct(array $icon_ids)
    {
        $html_array = [];
        $producticonsFactory = $this->_producticonsFactory->create();

        //TODO:: Should change this to Icon Block Position!!

        $sortOrder = $this->_sortOrderBuilder->setField('icon_id')->setDirection("ASC")->create();
        $currentDate = $this->_stdTimeZone->date()->format('Y-m-d H:i:s');
        //echo $currentDate;

        // Don't Show if item is not meant to be shown yet.
        $filter1 = $this->_filterBuilder->create()->setField('icon_start_date')->setValue($currentDate)->setConditionType('lteq');

        // Don't Show if not Enabled
        //$filter2 = $this->_filterBuilder->create()->setField('enabled')->setValue('1')->setConditionType('eq');

        // Don't Show if End date Has Passed
        $filter2 = $this->_filterBuilder->create()->setField('icon_end_date')->setValue($currentDate)->setConditionType('gt');

        // Limit to Icons
        $filter3 = $this->_filterBuilder->create()->setField('icon_id')->setValue($icon_ids)->setConditionType('in');

        // If you want to have each filter applied in an AND operation you need to insert each filter into a seperate filtergroup, otherwise they would be applied in an OR fashion

        $filterGroup1 = $this->_filterGroupBuilder->create()->setFilters([$filter1]);
        $filterGroup2 = $this->_filterGroupBuilder->create()->setFilters([$filter2]);
        $filterGroup3 = $this->_filterGroupBuilder->create()->setFilters([$filter3]);

        $searchCriteria = $this->_searchCriteriaBuilder->setFilterGroups([$filterGroup1,$filterGroup2,$filterGroup3])->setSortOrders([$sortOrder])->create();

        $icons_for_product = $this->_producticonsRepositoryInterface->getList($searchCriteria);

        $currentStore = $this->_storeManager->getStore();
        $mediaUrl = $currentStore->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);

        $html = "";

        $icons = $icons_for_product->getItems();

        foreach ($icons as $icon) {
            $icon_image = $icon->getIconUrl();
            $icon_title = $icon->getIconTitle();
            $icon_custom_css_classes = $icon->getIconBlkStyle();

            $html .= '<div class="ev-product-icon-outer ' . $icon_custom_css_classes . '">';
            $html .= '<div class="ev-product-icon-inner ' . $icon_custom_css_classes . '">';
            $html .= '<img src="' . $mediaUrl . 'elevate/tmp/producticons/' . $icon_image . '" alt="' . $icon_title . '">';
            $html .= '</div>'; // End .ev-product-icon-inner
      $html .= '</div>'; // End .ev-product-icon-outer
        }

        $html_array[1] = $html;

        return $html_array;
    }

    public function getMediaUrl()
    {
        return $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
    }

    /**
     * @param string $icon_ids
     *
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getIconsForProductAsArray($icon_ids)
    {
        $html_array = [];
        $producticonsFactory = $this->_producticonsFactory->create();

        //TODO:: Should change this to Icon Block Position!!

        $sortOrder = $this->_sortOrderBuilder->setField('icon_blk_position')->setDirection("ASC")->create();
        $currentDate = $this->_stdTimeZone->date()->format('Y-m-d H:i:s');
        //echo $currentDate;

        // Don't Show if item is not meant to be shown yet.
        $filter1 = $this->_filterBuilder->create()->setField('icon_start_date')->setValue($currentDate)->setConditionType('lteq');

        // Don't Show if not Enabled
        //$filter2 = $this->_filterBuilder->create()->setField('enabled')->setValue('1')->setConditionType('eq');

        // Don't Show if End date Has Passed
        $filter2 = $this->_filterBuilder->create()->setField('icon_end_date')->setValue($currentDate)->setConditionType('gt');

        // Limit to Icons
        $filter3 = $this->_filterBuilder->create()->setField('icon_id')->setValue($icon_ids)->setConditionType('in');

        // If you want to have each filter applied in an AND operation you need to insert each filter into a seperate filtergroup, otherwise they would be applied in an OR fashion

        $filterGroup1 = $this->_filterGroupBuilder->create()->setFilters([$filter1]);
        $filterGroup2 = $this->_filterGroupBuilder->create()->setFilters([$filter2]);
        $filterGroup3 = $this->_filterGroupBuilder->create()->setFilters([$filter3]);

        $searchCriteria = $this->_searchCriteriaBuilder->setFilterGroups([$filterGroup1,$filterGroup2,$filterGroup3])->setSortOrders([$sortOrder])->create();

        $icons_for_product = $this->_producticonsRepositoryInterface->getList($searchCriteria);

        $currentStore = $this->_storeManager->getStore();
        $mediaUrl = $currentStore->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);

        $html = "";

        $icon_array = [];

        $icons = $icons_for_product->getItems();

        foreach ($icons as $icon) {
            $icon_id = $icon->getId();
            $icon_image = $icon->getIconUrl();
            $icon_title = $icon->getIconTitle();
            $icon_custom_css_classes = $icon->getIconBlkStyle();

            $icon_array[$icon_id] = $icon->getAllData();


            /*
            $html = '<div class="ev-product-icon-outer ' . $icon_custom_css_classes . '">';
            $html .= '<div class="ev-product-icon-inner ' . $icon_custom_css_classes . '">';
            $html .= '<img src="' . $mediaUrl . 'elevate/tmp/producticons/' . $icon_image . '" alt="' . $icon_title . '">';
            $html .= '</div>'; // End .ev-product-icon-inner
            $html .= '</div>'; // End .ev-product-icon-outer

            $html_array[$icon_id] = $html;
*/
        }


        return [
            'icons' => $icon_array,
            'media_url' => $mediaUrl
        ];
    }

    public function getHtmlOld()
    {
        $html_array = [];
        $producticonsFactory = $this->_producticonsFactory->create();

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

        $menuitems = $this->_producticonsRepositoryInterface->getList($searchCriteria);

        $html = "";

        $currentStore = $this->_storeManager->getStore();
        $mediaUrl = $currentStore->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        $logo_image_link = $this->_scopeConfig->getValue('elevateproducticonsconfig/general/logo', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $logo_alt_text = $this->_scopeConfig->getValue('elevateproducticonsconfig/general/logo_alt_text', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

        $html .= '<a class="cd-nav-trigger-close" href="#cd-primary-nav"></a>';
        $html .= '<li class="mobile-logo">';
        $html .= '<div class="mobile-logo-inner">';
        $html .= '<img src="' . $mediaUrl . 'logo/' . $logo_image_link . '" alt="' . $logo_alt_text . '">';
        $html .= '</div>';
        $html .= '<div style="clear:both;"></div>';
        $html .= '<div class="search-mob-menu" style="padding: 6px;">';
        $html_array[0] = $html;

        $html = '';

        $html .= '</div>';
        $html .= '</li>';

        $menuitems_count = $menuitems->getTotalCount();
        $counter = 0;
        $itemcounter = 1;
        foreach ($menuitems->getItems() as $item) {
            $item->setIsFirst($counter == 0);
            $item->setIsLast($counter == $menuitems_count);
            $item->setNavNumber($itemcounter);

            $classes = $this->getItemClasses($item);
            $classes_output = '';

            foreach ($classes as $class => $classValue) {
                $classes_output .= $classValue . ' ';
            }
            $inner_menu_content = $item->getMenuContent();
            $html .= '<li class="ev_nav_level_1 has-children ' . $classes_output . '">';
            $html .= '<a class="" href="' . $item->getMenuLink() . '">';
            $html .= '<span>' . $item->getMenuName() . '</span>';

            $dropdown_icon = $item->getMenuDropdownicon();

            if ($dropdown_icon === 1) {
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
                } elseif ($menu_align == 2) {
                    $menu_align_class = "mm-center-align";
                } else {
                    $menu_align_class = "mm-right-align";
                }

                $menu_type = $item->getMenuType();
                if ($menu_type == 1) {
                    $html .= '<ul class="cd-secondary-nav ev_nav_level_2 hoverable-content menu-style-1 ' . $menu_align_class . ' is-hidden">';
                //$html .= '<ul class="cd-secondary-nav ev_nav_level_2 hoverable-content is-hidden">';
                } elseif ($menu_type == 3) {
                    $html .= '<ul class="cd-secondary-nav ev_nav_level_2 five-column menu-style-3 ' . $menu_align_class . ' is-hidden">';
                } else {
                    $html .= '<ul class="cd-secondary-nav ev_nav_level_2 five-column menu-style-2 ' . $menu_align_class . ' is-hidden">';
                }
                $html .= '<li class="go-back"><a href="#0"><span class="bk-arrows"></span> Back</a></li>';
                $html .= '<li class="see-all"><a href="' . $item->getMenuLink() . '">All ' . $item->getMenuName() . '</a></li>';
                $html .= $inner_menu_content;
                $html .= '</ul>';
            }
            $html .= '</li>';
            $counter++;
            $itemcounter++;
        }
        $html_array[1] = $html;
    }

    /**
     * Get applicable classes
     *
     * @param object $item
     *
     * @return array
     */

    public function getItemClasses($item)
    {
        $classes = [];

        //$classes[] = 'level' . $item->getLevel();
        //$classes[] = $item->getPositionClass();

        if ($item->getIsFirst()) {
            $classes[] = 'first';
        }

        if ($item->getNavNumber()) {
            $classes[] = 'nav-' . $item->getNavNumber();
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
     * Count All Items
     *
     * @param \Magento\Backend\Model\Menu $items
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
}
