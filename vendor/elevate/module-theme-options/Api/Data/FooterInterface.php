<?php
namespace Elevate\Themeoptions\Api\Data;

interface FooterInterface extends \Magento\Framework\Api\ExtensibleDataInterface {

    const ENTITY_ID = 'entity_id';
    const FOOTER_OPTIONS_NAME = 'footer_options_name';
    const FTR_M_BG_COLOR = 'ftr_m_bg_color';
    const FTR_M_HDR_TEXT_COLOR = 'ftr_m_hdr_text_color';
    const FTR_M_FONT_SIZE = 'ftr_m_font_size';
    const FTR_M_HDR_FONT_SIZE = 'ftr_m_hdr_font_size';
    const FTR_M_HDR_FONT_WEIGHT = 'ftr_m_hdr_font_weight';
    const FTR_M_HDR_TEXT_TRANSFORM_ON = 'ftr_m_hdr_text_transform_on';
    const FTR_M_HDR_TEXT_TRANS = 'ftr_m_hdr_text_trans';
    const FTR_M_HDR_MARGIN = 'ftr_m_hdr_margin';
    const FTR_M_TEXT_COLOR = 'ftr_m_text_color';
    const FTR_M_TEXT_HOVER_COLOR = 'ftr_m_text_hover_color';
    const FTR_M_PADDING = 'ftr_m_padding';
    const FTR_M_PADDING_DESKTOP = 'ftr_m_padding_desktop';
    const FTR_M_LI_PADDING = 'ftr_m_li_padding';
    const FTR_M_BORDER_ON = 'ftr_m_border_on';
    const FTR_M_BORDER_POSITION = 'ftr_m_border_position';
    const FTR_M_BORDER_THICKNESS = 'ftr_m_border_thickness';
    const FTR_M_BORDER_STYLE = 'ftr_m_border_style';
    const FTR_M_BORDER_COLOR = 'ftr_m_border_color';
    const FTR_BLOCKS_NUMBEROF = 'ftr_blocks_numberof';
    const FTR_BLOCK_SMALL_TABLET_WIDTH = 'ftr_block_small_tablet_width';
    const FTR_BLOCK_DESKTOP_WIDTH = 'ftr_block_desktop_width';
    const FTR_BLOCK_DESKTOP_PADDING = 'ftr_block_desktop_padding';
    const FTR_BLOCK_SMALL_TABLET_PADDING = 'ftr_block_small_tablet_padding';
    const FTR_BLOCK_MOBILE_PADDING = 'ftr_block_mobile_padding';
    const FTR_BLOCK_1_SMALL_TABLET_WIDTH = 'ftr_block_1_small_tablet_width';
    const FTR_BLOCK_1_DESKTOP_WIDTH = 'ftr_block_1_desktop_width';
    const FTR_BLOCK_1_DESKTOP_PADDING = 'ftr_block_1_desktop_padding';
    const FTR_BLOCK_1_SMALL_TABLET_PADDING = 'ftr_block_1_small_tablet_padding';
    const FTR_BLOCK_1_MOBILE_PADDING = 'ftr_block_1_mobile_padding';
    const FTR_BLOCK_2_SMALL_TABLET_WIDTH = 'ftr_block_2_small_tablet_width';
    const FTR_BLOCK_2_DESKTOP_WIDTH = 'ftr_block_2_desktop_width';
    const FTR_BLOCK_2_DESKTOP_PADDING = 'ftr_block_2_desktop_padding';
    const FTR_BLOCK_2_SMALL_TABLET_PADDING = 'ftr_block_2_small_tablet_padding';
    const FTR_BLOCK_2_MOBILE_PADDING = 'ftr_block_2_mobile_padding';
    const FTR_BLOCK_3_DESKTOP_WIDTH = 'ftr_block_3_desktop_width';
    const FTR_BLOCK_3_SMALL_TABLET_WIDTH = 'ftr_block_3_small_tablet_width';
    const FTR_BLOCK_3_DESKTOP_PADDING = 'ftr_block_3_desktop_padding';
    const FTR_BLOCK_3_SMALL_TABLET_PADDING = 'ftr_block_3_small_tablet_padding';
    const FTR_BLOCK_3_MOBILE_PADDING = 'ftr_block_3_mobile_padding';
    const FTR_BLOCK_4_SMALL_TABLET_WIDTH = 'ftr_block_4_small_tablet_width';
    const FTR_BLOCK_4_DESKTOP_WIDTH = 'ftr_block_4_desktop_width';
    const FTR_BLOCK_4_DESKTOP_PADDING = 'ftr_block_4_desktop_padding';
    const FTR_BLOCK_4_SMALL_TABLET_PADDING = 'ftr_block_4_small_tablet_padding';
    const FTR_BLOCK_4_MOBILE_PADDING = 'ftr_block_4_mobile_padding';
    const FTR_LINKS_COL = 'ftr_links_col';
    const FTR_LINKS_ICO = 'ftr_links_ico';
    const FTR_LINKS_DECORATION = 'ftr_links_decoration';
    const FTR_INPUT_SEARCH_BTN_TEXT_TRANSFORM_ON = 'ftr_input_search_btn_text_transform_on';
    const FTR_INPUT_SEARCH_BTN_TEXT_TRANSFORM = 'ftr_input_search_btn_text_transform';
    const FTR_INPUT_SEARCH_BTN_ALIGN = 'ftr_input_search_btn_align';
    const FTR_INPUT_PLACEHOLDER_COLOR = 'ftr_input_placeholder_color';
    const FTR_COPYRIGHT_TEXT_COLOR = 'ftr_copyright_text_color';

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
     * @return \Elevate\Themeoptions\Api\Data\FooterInterface
     */
    public function setEntityId($entityId);

