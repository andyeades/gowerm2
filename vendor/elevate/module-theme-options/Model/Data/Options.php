<?php

namespace Elevate\Themeoptions\Model\Data;

use Elevate\Themeoptions\Api\Data\OptionsInterface;

class Options extends \Magento\Framework\Api\AbstractExtensibleObject implements OptionsInterface {

    /**
     * Get entity_id
     *
     * @return string|null
     */
    public function getEntityId() {
        return $this->_get(self::ENTITY_ID);
    }

    /**
     * Set entity_id
     *
     * @param string $entityId
     *
     * @return \Elevate\Themeoptions\Api\Data\OptionsInterface
     */
    public function setEntityId($entityId) {
        return $this->setData(self::ENTITY_ID, $entityId);
    }

    /**
     * @return string
     */
    public function getThemeOptionsName() {
        return $this->_get(self::THEME_OPTIONS_NAME);
    }

    /**
     *
     * @param string $entityId
     *
     * @return \Elevate\Themeoptions\Api\Data\OptionsInterface
     */
    public function setThemeOptionsName($theme_options_name) {
        return $this->setData(self::THEME_OPTIONS_NAME, $theme_options_name);
    }

    /**
     * @return string
     */
    public function getHdrGeneralColor() {
        return $this->_get(self::HDR_GENERAL_COLOR);
    }

    /**
     * @param \Elevate\Themeoptions\Api\Data\OptionsInterface
     */
    public function setHdrGeneralColor($hdr_general_color) {
        return $this->setData(self::HDR_GENERAL_COLOR, $hdr_general_color);
    }

    /**
     * @return string
     */
    public function getHdrLinkColor() {
        return $this->_get(self::HDR_LINK_COLOR);
    }

    /**
     * @param string $hdr_link_color
     */
    public function setHdrLinkColor($hdr_link_color) {
        return $this->setData(self::HDR_LINK_COLOR, $hdr_link_color);
    }

    /**
     * @return string
     */
    public function getHdrLinkHoverColor() {
        return $this->_get(self::HDR_LINK_HOVER_COLOR);
    }

    /**
     * @param string $hdr_link_hover_color
     */
    public function setHdrLinkHoverColor($hdr_link_hover_color) {
        return $this->setData(self::HDR_LINK_HOVER_COLOR, $hdr_link_hover_color);
    }

    /**
     * @return string
     */
    public function getHdrGeneralFontSize() {
        return $this->_get(self::HDR_GENERAL_FONT_SIZE);
    }

    /**
     * @param string $hdr_general_font_size
     */
    public function setHdrGeneralFontSize($hdr_general_font_size) {
        return $this->setData(self::HDR_GENERAL_FONT_SIZE, $hdr_general_font_size);
    }

    /**
     * @return string
     */
    public function getHdrFontSize() {
        return $this->_get(self::HDR_FONT_SIZE);
    }

    /**
     * @param string $hdr_font_size
     */
    public function setHdrFontSize($hdr_font_size) {
        return $this->setData(self::HDR_FONT_SIZE, $hdr_font_size);
    }

    /**
     * @return string
     */
    public function getHdrMainOuterBackgroundColor() {
        return $this->_get(self::HDR_MAIN_OUTER_BACKGROUND_COLOR);
    }

    /**
     * @param string $hdr_main_outer_background_color
     */
    public function setHdrMainOuterBackgroundColor($hdr_main_outer_background_color) {
        return $this->setData(self::HDR_MAIN_OUTER_BACKGROUND_COLOR, $hdr_main_outer_background_color);
    }

    /**
     * @return string
     */
    public function getHdrTextColor() {
        return $this->_get(self::HDR_TEXT_COLOR);
    }

    /**
     * @param string $hdr_text_color
     */
    public function setHdrTextColor($hdr_text_color) {
        return $this->setData(self::HDR_TEXT_COLOR, $hdr_text_color);
    }

