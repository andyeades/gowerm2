<?php $_product = $block->getProduct();


$track_icon_1 = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAApCAYAAAAvUenwAAACk0lEQVR42rXWS2gTURQG4AjZCCouCu5ciwulC0Er0uJGFKGuWhStFMnKtFqpxZYKQgXFIropaJWC2kWhCEEFqUW7sK1CMYhtIgqK4oNAfEySTh7zOp7TzONmJslk5o4Hfgg3954vcye5mZCw+7TXrPMy30vjjZi3mCRm0/8A+jGgpz9oIIz5agD663CQwHGzuZXOIIF4FSAeFNBmNnWmLQjgUR0gxgtsx2h1ABWzjQcYp0YuGfcLNGHyboA+p8kPcMls4p5hr8B6TNoDkKY1XoAILfSYiDtgnZYJH0CivNYdaKcFPnOoEWCOA5hzA5ppImea6wGTAQD3agFbMXIAgFTu5QRGay3KHh4CceguFG5MQ2EsBvmRB5A9OlIPGbUDGzB/7RNzJ6+CvJgAUDWoVsrHbyCev1UNyJZ7WsC5igl7ovRJ9cbuJc0sQab1rB3pNYAwJsW+WZycBa8lL30AYV+v/fgIE9DBNhcHboPfKt5/Zr+KDgLmzYG9PaB+T4PvkhXIHrnIAssEFIyB1b4x4K3ixFMWKBGQNPd+6gXwlrL8ueI+VPypSy/fAW9pwioL/CJgxhiQXyeBt7SMyAK/CZgwBkqPXwFvqV9SLPCegD5joHDzIfCWNPuGBaYI2GWeOe3DeJdULkAcvMMC0RCOhdgn59IT/9ukfPoJQksPC2wxgFPGYObAAKipPz72RoFc9zW2+SL1JoBS8XvIHbvsCdEKJTpi7MfETjvQyk7IHLwA8sJKA9vyA3JdV+zNuzEhC7CQTsd/QuQ6lGLza/ur5fL4PVTXrk56Hi/f0JZojac8C7Aj+42b7iMnqLETcCKbMYMYtcHGC5gd1NQdsGJM7NIf0VcwAkbRr3Aac4a+ilZTJ/APFDD2DAhYxfMAAAAASUVORK5CYII=';
$track_icon_2 = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAApCAYAAAAvUenwAAADWElEQVR42q2XW0wTURCGNz7x7IsxPhgBkUSMxgfjixp9UEMUo+L9wYREg3jjYjQSsSAFkcglMQotVKFFyr0tpbSl0AtYNAQTgyaoYIiAgahBQC0g7Y5nCtttu5RetpNMupnO/N/Zs3vmnKU2dfYG5QWZojXB5AecKMwSr99fp7PtaeiYz7sn3hB2QEpRrZr8AjpehxVApiVil6JrkQHgNcbCBkgvkJYtibOeUSAtDRvggFxr8wZgLCyAu8IXqUuiXMf/eAOOS1STvgAnK5UTvACCbMnRSAMjyPUogxWyBZXxIQMulDUNotBqjjkhAXKzKmJitT3gD4A5mBs04HJpnXlJxL8nl8g7VwdwF9baHWqzI1AA5mJNwICbhTVSLAzGscYPgO2We+v188ECsAZr/QJu51fnY0EofievSuAXEC9t+xUqAGtXBdzPlpzDRD6OGj4BZ0QtI3wBZ0UtwysCcgSVu6M7rMAXsFn/ClCLA0h6Ut/nqyipWg3pbSYQKYwgV3SBsNUIuUVinxDUYnSZ/XbdtjYLzbldhQ6met8DOGhYyRY+jcKVdgsHsLW9m0ZNF+BqUa3KKwlHuizs30YNfRDrVX/t8ctm1HbutztVJo+2YFWaIVib6RuELQard/uIoG49lJa4i18ncx2qmcnA3LVQmzos08y43gAyAvvYdwjZFu2Q+azJBdhXr5+jtujYnn9JYwa+plWaXHox5JWl8KTGBPpVFuBrtoEvHs/BY1P/0fMO+Bo9/ccdQFMJVa0/mcD0mw/A1+iZvy7AdgScL2/+zASGtK+Bry2OTLgAZPoXcJEpmYBEaQS+Nt7V7wKcFiu+UlkPnl9kAplPGwHsDl6ANLXJfTU3UCRGuZ+ch3ShT9O/4W8Q3cmuZtKP4pyA1EJZFRM80qAHx+RUCOp2OKZlV/LBmvZZ1EYAcc/1ICwWBQWh5xawxXjubDmSU+4APEXfcE9IaNTDlHUggGkZhxRZk4c4mREJdlIG4PLljwqP5BO6bviosTrnl/5tIy3c4by7CeNb5wON9HHK4wLYO8lgHnqwnvZIVo7C3gCOk6/IjcnFcn2UIbA9+lCNZpacJhJR1D+AdWcSGZUIj+ik9c7HaSx0VIfV+RGYWKEcI+95C76KrCgX8B+Bmyo34kvohQAAAABJRU5ErkJggg==';
$track_icon_match = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAApCAYAAAAvUenwAAACk0lEQVR42rXWS2gTURQG4AjZCCouCu5ciwulC0Er0uJGFKGuWhStFMnKtFqpxZYKQgXFIropaJWC2kWhCEEFqUW7sK1CMYhtIgqK4oNAfEySTh7zOp7TzONmJslk5o4Hfgg3954vcye5mZCw+7TXrPMy30vjjZi3mCRm0/8A+jGgpz9oIIz5agD663CQwHGzuZXOIIF4FSAeFNBmNnWmLQjgUR0gxgtsx2h1ABWzjQcYp0YuGfcLNGHyboA+p8kPcMls4p5hr8B6TNoDkKY1XoAILfSYiDtgnZYJH0CivNYdaKcFPnOoEWCOA5hzA5ppImea6wGTAQD3agFbMXIAgFTu5QRGay3KHh4CceguFG5MQ2EsBvmRB5A9OlIPGbUDGzB/7RNzJ6+CvJgAUDWoVsrHbyCev1UNyJZ7WsC5igl7ovRJ9cbuJc0sQab1rB3pNYAwJsW+WZycBa8lL30AYV+v/fgIE9DBNhcHboPfKt5/Zr+KDgLmzYG9PaB+T4PvkhXIHrnIAssEFIyB1b4x4K3ixFMWKBGQNPd+6gXwlrL8ueI+VPypSy/fAW9pwioL/CJgxhiQXyeBt7SMyAK/CZgwBkqPXwFvqV9SLPCegD5joHDzIfCWNPuGBaYI2GWeOe3DeJdULkAcvMMC0RCOhdgn59IT/9ukfPoJQksPC2wxgFPGYObAAKipPz72RoFc9zW2+SL1JoBS8XvIHbvsCdEKJTpi7MfETjvQyk7IHLwA8sJKA9vyA3JdV+zNuzEhC7CQTsd/QuQ6lGLza/ur5fL4PVTXrk56Hi/f0JZojac8C7Aj+42b7iMnqLETcCKbMYMYtcHGC5gd1NQdsGJM7NIf0VcwAkbRr3Aac4a+ilZTJ/APFDD2DAhYxfMAAAAASUVORK5CYII=';
?>



