<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */


namespace Elevate\LandingPages\Firmness\Helper;



class Data extends \Magento\Framework\App\Helper\AbstractHelper {
    protected $_coreSession;
    protected $_assetRepo;
    protected $_sessionManagerInferface;
    public function __construct(

        \Magento\Framework\Session\SessionManagerInterface $coreSession,
        \Magento\Framework\View\Asset\Repository $assetRepo,
        \Magento\Framework\Session\SessionManagerInterface $sessionManagerInferface
    ) {

        $this->_coreSession = $coreSession;
        $this->_assetRepo = $assetRepo;
        $this->_sessionManagerInferface = $sessionManagerInferface;

    }
    function getBodyWeightType(){


        return $this->_sessionManagerInferface->getBodyWeightType();

    }
    function getListCss(){

        // no.
        $html = "";
        return $html;
    }




    function getCurrentBodyweight() {

        //currently updated in homerec controller
        $currentBodyWeight = $this->_coreSession->getBodyWeight();

        //baseline weight is 12
        if (!is_numeric($currentBodyWeight)) {
            $currentBodyWeight = 12;
        }

        return $currentBodyWeight;

    }

    function getCurrentBodyweight2() {

        //currently updated in homerec controller
        $currentBodyWeight = $this->_coreSession->getBodyWeight2();

        //baseline weight is 12
        if (!is_numeric($currentBodyWeight)) {
            $currentBodyWeight = false;
        }

        return $currentBodyWeight;

    }

    function getWeightArr() {

        $weight_arr[6] = array(
            'stones' => '6',
            'kg'     => '35-41'
        );
        $weight_arr[7] = array(
            'stones' => '7',
            'kg'     => '42-47'
        );
        $weight_arr[8] = array(
            'stones' => '8',
            'kg'     => '48-53'
        );
        $weight_arr[9] = array(
            'stones' => '9',
            'kg'     => '54-60'
        );
        $weight_arr[10] = array(
            'stones' => '10',
            'kg'     => '61-66'
        );
        $weight_arr[11] = array(
            'stones' => '11',
            'kg'     => '67-73'
        );
        $weight_arr[12] = array(
            'stones' => '12',
            'kg'     => '74-79'
        );
        $weight_arr[13] = array(
            'stones' => '13',
            'kg'     => '80-85'
        );
        $weight_arr[14] = array(
            'stones' => '14',
            'kg'     => '86-92'
        );
        $weight_arr[15] = array(
            'stones' => '15',
            'kg'     => '93-98'
        );
        $weight_arr[16] = array(
            'stones' => '16',
            'kg'     => '99-104'
        );
        $weight_arr[17] = array(
            'stones' => '17',
            'kg'     => '105-111'
        );
        $weight_arr[18] = array(
            'stones' => '18',
            'kg'     => '112-117'
        );
        $weight_arr[19] = array(
            'stones' => '19',
            'kg'     => '118-123'
        );
        $weight_arr[20] = array(
            'stones' => '20',
            'kg'     => '124-130'
        );
        $weight_arr[21] = array(
            'stones' => '21',
            'kg'     => '131-136'
        );
        $weight_arr[22] = array(
            'stones' => '22',
            'kg'     => '137-142'
        );
        $weight_arr[23] = array(
            'stones' => '23',
            'kg'     => '143-149'
        );
        $weight_arr[24] = array(
            'stones' => '24',
            'kg'     => '150-155'
        );
        $weight_arr[25] = array(
            'stones' => '25',
            'kg'     => '156-161'
        );
        $weight_arr[26] = array(
            'stones' => '26',
            'kg'     => '162-168'
        );
        $weight_arr[27] = array(
            'stones' => '27',
            'kg'     => '169-174'
        );
        $weight_arr[28] = array(
            'stones' => '28',
            'kg'     => '175-180'
        );
        return $weight_arr;
    }

    function getUpperBound() {

    }

    //lowest bound for the weight
    function getLowerBound() {

    }

    //adjusted firmness based on bodyweight
    function getBodyweightAdjustment() {

        $currentBodyWeight = $this->getCurrentBodyweight();

        //from the base we can translate
        //the max and the min based on the selected dropdown
        //we adjust based on weight to normalise to the ratings

        switch($currentBodyWeight) {
            case "6":
                $adjustment = 4;
                break;
            case "7":
                $adjustment = 3;
                break;
            case "8":
                $adjustment = 2;
                break;
            case "9":
                $adjustment = 2;
                break;
            case "10":
                $adjustment = 1;
                break;
            case "11":
                $adjustment = 0;
                break;
            case "12":
                $adjustment = 0;
                break;
            case "13":
                $adjustment = -1;
                break;
            case "14":
                $adjustment = -2;
                break;
            case "15":
                $adjustment = -2;
                break;
            case "16":
                $adjustment = -3;
                break;
            case "17":
                $adjustment = -4;
                break;
            case "18":
                $adjustment = -4;
                break;
            case "19":
                $adjustment = -5;
                break;
            case "20":
                $adjustment = -6;
                break;
            case "21":
                $adjustment = -6;
                break;
            case "22":
                $adjustment = -7;
                break;
            case "23":
                $adjustment = -8;
                break;
            case "24":
                $adjustment = -8;
                break;
            case "25":
                $adjustment = -9;
                break;
            case "26":
                $adjustment = -10;
                break;
            case "27":
                $adjustment = -10;
                break;
            case "28":
                $adjustment = -11;
                break;
            default:
                $adjustment = 0;
                break;
        }

        return $adjustment;

    }