    /**
     * @return string
     */
    public function getHdrSearchInputFontSize() {
        return $this->_get(self::HDR_SEARCH_INPUT_FONT_SIZE);
    }

    /**
     * @param string $hdr_search_input_font_size
     */
    public function setHdrSearchInputFontSize($hdr_search_input_font_size) {
        return $this->setData(self::HDR_SEARCH_INPUT_FONT_SIZE, $hdr_search_input_font_size);
    }

    /**
     * @return string
     */
    public function getHdrSearchInputBgColor() {
        return $this->_get(self::HDR_SEARCH_INPUT_BG_COLOR);
    }

    /**
     * @param string $hdr_search_input_bg_color
     */
    public function setHdrSearchInputBgColor($hdr_search_input_bg_color) {
        return $this->setData(self::HDR_SEARCH_INPUT_BG_COLOR, $hdr_search_input_bg_color);
    }

    /**
     * @return string
     */
    public function getHdrSearchTextColor() {
        return $this->_get(self::HDR_SEARCH_TEXT_COLOR);
    }

    /**
     * @param string $hdr_search_text_color
     */
    public function setHdrSearchTextColor($hdr_search_text_color) {
        return $this->setData(self::HDR_SEARCH_TEXT_COLOR, $hdr_search_text_color);
    }

    /**
     * @return string
     */
    public function getHdrSearchIconColor() {
        return $this->_get(self::HDR_SEARCH_ICON_COLOR);
    }

    /**
     * @param string $hdr_search_icon_color
     */
    public function setHdrSearchIconColor($hdr_search_icon_color) {
        return $this->setData(self::HDR_SEARCH_ICON_COLOR, $hdr_search_icon_color);
    }

    /**
     * @return string
     */
    public function getHdrSearchPlaceholderTextColor() {
        return $this->_get(self::HDR_SEARCH_PLACEHOLDER_TEXT_COLOR);
    }

    /**
     * @param string $hdr_search_placeholder_text_color
     */
    public function setHdrSearchPlaceholderTextColor($hdr_search_placeholder_text_color) {
        return $this->setData(self::HDR_SEARCH_PLACEHOLDER_TEXT_COLOR, $hdr_search_placeholder_text_color);
    }

    /**
     * @return string
     */
    public function getHdrSearchInputTextColor() {
        return $this->_get(self::HDR_SEARCH_INPUT_TEXT_COLOR);
    }

    /**
     * @param string $hdr_search_input_text_color
     */
    public function setHdrSearchInputTextColor($hdr_search_input_text_color) {
        return $this->setData(self::HDR_SEARCH_INPUT_TEXT_COLOR, $hdr_search_input_text_color);
    }

    /**
     * @return string
     */
    public function getHdrSearchBorderColor() {
        return $this->_get(self::HDR_SEARCH_BORDER_COLOR);
    }

    /**
     * @param string $hdr_search_border_color
     */
    public function setHdrSearchBorderColor($hdr_search_border_color) {
        return $this->setData(self::HDR_SEARCH_BORDER_COLOR, $hdr_search_border_color);
    }

    /**
     * @return string
     */
    public function getHdrRightLinkColor() {
        return $this->_get(self::HDR_RIGHT_LINK_COLOR);
    }

    /**
     * @param string $hdr_right_link_color
     */
    public function setHdrRightLinkColor($hdr_right_link_color) {
        return $this->setData(self::HDR_RIGHT_LINK_COLOR, $hdr_right_link_color);
    }

    /**
     * @return string
     */
    public function getHdrRTrowTextAlign() {
        return $this->_get(self::HDR_R_TROW_TEXT_ALIGN);
    }

    /**
     * @param string $hdr_r_trow_text_align
     */
    public function setHdrRTrowTextAlign($hdr_r_trow_text_align) {
        return $this->setData(self::HDR_R_TROW_TEXT_ALIGN, $hdr_r_trow_text_align);
    }

