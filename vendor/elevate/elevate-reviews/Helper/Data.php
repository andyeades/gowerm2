<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Elevate\Reviews\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper {
    protected $_coreSession;
    protected $_assetRepo;

    public function __construct(

        \Magento\Framework\Session\SessionManagerInterface $coreSession,
        \Magento\Framework\View\Asset\Repository $assetRepo
    ) {

        $this->_coreSession = $coreSession;
        $this->_assetRepo = $assetRepo;

    }

    function getReviewListCss() {

        $output = '

      ';

        return $output;
    }

    function getReviewListHtml($_product) {


        $rating_label_number = $_product->getData('product_rating');

        $rating_label_class = '';

        switch($rating_label_number) {
            case 5:
                $rating_style = '109px';
                break;
            case 4.9:
                $rating_style = '106.82px';
                break;
            case 4.8:
                $rating_style = '104.64px';
                break;
            case 4.7:
                $rating_style = '102.46px';
                break;
            case 4.6:
                $rating_style = '100.28px';
                break;
            case 4.5:
                $rating_style = '98.1px';
                break;
            case 4.4:
                $rating_style = '95.92px';
                break;
            case 4.3:
                $rating_style = '93.74px';
                break;
            case 4.2:
                $rating_style = '91.56px';
                break;
            case 4.1:
                $rating_style = '89.38px';
                break;
            case 4:
                $rating_style = '87.2px';
                break;
            case 3.9:
                $rating_style = '85.02px';
                break;
            case 3.8:
                $rating_style = '82.84px';
                break;
            case 3.7:
                $rating_style = '80.66px';
                break;
            case 3.6:
                $rating_style = '78.48px';
                break;
            case 3.5:
                $rating_style = '76.3px';
                break;
            case 3.4:
                $rating_style = '74.12px';
                break;
            case 3.3:
                $rating_style = '71.94px';
                break;
            case 3.2:
                $rating_style = '69.76px';
                break;
            case 3.1:
                $rating_style = '67.58px';
                break;
            case 3:
                $rating_style = '65.4px';
                break;
            case 2.9:
                $rating_style = '63.22px';
                break;
            case 2.8:
                $rating_style = '61.04px';
                break;
            case 2.7:
                $rating_style = '58.86px';
                break;
            case 2.6:
                $rating_style = '56.68px';
                break;
            case 2.5:
                $rating_style = '54.5px';
                break;
            case 2.4:
                $rating_style = '52.32px';
                break;
            case 2.3:
                $rating_style = '50.14px';
                break;
            case 2.2:
                $rating_style = '47.96px';
                break;
            case 2.1:
                $rating_style = '45.78px';
                break;
            case 2:
                $rating_style = '43.6px';
                break;
            case 1.9:
                $rating_style = '41.42px';
                break;
            case 1.8:
                $rating_style = '39.24px';
                break;
            case 1.7:
                $rating_style = '37.06px';
                break;
            case 1.6:
                $rating_style = '34.88px';
                break;
            case 1.5:
                $rating_style = '32.77px';
                break;
            case 1.4:
                $rating_style = '30.52px';
                break;
            case 1.3:
                $rating_style = '28.34px';
                break;
            case 1.2:
                $rating_style = '26.16px';
                break;
            case 1.1:
                $rating_style = '23.98px';
                break;
            case 1:
                $rating_style = '21.8px';
                break;
            case 0.9:
                $rating_style = '19.62px';
                break;
            case 0.8:
                $rating_style = '17.44px';
                break;
            case 0.7:
                $rating_style = '15.26px';
                break;
            case 0.6:
                $rating_style = '13.08px';
                break;
            case 0.5:
                $rating_style = '10.9px';
                break;
            case 0.4:
                $rating_style = '8.72px';
                break;
            case 0.3:
                $rating_style = '6.54px';
                break;
            case 0.2:
                $rating_style = '4.36px';
                break;
            case 0.1:
                $rating_style = '2.18px';
                break;
            case 0:
                $rating_style = '0';
                break;
            default:
                $rating_style = '0';
        }

        if ($rating_label_number >= 4.7) {
            $rating_label_class = "prod-rating-5";
        } else if ($rating_label_number >= 4.4) {
            $rating_label_class = "prod-rating-4-4";
        } else if ($rating_label_number >= 4) {
            $rating_label_class = "prod-rating-4";

        } else if ($rating_label_number >= 3) {
            $rating_label_class = "prod-rating-3";

        } else if ($rating_label_number >= 2) {
            $rating_label_class = "prod-rating-2";

        } else if ($rating_label_number >= 1) {
            $rating_label_class = "prod-rating-1";

        }
        $output = '';
         /*
        if (!empty($rating_label_class)) {
            $output .= '<div class="sr-clear">';
            $output .= '<div class="star-rating" style="width: ' . $rating_style . '"><span style="width: 100%;"></span></div>';
            $output .= '</div>';

        }
        else{
         $output .= '<div class="sr-clear">';
            $output .= '';
            $output .= '</div>';
        }
         */
        return $output;
    }
}