    //adjusted firmness based on bodyweight
    function getBodyweightAdjustment2() {

        $currentBodyWeight = $this->getCurrentBodyweight2();

        //from the base we can translaten
        //we adjust based on weight to normalise to the
        //the max and the min based on the selected dropdow ratings

        switch($currentBodyWeight) {
            case "6":
                $adjustment = 4;
                break;
            case "7":
                $adjustment = 3;
                break;
            case "8":
                $adjustment = 2;
                break;
            case "9":
                $adjustment = 2;
                break;
            case "10":
                $adjustment = 1;
                break;
            case "11":
                $adjustment = 0;
                break;
            case "12":
                $adjustment = 0;
                break;
            case "13":
                $adjustment = -1;
                break;
            case "14":
                $adjustment = -2;
                break;
            case "15":
                $adjustment = -2;
                break;
            case "16":
                $adjustment = -3;
                break;
            case "17":
                $adjustment = -4;
                break;
            case "18":
                $adjustment = -4;
                break;
            case "19":
                $adjustment = -5;
                break;
            case "20":
                $adjustment = -6;
                break;
            case "21":
                $adjustment = -6;
                break;
            case "22":
                $adjustment = -7;
                break;
            case "23":
                $adjustment = -8;
                break;
            case "24":
                $adjustment = -8;
                break;
            case "25":
                $adjustment = -9;
                break;
            case "26":
                $adjustment = -10;
                break;
            case "27":
                $adjustment = -10;
                break;
            case "28":
                $adjustment = -11;
                break;
            default:
                $adjustment = 0;
                break;
        }

        return $adjustment;

    }