<style>

    .product-view-divans #what-makes-it-great .nav-tabs>li>a{
        border:none
    }
    .product-view-divans .what-makes-it-great-bottom-row-inner .tab-pane{
        background-color:#00acb6
    }

    #infoModal .what-makes-it-great-icon-row-inner{
        display:flex;
        flex-wrap:wrap
    }

    .qty-selector{
        float:none;
        display:flex;
        align-items:center;
        justify-content:center
    }
    .qty-selector label,.qty-selector select{
        flex:0 1 auto
    }
    .qty-selector label{
        margin-bottom:0
    }
    .qty-selector #qtySelect{
        width:50px
    }
    .add-questions #searchque{
        max-width:100%;
        width:100%;
        margin-left:0
    }
    #product-view-divans .price-box{
        text-align:center;
        float:none
    }
    #product-view-divans .price-box>div{
        text-align:center
    }
    .product-view-divans #what-makes-it-great{
        margin-left:0;
        margin-right:0
    }

    @media screen and (min-width:768px){
        .doyouneed-mattress-lbl{
            padding-top:22px
        }
        .add-to-box{
            padding-left:15px;
            padding-right:15px
        }
        .add-to-box .add-to-cart{
            margin:15px -15px;
            text-align:left
        }
        .add-to-box .add-to-cart:after,.add-to-box .add-to-cart:before{
            content:" ";
            display:table
        }
        .add-to-box .add-to-cart:after{
            clear:both
        }
        .add-to-box .add-to-links,.add-to-box .prod-icons{
            margin-left:-15px;
            margin-right:-15px
        }
        .add-to-box .add-to-links .separator,.add-to-box .add-to-links li{
            float:none;
            font-size:13px;
            font-size:1.3rem;
            display:inline-block
        }
        .product-essential .product-img-box,.product-essential .product-shop{
            width:50%;
            padding-left:15px;
            padding-right:15px
        }
        .product-view .price-box>div{
            text-align:right
        }
        #feefo-product-review-left-outer img{
            display:block;
            height:auto;
            max-width:none!important;
            position:absolute;
            top:-3px;
            left:-3px
        }
        #prod-pricing-inner .prod-price-now .price{
            font-size:28px
        }
        #feefo-product-review-left{
            position:relative;
            overflow:hidden
        }
        #feefo-product-review-left:hover{
            opacity:.8
        }


        .ask-question-search{
            width:70%
        }
        .product-options-bottom .add-to-cart{
            float:right;
            text-align:right
        }
        .product-shop .product-options-bottom .price-box>div{
            text-align:right
        }
        #product-view-divans .price-box{
            float:right
        }
        .product-view-divans .product-essential .product-divans-shop{
            width:50%
        }
        .product-view-divans .product-img-box{
            display:flex;
            flex-direction:column;
            min-height:100%!important
        }
        .product-view-divans #feefo-product-review-left{
            height:auto;
            width:auto;
            position:relative;
            overflow:visible
        }
        .product-view-divans #feefo-product-review-left img{
            display:block;
            max-width:none;
            height:auto;
            margin:-2px
        }
    }
    @media(min-width:200px) and (max-width:400px){
        .attachment_sections_box ul{
            text-align:center
        }
        .attachment_sections_box ul li{
            float:none;
            display:inline-table;
            width:96%
        }
        .attachment_sections_box ul li.last{
            margin-right:5px
        }
    }
    @media(min-width:400px) and (max-width:600px){
        .attachment_sections_box ul{
            text-align:center
        }
        .attachment_sections_box ul li{
            float:none;
            display:inline-table;
            width:45%
        }
        .attachment_sections_box ul li.last{
            margin-right:5px
        }
    }
    @media(min-width:600px) and (max-width:1000px){
        .attachment_sections_box ul{
            text-align:center
        }
        .attachment_sections_box ul li{
            float:none;
            display:inline-table;
            width:30%
        }
        .attachment_sections_box ul li.last{
            margin-right:5px
        }
    }
    @media(min-width:1000px) and (max-width:1060px){
        .attachment_sections_box ul li{
            float:left;
            width:32%;
            margin:5px
        }
    }
    @media screen and (min-width:992px){

        #product-page-midpage-block .firmness-rating-bottom{
            padding-top:21px
        }

        #infoModal .product-img-box-1{
            float:left;
            width:230px
        }
        #infoModal .product-name{
            float:right;
            width:55%
        }
        #infoModal .product-price-block{
            text-align:right
        }
        #infoModal .product-shop-1{
            width:55%;
            float:right
        }
        .product-view-divans .product-essential .product-divans-shop{
            width:55%
        }
    }





