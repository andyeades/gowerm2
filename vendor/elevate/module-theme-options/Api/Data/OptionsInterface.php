<?php

namespace Elevate\Themeoptions\Api\Data;

interface OptionsInterface extends \Magento\Framework\Api\ExtensibleDataInterface {

    const ENTITY_ID = 'entity_id';
    const THEME_OPTIONS_NAME = 'theme_options_name';
    const HDR_GENERAL_COLOR = 'hdr_general_color';
    const HDR_LINK_COLOR = 'hdr_link_color';
    const HDR_LINK_HOVER_COLOR = 'hdr_link_hover_color';
    const HDR_GENERAL_FONT_SIZE = 'hdr_general_font_size';
    const HDR_FONT_SIZE = 'hdr_font_size';
    const HDR_MAIN_OUTER_BACKGROUND_COLOR = 'hdr_main_outer_background_color';
    const HDR_TEXT_COLOR = 'hdr_text_color';
    const HDR_SEARCH_STYLE = 'hdr_search_style';
    const HDR_SEARCH_INPUT_FONT_SIZE = 'hdr_search_input_font_size';
    const HDR_SEARCH_INPUT_BG_COLOR = 'hdr_search_input_bg_color';
    const HDR_SEARCH_TEXT_COLOR = 'hdr_search_text_color';
    const HDR_SEARCH_ICON_COLOR = 'hdr_search_icon_color';
    const HDR_SEARCH_PLACEHOLDER_TEXT_COLOR = 'hdr_search_placeholder_text_color';
    const HDR_SEARCH_INPUT_TEXT_COLOR = 'hdr_search_input_text_color';
    const HDR_SEARCH_BORDER_COLOR = 'hdr_search_border_color';
    const HDR_SEARCH_BORDER_POSITION = 'hdr_search_border_position';
    const HDR_SEARCH_BORDER_THICKNESS = 'hdr_search_border_thickness';
    const HDR_SEARCH_BORDER_STYLE = 'hdr_search_border_style';
    //const HDR_SEARCH_BORDER_TYPE = 'hdr_search_border_type';
    const HDR_RIGHT_LINK_COLOR = 'hdr_right_link_color';
    const HDR_R_TROWBROW_SIDEBYSIDE = 'hdr_r_trowbrow_sidebyside';
    const HDR_R_TROW_TEXT_ALIGN = 'hdr_r_trow_text_align';
    const HDR_R_TROW_HEIGHT = 'hdr_r_trow_height';
    const HDR_R_TROW_PADDING = 'hdr_r_trow_padding';
    const HDR_R_TROW_UL_LI_FONTWEIGHT = 'hdr_r_trow_ul_li_fontweight';
    const HDR_R_TROW_UL_LI_A_COLOR = 'hdr_r_trow_ul_li_a_color';
    const HDR_R_TROW_UL_LI_A_HOVER_COLOR = 'hdr_r_trow_ul_li_a_hover_color';
    const HDR_R_TROW_LINK_COLOR_ALT1 = 'hdr_r_trow_link_color_alt1';
    const HDR_R_TROW_LINK_HOVER_COLOR_ALT1 = 'hdr_r_trow_link_hover_color_alt1';
    const HDR_R_TROW_LINK_COLOR_ALT2 = 'hdr_r_trow_link_color_alt2';
    const HDR_R_TROW_LINK_HOVER_COLOR_ALT2 = 'hdr_r_trow_link_hover_color_alt2';

    const HDR_TOP_LIST_LI_COLOR = 'hdr_top_list_li_color';
    const HDR_R_TROW_UL_LI_FIRST_SEPERATOR = 'hdr_r_trow_ul_li_first_seperator';
    const HDR_R_TROW_UL_LI_FIRST_SEPERATOR_BORDER_COLOR = 'hdr_r_trow_ul_li_first_seperator_border_color';
    const HDR_R_TROW_UL_LI_FIRST_SEPERATOR_BORDER_THICKNESS = 'hdr_r_trow_ul_li_first_seperator_border_thickness';
    const HDR_R_TROW_UL_LI_FIRST_SEPERATOR_BORDER_STYLE = 'hdr_r_trow_ul_li_first_seperator_border_style';
    const HDR_TOP_LIST_FONT_SIZE = 'hdr_top_list_font_size';
    const HDR_DESKTOP_HEIGHT = 'hdr_desktop_height';
    const HDR_MOBILE_HEIGHT = 'hdr_mobile_height';

