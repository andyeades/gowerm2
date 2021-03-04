<?php

namespace Elevate\Megamenu\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

interface MegamenuInterface extends ExtensibleDataInterface
{
  /**
   * @return int
   */
  public function getId();

  /**
   * @param int $id
   * @return void
   */
  public function setId($id);

  /**
   * @return string
   */
  public function getName();

  /**
   * @param string $name
   * @return void
   */
  public function setName($name);

  /**
   * @return string
   */
  public function getMenuName();

  /**
   * @param string $name
   * @return void
   */
  public function setMenuName($name);

  /**
   * @return string
   */
  public function getMenuLink();

  /**
   * @param string $menu_link
   * @return void
   */
  public function setMenuLink($menu_link);

  /**
   * @return string
   */
  public function getMenuIcon();

  /**
   * @param string $menu_icon
   * @return void
   */
  public function setMenuIcon($menu_icon);

  /**
   * @return int
   */
  public function getEnabled();

  /**
   * @param int $enabled
   * @return void
   */
  public function setEnabled($enabled);


  /**
   * @return int
   */
  public function getPosition();

  /**
   * @param int $position
   * @return void
   */
  public function setPosition($position);

  /**
   * @return int
   */
  public function getShowType();

  /**
   * @param int $show_type
   * @return void
   */
  public function setShowType($show_type);

  /**
   * @return string
   */
  public function getStartDate();

  /**
   * @param string $start_date
   * @return void
   */
  public function setStartDate($start_date);

  /**
   * @return string
   */
  public function getEndDate();

  /**
   * @param string $end_date
   * @return void
   */
  public function setEndDate($end_date);


  /**
   * @return string
   */
  public function getMenuContent();

  /**
   * @param string $menu_content
   * @return void
   */
  public function setMenuContent($menu_content);

  /**
   * @return int
   */
  public function getMenuType();

  /**
   * @param int $menu_type
   * @return void
   */
  public function setMenuType($menu_type);



    /**
     * @return int
     */
    public function getMenuColumns();

    /**
     * @param int $menu_columns
     * @return void
     */
    public function setMenuColumns($menu_columns);


    /**
     * @return string
     */
    public function getMenuDropdownicon();

    /**
     * @param string $menu_dropdownicon
     * @return void
     */
    public function setMenuDropdownicon($menu_dropdownicon);

    /**
     * @return string
     */
    public function getCustomToplevelHtmlOn();

    /**
     * @param string $custom_toplevel_html_on
     * @return void
     */
    public function setCustomToplevelHtmlOn($custom_toplevel_html_on);

    /**
     * @return string
     */
    public function getCustomToplevelHtml();

    /**
     * @param string $custom_toplevel_html
     * @return void
     */
    public function setCustomToplevelHtml($custom_toplevel_html);
}