    /**
     * @return string
     */
    public function getHdrRTrowHeight() {
        return $this->_get(self::HDR_R_TROW_HEIGHT);
    }

    /**
     * @param string $hdr_r_trow_height
     */
    public function setHdrRTrowHeight($hdr_r_trow_height) {
        return $this->setData(self::HDR_R_TROW_HEIGHT, $hdr_r_trow_height);
    }

    /**
     * @return string
     */
    public function getHdrRTrowPadding() {
        return $this->_get(self::HDR_R_TROW_PADDING);
    }

    /**
     * @param string $hdr_r_trow_padding
     */
    public function setHdrRTrowPadding($hdr_r_trow_padding) {
        return $this->setData(self::HDR_R_TROW_PADDING, $hdr_r_trow_padding);
    }

    /**
     * @return string
     */
    public function getHdrRTrowUlLiFontweight() {
        return $this->_get(self::HDR_R_TROW_UL_LI_FONTWEIGHT);
    }

    /**
     * @param string $hdr_r_trow_ul_li_fontweight
     */
    public function setHdrRTrowUlLiFontweight($hdr_r_trow_ul_li_fontweight) {
        return $this->setData(self::HDR_R_TROW_UL_LI_FONTWEIGHT, $hdr_r_trow_ul_li_fontweight);
    }

    /**
     * @return string
     */
    public function getHdrRTrowUlLiAColor() {
        return $this->_get(self::HDR_R_TROW_UL_LI_A_COLOR);
    }

    /**
     * @param string $hdr_r_trow_ul_li_a_color
     */
    public function setHdrRTrowUlLiAColor($hdr_r_trow_ul_li_a_color) {
        return $this->setData(self::HDR_R_TROW_UL_LI_A_COLOR, $hdr_r_trow_ul_li_a_color);
    }

    /**
     * @return string
     */
    public function getHdrRTrowUlLiAHoverColor() {
        return $this->_get(self::HDR_R_TROW_UL_LI_A_HOVER_COLOR);
    }

    /**
     * @param string $hdr_r_trow_ul_li_a_hover_color
     */
    public function setHdrRTrowUlLiAHoverColor($hdr_r_trow_ul_li_a_hover_color) {
        return $this->setData(self::HDR_R_TROW_UL_LI_A_HOVER_COLOR, $hdr_r_trow_ul_li_a_hover_color);
    }

    /**
     * @return string
     */
    public function getHdrRTrowLinkColorAlt1() {
        return $this->_get(self::HDR_R_TROW_LINK_COLOR_ALT1);
    }

    /**
     * @param string $hdr_r_trow_link_color_alt1
     */
    public function setHdrRTrowLinkColorAlt1($hdr_r_trow_link_color_alt1) {
        return $this->setData(self::HDR_R_TROW_LINK_COLOR_ALT1, $hdr_r_trow_link_color_alt1);
    }

    /**
     * @return string
     */
    public function getHdrRTrowLinkHoverColorAlt1() {
        return $this->_get(self::HDR_R_TROW_LINK_HOVER_COLOR_ALT1);
    }

    /**
     * @param string $hdr_r_trow_link_hover_color_alt1
     */
    public function setHdrRTrowLinkHoverColorAlt1($hdr_r_trow_link_hover_color_alt1) {
        return $this->setData(self::HDR_R_TROW_LINK_HOVER_COLOR_ALT1, $hdr_r_trow_link_hover_color_alt1);
    }

    /**
     * @return string
     */
    public function getHdrRTrowLinkColorAlt2() {
        return $this->_get(self::HDR_R_TROW_LINK_COLOR_ALT2);
    }

    /**
     * @param string $hdr_r_trow_link_color_alt2
     */
    public function setHdrRTrowLinkColorAlt2($hdr_r_trow_link_color_alt2) {
        return $this->setData(self::HDR_R_TROW_LINK_COLOR_ALT2, $hdr_r_trow_link_color_alt2);
    }