</style>
<div style="width:100%;    order: 3;">






    <?php



    $lower_bound = 0;
    $upper_bound = 19;
    $symbolArr = [];
    $firmnessRatingHelper = \Magento\Framework\App\ObjectManager::getInstance()->get("Elevate\Firmness\Helper\Data");
    $filterOutputFirm = '';
    // $firmnessRatingHelper = Mage::helper('elevate_firmnessrating');
    $original_mattress_firmness = $_product->getResource()->getAttribute('mattress_firmness')->getFrontend()->getValue($_product);


    $our_expert_says = $_product->getResource()->getAttribute('our_expert_says')->getFrontend()->getValue($_product);
    //lets get the weight
    $firm_slide_class = '';
    //attribute is called matteress symbol - previous dev leftover
    $matteress_symbol = $_product->getResource()->getAttribute('matteress_symbol')->getFrontend()->getValue($_product);
    if ($matteress_symbol != '') {
        if ($matteress_symbol != '') {
            $temp_arr = explode(", ", $matteress_symbol);
            foreach ($temp_arr as $option) {
                $symbolArr[] = $option;
            }
        }
        $symbolArr = array_unique($symbolArr);
        sort($symbolArr);

    }
    if (!empty($mattress_firmness) || count($symbolArr) > 0) { ?>
        <div id="product-page-midpage-block">
            <?php if (!empty($our_expert_says)) { ?>
                <div class="firmness-rating-expert-says-row">
                    <div class="container">
                        <div class="firmness-rating-expert-says-inner">
                            <div class="firmness-expert-says">
                                <div class="firmness-expert-says-icon">
                                    <i class="firmness-says-ico"></i>
                                </div>
                                <div class="firmness-expert-says-title">
                                    Our Expert Says
                                </div>

                            </div>
                            <div class="firmness-expert-says-comment">
                                <?php echo $our_expert_says; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <div class="firmness-rating-bottom">
                <div class="container">


                    <div id="mattress_firmness_rating"></div>


                    <?php if ($matteress_symbol != '') { ?>
                        <style>
                            #what-makes-it-great.full-width-symbols {
                                width: 100%;
                            }
                        </style>
                        <div id="what-makes-it-great" class="<?php if (empty($mattress_firmness)) {
                            echo 'full-width-symbols';
                        } ?>">
                            <div class="what-makes-it-great-toprow-outer">
                                <div class="what-makes-it-great-inner">
                                    <div id="what-makes-it-great-title-outer">
                                        <div id="what-makes-it-great-title-inner"><i class="fa fa-smile"></i> What Makes It Great</div>
                                    </div>


                                    <div id="what-makes-it-great-icon-row">
                                        <div class="what-makes-it-great-icon-row-inner">

                                            <?php
                                            $i = 0;
                                            //echo "<pre>";
                                            //print_r($symbolArr);
                                            //echo "</pre>";
                                            $symbolcount = count($symbolArr);

                                            $total_percantage = ($symbolcount * 12.5);

                                            $margin_total = 100 - $total_percantage;

                                            $margin_half = $margin_total / 2;

                                            $individual_margin = round(100 / $symbolcount);

                                            $tech_ico_outer_class = "tech-ico-outer-$individual_margin";
                                            //echo $symbolcount;
                                            $heightArr = array(
                                                '15cm',
                                                '16cm',
                                                '17cm',
                                                '18cm',
                                                '19cm',
                                                '20cm',
                                                '21cm',
                                                '22cm',
                                                '23cm',
                                                '24cm',
                                                '25cm',
                                                '26cm',
                                                '27cm',
                                                '28cm',
                                                '29cm',
                                                '30cm',
                                                '31cm',
                                                '32cm',
                                                '33cm'
                                            );

                                            $reflexfoam = array(
                                                'reflexfoam',
                                                'reflex-foam',
                                                'reflex-foam-10',
                                                'reflex-foam-20',
                                                'reflex-foam-25',
                                                'reflex-foam-30',
                                                'reflex-foam-40',
                                                'reflex-foam-50',
                                                'reflex-foam-75',
                                                'reflex-foam-100',
                                                'reflex-foam-110',
                                                'reflex-foam-120',
                                                'reflex-foam-125',
                                                'reflex-foam-130',
                                                'reflex-foam-140',
                                                'reflex-foam-150',
                                                'reflex-foam-160',
                                                'reflex-foam-170',
                                                'reflex-foam-180',
                                                'reflex-foam-240'
                                            );

                                            $memoryfoam = array(
                                                'memoryfoam',
                                                'memory-foam',
                                                'memory-foam-20',
                                                'memory-foam-25',
                                                'memory-foam-30',
                                                'memory-foam-40',
                                                'memory-foam-45',
                                                'memory-foam-50',
                                                'memory-foam-67',
                                                'memory-foam-70',
                                                'memory-foam-75',
                                                'memory-foam-80',
                                                'memory-foam-100'
                                            );
                                            $latexfoam = array(
                                                'latex-foam',
                                                'latex-foam-20',
                                                'latex-foam-50',
                                                'latex-foam-100'
                                            );
                                            $laytechfoam = array(
                                                'laytech-foam-10',
                                                'laytech-foam-20',
                                                'laytech-foam-50'
                                            );
                                            $coolindigofoam = array(
                                                'cool-indigo-memfoam-50',
                                                'cool-indigo-memfoam-70'
                                            );
                                            $gelmemoryfoam = array(
                                                'gel-memory-foam',
                                                'gel-memory-foam-20',
                                                'gel-memory-foam-50',
                                                'gel-memory-foam-100'
                                            );
                                            $opencoilsprings = array(
                                                'open-coil-spring',
                                                'open-coil-springs',
                                                'open-coil-springs-600'
                                            );
                                            $pocketsprings = array(
                                                'pocketspring',
                                                'pocketspring',
                                                'pocket-springs',
                                                'pocket-springs-800',
                                                'pocket-springs-1000',
                                                'pocket-springs-1200',
                                                'pocket-springs-1400',
                                                'pocket-springs-1500',
                                                'pocket-springs-1800',
                                                'pocket-springs-2000',
                                                'pocket-springs-2500',
                                                'pocket-springs-3000'
                                            );
                                            $twinpocketsprings = array(
                                                'twin-pocket-springs',
                                                'twin-pocket-springs-4000',
                                                "Twinpocketspring"
                                            );
                                            $removeablecover = array(
                                                'removable-cover',
                                                'removeable-cover'
                                            );

                                            $icons_to_display = array();
                                            foreach ($symbolArr as $symbol) {
                                                if ($i == 0) {
                                                    $activeclass = 'active';
                                                }
                                                if ($i == 8) {
                                                    // 8 is the max we want to show (so this next one would be 9) so we want to break
                                                    break;
                                                }

                                                if (in_array($symbol, $heightArr)) {
                                                    $aria_controls = "size";
                                                } elseif (in_array($symbol, $reflexfoam)) {
                                                    $aria_controls = "reflexfoam";
                                                } elseif (in_array($symbol, $memoryfoam)) {
                                                    $aria_controls = "memoryfoam";
                                                } elseif (in_array($symbol, $latexfoam)) {
                                                    $aria_controls = "latexfoam";
                                                } elseif (in_array($symbol, $coolindigofoam)) {
                                                    $aria_controls = "coolindigofoam";
                                                } elseif (in_array($symbol, $gelmemoryfoam)) {
                                                    $aria_controls = "gelmemoryfoam";
                                                } elseif (in_array($symbol, $opencoilsprings)) {
                                                    $aria_controls = "opencoilsprings";
                                                } elseif (in_array($symbol, $pocketsprings)) {
                                                    $aria_controls = "pocketsprings";
                                                } elseif (in_array($symbol, $twinpocketsprings)) {
                                                    $aria_controls = "twinpocketsprings";
                                                } else {

                                                    $aria_controls = strtolower($symbol);
                                                }

                                                ?>

                                                <div id="tech-<?php echo strtolower($symbol) ?>-button" class="tech-ico-outer <?php echo $tech_ico_outer_class . " " . $activeclass; ?>" aria-controls="tech-<?php echo $aria_controls; ?>" role="tab">
                                                    <i class="tech-ico tech-<?php echo strtolower($symbol) ?>-ico tech-icon"></i>
                                                </div>
                                                <?php

                                                $i++;

                                                $activeclass = "";

                                            }

                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="what-makes-it-great-bottom-row">
                                <div class="what-makes-it-great-bottom-row-inner">
                                    <div id="what-makes-it-great-tab-content" class="tab-content">

                                        <?php foreach ($symbolArr as $symbol) {
                                            if (in_array($symbol, $heightArr)) { ?>

                                                <div role="tabpanel" class="tab-pane active" id="tech-size">
                                                    Depth of the mattress can affect the comfort and suitability for use in certain bed frames or bunk beds.
                                                </div>
                                                <?php continue; ?>

                                            <?php } elseif (in_array($symbol, $reflexfoam)) { ?>
                                                <div role="tabpanel" class="tab-pane" id="tech-reflexfoam">
                                                    The reflex foam provides extra support. It is sanitised and guarantees complete hygiene. It stores less body heat than the memory foam.
                                                </div>
                                                <?php continue; ?><?php } elseif (in_array($symbol, $memoryfoam)) { ?>
                                                <div role="tabpanel" class="tab-pane" id="tech-memoryfoam">
                                                    Memory foam offers optimum support for your spine and joints. It moulds in the shape of your body for maximum support and comfort when you sleep. This material is sensitive to your temperature and weight and aids your blood circulation. It was initially designed by NASA.
                                                </div>
                                                <?php continue; ?><?php } elseif (in_array($symbol, $latexfoam)) { ?>
                                                <div role="tabpanel" class="tab-pane" id="tech-latexfoam">
                                                    This superior foam offers you supreme comfort. It offers a sturdier feeling than memory foam does. Latex foam has multiple air holes for ventilation and is known to relieve allergies as well.
                                                </div>
                                                <?php continue; ?><?php } elseif (in_array($symbol, $laytechfoam)) { ?>
                                                <div role="tabpanel" class="tab-pane" id="tech-laytechfoam">
                                                    The laytech foam is a new kind of latex foam which contributes to the ultimate support of your mattress. This modern material is naturally hypoallergenic, anti-microbial and perfect for people with asthma and allergies.
                                                </div>
                                                <?php continue; ?><?php } elseif (in_array($symbol, $coolindigofoam)) { ?>
                                                <div role="tabpanel" class="tab-pane" id="tech-coolindigofoam">
                                                    Cool Indigo represents the next generation of mattresses especially when it comes to Memory Foam. In addition to the standard Memory Foam, it also has a Visco Elastic Cool Indigo Memory Foam eliminating any form of heat discomfort associated with the regular Memory Foams.
                                                </div>
                                                <?php continue; ?><?php } elseif (in_array($symbol, $gelmemoryfoam)) { ?>
                                                <div role="tabpanel" class="tab-pane" id="tech-gelmemoryfoam">
                                                    This gel-infused memory foam perfectly adjusts and conforms to your body shape. The gel gives off a cooling sensation that help maintain your body temperature for you to feel fresher and have a deeper sleep.
                                                </div>
                                                <?php continue; ?><?php } elseif (in_array($symbol, $opencoilsprings)) { ?>
                                                <div role="tabpanel" class="tab-pane" id="tech-opencoilsprings">
                                                    Open coil springs are also known as bonnell springs. The springs are larger and fewer than in other spring systems. The open coil springs are linked together in a wire frame under the mattress fillings.
                                                </div>
                                                <?php continue; ?><?php } elseif (in_array($symbol, $pocketsprings)) { ?>
                                                <div role="tabpanel" class="tab-pane" id="tech-pocketsprings">
                                                    Each individually nested spring is placed in a fabric pocket and moves independently when you lie on the mattress. When two people are on the mattress, neither of them will be disturbed if the other one moves. Mattresses with pocket springs offer more comfort than those with open coil springs.
                                                </div>
                                                <?php continue; ?><?php } elseif (in_array($symbol, $twinpocketsprings)) { ?>
                                                <div role="tabpanel" class="tab-pane" id="tech-twinpocketsprings">
                                                    With two sets of individually wrapped pocket springs placed one on top of the other, the mattress offers a higher level of comfort and a more 'springy' and supportive effect than just one set of springs.
                                                </div>
                                                <?php continue; ?>

                                            <?php } else {

                                            }

                                            switch($symbol) {
                                                case 'air-stream':
                                                    echo "<div role=\"tabpanel\" class=\"tab-pane\" id=\"tech-air-stream\">
                        The filings in the mattress are specially crafted to be breathable, enhancing freshness.
                    </div>";
                                                    break;

                                                case 'air-vents':
                                                    echo "<div role=\"tabpanel\" class=\"tab-pane\" id=\"tech-air-vents\">To make the mattress breathable, specially designed air vents are placed on the sides. This aids in keeping the mattress cool and dry.</div>";
                                                    break;

                                                case 'air-flow':
                                                    echo "<div role=\"tabpanel\" class=\"tab-pane\" id=\"tech-air-flow\">The filings in the mattress are specially crafted to be breathable, enhancing freshness.</div>";
                                                    break;

                                                case 'aloe':
                                                    echo "<div role=\"tabpanel\" class=\"tab-pane\" id=\"tech-aloe\">
                      Enjoy the benefits of Aloe Vera with this special type of mattress. This natureâ€™s gift is known to aid in renewing skin cells along with other health benefits.
                    </div>";
                                                    break;

                                                case 'antibug':
                                                    echo "<div role=\"tabpanel\" class=\"tab-pane\" id=\"tech-anti-bug\">
                      Bed bugs and other insects wonâ€™t be a problem with this kind of fabric, helping to repel these nuisances.
                    </div>";
                                                    break;

                                                case 'amethyst':
                                                    echo "<div role=\"tabpanel\" class=\"tab-pane\" id=\"tech-amethyst\">
                     Amethyst is a violet variety of quartz often used in jewellery. We are innovative and are using fibres enhanced with it in the mattress fabric. This guarantees a pacifying and relaxing effect for your body and a harmonious sleep.
                    </div>";
                                                    break;

                                                case 'bonnell-springs':
                                                    echo "<div role=\"tabpanel\" class=\"tab-pane\" id=\"tech-bonnell-springs\">
                      Also known as open coil springs. The springs are larger and fewer than other systems. These springs are linked together beneath the mattress fillings. This gives a firmer support as compared to the pocket spring system.
                    </div>";
                                                    break;

                                                case 'bamboo-fibre':
                                                    echo "<div role=\"tabpanel\" class=\"tab-pane\" id=\"tech-bamboo-fibre\">
                      The fabric is crafted with bamboo yarn. It is an eco-friendly material and a natural hypoallergenic. Itâ€™s also a deodorant that naturally absorbs moisture keeping the mattress dry and cool.
                    </div>";
                                                    break;
                                                case 'cashmere':
                                                    echo "<div role=\"tabpanel\" class=\"tab-pane\" id=\"tech-cashmere\">
                        Cashmere.
                    </div>";
                                                    break;
                                                case 'cotton-based':
                                                    echo "<div role=\"tabpanel\" class=\"tab-pane\" id=\"tech-cotton-based\">
                        Soft and absorbent, cotton is an indispensable natural material. It is used when making the fabric of the mattress and is often combined with linen.
                    </div>";
                                                    break;
                                                case 'chitosan-and-eucalyptus-infused':
                                                    echo "<div role=\"tabpanel\" class=\"tab-pane\" id=\"tech-chitosan-and-eucalyptus-infused\">
                         Chitosan and eucalyptus are natural ingredients in the fabric of the mattress which are renowned for their medicinal use as they help reduce the skinâ€™s moisture loss.
                    </div>";
                                                    break;

                                                case 'classic-fillings':
                                                    echo "<div role=\"tabpanel\" class=\"tab-pane\" id=\"tech-classic-fillings\">
                        These filings are found on high-end mattresses. It comprises a unique combination of cashmere, wool, and silk.
                    </div>";
                                                    break;
                                                case 'damask-fabric':
                                                    echo "<div role=\"tabpanel\" class=\"tab-pane\" id=\"tech-damask-fabric\">
                        A special fabric is used when the mattress was crafted. For more info, please take a look at the mattress specifications.
                    </div>";
                                                    break;
                                                case 'double-jersey':
                                                    echo "<div role=\"tabpanel\" class=\"tab-pane\" id=\"tech-double-jersey\">
                        Jersey is a type of fabric that is crafted with wool, cotton, and other synthetic fibres. For a double knitted jersey, it offers a heavier and more durable kind of fabric with less stretch.</div>";
                                                    break;

                                                case 'fire-retard':
                                                    echo "<div role=\"tabpanel\" class=\"tab-pane\" id=\"tech-fire-retard\">
                      Our mattresses comply with British Standards and Regulations, which make them safer for all (BS 7177:2008 compliant).
                    </div>";
                                                    break;

                                                case 'flip':
                                                    echo "<div role=\"tabpanel\" class=\"tab-pane\" id=\"tech-flip\">
                     Flip your zonal support mattress every 6 weeks to take advantage of the comfort of both sleeping surfaces. To flip your mattress, change the sleeping surface by putting the mattress on its long side in between change. After the flip, the head end remains the head end but the other sleeping surface is facing up.
                    </div>";
                                                    break;

                                                case 'flip-and-rotate':
                                                    echo "<div role=\"tabpanel\" class=\"tab-pane active\" id=\"tech-flip-and-rotate\">
                     Flip (change the sleeping surface) and rotate (turn 180Â° your mattress so the head end becomes the foot end and vise versa) your two-sided mattress every 6 weeks to enjoy both sleeping surfaces. Similar springs and fillings are placed on each sleeping surface.
                    </div>";
                                                    break;

                                                case 'hand-tufted':
                                                    echo "<div role=\"tabpanel\" class=\"tab-pane\" id=\"tech-hand-tufted\">
                        Tufting involves fastening the two ends of the mattress to make it tighter and more durable. Tufting by hand is far better than machine tufted because attention to detail is not ignored.
                    </div>";
                                                    break;

                                                case 'handles':
                                                    echo "<div role=\"tabpanel\" class=\"tab-pane\" id=\"tech-handles\">
                        Conveniently located on the sides of the mattress for easy turning and handling. These handles are flag stitched for strong attachment.
                    </div>";
                                                    break;
                                                case 'handmade-uk':
                                                    echo "<div role=\"tabpanel\" class=\"tab-pane\" id=\"tech-handmade-uk\">
                        All our mattresses, toppers and divan beds are manufactured in the United Kingdom to ensure product quality and safety. We do not import materials and products from other regions.
                    </div>";
                                                    break;
                                                case 'hypoallergenic':
                                                    echo "<div role=\"tabpanel\" class=\"tab-pane\" id=\"tech-hypoallergenic\">
                       The materials used in the mattress are suitable for people who have sensitive skin or are suffering from asthma. A hypoallergenic mattress helps prevent an allergic reaction because of its composition.
                    </div>";
                                                    break;
                                                case 'kids-mattress':
                                                    echo "<div role=\"tabpanel\" class=\"tab-pane\" id=\"tech-kids-mattress\">
                        These mattresses are great for kids.
                    </div>";
                                                    break;
                                                case 'lavender':
                                                    echo "<div role=\"tabpanel\" class=\"tab-pane\" id=\"tech-lavender\">
                        Lavender.
                    </div>";
                                                    break;
                                                case 'luxury-fillings':
                                                    echo "<div role=\"tabpanel\" class=\"tab-pane\" id=\"tech-luxury-fillings\">
                        These filings are found on high-end mattresses. It comprises a unique combination of cashmere, wool, and silk.
                    </div>";
                                                    break;
                                                case 'mattress-topper':
                                                    echo "<div role=\"tabpanel\" class=\"tab-pane\" id=\"tech-mattress-topper\">Placed on top of the mattress for added softness and comfort, the mattress topper moulds to the shape of your body to relieve pressure and prevent aches and pains.</div>";
                                                    break;

                                                case 'milk-protein-infused':
                                                    echo "<div role=\"tabpanel\" class=\"tab-pane\" id=\"tech-milk-protein-infused\">The fabric containing milk protein makes the mattress hygienic, heat-resistant and moisture absorbent. The ingredients in the milk protein will keep your skin tender and smooth.</div>";
                                                    break;

                                                case 'memory-wool':
                                                    echo "<div role=\"tabpanel\" class=\"tab-pane\" id=\"tech-memory-wool\">This special material is environmentally friendly and provides many benefits. It is known to relieve pressure while providing comfort and support.</div>";
                                                    break;

                                                case 'natural-fillings':
                                                    echo "<div role=\"tabpanel\" class=\"tab-pane\" id=\"tech-natural-fillings\">
                        The fillings of the mattress are crafted with natureâ€™s gifts such as Wool, Cashmere, Cotton and Silk. This will make you feel fresh and closer to nature long after you have got off your mattress.
                    </div>";
                                                    break;

                                                case 'non-turn':
                                                    echo "<div role=\"tabpanel\" class=\"tab-pane\" id=\"tech-non-turn\">
                        You can only make use of one side of the mattress. Most of the time, these mattresses have memory foam or pillowtop on its sleeping surface. Itâ€™s a hassle-free mattress because there is no need to turn it over every few weeks. However, it is highly recommended that it should be rotated from the head-end to the foot-end for longevity.
                    </div>";
                                                    break;

                                                case 'organic':
                                                    echo "<div role=\"tabpanel\" class=\"tab-pane\" id=\"tech-organic\">
                        This is a more expensive kind of fabric because it is all-natural. There are no chemicals or additives in this fabric.
                    </div>";
                                                    break;

                                                case 'orthopaedic':
                                                    if ($_product->getAttributeSetId() == 16) {
                                                        echo "<div role=\"tabpanel\" class=\"tab-pane\" id=\"tech-orthopaedic\">
                                This type of mattress topper is ideal for those having back and joint problems. It is known to relieve pressure on joints and the spine with all-around support.
                            </div>";
                                                    } else {
                                                        echo "<div role=\"tabpanel\" class=\"tab-pane\" id=\"tech-orthopaedic\">
                        This type of mattress is ideal for those having back and joint problems. It is known to relieve pressure on joints and the spine with all-around support.
                    </div>";
                                                    }
                                                    break;

                                                case 'one-sided':
                                                    echo "  <div role=\"tabpanel\" class=\"tab-pane\" id=\"tech-one-sided\">
                        Rotate (turn 180Â°) your mattress every 6 weeks so the head end becomes the foot end and vise versa. The rotatable mattress has one sleeping surface. Most of the time, it has memory foam or a pillow top on its sleeping surface. Itâ€™s easy to look after this mattress as there is no need to flip it over.
                      </div>";
                                                    break;

                                                case 'pillowtop':
                                                    echo "<div role=\"tabpanel\" class=\"tab-pane\" id=\"tech-pillowtop\">
                        This provides an extra layer that is stitchedq on top of the mattress. This layer is made from wool and silk for added comfort.
                    </div>";
                                                    break;

                                                case 'quilted':
                                                    echo "<div role=\"tabpanel\" class=\"tab-pane\" id=\"tech-quilted\">
                        Quilting is sewing two or more layers of fabric together to make a thicker padded material. The quilts are placed on the surface and attached to the underlying filling for comfort. A quilted finish mattress is known to be smoother compared to other finishes.
                    </div>";
                                                    break;
                                                case 'removable-cover':
                                                    if ($_product->getAttributeSetId() == 16) {
                                                        echo "  <div role=\"tabpanel\" class=\"tab-pane\" id=\"tech-removable-cover\">
                        The mattress topper features a removable cover that is easy to wash or replace. A removable cover is good for the topper to protect it from stains and dirt.
                      </div>";
                                                    } else {
                                                        echo "  <div role=\"tabpanel\" class=\"tab-pane\" id=\"tech-removable-cover\">
                        The mattress features a removable cover that is easy to wash or replace. A removable cover is good for the mattress to protect it from stains and dirt.
                      </div>";
                                                    }
                                                    break;
                                                case 'rotate':
                                                    echo "  <div role=\"tabpanel\" class=\"tab-pane\" id=\"tech-rotate\">
                        Rotate (turn 180Â°) your mattress every 6 weeks so the head end becomes the foot end and vise versa. The rotatable mattress has one sleeping surface. Most of the time, it has memory foam or a pillow top on its sleeping surface. Itâ€™s easy to look after this mattress as there is no need to flip it over.
                      </div>";
                                                    break;
                                                case 'semi-orthopaedic':
                                                    echo "<div role=\"tabpanel\" class=\"tab-pane\" id=\"tech-semi-orthopaedic\">
                        This type of mattress is ideal for those having back and joint problems. It is known to relieve pressure on joints and the spine with all-around support.
                    </div>";
                                                    break;

                                                case 'special-fabric':
                                                    echo "<div role=\"tabpanel\" class=\"tab-pane\" id=\"tech-special-fabric\">
                        A special fabric is used when the mattress was crafted. For more info, please take a look at the mattress specifications.
                    </div>";
                                                    break;

                                                case 'suitable-for-bunk-bed':
                                                    echo "<div role=\"tabpanel\" class=\"tab-pane\" id=\"suitable-for-bunk-bed\">
                        The mattress is up to 15 cm deep; hence it is suitable for a top bunk and a trundle bed. This depth provides for the safety requirements for the top bunks and the limited under bed space for the trundles.
                    </div>";
                                                    break;

                                                case 'two-sided':
                                                    echo "<div role=\"tabpanel\" class=\"tab-pane\" id=\"tech-two-sided\">
                        You can enjoy both sides of the mattress because similar springs and fillings are placed on each side. It is highly recommended that this kind of mattress be turned over regularly to prolong its life.
                    </div>";
                                                    break;

                                                case 'vacuum-packed':
                                                    if ($_product->getAttributeSetId() == 16) {
                                                        echo "<div role=\"tabpanel\" class=\"tab-pane\" id=\"tech-vacuum-packed\">

The Topper is crafted in a way that allows its rolling. The air is removed from the package before it is sealed. All this aids in easy handling and delivery of the topper. When opening a vacuum packed topper, please allow 4 hours for its initial expansion. The topper is okay to use after this period however it will continue to expand (to a lesser degree) over the following 72 hours. Any odours from the packaging will be eliminated in the process.
                    </div>";
                                                    } else {
                                                        echo "<div role=\"tabpanel\" class=\"tab-pane\" id=\"tech-vacuum-packed\">
                        The mattress is crafted in a way that allows its rolling. The air is removed from the package before it is sealed. All this aids in easy handling and delivery of the mattress. When opening a vacuum packed mattress, please allow 4 hours for its initial expansion. The mattress is okay to use after this period however it will continue to expand (to a lesser degree) over the following 72 hours. Any odours from the packaging will be eliminated in the process.
                    </div>";
                                                    }
                                                    break;

                                                case 'velour-fabric':
                                                    echo "<div role=\"tabpanel\" class=\"tab-pane\" id=\"velour-fabric\">
                        A special fabric is used when the mattress was crafted. For more info, please take a look at the mattress specifications.
                    </div>";
                                                    break;

                                                case 'wool-tufts':
                                                    echo "<div role=\"tabpanel\" class=\"tab-pane\" id=\"tech-wool-tufts\">
                        Wool is used to tuft the mattress. Both ends of the mattress is fastened together to make it tighter and more durable. Our mattresses are hand-tufted for special care.
                    </div>";
                                                    break;
                                                case 'zonal-support':
                                                    echo "<div role=\"tabpanel\" class=\"tab-pane\" id=\"tech-zonal-support\">
                       Using cleverly designed pressure relieving advanced technology, this mattress offers unparalleled support for entire body. It provides extra protection for the lower back with more support for the heavier body parts and a soft feel for the lighter parts.
                    </div>";
                                                    break;

                                                default:

                                                    //                                                    Mage::log('Tab Missing for:' . $symbol, NULL, 'mattress-symbols-viewpage.log');

                                                    echo '';
                                            }

                                            ?><?php } ?>
                                    </div>
                                </div>
                            </div>

                        </div>
                    <?php } ?>
                </div>
            </div>

            <div style="clear:both;"></div>

        </div>


        <script>

            jQuery('.what-makes-it-great-icon-row-inner div').on('click', function(){



                jQuery('.tech-ico-outer').removeClass('active');
                jQuery('.tab-pane').removeClass('active');
                jQuery('#'+jQuery(this).attr('aria-controls')).addClass('active');
                jQuery(this).addClass('active');

            });
        </script>


    <?php }

    ?>