    /**
     * @param mixed $footer_options_name
     */
    public function setFooterOptionsName($footer_options_name);

    /**
     * @return mixed
     */
    public function getFtrMBgColor();

    /**
     * @param mixed $ftr_m_bg_color
     */
    public function setFtrMBgColor($ftr_m_bg_color);

    /**
     * @return mixed
     */
    public function getFtrMHdrTextColor();

    /**
     * @param mixed $ftr_m_hdr_text_color
     */
    public function setFtrMHdrTextColor($ftr_m_hdr_text_color);

    /**
     * @return mixed
     */
    public function getFtrMFontSize();

    /**
     * @param mixed $ftr_m_font_size
     */
    public function setFtrMFontSize($ftr_m_font_size);

    /**
     * @return mixed
     */
    public function getFtrMHdrFontSize();

    /**
     * @param mixed $ftr_m_hdr_font_size
     */
    public function setFtrMHdrFontSize($ftr_m_hdr_font_size);

    /**
     * @return mixed
     */
    public function getFtrMHdrFontWeight();

    /**
     * @param mixed $ftr_m_hdr_font_weight
     */
    public function setFtrMHdrFontWeight($ftr_m_hdr_font_weight);

    /**
     * @return mixed
     */
    public function getFtrMHdrTextTransformOn();

    /**
     * @param mixed $ftr_m_hdr_text_transform_on
     */
    public function setFtrMHdrTextTransformOn($ftr_m_hdr_text_transform_on);

    /**
     * @return mixed
     */
    public function getFtrMHdrTextTrans();

    /**
     * @param mixed $ftr_m_hdr_text_trans
     */
    public function setFtrMHdrTextTrans($ftr_m_hdr_text_trans);

    /**
     * @return mixed
     */
    public function getFtrMHdrMargin();

    /**
     * @param mixed $ftr_m_hdr_margin
     */
    public function setFtrMHdrMargin($ftr_m_hdr_margin);

    /**
     * @return mixed
     */
    public function getFtrMTextColor();

    /**
     * @param mixed $ftr_m_text_color
     */
    public function setFtrMTextColor($ftr_m_text_color);

    /**
     * @return mixed
     */
    public function getFtrMTextHoverColor();

    /**
     * @param mixed $ftr_m_text_hover_color
     */
    public function setFtrMTextHoverColor($ftr_m_text_hover_color);

    /**
     * @return mixed
     */
    public function getFtrMPadding();

    /**
     * @param mixed $ftr_m_padding
     */
    public function setFtrMPadding($ftr_m_padding);

    /**
     * @return mixed
     */
    public function getFtrMPaddingDesktop();

    /**
     * @param mixed $ftr_m_padding_desktop
     */
    public function setFtrMPaddingDesktop($ftr_m_padding_desktop);

    /**
     * @return mixed
     */
    public function getFtrMLiPadding();

    /**
     * @param mixed $ftr_m_li_padding
     */
    public function setFtrMLiPadding($ftr_m_li_padding);

    /**
     * @return mixed
     */
    public function getFtrMBorderOn();

    /**
     * @param mixed $ftr_m_border_on
     */
    public function setFtrMBorderOn($ftr_m_border_on);

    /**
     * @return mixed
     */
    public function getFtrMBorderPosition();

    /**
     * @param mixed $ftr_m_border_position
     */
    public function setFtrMBorderPosition($ftr_m_border_position);

    /**
     * @return mixed
     */
    public function getFtrMBorderThickness();

    /**
     * @param mixed $ftr_m_border_thickness
     */
    public function setFtrMBorderThickness($ftr_m_border_thickness);

