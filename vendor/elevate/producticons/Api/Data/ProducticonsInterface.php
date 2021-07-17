<?php

namespace Elevate\ProductIcons\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

interface ProducticonsInterface extends ExtensibleDataInterface
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
  public function getIconTitle();

  /**
   * @param string $icon_title
   * @return void
   */
  public function setIconTitle($icon_title);

  /**
   * @return string
   */
  public function getIconUrl();

  /**
   * @param string $icon_url
   * @return void
   */
  public function setIconUrl($icon_url);

  /**
   * @return int
   */
  public function getIconBlkPosition();

  /**
   * @param string $iconblkposition
   * @return void
   */
  public function setIconBlkPosition($iconblkposition);

  /**
   * @return int
   */
  public function getIconBlkShortDescEnabled();

  /**
   * @param string $iconblkshortdescenabled
   * @return void
   */
  public function setIconBlkShortDescEnabled($iconblkshortdescenabled);

  /**
   * @return int
   */
  public function getIconBlkDescriptionEnabled();

  /**
   * @param string $iconblkdescenabled
   * @return void
   */
  public function setIconBlkDescriptionEnabled($iconblkdescenabled);


  /**
   * @return string
   */
  public function getIconStartDate();

  /**
   * @param string $icon_start_date
   * @return void
   */
  public function setIconStartDate($icon_start_date);

  /**
   * @return string
   */
  public function getIconEndDate();

  /**
   * @param string $icon_end_date
   * @return void
   */
  public function setIconEndDate($icon_end_date);

  /**
   * @return string
   */
  public function getIconBlkShortDescription();

  /**
   * @param string $icon_blk_short_description
   * @return void
   */
  public function setIconBlkShortDescription($icon_blk_short_description);

  /**
   * @return string
   */
  public function getIconBlkDescription();

  /**
   * @param string $icon_blk_description
   * @return void
   */
  public function setIconBlkDescription($icon_blk_description);


  /**
   * @return string
   */
  public function getIconBlkStyle();

  /**
   * @param string $icon_blk_style
   * @return void
   */
  public function setIconBlkStyle($icon_blk_style);

    /**
     * @return mixed
     */
    public function getAllData();
}