    function getFirmnessCss(){

        //  $track_icon_1 = $this->_assetRepo->getUrl("Elevate_Firmness::images/firmness-selector.png");
        //  $track_icon_2 = $this->_assetRepo->getUrl("Elevate_Firmness::images/firmness-selector2.png");
        //  $track_icon_match = $this->_assetRepo->getUrl("Elevate_Firmness::images/firmness-selectormatch.png");


        $track_icon_1 = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAApCAYAAAAvUenwAAACk0lEQVR42rXWS2gTURQG4AjZCCouCu5ciwulC0Er0uJGFKGuWhStFMnKtFqpxZYKQgXFIropaJWC2kWhCEEFqUW7sK1CMYhtIgqK4oNAfEySTh7zOp7TzONmJslk5o4Hfgg3954vcye5mZCw+7TXrPMy30vjjZi3mCRm0/8A+jGgpz9oIIz5agD663CQwHGzuZXOIIF4FSAeFNBmNnWmLQjgUR0gxgtsx2h1ABWzjQcYp0YuGfcLNGHyboA+p8kPcMls4p5hr8B6TNoDkKY1XoAILfSYiDtgnZYJH0CivNYdaKcFPnOoEWCOA5hzA5ppImea6wGTAQD3agFbMXIAgFTu5QRGay3KHh4CceguFG5MQ2EsBvmRB5A9OlIPGbUDGzB/7RNzJ6+CvJgAUDWoVsrHbyCev1UNyJZ7WsC5igl7ovRJ9cbuJc0sQab1rB3pNYAwJsW+WZycBa8lL30AYV+v/fgIE9DBNhcHboPfKt5/Zr+KDgLmzYG9PaB+T4PvkhXIHrnIAssEFIyB1b4x4K3ixFMWKBGQNPd+6gXwlrL8ueI+VPypSy/fAW9pwioL/CJgxhiQXyeBt7SMyAK/CZgwBkqPXwFvqV9SLPCegD5joHDzIfCWNPuGBaYI2GWeOe3DeJdULkAcvMMC0RCOhdgn59IT/9ukfPoJQksPC2wxgFPGYObAAKipPz72RoFc9zW2+SL1JoBS8XvIHbvsCdEKJTpi7MfETjvQyk7IHLwA8sJKA9vyA3JdV+zNuzEhC7CQTsd/QuQ6lGLza/ur5fL4PVTXrk56Hi/f0JZojac8C7Aj+42b7iMnqLETcCKbMYMYtcHGC5gd1NQdsGJM7NIf0VcwAkbRr3Aac4a+ilZTJ/APFDD2DAhYxfMAAAAASUVORK5CYII=';
        $track_icon_2 = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAApCAYAAAAvUenwAAADWElEQVR42q2XW0wTURCGNz7x7IsxPhgBkUSMxgfjixp9UEMUo+L9wYREg3jjYjQSsSAFkcglMQotVKFFyr0tpbSl0AtYNAQTgyaoYIiAgahBQC0g7Y5nCtttu5RetpNMupnO/N/Zs3vmnKU2dfYG5QWZojXB5AecKMwSr99fp7PtaeiYz7sn3hB2QEpRrZr8AjpehxVApiVil6JrkQHgNcbCBkgvkJYtibOeUSAtDRvggFxr8wZgLCyAu8IXqUuiXMf/eAOOS1STvgAnK5UTvACCbMnRSAMjyPUogxWyBZXxIQMulDUNotBqjjkhAXKzKmJitT3gD4A5mBs04HJpnXlJxL8nl8g7VwdwF9baHWqzI1AA5mJNwICbhTVSLAzGscYPgO2We+v188ECsAZr/QJu51fnY0EofievSuAXEC9t+xUqAGtXBdzPlpzDRD6OGj4BZ0QtI3wBZ0UtwysCcgSVu6M7rMAXsFn/ClCLA0h6Ut/nqyipWg3pbSYQKYwgV3SBsNUIuUVinxDUYnSZ/XbdtjYLzbldhQ6met8DOGhYyRY+jcKVdgsHsLW9m0ZNF+BqUa3KKwlHuizs30YNfRDrVX/t8ctm1HbutztVJo+2YFWaIVib6RuELQard/uIoG49lJa4i18ncx2qmcnA3LVQmzos08y43gAyAvvYdwjZFu2Q+azJBdhXr5+jtujYnn9JYwa+plWaXHox5JWl8KTGBPpVFuBrtoEvHs/BY1P/0fMO+Bo9/ccdQFMJVa0/mcD0mw/A1+iZvy7AdgScL2/+zASGtK+Bry2OTLgAZPoXcJEpmYBEaQS+Nt7V7wKcFiu+UlkPnl9kAplPGwHsDl6ANLXJfTU3UCRGuZ+ch3ShT9O/4W8Q3cmuZtKP4pyA1EJZFRM80qAHx+RUCOp2OKZlV/LBmvZZ1EYAcc/1ICwWBQWh5xawxXjubDmSU+4APEXfcE9IaNTDlHUggGkZhxRZk4c4mREJdlIG4PLljwqP5BO6bviosTrnl/5tIy3c4by7CeNb5wON9HHK4wLYO8lgHnqwnvZIVo7C3gCOk6/IjcnFcn2UIbA9+lCNZpacJhJR1D+AdWcSGZUIj+ik9c7HaSx0VIfV+RGYWKEcI+95C76KrCgX8B+Bmyo34kvohQAAAABJRU5ErkJggg==';
        $track_icon_match = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAApCAYAAAAvUenwAAACk0lEQVR42rXWS2gTURQG4AjZCCouCu5ciwulC0Er0uJGFKGuWhStFMnKtFqpxZYKQgXFIropaJWC2kWhCEEFqUW7sK1CMYhtIgqK4oNAfEySTh7zOp7TzONmJslk5o4Hfgg3954vcye5mZCw+7TXrPMy30vjjZi3mCRm0/8A+jGgpz9oIIz5agD663CQwHGzuZXOIIF4FSAeFNBmNnWmLQjgUR0gxgtsx2h1ABWzjQcYp0YuGfcLNGHyboA+p8kPcMls4p5hr8B6TNoDkKY1XoAILfSYiDtgnZYJH0CivNYdaKcFPnOoEWCOA5hzA5ppImea6wGTAQD3agFbMXIAgFTu5QRGay3KHh4CceguFG5MQ2EsBvmRB5A9OlIPGbUDGzB/7RNzJ6+CvJgAUDWoVsrHbyCev1UNyJZ7WsC5igl7ovRJ9cbuJc0sQab1rB3pNYAwJsW+WZycBa8lL30AYV+v/fgIE9DBNhcHboPfKt5/Zr+KDgLmzYG9PaB+T4PvkhXIHrnIAssEFIyB1b4x4K3ixFMWKBGQNPd+6gXwlrL8ueI+VPypSy/fAW9pwioL/CJgxhiQXyeBt7SMyAK/CZgwBkqPXwFvqV9SLPCegD5joHDzIfCWNPuGBaYI2GWeOe3DeJdULkAcvMMC0RCOhdgn59IT/9ukfPoJQksPC2wxgFPGYObAAKipPz72RoFc9zW2+SL1JoBS8XvIHbvsCdEKJTpi7MfETjvQyk7IHLwA8sJKA9vyA3JdV+zNuzEhC7CQTsd/QuQ6lGLza/ur5fL4PVTXrk56Hi/f0JZojac8C7Aj+42b7iMnqLETcCKbMYMYtcHGC5gd1NQdsGJM7NIf0VcwAkbRr3Aac4a+ilZTJ/APFDD2DAhYxfMAAAAASUVORK5CYII=';

        // Please don't put styles in here without telling me why you are doing it (I've pulled them out now twice)- and I imagine there's probably a better way to achieve what you want - but I can't figure out what that is if you don't say
        $html = "";

        return $html;

    }