    /**
     * @return mixed
     */
    public function getFtrMBorderStyle();

    /**
     * @param mixed $ftr_m_border_style
     */
    public function setFtrMBorderStyle($ftr_m_border_style);

    /**
     * @return mixed
     */
    public function getFtrMBorderColor();

    /**
     * @param mixed $ftr_m_border_color
     */
    public function setFtrMBorderColor($ftr_m_border_color);

    /**
     * @return mixed
     */
    public function getFtrBlocksNumberof();

    /**
     * @param mixed $ftr_blocks_numberof
     */
    public function setFtrBlocksNumberof($ftr_blocks_numberof);

    /**
     * @return mixed
     */
    public function getFtrBlockSmallTabletWidth();

    /**
     * @param mixed $ftr_block_small_tablet_width
     */
    public function setFtrBlockSmallTabletWidth($ftr_block_small_tablet_width);

    /**
     * @return mixed
     */
    public function getFtrBlockDesktopWidth();

    /**
     * @param mixed $ftr_block_desktop_width
     */
    public function setFtrBlockDesktopWidth($ftr_block_desktop_width);

    /**
     * @return mixed
     */
    public function getFtrBlockDesktopPadding();

    /**
     * @param mixed $ftr_block_desktop_padding
     */
    public function setFtrBlockDesktopPadding($ftr_block_desktop_padding);

    /**
     * @return mixed
     */
    public function getFtrBlockSmallTabletPadding();

    /**
     * @param mixed $ftr_block_small_tablet_padding
     */
    public function setFtrBlockSmallTabletPadding($ftr_block_small_tablet_padding);

    /**
     * @return mixed
     */
    public function getFtrBlockMobilePadding();

    /**
     * @param mixed $ftr_block_mobile_padding
     */
    public function setFtrBlockMobilePadding($ftr_block_mobile_padding);

    /**
     * @return mixed
     */
    public function getFtrBlock1SmallTabletWidth();

    /**
     * @param mixed $ftr_block_1_small_tablet_width
     */
    public function setFtrBlock1SmallTabletWidth($ftr_block_1_small_tablet_width);

    /**
     * @return mixed
     */
    public function getFtrBlock1DesktopWidth();

    /**
     * @param mixed $ftr_block_1_desktop_width
     */
    public function setFtrBlock1DesktopWidth($ftr_block_1_desktop_width);

    /**
     * @return mixed
     */
    public function getFtrBlock1DesktopPadding();

    /**
     * @param mixed $ftr_block_1_desktop_padding
     */
    public function setFtrBlock1DesktopPadding($ftr_block_1_desktop_padding);

    /**
     * @return mixed
     */
    public function getFtrBlock1SmallTabletPadding();

    /**
     * @param mixed $ftr_block_1_small_tablet_padding
     */
    public function setFtrBlock1SmallTabletPadding($ftr_block_1_small_tablet_padding);

    /**
     * @return mixed
     */
    public function getFtrBlock1MobilePadding();

    /**
     * @param mixed $ftr_block_1_mobile_padding
     */
    public function setFtrBlock1MobilePadding($ftr_block_1_mobile_padding);

    /**
     * @return mixed
     */
    public function getFtrBlock2SmallTabletWidth();

    /**
     * @param mixed $ftr_block_2_small_tablet_width
     */
    public function setFtrBlock2SmallTabletWidth($ftr_block_2_small_tablet_width);

    /**
     * @return mixed
     */
    public function getFtrBlock2DesktopWidth();

    /**
     * @param mixed $ftr_block_2_desktop_width
     */
    public function setFtrBlock2DesktopWidth($ftr_block_2_desktop_width);

    /**
     * @return mixed
     */
    public function getFtrBlock2DesktopPadding();

    /**
     * @param mixed $ftr_block_2_desktop_padding
     */
    public function setFtrBlock2DesktopPadding($ftr_block_2_desktop_padding);

    /**
     * @return mixed
     */
    public function getFtrBlock2SmallTabletPadding();

    /**
     * @param mixed $ftr_block_2_small_tablet_padding
     */
    public function setFtrBlock2SmallTabletPadding($ftr_block_2_small_tablet_padding);

    /**
     * @return mixed
     */
    public function getFtrBlock2MobilePadding();

    /**
     * @param mixed $ftr_block_2_mobile_padding
     */
    public function setFtrBlock2MobilePadding($ftr_block_2_mobile_padding);

    /**
     * @return mixed
     */
    public function getFtrBlock3DesktopWidth();

    /**
     * @param mixed $ftr_block_3_desktop_width
     */
    public function setFtrBlock3DesktopWidth($ftr_block_3_desktop_width);

