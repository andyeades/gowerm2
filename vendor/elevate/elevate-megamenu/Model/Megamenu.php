<?php
namespace Elevate\Megamenu\Model;

use Elevate\Megamenu\Api\Data\MegamenuInterface;
use Elevate\Megamenu\Api\Data\MegamenuExtensionInterface;
use Magento\Framework\DataObject\IdentityInterface;

class Megamenu extends \Magento\Framework\Model\AbstractModel implements \Elevate\Megamenu\Api\Data\MegamenuInterface
{
  const CACHE_TAG = 'elevate_megamenu_megamenu';
  const NAME = 'name';
  const MENUNAME = 'menu_name';
  const MENULINK = 'menu_link';
  const MENUICON = 'menu_icon';
  const ENABLED = 'enabled';
  const POSITION = 'position';
  const SHOWTYPE = 'show_type';
  const STARTDATE = 'start_date';
  const ENDDATE = 'end_date';
  const MENUCONTENT = 'menu_content';
  const MENUCOLUMNS = 'menu_columns';
  const MENUTYPE = 'menu_type';
  const MENUALIGN = 'menu_align';
  const CUSTOM_TOPLEVEL_HTML_ON = 'custom_toplevel_html_on';
  const CUSTOM_TOPLEVEL_HTML = 'custom_toplevel_html';
  const MENUDROPDOWNICON = 'menu_dropdownicon';

  protected $_cacheTag = 'elevate_megamenu_megamenu';

  protected $_eventPrefix = 'elevate_megamenu_megamenu';

  protected function _construct()
  {
    $this->_init('Elevate\Megamenu\Model\ResourceModel\Megamenu');
  }

  public function getIdentities()
  {
    return [self::CACHE_TAG . '_' . $this->getId()];
  }

  public function getDefaultValues()
  {
    $values = [];

    return $values;
  }

  public function getName() {
    return $this->_getData(self::NAME);
  }

  public function setName($name) {
    $this->setData(self::NAME, $name);
  }

  public function getMenuName() {
    return $this->_getData(self::MENUNAME);
  }

  public function setMenuName($menu_name) {
    $this->setData(self::MENUNAME, $menu_name);
  }

  public function getMenuLink() {
    return $this->_getData(self::MENULINK);
  }

  public function setMenuLink($menu_link) {
    $this->setData(self::MENULINK, $menu_link);
  }

  public function getMenuIcon() {
    return $this->_getData(self::MENUICON);
  }

  public function setMenuIcon($menu_icon) {
    $this->setData(self::MENUICON, $menu_icon);
  }

  public function getEnabled() {
    return $this->_getData(self::ENABLED);
  }

  public function setEnabled($enabled) {
    $this->setData(self::ENABLED, $enabled);
  }

  public function getPosition() {
    return $this->_getData(self::POSITION);
  }

  public function setPosition($position) {
    $this->setData(self::POSITION, $position);
  }

  public function getShowType() {
    return $this->_getData(self::SHOWTYPE);
  }

  public function setShowType($show_type) {
    $this->setData(self::SHOWTYPE, $show_type);
  }

  public function getStartDate() {
    return $this->_getData(self::STARTDATE);
  }

  public function setStartDate($start_date) {
    $this->setData(self::STARTDATE, $start_date);
  }

  public function getEndDate() {
    return $this->_getData(self::ENDDATE);
  }

  public function setEndDate($end_date) {
    $this->setData(self::ENDDATE, $end_date);
  }

  public function getMenuContent() {
    return $this->_getData(self::MENUCONTENT);
  }

  public function setMenuContent($menu_content) {
    $this->setData(self::MENUCONTENT, $menu_content);
  }

  public function getMenuType() {
    return $this->_getData(self::MENUTYPE);
  }

  public function setMenuType($menu_type) {
    $this->setData(self::MENUTYPE, $menu_type);
  }

    public function getMenuColumns() {
        return $this->_getData(self::MENUCOLUMNS);
    }

    public function setMenuColumns($menu_columns) {
        $this->setData(self::MENUCOLUMNS, $menu_columns);
    }

  public function getMenuAlign() {
  return $this->_getData(self::MENUALIGN);
}

  public function setMenuAlign($menu_align) {
    $this->setData(self::MENUALIGN, $menu_align);
  }

  public function getMenuDropdownicon() {
    return $this->_getData(self::MENUDROPDOWNICON);
  }

  public function setMenuDropdownicon($menu_dropdownicon) {
    $this->setData(self::MENUDROPDOWNICON, $menu_dropdownicon);
  }

    public function getCustomToplevelHtmlOn() {
        return $this->_getData(self::CUSTOM_TOPLEVEL_HTML_ON);
    }

    public function setCustomToplevelHtmlOn($custom_toplevel_html_on) {
        $this->setData(self::CUSTOM_TOPLEVEL_HTML_ON, $custom_toplevel_html_on);
    }


    public function getCustomToplevelHtml() {
        return $this->_getData(self::CUSTOM_TOPLEVEL_HTML);
    }

    public function setCustomToplevelHtml($custom_toplevel_html) {
        $this->setData(self::CUSTOM_TOPLEVEL_HTML_ON, $custom_toplevel_html);
    }
}
