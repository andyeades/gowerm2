<?php
namespace Elevate\ProductIcons\Model;

use Elevate\ProductIcons\Api\Data\ProducticonsInterface;
use Elevate\ProductIcons\Api\Data\ProducticonsExtensionInterface;
use Magento\Framework\DataObject\IdentityInterface;

class Producticons extends \Magento\Framework\Model\AbstractModel implements \Elevate\ProductIcons\Api\Data\ProducticonsInterface
{
  const CACHE_TAG = 'elevate_producticons_producticons';

  const ICON_TITLE = 'icon_title';
  const ICON_BLK_POSITION = 'icon_blk_position';
  const ICON_BLK_SHORT_DESC_ENABLED = 'icon_blk_short_desc_enabled';
  const ICON_BLK_DESCRIPTION_ENABLED = 'icon_blk_description_enabled';
  const ICON_START_DATE = 'icon_start_date';
  const ICON_END_DATE = 'icon_end_date';
  const ICON_BLK_SHORT_DESCRIPTION = 'icon_blk_short_description';
  const ICON_BLK_DESCRIPTION = 'icon_blk_description';
  const ICON_BLK_STYLE = 'icon_blk_style';
  const ICON_URL = 'icon_url';

  protected $_cacheTag = 'elevate_producticons_producticons';

  protected $_eventPrefix = 'elevate_producticons_producticons';

  protected function _construct()
  {
    $this->_init('Elevate\ProductIcons\Model\ResourceModel\Producticons');
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

  /**
   * @return string
   */
  public function getIconTitle() {
    return $this->_getData(self::ICON_TITLE);
  }

  /**
   * @param string $title
   * @return void
   */
  public function setIconTitle($title) {
    $this->setData(self::ICON_TITLE, $title);
  }


  /**
   * @return string
   */
  public function getIconUrl() {
    return $this->_getData(self::ICON_URL);
  }

  /**
   * @param string $icon_url
   * @return void
   */
  public function setIconUrl($icon_url) {
    $this->setData(self::ICON_URL, $icon_url);
  }


  /**
   * @return int
   */
  public function getIconBlkPosition() {
    return $this->_getData(self::ICON_BLK_POSITION);
  }

  /**
   * @param string $iconblkposition
   * @return void
   */
  public function setIconBlkPosition($iconblkposition) {
    $this->setData(self::ICON_BLK_POSITION, $iconblkposition);
  }

  /**
   * @return int
   */
  public function getIconBlkShortDescEnabled() {
    return $this->_getData(self::ICON_BLK_SHORT_DESC_ENABLED);
  }

  /**
   * @param string $iconblkshortdescenabled
   * @return void
   */
  public function setIconBlkShortDescEnabled($iconblkshortdescenabled) {
    $this->setData(self::ICON_BLK_SHORT_DESC_ENABLED, $iconblkshortdescenabled);
  }

  /**
   * @return int
   */
  public function getIconBlkDescriptionEnabled() {
    return $this->_getData(self::ICON_BLK_DESCRIPTION_ENABLED);
  }

  /**
   * @param string $iconblkdescenabled
   * @return void
   */
  public function setIconBlkDescriptionEnabled($iconblkdescenabled) {
    $this->setData(self::ICON_BLK_DESCRIPTION_ENABLED, $iconblkdescenabled);
  }


  /**
   * @return string
   */
  public function getIconStartDate() {
    return $this->_getData(self::ICON_START_DATE);
  }

  /**
   * @param string $icon_start_date
   * @return void
   */
  public function setIconStartDate($icon_start_date) {
    $this->setData(self::ICON_START_DATE, $icon_start_date);
  }

  /**
   * @return string
   */
  public function getIconEndDate() {
    return $this->_getData(self::ICON_END_DATE);
  }

  /**
   * @param string $icon_end_date
   * @return void
   */
  public function setIconEndDate($icon_end_date) {
    $this->setData(self::ICON_END_DATE, $icon_end_date);
  }

  /**
   * @return string
   */
  public function getIconBlkShortDescription() {
    return $this->_getData(self::ICON_BLK_SHORT_DESCRIPTION);
  }

  /**
   * @param string $icon_blk_short_description
   * @return void
   */
  public function setIconBlkShortDescription($icon_blk_short_description) {
    $this->setData(self::ICON_BLK_SHORT_DESCRIPTION, $icon_blk_short_description);
  }

  /**
   * @return string
   */
  public function getIconBlkDescription() {
    return $this->_getData(self::ICON_BLK_DESCRIPTION);
  }

  /**
   * @param string $icon_blk_description
   * @return void
   */
  public function setIconBlkDescription($icon_blk_description) {
    $this->setData(self::ICON_BLK_DESCRIPTION, $icon_blk_description);
  }


  /**
   * @return string
   */
  public function getIconBlkStyle() {
    return $this->_getData(self::ICON_BLK_STYLE);
  }

  /**
   * @param string $icon_blk_style
   * @return void
   */
  public function setIconBlkStyle($icon_blk_style) {
    $this->setData(self::ICON_BLK_STYLE, $icon_blk_style);
  }

}