    /**
     * @return mixed
     */
    public function getFtrBlock3SmallTabletWidth();

    /**
     * @param mixed $ftr_block_3_small_tablet_width
     */
    public function setFtrBlock3SmallTabletWidth($ftr_block_3_small_tablet_width);

    /**
     * @return mixed
     */
    public function getFtrBlock3DesktopPadding();

    /**
     * @param mixed $ftr_block_3_desktop_padding
     */
    public function setFtrBlock3DesktopPadding($ftr_block_3_desktop_padding);

    /**
     * @return mixed
     */
    public function getFtrBlock3SmallTabletPadding();

    /**
     * @param mixed $ftr_block_3_small_tablet_padding
     */
    public function setFtrBlock3SmallTabletPadding($ftr_block_3_small_tablet_padding);

    /**
     * @return mixed
     */
    public function getFtrBlock3MobilePadding();

    /**
     * @param mixed $ftr_block_3_mobile_padding
     */
    public function setFtrBlock3MobilePadding($ftr_block_3_mobile_padding);

    /**
     * @return mixed
     */
    public function getFtrBlock4SmallTabletWidth();

    /**
     * @param mixed $ftr_block_4_small_tablet_width
     */
    public function setFtrBlock4SmallTabletWidth($ftr_block_4_small_tablet_width);

    /**
     * @return mixed
     */
    public function getFtrBlock4DesktopWidth();

    /**
     * @param mixed $ftr_block_4_desktop_width
     */
    public function setFtrBlock4DesktopWidth($ftr_block_4_desktop_width);

    /**
     * @return mixed
     */
    public function getFtrBlock4DesktopPadding();

    /**
     * @param mixed $ftr_block_4_desktop_padding
     */
    public function setFtrBlock4DesktopPadding($ftr_block_4_desktop_padding);

    /**
     * @return mixed
     */
    public function getFtrBlock4SmallTabletPadding();

    /**
     * @param mixed $ftr_block_4_small_tablet_padding
     */
    public function setFtrBlock4SmallTabletPadding($ftr_block_4_small_tablet_padding);

    /**
     * @return mixed
     */
    public function getFtrBlock4MobilePadding();

    /**
     * @param mixed $ftr_block_4_mobile_padding
     */
    public function setFtrBlock4MobilePadding($ftr_block_4_mobile_padding);

    /**
     * @return mixed
     */
    public function getFtrLinksCol();

    /**
     * @param mixed $ftr_links_col
     */
    public function setFtrLinksCol($ftr_links_col);

    /**
     * @return mixed
     */
    public function getFtrLinksIco();

    /**
     * @param mixed $ftr_links_ico
     */
    public function setFtrLinksIco($ftr_links_ico);

    /**
     * @return mixed
     */
    public function getFtrLinksDecoration();

    /**
     * @param mixed $ftr_links_decoration
     */
    public function setFtrLinksDecoration($ftr_links_decoration);

    /**
     * @return mixed
     */
    public function getFtrInputSearchBtnTextTransformOn();

    /**
     * @param mixed $ftr_input_search_btn_text_transform_on
     */
    public function setFtrInputSearchBtnTextTransformOn($ftr_input_search_btn_text_transform_on);

    /**
     * @return mixed
     */
    public function getFtrInputSearchBtnTextTransform();

    /**
     * @param mixed $ftr_input_search_btn_text_transform
     */
    public function setFtrInputSearchBtnTextTransform($ftr_input_search_btn_text_transform);

    /**
     * @return mixed
     */
    public function getFtrInputSearchBtnAlign();

    /**
     * @param mixed $ftr_input_search_btn_align
     */
    public function setFtrInputSearchBtnAlign($ftr_input_search_btn_align);

    /**
     * @return mixed
     */
    public function getFtrInputPlaceholderColor();

    /**
     * @param mixed $ftr_input_placeholder_color
     */
    public function setFtrInputPlaceholderColor($ftr_input_placeholder_color);

    /**
     * @return mixed
     */
    public function getFtrCopyrightTextColor();

    /**
     * @param mixed $ftr_copyright_text_color
     */
    public function setFtrCopyrightTextColor($ftr_copyright_text_color);

    /**
     * @return mixed
     */
    public function getAllData();
    /**
     * Retrieve existing extension attributes object or create a new one.
     *
     * @return \Elevate\Themeoptions\Api\Data\FooterExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     *
     * @param \Elevate\Themeoptions\Api\Data\FooterExtensionInterface    $extensionAttributes
     *
     * @return   $this
     */
    public function setExtensionAttributes(
        \Elevate\Themeoptions\Api\Data\FooterExtensionInterface $extensionAttributes
    );
}