    function getFirmnessGridHtml($product) {

        $lower_bound = 0;
        $upper_bound = 19;
        $html = '';
        $selectormatch = '';
        $whereontrack = '';
        $whereontrack2 = '';
        $firm_slide_message = '';
        $original_mattress_firmness = $product->getResource()->getAttribute('mattress_firmness')->getFrontend()->getValue($product);

        if(!is_numeric($original_mattress_firmness)){

            return '';
        }

        $attribute_set = $product->getResource()->getAttribute('attribute_set_id')->getFrontend()->getValue($product);
        $matt_check = $original_mattress_firmness;
        //lets get the weight
        $adjustment = self::getBodyweightAdjustment();



        $mattress_firmness = $original_mattress_firmness + $adjustment;

        //  echo $original_mattress_firmness."|".$adjustment;
        $firmness_bound_hit = false;

        if ($mattress_firmness < -1) {
            $firmness_bound_hit = true;
        }
        if ($mattress_firmness > 19) {
            $firmness_bound_hit = true;
        }

        if ($mattress_firmness < 1) {
            $mattress_firmness = 1;
        }
        if ($mattress_firmness > 17) {
            $mattress_firmness = 17;
        }


        if (is_numeric($mattress_firmness) && is_numeric($matt_check)) {
            $whereontrack = round((($mattress_firmness / 17) * 100), 2);
        }
        if ($firmness_bound_hit) {
            $firm_slide_class = 'firm_opacity';
        } else {
            $firm_slide_class = '';
            $firm_slide_message = 'firm_hidden';

        }

        /* Person 2 Calculations */
        //lets get the weight
        $adjustment2 = self::getBodyweightAdjustment2();
        $current_body_2 = self::getCurrentBodyweight2();
        $mattress_firmness2 = $original_mattress_firmness + $adjustment2;
        //  echo $original_mattress_firmness."|".$adjustment;
        $firmness_bound_hit2 = false;
        if ($mattress_firmness2 < -1) {
            $firmness_bound_hit2 = true;
        }
        if ($mattress_firmness2 > 19) {
            $firmness_bound_hit2 = true;
        }

        if ($mattress_firmness2 < 1) {
            $mattress_firmness2 = 1;
        }
        if ($mattress_firmness2 > 17) {
            $mattress_firmness2 = 17;
        }

        if (is_numeric($mattress_firmness2) && is_numeric($matt_check)) {
            $whereontrack2 = round((($mattress_firmness2 / 17) * 100), 2);

            if (!$current_body_2) {
                $whereontrack2 = "10000";
            }
            if ($firmness_bound_hit2) {
                $firm_slide_class2 = 'firm_opacity';
            } else {
                $firm_slide_message2 = 'firm_hidden';

            }
            $selectormatch = '';
            if ($whereontrack == $whereontrack2) {
                $selectormatch = 'selectormatch';
            }

        }
        //$html = '<div>';

        $html .= '<div class="firmness-rating-outer">';
        if ($attribute_set == 14) {
            $html .= '<div class="firmness_not_suited '.$firm_slide_message.'">This divan is not suitable for the selected bodyweight<br></div>';
        } else {
            $html .= '<div class="firmness_not_suited '.$firm_slide_message.'">This mattress is not suitable for the selected bodyweight<br></div>';
        }

        $html .= '<div class="firmness_rating_slide'.$firm_slide_class.'">';

        $html .= '<div class="firmness-rating-title-row">';
        $html .= '<div id="firmness-r-t-1" class="firmness-rating-title-narrow">Soft</div>';
        $html .= '<div id="firmness-r-t-2" class="firmness-rating-title">Medium</div>';
        $html .= '<div id="firmness-r-t-3" class="firmness-rating-title-narrow">Firm</div>';
        $html .= '</div>';

        $html .= '<div class="firmness-rating-notches-row">';

        $html .= '<div id="firmness-notch-section-1" class="firmness-notch-section">';
        $html .= '<div class="firmness-thin-notch"></div>';
        $html .= '</div>';

        $html .= '<div id="firmness-notch-section-2" class="firmness-notch-section">';
        $html .= '<div class="firmness-thin-notch"></div>';
        $html .= '<div class="firmness-thin-notch"></div>';
        $html .= '<div class="firmness-thin-notch"></div>';
        $html .= '</div>';

        $html .= '<div id="firmness-notch-section-3" class="firmness-notch-section">';
        $html .= '<div class="firmness-thin-notch"></div>';
        $html .= '<div class="firmness-thin-notch"></div>';
        $html .= '<div class="firmness-thin-notch"></div>';
        $html .= '</div>';

        $html .= '<div id="firmness-notch-section-4" class="firmness-notch-section">';
        $html .= '<div class="firmness-thin-notch"></div>';
        $html .= '<div class="firmness-thin-notch"></div>';
        $html .= '<div class="firmness-thin-notch"></div>';
        $html .= '</div>';

        $html .= '<div id="firmness-notch-section-5" class="firmness-notch-section">';
        $html .= '<div class="firmness-thin-notch"></div>';
        $html .= '</div>';

        $html .= '</div>';

        $html .= '<div class="firmness-rating-tablet-row">';

        //$html .= '<div id="frm-fb">';

        $html .= '<div class="slider slider-horizontal">';

        $html .= '<div class="slider-track">';
        /*
          $html .= '<div class="slider-track-low" style="left: 0px; width: 0%;"></div>';
          $html .= '<div class="slider-selection" style="left: 0%; width: 50%;"></div>';
          $html .= '<div class="slider-track-high" style="right: 0px; width: 50%;"></div>';
        */
        $html .= '</div>';

        /*
        $html .= '<div class="tooltip tooltip-main top" role="presentation" style="left: 50%; margin-left: -11px;">';
          $html .= '<div class="tooltip-arrow"></div>';
          $html .= '<div class="tooltip-inner">9</div>';
        $html .= '</div>';

        $html .= '<div class="tooltip tooltip-min top" role="presentation" style="display: none;">';
          $html .= '<div class="tooltip-arrow"></div>';
          $html .= '<div class="tooltip-inner"></div>';
        $html .= '</div>';

        $html .= '<div class="tooltip tooltip-max top" role="presentation" style="display: none;">';
          $html .= '<div class="tooltip-arrow"></div>';
          $html .= '<div class="tooltip-inner"></div>';
        $html .= '</div>';
        */

        $html .= '<div class="slider-handle min-slider-handle custom '.$selectormatch.'" role="slider" aria-valuemin="0" aria-valuemax="17" aria-valuenow="'.$product->getMattressFirmness().'" tabindex="0" style="left: '.$whereontrack.'%">';
        $html .= '</div>';

        $html .= '<div class="bodyweight_pp2 firm_hidden slider-handle min-slider-handle custom '.$selectormatch.'" data-originalfirmness="'.$original_mattress_firmness.'" role="slider" aria-valuemin="0" aria-valuemax="17" aria-valuenow="'.$product->getMattressFirmness().'" tabindex="0" style="left: '.$whereontrack2.'%">';
        $html .= '</div>';

        //$html .= '<div class="slider-handle max-slider-handle custom hide" role="slider" aria-valuemin="0" aria-valuemax="17" aria-valuenow="0" tabindex="0" style="left: 0%;">';
        //$html .= '</div>';

        $html .= '</div>';

        $html .= '<input id="firmness-slider" type="text" data-slider-min="0" data-slider-max="17" data-slider-step="1" data-slider-value="9" data-slider-handle="custom" data-value="'.$product->getMattressFirmness().'" value="'.$product->getMattressFirmness().'" style="display: none;">';

        //$html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        //$html .= '</div>';

        return $html;
    }
    public function doesProductHaveIcons($product) {

        $matteress_symbol_category = $product->getResource()->getAttribute('matteress_symbol_category')->getFrontend()->getValue($product);

        if ($matteress_symbol_category != '') {
            return true;
        } else {
            return false;
        }

    }