    const HDR_MINICART_COUNTER_BORDER_ENABLED = 'hdr_minicart_counter_border_enabled';
    const HDR_MINICART_COUNTER_POSITION = 'hdr_minicart_counter_position';
    const HDR_MINICART_COUNTER_BORDER_COLOR = 'hdr_minicart_counter_border_color';
    const HDR_MINICART_COUNTER_BORDER_STYLE = 'hdr_minicart_counter_border_style';
    const HDR_MINICART_COUNTER_BORDER_THICKNESS = 'hdr_minicart_counter_border_thickness';
    const HDR_MINICART_COUNTER_BACKGROUND_COLOR = 'hdr_minicart_counter_background_color';
    const HDR_MINICART_COUNTER_TEXT_COLOR = 'hdr_minicart_counter_text_color';
    const HDR_SEARCH_ICON_POSITION = 'hdr_search_icon_position';


    /**
     * Get entity_id
     *
     * @return string|null
     */
    public function getEntityId();

    /**
     * Set entity_id
     *
     * @param string $entityId
     *
     * @return \Elevate\Themeoptions\Api\Data\OptionsInterface
     */
    public function setEntityId($entityId);

    /**
     * @return string
     */
    public function getThemeOptionsName();

    /**
     *
     * @param string $entityId
     *
     * @return \Elevate\Themeoptions\Api\Data\OptionsInterface
     */
    public function setThemeOptionsName($theme_options_name);

    /**
     * @return string
     */
    public function getHdrGeneralColor();

    /**
     * @param string $hdr_general_color
     */
    public function setHdrGeneralColor($hdr_general_color);

    /**
     * @return string
     */
    public function getHdrLinkColor();

    /**
     * @param string $hdr_link_color
     */
    public function setHdrLinkColor($hdr_link_color);

    /**
     * @return string
     */
    public function getHdrLinkHoverColor();

    /**
     * @param string $hdr_link_hover_color
     */
    public function setHdrLinkHoverColor($hdr_link_hover_color);

    /**
     * @return string
     */
    public function getHdrGeneralFontSize();

    /**
     * @param string $hdr_general_font_size
     */
    public function setHdrGeneralFontSize($hdr_general_font_size);

    /**
     * @return string
     */
    public function getHdrFontSize();

    /**
     * @param string $hdr_font_size
     */
    public function setHdrFontSize($hdr_font_size);

    /**
     * @return string
     */
    public function getHdrMainOuterBackgroundColor();

    /**
     * @param string $hdr_main_outer_background_color
     */
    public function setHdrMainOuterBackgroundColor($hdr_main_outer_background_color);

    /**
     * @return string
     */
    public function getHdrTextColor();

    /**
     * @param string $hdr_text_color
     */
    public function setHdrTextColor($hdr_text_color);

    /**
     * @return string
     */
    public function getHdrSearchInputFontSize();

    /**
     * @param string $hdr_search_input_font_size
     */
    public function setHdrSearchInputFontSize($hdr_search_input_font_size);

    /**
     * @return string
     */
    public function getHdrSearchInputBgColor();
    /**
     * @param string $hdr_search_input_bg_color
     */
    public function setHdrSearchInputBgColor($hdr_search_input_bg_color);

    /**
     * @return string
     */
    public function getHdrSearchTextColor();

    /**
     * @param string $hdr_search_text_color
     */
    public function setHdrSearchTextColor($hdr_search_text_color);

    /**
     * @return string
     */
    public function getHdrSearchIconColor();

    /**
     * @param string $hdr_search_icon_color
     */
    public function setHdrSearchIconColor($hdr_search_icon_color);

    /**
     * @return string
     */
    public function getHdrSearchPlaceholderTextColor();

    /**
     * @param string $hdr_search_placeholder_text_color
     */
    public function setHdrSearchPlaceholderTextColor($hdr_search_placeholder_text_color);

    /**
     * @return string
     */
    public function getHdrSearchInputTextColor();

    /**
     * @param string $hdr_search_input_text_color
     */
    public function setHdrSearchInputTextColor($hdr_search_input_text_color);

    /**
     * @return string
     */
    public function getHdrSearchBorderColor();

