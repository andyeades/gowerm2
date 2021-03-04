<?php

namespace SecureTrading\Trust\Helper;


use Magento\Framework\App\Helper\AbstractHelper;

/**
 * Class Data
 *
 * @package SecureTrading\Trust\Helper
 */
class Data extends AbstractHelper
{
    const IS_TEST           = 'is_test';
    const CHOICE_PAGE       = 'url_choice_page';
    const DETAILS_PAGE      = 'url_details_page';
    const SKIP_CHOICE_PAGE  = 'payment_pages/payment_pages_optional/version_settings/skip_choice_page';
    const ST_PROFILE        = 'payment_pages/payment_pages_optional/version_settings/st_profile';
    const CURRENCY          = 'currency';
    const SITE_REFERENCE    = 'site_reference';
    const VERSION           = 'payment_pages/payment_pages_optional/version_settings/version';
    const SETTLE_DUE_DATE   = 'payment_pages/payment_pages_optional/other_settings/settle_due_date';
    const SITE_PASS         = 'site_password';
    const USE_IFRAME        = 'payment_pages/payment_pages_optional/display_settings/use_iframe';
    const IFRAME_HEIGHT     = 'payment_pages/payment_pages_optional/display_settings/iframe_height';
    const IFRAME_WIDTH      = 'payment_pages/payment_pages_optional/display_settings/iframe_width';
    const PAYMENT_ACTION    = 'payment_pages/payment_pages_optional/other_settings/payment_action';
    const SETTLE_STATUS     = 'payment_pages/payment_pages_optional/other_settings/settle_status';
    const BACK_OFFICE       = 'back_office';
    const USER_NAME         = 'username';
    const PASSWORD          = 'password';
    const ORDER_STATUS      = 'pending_secure_trading_payment';
    const ORDER_STATUS_LABEL= 'Payment Pages';
    const DESCRIPTION 		= 'payment_pages/payment_pages_optional/display_settings/description';
}