    public function showMattressCategoryIconsNew($product) {

        //attribute is called matteress_symbol_category
        $symbol_loop_count = 0;
        $html = '';
        $matteress_symbol_category = $product->getResource()->getAttribute('matteress_symbol_category')->getFrontend()->getValue($product);
        //print_r($matteress_symbol_category);
        if ($matteress_symbol_category != '') {
            $temp_arr = explode(", ", $matteress_symbol_category);
            foreach ($temp_arr as $option) {
                $symbolArr[] = $option;
            }
            $symbolcount = count($symbolArr);
            $html = '';
            foreach ($symbolArr as $symbol) {

                if ($symbol_loop_count == 2) {
                    // only want to show 2 so this would be 3 so out.
                    break;
                }



                $html .= '<img class="lazyload img-responsive" src="/media/loader.jpg" data-src="'.$this->_assetRepo->getUrl('Elevate_Firmness::images/whats-great-about-this/'.strtolower($symbol)).'.svg" />';
                $symbol_loop_count++;

            }
        }
        return $html;
    }

    public function getDivanColourLookup(){


        $colour_lookup_array_divan['black-cotton'] = array(
            'colour' => 'linoso',
            'number' => '0'
        );
        $colour_lookup_array_divan['charcoal-cotton'] = array(
            'colour' => 'linoso',
            'number' => '1'
        );
        $colour_lookup_array_divan['chocolate-cotton'] = array(
            'colour' => 'linoso',
            'number' => '2'
        );
        $colour_lookup_array_divan['cream-cotton'] = array(
            'colour' => 'linoso',
            'number' => '3'
        );
        $colour_lookup_array_divan['duck-egg-blue-cotton'] = array(
            'colour' => 'linoso',
            'number' => '4'
        );
        $colour_lookup_array_divan['gold-cotton'] = array(
            'colour' => 'linoso',
            'number' => '5'
        );
        $colour_lookup_array_divan['lime-cotton'] = array(
            'colour' => 'linoso',
            'number' => '6'
        );
        $colour_lookup_array_divan['midnight-blue-cotton'] = array(
            'colour' => 'linoso',
            'number' => '7'
        );
        $colour_lookup_array_divan['orchid-cotton'] = array(
            'colour' => 'linoso',
            'number' => '8'
        );
        $colour_lookup_array_divan['plum-cotton'] = array(
            'colour' => 'linoso',
            'number' => '9'
        );
        $colour_lookup_array_divan['purple-cotton'] = array(
            'colour' => 'linoso',
            'number' => '10'
        );
        $colour_lookup_array_divan['red-cotton'] = array(
            'colour' => 'linoso',
            'number' => '11'
        );
        $colour_lookup_array_divan['teal-cotton'] = array(
            'colour' => 'linoso',
            'number' => '12'
        );
        $colour_lookup_array_divan['slate-grey-cotton'] = array(
            'colour' => 'linoso',
            'number' => '13'
        );
        $colour_lookup_array_divan['white-cotton'] = array(
            'colour' => 'linoso',
            'number' => '14'
        );
        $colour_lookup_array_divan['black-suede'] = array(
            'colour' => 'suede',
            'number' => '13'
        );
        $colour_lookup_array_divan['charcoal-suede'] = array(
            'colour' => 'suede',
            'number' => '12'
        );
        $colour_lookup_array_divan['cappuccino-suede'] = array(
            'colour' => 'suede',
            'number' => '7'
        );
        $colour_lookup_array_divan['aubergine-suede'] = array(
            'colour' => 'suede',
            'number' => '10'
        );
        $colour_lookup_array_divan['mid-blue-suede'] = array(
            'colour' => 'suede',
            'number' => '11'
        );
        $colour_lookup_array_divan['red-suede'] = array(
            'colour' => 'suede',
            'number' => '9'
        );
        $colour_lookup_array_divan['olive-suede'] = array(
            'colour' => 'suede',
            'number' => '8'
        );
        $colour_lookup_array_divan['sand-suede'] = array(
            'colour' => 'suede',
            'number' => '3'
        );
        $colour_lookup_array_divan['natural-suede'] = array(
            'colour' => 'suede',
            'number' => '2'
        );
        $colour_lookup_array_divan['white-suede'] = array(
            'colour' => 'suede',
            'number' => '1'
        );
        return $colour_lookup_array_divan;

    }