    /**
     * @param string $hdr_search_border_color
     */
    public function setHdrSearchBorderColor($hdr_search_border_color);

    /**
     * @return string
     */
    public function getHdrRightLinkColor();

    /**
     * @param string $hdr_right_link_color
     */
    public function setHdrRightLinkColor($hdr_right_link_color);

    /**
     * @return string
     */
    public function getHdrRTrowTextAlign();

    /**
     * @param string $hdr_r_trow_text_align
     */
    public function setHdrRTrowTextAlign($hdr_r_trow_text_align);

    /**
     * @return string
     */
    public function getHdrRTrowHeight();

    /**
     * @param string $hdr_r_trow_height
     */
    public function setHdrRTrowHeight($hdr_r_trow_height);

    /**
     * @return string
     */
    public function getHdrRTrowPadding();

    /**
     * @param string $hdr_r_trow_padding
     */
    public function setHdrRTrowPadding($hdr_r_trow_padding);

    /**
     * @return string
     */
    public function getHdrRTrowUlLiFontweight();

    /**
     * @param string $hdr_r_trow_ul_li_fontweight
     */
    public function setHdrRTrowUlLiFontweight($hdr_r_trow_ul_li_fontweight);

    /**
     * @return string
     */
    public function getHdrRTrowUlLiAColor();

    /**
     * @param string $hdr_r_trow_ul_li_a_color
     */
    public function setHdrRTrowUlLiAColor($hdr_r_trow_ul_li_a_color);

    /**
     * @return string
     */
    public function getHdrRTrowUlLiAHoverColor();

    /**
     * @param string $hdr_r_trow_ul_li_a_hover_color
     */
    public function setHdrRTrowUlLiAHoverColor($hdr_r_trow_ul_li_a_hover_color);

    /**
     * @return string
     */
    public function getHdrRTrowLinkColorAlt1();

    /**
     * @param string $hdr_r_trow_link_color_alt1
     */
    public function setHdrRTrowLinkColorAlt1($hdr_r_trow_link_color_alt1);

    /**
     * @return string
     */
    public function getHdrRTrowLinkHoverColorAlt1();

    /**
     * @param string $hdr_r_trow_link_hover_color_alt1
     */
    public function setHdrRTrowLinkHoverColorAlt1($hdr_r_trow_link_hover_color_alt1);

    /**
     * @return string
     */
    public function getHdrRTrowLinkColorAlt2();

    /**
     * @param string $hdr_r_trow_link_color_alt2
     */
    public function setHdrRTrowLinkColorAlt2($hdr_r_trow_link_color_alt2);

    /**
     * @return string
     */
    public function getHdrRTrowLinkHoverColorAlt2();
    /**
     * @param string $hdr_r_trow_link_hover_color_alt2
     */
    public function setHdrRTrowLinkHoverColorAlt2($hdr_r_trow_link_hover_color_alt2);
    /**
     * @return string
     */
    public function getHdrTopListLiColor();

    /**
     * @param string $hdr_top_list_li_color
     */
    public function setHdrTopListLiColor($hdr_top_list_li_color);

    /**
     * @return string
     */
    public function getHdrSearchBorderPosition();

    /**
     * @param string $hdr_search_border_position
     */
    public function setHdrSearchBorderPosition($hdr_search_border_position);

    /**
     * @return string
     */
    public function getHdrSearchBorderThickness();

    /**
     * @param string $hdr_search_border_thickness
     */
    public function setHdrSearchBorderThickness($hdr_search_border_thickness);

    /**
     * @return string
     */
    public function getHdrSearchBorderStyle();

    /**
     * @param string $hdr_search_border_style
     */
    public function setHdrSearchBorderStyle($hdr_search_border_style);

    /**
     * @return mixed
     */
    public function getHdrRTrowUlLiFirstSeperator();

    /**
     * @param mixed $hdr_r_trow_ul_li_first_seperator
     */
    public function setHdrRTrowUlLiFirstSeperator($hdr_r_trow_ul_li_first_seperator);

    /**
     * @return mixed
     */
    public function getHdrRTrowUlLiFirstSeperatorBorderColor();

    /**
     * @param mixed $hdr_r_trow_ul_li_first_seperator_border_color
     */
    public function setHdrRTrowUlLiFirstSeperatorBorderColor($hdr_r_trow_ul_li_first_seperator_border_color);