    /**
     * @return string
     */
    public function getHdrRTrowLinkHoverColorAlt2() {
        return $this->_get(self::HDR_R_TROW_LINK_HOVER_COLOR_ALT2);
    }

    /**
     * @param string $hdr_r_trow_link_hover_color_alt2
     */
    public function setHdrRTrowLinkHoverColorAlt2($hdr_r_trow_link_hover_color_alt2) {
        return $this->setData(self::HDR_R_TROW_LINK_HOVER_COLOR_ALT2, $hdr_r_trow_link_hover_color_alt2);
    }

    /**
     * @return string
     */
    public function getHdrTopListLiColor() {
        return $this->_get(self::HDR_TOP_LIST_LI_COLOR);
    }

    /**
     * @param string $hdr_top_list_li_color
     */
    public function setHdrTopListLiColor($hdr_top_list_li_color) {
        return $this->setData(self::HDR_TOP_LIST_LI_COLOR, $hdr_top_list_li_color);
    }

    /**
     * @return string
     */
    public function getHdrSearchBorderPosition() {
        return $this->_get(self::HDR_SEARCH_BORDER_POSITION);

    }

    /**
     * @param string $hdr_search_border_position
     */
    public function setHdrSearchBorderPosition($hdr_search_border_position) {
        return $this->setData(self::HDR_SEARCH_BORDER_POSITION, $hdr_search_border_position);
    }

    /**
     * @return string
     */
    public function getHdrSearchBorderThickness() {
        return $this->_get(self::HDR_SEARCH_BORDER_THICKNESS);

    }

    /**
     * @param string $hdr_search_border_thickness
     */
    public function setHdrSearchBorderThickness($hdr_search_border_thickness) {
        return $this->setData(self::HDR_SEARCH_BORDER_THICKNESS, $hdr_search_border_thickness);
    }

    /**
     * @return string
     */
    public function getHdrSearchBorderStyle() {
        return $this->_get(self::HDR_SEARCH_BORDER_STYLE);

    }

    /**
     * @param string $hdr_search_border_style
     */
    public function setHdrSearchBorderStyle($hdr_search_border_style) {
        return $this->setData(self::HDR_SEARCH_BORDER_STYLE, $hdr_search_border_style);
    }

    /**
     * @return mixed
     */
    public function getHdrRTrowUlLiFirstSeperator() {
        return $this->_get(self::HDR_R_TROW_UL_LI_FIRST_SEPERATOR);
    }

    /**
     * @param mixed $hdr_r_trow_ul_li_first_seperator
     */
    public function setHdrRTrowUlLiFirstSeperator($hdr_r_trow_ul_li_first_seperator) {
        return $this->setData(self::HDR_R_TROW_UL_LI_FIRST_SEPERATOR, $hdr_r_trow_ul_li_first_seperator);
    }

    /**
     * @return mixed
     */
    public function getHdrRTrowUlLiFirstSeperatorBorderColor() {
        return $this->_get(self::HDR_R_TROW_UL_LI_FIRST_SEPERATOR_BORDER_COLOR);

    }

    /**
     * @param mixed $hdr_r_trow_ul_li_first_seperator_border_color
     */
    public function setHdrRTrowUlLiFirstSeperatorBorderColor($hdr_r_trow_ul_li_first_seperator_border_color) {
        return $this->setData(self::HDR_R_TROW_UL_LI_FIRST_SEPERATOR_BORDER_COLOR, $hdr_r_trow_ul_li_first_seperator_border_color);
    }

    /**
     * @return mixed
     */
    public function getHdrRTrowUlLiFirstSeperatorBorderThickness() {
        return $this->_get(self::HDR_R_TROW_UL_LI_FIRST_SEPERATOR_BORDER_THICKNESS);

    }