    public function getDivanColourLookupByName(){

        $colour_lookup_array['Black Cotton'] = array(
            'colour' => 'linoso',
            'number' => '0'
        );
        $colour_lookup_array['Charcoal Cotton'] = array(
            'colour' => 'linoso',
            'number' => '1'
        );
        $colour_lookup_array['Chocolate Cotton'] = array(
            'colour' => 'linoso',
            'number' => '2'
        );
        $colour_lookup_array['Cream Cotton'] = array(
            'colour' => 'linoso',
            'number' => '3'
        );
        $colour_lookup_array['Duck Egg Blue Cotton'] = array(
            'colour' => 'linoso',
            'number' => '4'
        );
        $colour_lookup_array['Gold Cotton'] = array(
            'colour' => 'linoso',
            'number' => '5'
        );
        $colour_lookup_array['Lime Cotton'] = array(
            'colour' => 'linoso',
            'number' => '6'
        );
        $colour_lookup_array['Midnight Blue Cotton'] = array(
            'colour' => 'linoso',
            'number' => '7'
        );
        $colour_lookup_array['Orchid Cotton'] = array(
            'colour' => 'linoso',
            'number' => '8'
        );
        $colour_lookup_array['Plum Cotton'] = array(
            'colour' => 'linoso',
            'number' => '9'
        );
        $colour_lookup_array['Purple Cotton'] = array(
            'colour' => 'linoso',
            'number' => '10'
        );
        $colour_lookup_array['Red Cotton'] = array(
            'colour' => 'linoso',
            'number' => '11'
        );
        $colour_lookup_array['Teal Cotton'] = array(
            'colour' => 'linoso',
            'number' => '12'
        );
        $colour_lookup_array['Slate Grey Cotton'] = array(
            'colour' => 'linoso',
            'number' => '13'
        );
        $colour_lookup_array['White Cotton'] = array(
            'colour' => 'linoso',
            'number' => '14'
        );
        $colour_lookup_array['Black Suede'] = array(
            'colour' => 'suede',
            'number' => '13'
        );
        $colour_lookup_array['Charcoal Suede'] = array(
            'colour' => 'suede',
            'number' => '12'
        );
        $colour_lookup_array['Cappuccino Suede'] = array(
            'colour' => 'suede',
            'number' => '7'
        );
        $colour_lookup_array['Aubergine Suede'] = array(
            'colour' => 'suede',
            'number' => '10'
        );
        $colour_lookup_array['Mid Blue Suede'] = array(
            'colour' => 'suede',
            'number' => '11'
        );
        $colour_lookup_array['Red Suede'] = array(
            'colour' => 'suede',
            'number' => '9'
        );
        $colour_lookup_array['Olive Suede'] = array(
            'colour' => 'suede',
            'number' => '8'
        );
        $colour_lookup_array['Sand Suede'] = array(
            'colour' => 'suede',
            'number' => '3'
        );
        $colour_lookup_array['Natural Suede'] = array(
            'colour' => 'suede',
            'number' => '2'
        );
        $colour_lookup_array['White Suede'] = array(
            'colour' => 'suede',
            'number' => '1'
        );
        return $colour_lookup_array;

    }