    /**
     * @return mixed
     */
    public function getHdrRTrowUlLiFirstSeperatorBorderThickness();

    /**
     * @param mixed $hdr_r_trow_ul_li_first_seperator_border_thickness
     */
    public function setHdrRTrowUlLiFirstSeperatorBorderThickness($hdr_r_trow_ul_li_first_seperator_border_thickness);

    /**
     * @return mixed
     */
    public function getHdrRTrowUlLiFirstSeperatorBorderStyle();

    /**
     *
     * @param mixed $hdr_r_trow_ul_li_first_seperator_border_style
     */

    public function setHdrRTrowUlLiFirstSeperatorBorderStyle($hdr_r_trow_ul_li_first_seperator_border_style);


    /**
     *
     * @return mixed
     */
    public function getHdrSearchStyle();

    /**
     *
     * @param mixed $hdr_search_style
     */
    public function setHdrSearchStyle($hdr_search_style);


    /**
     * @return mixed
     */
    public function getHdrRTrowbrowSidebyside();

    /**
     * @param mixed $hdr_r_trowbrow_sidebyside
     */
    public function setHdrRTrowbrowSidebyside($hdr_r_trowbrow_sidebyside);

    /**
     * @return mixed
     */
    public function getHdrTopListFontSize();

    /**
     * @param mixed $hdr_top_list_font_size
     */
    public function setHdrTopListFontSize($hdr_top_list_font_size);

    /**
     * @return mixed
     */
    public function getHdrDesktopHeight();

    /**
     * @param mixed $hdr_desktop_height
     */
    public function setHdrDesktopHeight($hdr_desktop_height);

    /**
     * @return mixed
     */
    public function getHdrMobileHeight();

    /**
     * @param mixed $hdr_mobile_height
     */
    public function setHdrMobileHeight($hdr_mobile_height);


    /**
     * @return mixed
     */
    public function getHdrMinicartCounterBorderEnabled();

    /**
     * @param mixed $hdr_minicart_counter_border_enabled
     */
    public function setHdrMinicartCounterBorderEnabled($hdr_minicart_counter_border_enabled);

    /**
     * @return mixed
     */
    public function getHdrMinicartCounterPosition();

    /**
     * @param mixed $hdr_minicart_counter_position
     */
    public function setHdrMinicartCounterPosition($hdr_minicart_counter_position);

    /**
     * @return mixed
     */
    public function getHdrMinicartCounterBorderColor();

    /**
     * @param mixed $hdr_minicart_counter_border_color
     */
    public function setHdrMinicartCounterBorderColor($hdr_minicart_counter_border_color);

    /**
     * @return mixed
     */
    public function getHdrMinicartCounterBorderStyle();

    /**
     * @param mixed $hdr_minicart_counter_border_style
     */
    public function setHdrMinicartCounterBorderStyle($hdr_minicart_counter_border_style);

    /**
     * @return mixed
     */
    public function getHdrMinicartCounterBorderThickness();

    /**
     * @param mixed $hdr_minicart_counter_border_thickness
     */
    public function setHdrMinicartCounterBorderThickness($hdr_minicart_counter_border_thickness);

    /**
     * @return mixed
     */
    public function getHdrMinicartCounterBackgroundColor();
    /**
     * @param mixed $hdr_minicart_counter_background_color
     */
    public function setHdrMinicartCounterBackgroundColor($hdr_minicart_counter_background_color);

    /**
     * @return mixed
     */
    public function getHdrMinicartCounterTextColor();

    /**
     * @param mixed $hdr_minicart_counter_text_color
     */
    public function setHdrMinicartCounterTextColor($hdr_minicart_counter_text_color);


    /**
     * @return mixed
     */
    public function getHdrSearchIconPosition();

    /**
     * @param mixed $hdr_search_icon_position
     */
    public function setHdrSearchIconPosition($hdr_search_icon_position);

    /**
     * @return mixed
     */
    public function getAllData();
    /**
     * Retrieve existing extension attributes object or create a new one.
     *
     * @return \Elevate\Themeoptions\Api\Data\OptionsExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     *
     * @param \Elevate\Themeoptions\Api\Data\OptionsExtensionInterface $extensionAttributes
     *
     * @return $this
     */
    public function setExtensionAttributes(
        \Elevate\Themeoptions\Api\Data\OptionsExtensionInterface $extensionAttributes
    );
}