    /**
     * @param mixed $hdr_r_trow_ul_li_first_seperator_border_thickness
     */
    public function setHdrRTrowUlLiFirstSeperatorBorderThickness($hdr_r_trow_ul_li_first_seperator_border_thickness) {
        return $this->setData(self::HDR_R_TROW_UL_LI_FIRST_SEPERATOR_BORDER_THICKNESS, $hdr_r_trow_ul_li_first_seperator_border_thickness);
    }

    /**
     * @return mixed
     */
    public function getHdrRTrowUlLiFirstSeperatorBorderStyle() {
        return $this->_get(self::HDR_R_TROW_UL_LI_FIRST_SEPERATOR_BORDER_STYLE);

    }

    /**
     * @param mixed $hdr_r_trow_ul_li_first_seperator_border_style
     */
    public function setHdrRTrowUlLiFirstSeperatorBorderStyle($hdr_r_trow_ul_li_first_seperator_border_style) {
        return $this->setData(self::HDR_R_TROW_UL_LI_FIRST_SEPERATOR_BORDER_STYLE, $hdr_r_trow_ul_li_first_seperator_border_style);
    }

    /**
     * @return mixed
     */
    public function getHdrSearchStyle() {
        return $this->_get(self::HDR_SEARCH_STYLE);

    }

    /**
     * @param mixed $hdr_search_style
     */
    public function setHdrSearchStyle($hdr_search_style) {
        return $this->setData(self::HDR_SEARCH_STYLE, $hdr_search_style);
    }

    /**
     * @return mixed
     */
    public function getHdrRTrowbrowSidebyside() {
        return $this->_get(self::HDR_R_TROWBROW_SIDEBYSIDE);

    }

    /**
     * @param mixed $hdr_r_trowbrow_sidebyside
     */
    public function setHdrRTrowbrowSidebyside($hdr_r_trowbrow_sidebyside) {
        return $this->setData(self::HDR_R_TROWBROW_SIDEBYSIDE, $hdr_r_trowbrow_sidebyside);
    }

    /**
     * @return mixed
     */
    public function getHdrTopListFontSize() {
        return $this->_get(self::HDR_TOP_LIST_FONT_SIZE);

    }

    /**
     * @param mixed $hdr_top_list_font_size
     */
    public function setHdrTopListFontSize($hdr_top_list_font_size) {
        return $this->setData(self::HDR_TOP_LIST_FONT_SIZE, $hdr_top_list_font_size);
    }

    /**
     * @return mixed
     */
    public function getHdrDesktopHeight() {
        return $this->_get(self::HDR_DESKTOP_HEIGHT);

    }

    /**
     * @param mixed $hdr_desktop_height
     */
    public function setHdrDesktopHeight($hdr_desktop_height) {
        return $this->setData(self::HDR_DESKTOP_HEIGHT, $hdr_desktop_height);
    }

    /**
     * @return mixed
     */
    public function getHdrMobileHeight() {
        return $this->_get(self::HDR_MOBILE_HEIGHT);

    }

    /**
     * @param mixed $hdr_mobile_height
     */
    public function setHdrMobileHeight($hdr_mobile_height) {
        return $this->setData(self::HDR_MOBILE_HEIGHT, $hdr_mobile_height);
    }

    /**
     * @return mixed
     */
    public function getHdrMinicartCounterBorderEnabled() {
        return $this->_get(self::HDR_MINICART_COUNTER_BORDER_ENABLED);

    }

    /**
     * @param mixed $hdr_minicart_counter_border_enabled
     */
    public function setHdrMinicartCounterBorderEnabled($hdr_minicart_counter_border_enabled) {
        return $this->setData(self::HDR_MINICART_COUNTER_BORDER_ENABLED, $hdr_minicart_counter_border_enabled);
    }

    /**
     * @return mixed
     */
    public function getHdrMinicartCounterPosition() {
        return $this->_get(self::HDR_MINICART_COUNTER_POSITION);

    }