    public function getBespokeListCss(){

        // Some of these styles are redudant
        $output = ' <style>

                        #main-header {
                            z-index: 999;
                          }

                          #dynamic_product_images {
                            background-color: #f0f0f0;
                            position:         relative;
                            float:            left;
                            width:            100%;
                            display:          block;
                          }

                          .dynamic_image_items {
                            position:  absolute;
                            max-width: 100%;
                            left:      0;
                            top:       0;
                          }

                          #cd-primary-nav2, #bodyweight_filter {

                            z-index: 99999;
                          }

                          @media screen and (max-width: 767.98px) {
                            .products-grid .prod-img .product-image img {
                              width:      100%;
                              text-align: center;
                            }

                            .products-grid .item {
                              width: 100%;
                            }
                          }

                          @media screen and (min-width: 992px) {

                            .category-divan-beds .products-grid .item {
                              width: 33.33333%;
                            }
                          }

                          .products-grid .prod-img .product-image img {

                            margin-bottom: 0px;
                          }

                          #header-nav-container {
                            z-index: 999999999;
                          }

                          .list_dpi {
                            right:            0;
                            padding:          20px;
                            background-color: #00acb6;
                            color:            white;
                            text-transform:   uppercase;
                            width:            100%;
                            font-size:        13px;
                            text-align:       right;
                            border-bottom:    3px solid #f0f0f0;
                            font-weight:      bold;

                          }

                          @media screen and (max-width: 767.98px) {
                            .list_dpi {
                              padding:   22px 10px 18px 20px !important;
                              font-size: 11px !important;
                            }

                            .sort-by select.input-md {
                              width: 100%;
                            }
                          }</style>';
        return $output;
    }

    public function headboardSwatches($_product){
        $product_url = $_product->getProductUrl();
        $output= '';
        if (($_product->getId() >= '1291586' && $_product->getId() <= '1291591') || $_product->getId() == '1291598') {

            $output = '
        <div class="frame">
            <ul class="slidee">
                <li>
                    <a href="'.$product_url.'?colour=black-cotton"><img alt="Colour Black Cotton" class="colourspan lazyload" loading="lazy" src="/media/loader.jpg" data-src="/media/configurator/fabric-colors/black-cotton.png"></a>
                </li>
                <li>
                    <a href="'.$product_url.'?colour=charcoal-cotton"><img alt="Colour Charcoal Cotton" class="colourspan lazyload" loading="lazy" src="/media/loader.jpg" data-src="/media/configurator/fabric-colors/charcoal-cotton.png"></a>
                </li>
                <li>
                    <a href="'.$product_url.'?colour=chocolate-cotton"><img alt="Colour Chocolate Cotton" class="colourspan lazyload" loading="lazy" src="/media/loader.jpg" data-src="/media/configurator/fabric-colors/chocolate-cotton.png"></a>
                </li>
                <li>
                    <a href="'.$product_url.'?colour=cream-cotton"><img alt="Colour Cream Cotton" class="colourspan lazyload" loading="lazy" src="/media/loader.jpg" data-src="/media/configurator/fabric-colors/cream-cotton.png"></a>
                </li>
                <li>
                    <a href="'.$product_url.'?colour=duck-egg-blue-cotton"><img alt="Colour Duck Egg Blue Cotton" class="colourspan lazyload" loading="lazy" src="/media/loader.jpg" data-src="/media/configurator/fabric-colors/duck-egg-blue-cotton.png"></a>
                </li>
                <li>
                    <a href="'.$product_url.'?colour=lime-cotton"><img alt="Colour Lime Cotton" class="colourspan lazyload" loading="lazy" src="/media/loader.jpg" data-src="/media/configurator/fabric-colors/lime-cotton.png"></a>
                </li>
                <li>
                    <a href="'.$product_url.'?colour=midnight-blue-cotton"><img alt="Colour Midnight Blue Cotton" class="colourspan lazyload" loading="lazy" src="/media/loader.jpg" data-src="/media/configurator/fabric-colors/midnight-blue-cotton.png"></a>
                </li>
                <li>
                    <a href="'.$product_url.'?colour=orchid-cotton"><img alt="Colour Orichid Cotton" class="colourspan lazyload" loading="lazy" src="/media/loader.jpg" data-src="/media/configurator/fabric-colors/orchid-cotton.png"></a>
                </li>
                <li>
                    <a href="'.$product_url.'?colour=plum-cotton"><img alt="Colour Plum Cotton" class="colourspan lazyload" loading="lazy" src="/media/loader.jpg" data-src="/media/configurator/fabric-colors/plum-cotton.png"></a>
                </li>
                <li>
                    <a href="'.$product_url.'?colour=purple-cotton"><img alt="Colour Purple Cotton" class="colourspan lazyload" loading="lazy" src="/media/loader.jpg" data-src="/media/configurator/fabric-colors/purple-cotton.png"></a>
                </li>
                <li>
                    <a href="'.$product_url.'?colour=red-cotton"><img alt="Colour Red Cotton" class="colourspan lazyload" loading="lazy" src="/media/loader.jpg" data-src="/media/configurator/fabric-colors/red-cotton.png"></a>
                </li>
                <li>
                    <a href="'.$product_url.'?colour=slate-grey-cotton"><img alt="Colour Grey Cotton" class="colourspan lazyload" loading="lazy" src="/media/loader.jpg" data-src="/media/configurator/fabric-colors/slate-grey-cotton.png"></a>
                </li>
                <li>
                    <a href="'.$product_url.'?colour=white-cotton"><img alt="Colour White Cotton" class="colourspan lazyload" loading="lazy" src="/media/loader.jpg" data-src="/media/configurator/fabric-colors/white-cotton.png"></a>
                </li>
            </ul>
        </div>';
        }

        if (($_product->getId() >= '1291592' && $_product->getId() <= '1291597') || $_product->getId() == '1291599') {
            $output = '         <div class="frame">
                                    <ul class="slidee">
                                        <li>
                                            <a href="'.$product_url.'?colour=aubergine-suede"><img alt="Colour Aubergine Suede" class="colourspan lazyload" loading="lazy" src="/media/loader.jpg" data-src="/media/configurator/fabric-colors/aubergine-suede.png"></a>
                                        </li>
                                        <li>
                                            <a href="'.$product_url.'?colour=black-suede"><img alt="Colour Black Suede" class="colourspan lazyload" loading="lazy" src="/media/loader.jpg" data-src="/media/configurator/fabric-colors/black-suede.png"></a>
                                        </li>
                                        <li>
                                            <a href="'.$product_url.'?colour=black-suede"><img alt="Colour Cappuccino Suede" class="colourspan lazyload" loading="lazy" src="/media/loader.jpg" data-src="/media/configurator/fabric-colors/cappuccino-suede.png"></a>
                                        </li>
                                        <li>
                                            <a href="'.$product_url.'?colour=charcoal-suede"><img alt="Colour Charcoal Suede" class="colourspan lazyload" loading="lazy" src="/media/loader.jpg" data-src="/media/configurator/fabric-colors/charcoal-suede.png"></a>
                                        </li>
                                        <li>
                                            <a href="'.$product_url.'?colour=mid-blue-suede"><img alt="Colour Mid Blue Suede" class="colourspan lazyload" loading="lazy" src="/media/loader.jpg" data-src="/media/configurator/fabric-colors/mid-blue-suede.png"></a>
                                        </li>
                                        <li>
                                            <a href="'.$product_url.'?colour=natural-suede"><img alt="Colour Natural Suede" class="colourspan lazyload" loading="lazy" src="/media/loader.jpg" data-src="/media/configurator/fabric-colors/natural-suede.png"></a>
                                        </li>
                                        <li>
                                            <a href="'.$product_url.'?colour=olive-suede"><img alt="Colour Olive Suede" class="colourspan lazyload" loading="lazy" src="/media/loader.jpg" data-src="/media/configurator/fabric-colors/olive-suede.png"></a>
                                        </li>
                                        <li>
                                            <a href="'.$product_url.'?colour=red-suede"><img alt="Colour Red Suede" class="colourspan lazyload" loading="lazy" src="/media/loader.jpg" data-src="/media/configurator/fabric-colors/red-suede.png"></a>
                                        </li>
                                        <li>
                                            <a href="'.$product_url.'?colour=sand-suede"><img alt="Colour Sand Suede" class="colourspan lazyload" loading="lazy" src="/media/loader.jpg" data-src="/media/configurator/fabric-colors/sand-suede.png"></a>
                                        </li>
                                        <li>
                                            <a href="'.$product_url.'?colour=white-suede"><img alt="Colour White Suede" class="colourspan lazyload" loading="lazy" src="/media/loader.jpg" data-src="/media/configurator/fabric-colors/white-suede.png"></a>
                                        </li>
                                    </ul>
                                </div>';
        }
        return $output;
    }
}