</div>


<script type="text/javascript">jQuery(window).bind("pageshow", function (event) {
        if (event.originalEvent.persisted) {
            window.location.reload()
        }
    });
    //ELEVATE.Megamenu.init();

    function ep_createCookie(name, value, days) {
        var expires = "";
        if (days) {
            var date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = "; expires=" + date.toUTCString();
        }
        document.cookie = name + "=" + value + expires + "; path=/";
    }

    function ep_readCookie(name) {
        var nameEQ = name + "=";
        var ca = document.cookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
        }
        return null;
    }

    function ep_eraseCookie(name) {
        createCookie(name, "", -1);
    }</script>


<script type="text/javascript">




    <?php if (!empty($mattress_firmness) || count($symbolArr) > 0) { ?>
    var whereontrack1fixed = '<?php echo $whereontrack; ?>';
    var whereontrack2fixed = '<?php echo $whereontrack2; ?>';
    var readhowmany = 1;

    var radios = jQuery('input:radio[name=bwpeople]');
    readhowmany = ep_readCookie('bwhowmany');

    if (readhowmany == 2) {
        radios.filter('[value=2]').prop('checked', true);
        jQuery('.bodyweight_pp2').removeClass('firm_hidden');
        jQuery('.bwselwrap2').show();
    } else if (readhowmany == 1) {
        radios.filter('[value=1]').prop('checked', true);
        jQuery('.bodyweight_pp2').addClass('firm_hidden');
        jQuery('.bwselwrap2').hide();
    }

    jQuery('input[name=bwpeople]').change(function () {
        var value = jQuery('input[name=bwpeople]:checked').val();
        if (value == 2) {


            ep_createCookie('bwhowmany', '2', 7);

            jQuery('.bodyweight_pp2').show();
            jQuery('.bwselwrap2').show();

            //if both match
            if (whereontrack1fixed == whereontrack2fixed) {
                jQuery('.bodyweight_pp').addClass('selectormatch');
                jQuery('.bodyweight_pp2').addClass('selectormatch');
            } else {
                jQuery('.bodyweight_pp').removeClass('selectormatch');
                jQuery('.bodyweight_pp2').removeClass('selectormatch');
            }

        } else {
            ep_createCookie('bwhowmany', '1', 7);
            jQuery('.bodyweight_pp2').hide();

            jQuery('.bodyweight_pp').removeClass('selectormatch');
            jQuery('.bodyweight_pp2').removeClass('selectormatch');

            jQuery('.bwselwrap2').hide();

        }
    });
    readhowmany = ep_readCookie('bwhowmany');
    if (readhowmany == 2) {

        jQuery('.bodyweight_pp2').removeClass('firm_hidden');
        jQuery('.bwselwrap2').show();


    } else if (readhowmany == 1) {
        jQuery('.bodyweight_pp2').addClass('firm_hidden');
        jQuery('.bwselwrap2').hide();
        jQuery('.bodyweight_pp').removeClass('selectormatch');
        jQuery('.bodyweight_pp2').removeClass('selectormatch');
    } else {
        jQuery('.bodyweight_pp2').addClass('firm_hidden');
        jQuery('.bwselwrap2').hide();
        jQuery('.bodyweight_pp').removeClass('selectormatch');
        jQuery('.bodyweight_pp2').removeClass('selectormatch');
    }

    function changeBodyweightType(input) {


        if (input == 'kg') {
            jQuery(".bodyweightstone").removeClass('bodyweightbold');
            jQuery(".bodyweightkg").addClass('bodyweightbold');
        } else {
            jQuery(".bodyweightstone").addClass('bodyweightbold');
            jQuery(".bodyweightkg").removeClass('bodyweightbold');
        }

        try {


            //   var mattress_sku = getUrlParameter('mattress');

            jQuery.ajax({
                url: '/ev_firmness/index/setbodyweighttype/type/' + input,
                dataType: 'json',
                type: 'get',

                success: function (data) {


                }
            });


            var selectbox = jQuery('#bodyweight_select');
            var selectbox2 = jQuery('#bodyweight_select2');
            var bodyweight = parseFloat(selectbox.find(':selected').data('bodyweight'));
            var bodyweight2 = parseFloat(selectbox2.find(':selected').data('bodyweight'));
            selectbox.empty();
            selectbox2.empty();
            var list = '';


            var data = '[{"stone":"6","kg":"35-41"} ,{"stone":"7","kg":"42-47"} ,{"stone":"8","kg":"48-53"} ,{"stone":"9","kg":"54-60"} ,{"stone":"10","kg":"61-66"} ,{"stone":"11","kg":"67-73"} ,{"stone":"12","kg":"74-79"} ,{"stone":"13","kg":"80-85"} ,{"stone":"14","kg":"86-92"} ,{"stone":"15","kg":"93-98"} ,{"stone":"16","kg":"99-104"} ,{"stone":"17","kg":"105-111"} ,{"stone":"18","kg":"112-117"} ,{"stone":"19","kg":"118-123"} ,{"stone":"20","kg":"124-130"} ,{"stone":"21","kg":"131-136"} ,{"stone":"22","kg":"137-142"} ,{"stone":"23","kg":"143-149"} ,{"stone":"24","kg":"150-155"} ,{"stone":"25","kg":"156-161"} ,{"stone":"26","kg":"162-168"} ,{"stone":"27","kg":"169-174"} ,{"stone":"28","kg":"175-180"}]';
            var jsonArray = JSON.parse(data);


            for (var i = 0; i < jsonArray.length; i++) {

                if (input == 'kg') {
                    list += "<option class='bodyweight_data' data-bodyweight='" + jsonArray[i]["stone"] + "' value='" + jsonArray[i]["stone"] + "'>" + jsonArray[i]["kg"] + " Kg</option>";
                } else {
                    list += "<option class='bodyweight_data' data-bodyweight='" + jsonArray[i]["stone"] + "' value='" + jsonArray[i]["stone"] + "'>" + jsonArray[i]["stone"] + " Stone</option>";
                }

            }


            selectbox.html(list);
            selectbox2.html(list);

            jQuery('#bodyweight_select').val(bodyweight);
            jQuery('#bodyweight_select2').val(bodyweight2);


        } catch (e) {
        }
    }


    jQuery('#bodyweight_select').change(function () {

        var bodyweight = parseFloat(jQuery(this).find(':selected').data('bodyweight'));

        try {


            //   var mattress_sku = getUrlParameter('mattress');

            jQuery.ajax({
                url: '/ev_firmness/index/setbodyweight/bodyweight/' + bodyweight,
                dataType: 'json',
                type: 'get',

                success: function (data) {

                    var original_firmness = parseFloat(<?php echo $original_mattress_firmness; ?>);
                    var adjustment = parseFloat(data.adjustment);


                    var firmness_rating = parseFloat(original_firmness + adjustment);
                    var firmness_bound_hit = false;
                    if (firmness_rating < -1) {
                        firmness_bound_hit = true;
                    }
                    if (firmness_rating > 19) {
                        firmness_bound_hit = true;
                    }
                    if (firmness_rating < 1) {
                        firmness_rating = 1;
                    }
                    if (firmness_rating > 17) {
                        firmness_rating = 17;
                    }

                    jQuery('#current_firmness_val').html(bodyweight + " stone adult");

                    if (firmness_bound_hit) {

                        jQuery(".firmness_rating_slide").addClass('firm_opacity');
                        jQuery(".firmness_not_suited").removeClass('firm_hidden');
                    } else {
                        jQuery(".firmness_rating_slide").removeClass('firm_opacity');
                        jQuery(".firmness_not_suited").addClass('firm_hidden');

                    }


                    var whereontrack = ((((firmness_rating) / 17) * 100)).toFixed(2);

                    whereontrack1fixed = whereontrack;

                    if (whereontrack2fixed == whereontrack && readhowmany == 2) {
                        jQuery('.bodyweight_pp').addClass('selectormatch');
                        jQuery('.bodyweight_pp2').addClass('selectormatch');
                    } else {
                        jQuery('.bodyweight_pp').removeClass('selectormatch');
                        jQuery('.bodyweight_pp2').removeClass('selectormatch');
                    }
                    jQuery(".bodyweight_pp").css('left', whereontrack + '%');
                }
            });
        } catch (e) {
        }


    });


    jQuery('#bodyweight_select2').change(function () {
        var bodyweight = parseFloat(jQuery(this).find(':selected').data('bodyweight'));
        try {


            //   var mattress_sku = getUrlParameter('mattress');

            jQuery.ajax({
                url: '/ev_firmness/index/setbodyweight2/bodyweight/' + bodyweight,
                dataType: 'json',
                type: 'get',

                success: function (data) {

                    var original_firmness = parseFloat(<?php echo $original_mattress_firmness; ?>);
                    var adjustment = parseFloat(data.adjustment);


                    var firmness_rating = parseFloat(original_firmness + adjustment);
                    var firmness_bound_hit = false;
                    if (firmness_rating < -1) {
                        firmness_bound_hit = true;
                    }
                    if (firmness_rating > 19) {
                        firmness_bound_hit = true;
                    }
                    if (firmness_rating < 1) {
                        firmness_rating = 1;
                    }
                    if (firmness_rating > 17) {
                        firmness_rating = 17;
                    }

                    jQuery('#current_firmness_val').html(bodyweight + " stone adult");

                    if (firmness_bound_hit) {

                        jQuery(".firmness_rating_slide").addClass('firm_opacity');
                        jQuery(".firmness_not_suited").removeClass('firm_hidden');
                    } else {
                        jQuery(".firmness_rating_slide").removeClass('firm_opacity');
                        jQuery(".firmness_not_suited").addClass('firm_hidden');

                    }


                    var whereontrack = ((((firmness_rating) / 17) * 100)).toFixed(2);
                    whereontrack2fixed = whereontrack;


                    if (whereontrack1fixed == whereontrack) {
                        jQuery('.bodyweight_pp').addClass('selectormatch');
                        jQuery('.bodyweight_pp2').addClass('selectormatch');
                    } else {
                        jQuery('.bodyweight_pp').removeClass('selectormatch');
                        jQuery('.bodyweight_pp2').removeClass('selectormatch');
                    }
                    jQuery(".bodyweight_pp2").css('left', whereontrack + '%');
                }
            });
        } catch (e) {
        }
    });

    <?php } ?>


</script>