    /**
     * @param mixed $hdr_minicart_counter_position
     */
    public function setHdrMinicartCounterPosition($hdr_minicart_counter_position) {
        return $this->setData(self::HDR_MINICART_COUNTER_POSITION, $hdr_minicart_counter_position);
    }

    /**
     * @return mixed
     */
    public function getHdrMinicartCounterBorderColor() {
        return $this->_get(self::HDR_MINICART_COUNTER_BORDER_COLOR);

    }

    /**
     * @param mixed $hdr_minicart_counter_border_color
     */
    public function setHdrMinicartCounterBorderColor($hdr_minicart_counter_border_color) {
        return $this->setData(self::HDR_MINICART_COUNTER_BORDER_COLOR, $hdr_minicart_counter_border_color);
    }

    /**
     * @return mixed
     */
    public function getHdrMinicartCounterBorderStyle() {
        return $this->_get(self::HDR_MINICART_COUNTER_BORDER_STYLE);

    }

    /**
     * @param mixed $hdr_minicart_counter_border_style
     */
    public function setHdrMinicartCounterBorderStyle($hdr_minicart_counter_border_style) {
        return $this->setData(self::HDR_MINICART_COUNTER_BORDER_STYLE, $hdr_minicart_counter_border_style);
    }

    /**
     * @return mixed
     */
    public function getHdrMinicartCounterBorderThickness() {
        return $this->_get(self::HDR_MINICART_COUNTER_BORDER_THICKNESS);

    }

    /**
     * @param mixed $hdr_minicart_counter_border_thickness
     */
    public function setHdrMinicartCounterBorderThickness($hdr_minicart_counter_border_thickness) {
        return $this->setData(self::HDR_MINICART_COUNTER_BORDER_THICKNESS, $hdr_minicart_counter_border_thickness);
    }

    /**
     * @return mixed
     */
    public function getHdrMinicartCounterBackgroundColor() {
        return $this->_get(self::HDR_MINICART_COUNTER_BACKGROUND_COLOR);

    }

    /**
     * @param mixed $hdr_minicart_counter_background_color
     */
    public function setHdrMinicartCounterBackgroundColor($hdr_minicart_counter_background_color) {
        return $this->setData(self::HDR_MINICART_COUNTER_BACKGROUND_COLOR, $hdr_minicart_counter_background_color);
    }

    /**
     * @return mixed
     */
    public function getHdrMinicartCounterTextColor() {
        return $this->_get(self::HDR_MINICART_COUNTER_TEXT_COLOR);

    }

    /**
     * @param mixed $hdr_minicart_counter_text_color
     */
    public function setHdrMinicartCounterTextColor($hdr_minicart_counter_text_color) {
        return $this->setData(self::HDR_MINICART_COUNTER_TEXT_COLOR, $hdr_minicart_counter_text_color);
    }


    /**
     * @return mixed
     */
    public function getHdrSearchIconPosition() {
        return $this->_get(self::HDR_SEARCH_ICON_POSITION);

    }

    /**
     * @param mixed $hdr_search_icon_position
     */
    public function setHdrSearchIconPosition($hdr_search_icon_position) {
        return $this->setData(self::HDR_SEARCH_ICON_POSITION, $hdr_search_icon_position);
    }


    public function getAllData() {
        return $this->_data;
    }

    /**
     * Retrieve existing extension attributes object or create a new one.
     *
     * @return \Elevate\Themeoptions\Api\Data\OptionsExtensionInterface|null
     */
    public function getExtensionAttributes() {
        return $this->_getExtensionAttributes();
    }

    /**
     * Set an extension attributes object.
     *
     * @param \Elevate\Themeoptions\Api\Data\OptionsExtensionInterface $extensionAttributes
     *
     * @return $this
     */
    public function setExtensionAttributes(
        \Elevate\Themeoptions\Api\Data\OptionsExtensionInterface $extensionAttributes
    ) {
        return $this->_setExtensionAttributes($extensionAttributes);
    }
}
