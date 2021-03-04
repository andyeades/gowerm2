<?php

namespace Elevate\DataFeeds\Helper;

use Magento\Framework\App\Helper\Context;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Catalog\Model\ProductRepository;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\FilterGroupBuilder;
use Magento\Store\Model\StoreManagerInterface;
use Magento\CatalogInventory\Api\StockRegistryInterface;
use Magento\Catalog\Model\Product\Gallery\ReadHandler as GalleryReadHandler;
use Magento\Eav\Api\AttributeSetRepositoryInterface;

class FeedGenerator extends AbstractHelper
{

    protected $feeds;
    protected $rootDir;
    protected $productRepo;
    protected $searchCriteriaBuilder;
    protected $filterBuilder;
    protected $filterGroupBuilder;
    protected $storeManager;
    protected $stockReg;
    protected $galleryReadHandler;

    public function __construct(
        Context $context,
        DirectoryList $directoryList,
        ProductRepository $productRepo,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        FilterBuilder $filterBuilder,
        FilterGroupBuilder $filterGroupBuilder,
        StoreManagerInterface $storeManager,
        StockRegistryInterface $stockReg,
        GalleryReadHandler $galleryReadHandler,
        AttributeSetRepositoryInterface $attributeSetRepo
    ) {

        $this->rootDir = $directoryList->getRoot();
        $this->productRepo = $productRepo;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->filterBuilder = $filterBuilder;
        $this->filterGroupBuilder = $filterGroupBuilder;
        $this->storeManager = $storeManager;
        $this->stockReg = $stockReg;
        $this->galleryReadHandler = $galleryReadHandler;
        $this->attributeSetRepo = $attributeSetRepo;

        $this->feeds = [
            'epos-till-feed' => [
                'file_type' => 'csv',
                'filename' => 'epos-till-prices.csv',
                'field_separator' => ',',
                'field_enclosure' => '"',
                'escape_character' => '"',
                'is_enabled' => true,
                'has_header' => false,
                'fields' => [
                    ['header_name' => '','attribute' => 'sku','pre_text' => '','post_text' => '','transform_method' => '',],
                    ['header_name' => '','attribute' => 'special_price','pre_text' => '','post_text' => '','transform_method' => '2decimalplaces',],
                ],

                'filters' => [
                    ['attribute' => 'type_id', 'operator' => 'eq', 'value' => 'simple'],
                    ['attribute' => 'status', 'operator' => 'eq', 'value' => 1],
                    ['attribute' => 'price', 'operator' => 'gt', 'value' => 0],
                ],

            ],
            'google-feed' => [
                'file_type' => 'txt',
                'filename' => 'google_feed.txt',
                'field_separator' => "\t",
                'field_enclosure' => '',
                # TODO `escape_character` was duplicated - if this feed still works remove this entry.
                //'escape_character' => '"',
                'encoding' => 'Windows-1252//TRANSLIT',
                'escape_character' => '\\',
                'is_enabled' => true,
                'has_header' => true,
                'fields' => [
                    ['header_name' => 'id','attribute' => 'sku','pre_text' => '','post_text' => '','transform_method' => '',],
                    ['header_name' => 'product_type','attribute' => 'producttype','pre_text' => '','post_text' => '','transform_method' => 'getText',],
                    ['header_name' => 'title','attribute' => 'name','pre_text' => '','post_text' => '','transform_method' => '',],
                 //   ['header_name' => 'google_product_category','attribute' => 'name',  'feed_googleps_category', 'pre_text' => '','post_text' => '','transform_method' => '',],
                    ['header_name' => 'link','attribute' => 'productUrl','pre_text' => '','post_text' => '','transform_method' => '',],
                    ['header_name' => 'image_link','attribute' => 'image','pre_text' => 'https://www.pas-nutrition.co.uk/media/catalog/product','post_text' => '','transform_method' => '',],
                    ['header_name' => 'condition','attribute' => 'condition','pre_text' => '','post_text' => '','transform_method' => 'conditionText',],
                    ['header_name' => 'availability','attribute' => '','pre_text' => 'in stock','post_text' => '','transform_method' => '',],
                    ['header_name' => 'price','attribute' => 'special_price','pre_text' => '','post_text' => ' GBP','transform_method' => '2decimalplaces',],
                    ['header_name' => 'brand','attribute' => 'manufacturer','pre_text' => '','post_text' => '','transform_method' => 'getText',],
                    ['header_name' => 'gtin','attribute' => 'barcode','pre_text' => '','post_text' => '','transform_method' => '',],
                    ['header_name' => 'mpn','attribute' => 'mpn','pre_text' => '','post_text' => '','transform_method' => '',],
                    ['header_name' => 'custom_label_0','attribute' => 'attribute_set_name','pre_text' => '','post_text' => '','transform_method' => '',],
                    ['header_name' => 'promotion_id','attribute' => 'google_promotion_id','pre_text' => '','post_text' => '','transform_method' => 'getText',],
                    ['header_name' => 'description','attribute' => 'description','pre_text' => '','post_text' => '','transform_method' => '',],
                ],
                'filters' => [
                    ['attribute' => 'type_id', 'operator' => 'eq', 'value' => 'simple'],
                    ['attribute' => 'status', 'operator' => 'eq', 'value' => 1],
                   // ['attribute' => 'feed_googleps', 'operator' => 'eq', 'value' => 1],
                    ['attribute' => 'visibility', 'operator' => 'in', 'value' => '2,3,4'],
                ],
                'check_stock' => true,
                 

            ],
            'netrivals-feed' => [
                'file_type' => 'csv',
                'filename' => 'NetRivalsFeed.csv',
                'field_separator' => ',',
                'field_enclosure' => '"',
                'escape_character' => '"',
                'encoding' => 'Windows-1252//TRANSLIT',
                'is_enabled' => true,
                'has_header' => true,
                'fields' => [
                    ['header_name' => 'id','attribute' => 'sku','pre_text' => '','post_text' => '','transform_method' => '',],
                    ['header_name' => 'title','attribute' => 'name','pre_text' => '','post_text' => '','transform_method' => '',],
                    ['header_name' => 'images','attribute' => 'all_images','pre_text' => '','post_text' => '','transform_method' => '',],
                    ['header_name' => 'price','attribute' => 'special_price','pre_text' => '','post_text' => ' GBP','transform_method' => '2decimalplaces',],
                    ['header_name' => 'url','attribute' => 'productUrl','pre_text' => '','post_text' => '','transform_method' => '',],
                    ['header_name' => 'brand','attribute' => 'manufacturer','pre_text' => '','post_text' => '','transform_method' => 'getText',],
                    ['header_name' => 'category','attribute' => 'producttype','pre_text' => '','post_text' => '','transform_method' => 'getText',],
                    ['header_name' => 'gtin','attribute' => 'barcode','pre_text' => '','post_text' => '','transform_method' => '',],
                    ['header_name' => 'mpn','attribute' => 'mpn','pre_text' => '','post_text' => '','transform_method' => '',],
                    ['header_name' => 'tax','attribute' => '','pre_text' => '20%','post_text' => '','transform_method' => '',],
                    ['header_name' => 'availability','attribute' => '','pre_text' => 'in stock','post_text' => '','transform_method' => '',],
                    ['header_name' => 'google_product_category','attribute' => 'feed_googleps_category','pre_text' => '','post_text' => '','transform_method' => 'getText',],

                ],
                'filters' => [
                    ['attribute' => 'type_id', 'operator' => 'eq', 'value' => 'simple'],
                    ['attribute' => 'status', 'operator' => 'eq', 'value' => 1],
                    ['attribute' => 'publish_to_net_rivals', 'operator' => 'eq', 'value' => 1],
                ],
                'check_stock' => true,
            ],
            'live-feed' => [
                'file_type' => 'csv',
                'filename' => 'LiveProducts.csv',
                'field_separator' => ',',
                'field_enclosure' => '"',
                'escape_character' => '"',
                'encoding' => 'Windows-1252//TRANSLIT',
                'is_enabled' => true,
                'has_header' => true,
                'fields' => [
                    ['header_name' => 'sku','attribute' => 'sku','pre_text' => '','post_text' => '','transform_method' => '',],

                ],
                'filters' => [
                    ['attribute' => 'type_id', 'operator' => 'eq', 'value' => 'simple'],
                    ['attribute' => 'status', 'operator' => 'eq', 'value' => 1],
                ],
                'not_discontinued' => true,
            ],
            'out-of-stock-feed' => [
                'file_type' => 'csv',
                'filename' => 'oos.csv',
                'field_separator' => ",",
                'field_enclosure' => '"',
                'escape_character' => '"',
                'is_enabled' => true,
                'has_header' => true,
                'fields' => [
                    ['header_name' => 'SKU','attribute' => 'sku','pre_text' => '','post_text' => '','transform_method' => '',],
                    ['header_name' => 'Title','attribute' => 'name','pre_text' => '','post_text' => '','transform_method' => '',],
                    ['header_name' => 'Quantity','attribute' => 'quantity','pre_text' => '','post_text' => '','transform_method' => '',],
                ],
                'filters' => [
                    ['attribute' => 'type_id', 'operator' => 'eq', 'value' => 'simple'],
                    ['attribute' => 'status', 'operator' => 'eq', 'value' => 1],

                ],
                'out_of_stock' => true,
                'not_discontinued' => true,
            ],
            'criteo-feed' => [
                'file_type' => 'txt',
                'filename' => 'criteo_product_search_feed.txt',
                'field_separator' => "|",
                'field_enclosure' => '',
                'escape_character' => '"',
                'is_enabled' => true,
                'has_header' => true,
                'encoding' => 'Windows-1252//TRANSLIT',
                'fields' => [
                    ['header_name' => 'id','attribute' => 'sku','pre_text' => '','post_text' => '','transform_method' => '',],
                    ['header_name' => 'product_type','attribute' => 'producttype','pre_text' => '','post_text' => '','transform_method' => 'getText',],
                    ['header_name' => 'title','attribute' => 'name','pre_text' => '','post_text' => '','transform_method' => '',],
                    ['header_name' => 'description','attribute' => 'description','pre_text' => '','post_text' => '','transform_method' => '',],
                    ['header_name' => 'google_product_category','attribute' => 'google_product_type','pre_text' => '','post_text' => '','transform_method' => '',],
                    ['header_name' => 'link','attribute' => 'productUrl','pre_text' => '','post_text' => '','transform_method' => '',],
                    ['header_name' => 'image_link','attribute' => 'image','pre_text' => 'https://www.example.com/media/catalog/product/','post_text' => '','transform_method' => '',],
                    ['header_name' => 'condition','attribute' => 'condition','pre_text' => '','post_text' => '','transform_method' => 'getText',],
                    ['header_name' => 'availability','attribute' => '','pre_text' => '','post_text' => '','transform_method' => '',],
                    ['header_name' => 'price','attribute' => 'special_price','pre_text' => '','post_text' => '','transform_method' => '2decimalplaces',],
                    ['header_name' => 'brand','attribute' => 'manufacturer','pre_text' => '','post_text' => '','transform_method' => 'getText',],
                    ['header_name' => 'gtin','attribute' => 'barcode','pre_text' => '','post_text' => '','transform_method' => '',],
                    ['header_name' => 'mpn','attribute' => 'mpn','pre_text' => '','post_text' => '','transform_method' => '',],
                    ['header_name' => 'custom_label_0','attribute' => 'feed_googleeps_label','pre_text' => '','post_text' => '','transform_method' => '',],
                    ['header_name' => 'promotion_id','attribute' => 'google_promotion_id','pre_text' => '','post_text' => '','transform_method' => 'getText',],
                    ['header_name' => 'product_applicability','attribute' => '','pre_text' => 'SPECIFIC_PRODUCTS','post_text' => '','transform_method' => '',],
                    ['header_name' => 'custom_label_1','attribute' => 'custom_label_1','pre_text' => '','post_text' => '','transform_method' => 'getText',],
                ],
                'filters' => [
                    ['attribute' => 'type_id', 'operator' => 'eq', 'value' => 'simple'],
                    ['attribute' => 'status', 'operator' => 'eq', 'value' => 1],
                    ['attribute' => 'visibility', 'operator' => 'in', 'value' => '2,3,4'],
                ],
                'check_stock' => true,
            ],
            'intelli-ad-feed' => [
                'file_type' => 'csv',
                'filename' => 'intelliadsfeeds.csv',
                'field_separator' => ",",
                'field_enclosure' => '"',
                'escape_character' => '"',
                'is_enabled' => true,
                'has_header' => true,
                'fields' => [
                    ['header_name' => 'offer_id','attribute' => 'sku','pre_text' => '','post_text' => '','transform_method' => '',],
                    ['header_name' => 'name','attribute' => 'name','pre_text' => '','post_text' => '','transform_method' => '',],
                    ['header_name' => 'category','attribute' => 'producttype','pre_text' => '','post_text' => '','transform_method' => 'getText',],
                    ['header_name' => 'category_path','attribute' => 'feed_googleps_category','pre_text' => '','post_text' => '','transform_method' => 'getText',],
                    ['header_name' => 'price','attribute' => 'special_price','pre_text' => '','post_text' => '','transform_method' => '',],
                    ['header_name' => 'shop_url','attribute' => 'productUrl','pre_text' => '','post_text' => '','transform_method' => '',],
                    ['header_name' => 'brand','attribute' => 'manufacturer','pre_text' => '','post_text' => '','transform_method' => 'getText',],
                    ['header_name' => 'additionalFields','attribute' => '','pre_text' => '','post_text' => 'adparams=++PRICE++;++PERCENTALSAVINGS:a[%]:rs[ ][]:cse[0%][]++','transform_method' => '',],
                    ['header_name' => 'old_Price','attribute' => 'price','pre_text' => '','post_text' => '','transform_method' => '',],
                ],
                'filters' => [
                    ['attribute' => 'type_id', 'operator' => 'eq', 'value' => 'simple'],
                    ['attribute' => 'visibility', 'operator' => 'in', 'value' => '2,3,4'],
                    ['attribute' => 'publish_to_intelliad', 'operator' => 'eq', 'value' => 1],
                    ['attribute' => 'status', 'operator' => 'eq', 'value' => 1],
                    ['attribute' => 'condition', 'operator' => 'eq', 'value' => 111],


                ],
                'check_stock' => true,

            ],
            'mcsports-feed' => [
                'file_type' => 'csv',
                'filename' => 'McSports Feed.csv',
                'field_separator' => ",",
                'field_enclosure' => '"',
                'escape_character' => '"',
                'is_enabled' => true,
                'has_header' => true,
                'fields' => [
                    ['header_name' => 'SKU','attribute' => 'sku','pre_text' => '','post_text' => '','transform_method' => '',],
                    ['header_name' => 'Name','attribute' => 'name','pre_text' => '','post_text' => '','transform_method' => '',],
                    ['header_name' => 'Brand','attribute' => 'manufacturer','pre_text' => '','post_text' => '','transform_method' => 'getText',],
                    ['header_name' => 'RRP','attribute' => 'price','pre_text' => '','post_text' => '','transform_method' => '2decimalplaces',],
                    ['header_name' => 'Image URL','attribute' => 'image','pre_text' => 'https://www.example.com/media/catalog/product','post_text' => '','transform_method' => '',],

                ],
                'filters' => [
                    ['attribute' => 'type_id', 'operator' => 'eq', 'value' => 'simple'],
                    ['attribute' => 'visibility', 'operator' => 'in', 'value' => '2,3,4'],
                    ['attribute' => 'status', 'operator' => 'eq', 'value' => 1],
                    ['attribute' => 'manufacturer', 'operator' => 'in', 'value' => '1104,1103,439,620,460,466,438'],

                ],
                'check_stock' => true,
            ],
        ];

        parent::__construct($context);
    }

    public function generateFeed($feed, $storeId = 0)
    {

        $this->storeManager->setCurrentStore($storeId);

        if (empty($this->feeds[$feed])) {
            return ['success' => 0, 'error_msg' => "Feed $feed doesn't exist"];
        }

        if (empty($this->feeds[$feed]['filename'])) {
            return ['success' => 0, 'error_msg' => "No filename set for $feed "];
        }

        if (!empty($this->feeds[$feed]['append_filename_with_date']) && $this->feeds[$feed]['append_filename_with_date']) {
            $this->feeds[$feed]['filename'] = str_replace(
                ".csv",
                date("Y-m-d-His").".csv",
                $this->feeds[$feed]['filename']
            );
        }

        if (empty($this->feeds[$feed]['fields'])) {
            return ['success' => 0, 'error_msg' => "No filename set for $feed "];
        } else {
            $lastFieldKey = count($this->feeds[$feed]['fields']) - 1;
        }
        if (!file_exists($this->rootDir.'/pub/feeds/')) {
            mkdir($this->rootDir.'/pub/feeds/');
        }
        if (!empty($this->feeds[$feed]['sub_directory'])) {
            if (!file_exists($this->rootDir.'/pub/feeds/'.$this->feeds[$feed]['sub_directory'])) {
                mkdir($this->rootDir.'/pub/feeds/'.$this->feeds[$feed]['sub_directory']);
            }
            $filePath = $this->rootDir.'/pub/feeds/'.$this->feeds[$feed]['sub_directory'].'/'.$this->feeds[$feed]['filename'];
        } else {
            $filePath = $this->rootDir.'/pub/feeds/'.$this->feeds[$feed]['filename'];
        }

        $tmpFilePath = $filePath."tmp";
        // open temp file to write to (so old version of feed will still be available until we've finished generating new onw
        $fHandle = fopen($tmpFilePath, "w");
        if ($fHandle) {

            // write header if required
            if ($this->feeds[$feed]['has_header']) {
                // get headings
                foreach ($this->feeds[$feed]['fields'] as $key => $curField) {
                    if (!isset($curField['header_name'])) {
                        $curField['header_name'] = '';
                    }
                    fwrite(
                        $fHandle,
                        $this->wrapText(
                            $curField['header_name'],
                            $this->feeds[$feed]['field_enclosure'],
                            $this->feeds[$feed]['field_separator'],
                            $this->feeds[$feed]['escape_character']
                        )
                    );
                    if ($key != $lastFieldKey) {
                        fwrite($fHandle, $this->feeds[$feed]['field_separator']);
                    } else {
                        fwrite($fHandle, PHP_EOL);
                    }
                }
            }
            // build filter to query products with
            if (!empty($this->feeds[$feed]['filters'])) {

                $filterGroups = [];
                foreach ($this->feeds[$feed]['filters'] as $curFilter) {
                    $filter = $this->filterBuilder
                        ->setField($curFilter['attribute'])
                        ->setConditionType($curFilter['operator'])
                        ->setValue($curFilter['value'])
                        ->create();
                    // need multiple filter groups so the conditions are ANDed rather than ORed
                    $filterGroups[] = $this->filterGroupBuilder
                        ->addFilter($filter)
                        ->create();

                }
                $searchCriteria = $this->searchCriteriaBuilder
                    ->setFilterGroups($filterGroups)
                    ->create();

                $products = $this->productRepo->getList($searchCriteria)->getItems();
            } else {
                $searchCriteria = $this->searchCriteriaBuilder
                    ->create();
                $products = $this->productRepo->getList($searchCriteria)->getItems();
            }

            // write line for each product
            echo "Loaded ".count($products)." products to include in feed \n";
            foreach ($products as $curProduct) {
                // if product is discontinued and we don't want to include discontinued products skip to next product
                if (isset($this->feeds[$feed]['not_discontinued']) && $this->feeds[$feed]['not_discontinued'] == true && $curProduct->getData(
                        'availability'
                    ) == 112) {
                    continue;
                }
                // if only want to display in stock products skip to next product if item isn't in stock
                if (isset($this->feeds[$feed]['check_stock']) && $this->feeds[$feed]['check_stock'] == true && !$this->is_in_stock(
                        $curProduct
                    )) {
                    continue;
                }

                // if only want to display out of stock products skip to next product if item isn't in stock
                if (isset($this->feeds[$feed]['out_of_stock']) && $this->feeds[$feed]['out_of_stock'] == true && $this->is_in_stock(
                        $curProduct
                    )) {
                    continue;
                }

                // if only interested in marketplace products skip to next item if this product isn't enabled for any marketplace
                if (isset($this->feeds[$feed]['for_market_place']) && $this->feeds[$feed]['for_market_place'] == true) {

                    if ((!$this->getValue("sell_on_amazon", $curProduct, $feed)) && (!$this->getValue(
                            "sell_on_ebay",
                            $curProduct,
                            $feed
                        )) && (!$this->getValue("sell_on_tesco", $curProduct, $feed))) {
                        continue;
                    }
                }
                foreach ($this->feeds[$feed]['fields'] as $key => $curField) {
                    $value = '';
                    if (!empty($curField['attribute'])) {
                        $value = $this->getValue($curField['attribute'], $curProduct, $feed);

                        if (!empty($value) && !empty($curField['transform_method'])) {
                            $value = $this->transform(
                                $value,
                                $curField['transform_method'],
                                $curProduct,
                                $curField['attribute']
                            );
                        }
                        // Remove HTML and new lines
                        $value = strip_tags($value);
                        $value = str_replace(array("\n\r", "\n", "\r"), '  ', $value);
                    }
                    fwrite(
                        $fHandle,
                        $this->wrapText(
                            $curField['pre_text'].$value.$curField['post_text'],
                            $this->feeds[$feed]['field_enclosure'],
                            $this->feeds[$feed]['field_separator'],
                            $this->feeds[$feed]['escape_character']
                        )
                    );
                    if ($key != $lastFieldKey) {
                        fwrite($fHandle, $this->feeds[$feed]['field_separator']);
                    } else {
                        fwrite($fHandle, PHP_EOL);
                    }
                }
            }

            // close file
            fclose($fHandle);
            // replace old file with our new file
            if (!rename($tmpFilePath, $filePath)) {
                return [
                    'success' => 0,
                    'error_msg' => 'Sorry, there was an error trying to overwrite the old file with the new feed.',
                ];
            }

            // if have details try and ftp file
            if (!empty($this->feeds[$feed]['ftp-details'])) {
                try {
                    foreach ($this->feeds[$feed]['ftp-details'] as $curFTP) {
                        $ftpConnection = ftp_connect($curFTP['hostname']);
                        if ($ftpConnection) {
                            if (ftp_login($ftpConnection, $curFTP['username'], $curFTP['password'])) {
                                foreach ($curFTP['directories'] as $curDirectory) {
                                    if (!ftp_chdir($ftpConnection, $curDirectory)) {
                                        return [
                                            'success' => 0,
                                            'error_msg' => "FTP error: couldn't change to $curDirectory directory",
                                        ];
                                    }
                                }
                                $uploadFileName = $this->feeds[$feed]['filename'];
                                // channel advisor doesn't like us using same filename for file for different accounts so need to make it unique
                                if (!empty($curFTP['filename_append'])) {
                                    $uploadFileName = str_replace(
                                        ".csv",
                                        $curFTP['filename_append'].".csv",
                                        $uploadFileName
                                    );
                                }
                                if (ftp_put($ftpConnection, $uploadFileName, $filePath, FTP_ASCII)) {
                                    ftp_close($ftpConnection);
                                } else {
                                    return ['success' => 0, 'error_msg' => "Couldn't upload file to FTP server"];
                                }
                            } else {
                                return ['success' => 0, 'error_msg' => "Couldn't log in to FTP server"];
                            }
                        } else {
                            return ['success' => 0, 'error_msg' => "Couldn't open connection to FTP server"];
                        }
                    }
                    // since we've successfully ftp'd file elsewhere lets delete it to keep server from filling up
                    if (!empty($filePath)) {
                        unlink($filePath);
                    }
                } catch (\Exception $e) {
                    return ['success' => 0, 'error_msg' => "FTP error".$e->getMessage()];
                }
            }

            return ['success' => 1, 'error_msg' => ''];


        } else {
            return ['success' => 0, 'error_msg' => "Couldn't open file."];
        }


    }

    function is_in_stock($product)
    {
        $stockInfo = null;
        $stockInfo = $this->loadStock($product);

        if (empty($stockInfo)) {
            return false;
        }

        if ($stockInfo->getQty() < 1 || $stockInfo->getIsInStock() != 1) {
            return false;
        }

        return true;
    }


    function loadStock($product)
    {
        try {
            $stockInfo = $this->stockReg->getStockItem($product->getId(), $product->getStore()->getWebsiteId());
        } catch (\Exception $e) {
            echo "couldn't find stock information for product with id ".$product->getId()." as ".$e->getMessage()."\n";

            return null;
        }

        return $stockInfo;

    }

    function getValue($fieldName, $product, $feed)
    {

        $value = '';

        switch ($fieldName) {
            case 'attribute_set_name':
                $attributeSet = $this->attributeSetRepo->get($product->getAttributeSetId());
                if ($attributeSet) {
                    return $attributeSet->getAttributeSetName();
                } else {
                    return "";
                }
            case 'productUrl':
                return $product->setStoreId($product->getStore()->getStoreId())->getUrlInStore();
            case 'all_images':
                $this->galleryReadHandler->execute($product);
                $imageCollection = $product->getMediaGalleryImages();

                if (!empty($imageCollection) && method_exists($imageCollection, "getSize") && $imageCollection->getSize(
                    ) > 0) {
                    $images = $imageCollection->getItems();
                    foreach ($images as $curImage) {
                        $curImage['url'] = str_replace(
                            "/pub",
                            "",
                            $curImage['url']
                        ); // work around for Magento bug since it was incorrectly including /pub in urls
                        $value .= $curImage['url'].',';
                    }
                    // get rid of trailing comma
                    $value = substr($value, 0, -1);
                }

                return $value;
            case 'quantity':
                $stockInfo = $this->loadStock($product);
                if (!empty($stockInfo)) {
                    return $stockInfo->getQty();
                } else {
                    return 0;
                }
            case 'special_price':
                $specialPrice = $product->getData('special_price');
                $price = $product->getData('price');
                // if special price isn't set or special_price is more expensive than price we should use price instead
                if(empty($specialPrice) || $specialPrice > $price) {
                    return $price;
                }
                return $specialPrice;

            default:
                $value = $product->getData($fieldName);

                if (empty($value)) {
                    $methodName = 'get'.$fieldName;

                    if (method_exists($product, $methodName)) {
                        $value = $product->$methodName();
                    }
                }

                if (!empty($this->feeds[$feed]['encoding'])) {
                    $value = iconv("UTF-8", $this->feeds[$feed]['encoding'], $value);
                }

                return $value;


        }
    }

    function getEnabledFeeds()
    {
        $feedNames = [];

        foreach ($this->feeds as $curFeedName => $curFeed) {
            if ($curFeed['is_enabled']) {
                $feedNames[] = $curFeedName;
            }
        }

        return $feedNames;

    }
  /* Transform option */
    function transform($value, $transformer, $product, $attribute)
    {
        switch ($transformer) {
            case '2decimalplaces':
                return sprintf("%1.2f", $value);
                break;
            case 'getText':
                return $product->getAttributeText($attribute);
            case 'conditionText':
                if ($value == 111) {
                    $value = 'new';
                } else {
                    $value = 'refurbished';
                }
            default:
                return $value;
        }
    }

    // return value surrounded by $enclosure and suitably escaped.
    // eg if value is We're the no1 fitness company, enclosure is ' and escape character is \ this method should return 'We\'re the no1 fitness company'
    function wrapText($value, $enclosure, $separator, $escapeCharacter)
    {
        if (!empty($escapeCharacter)) {
            if (!empty($enclosure)) {
                $value = str_replace($enclosure, $escapeCharacter.$enclosure, $value);
            } elseif (!empty($separator)) {
                $value = str_replace($separator, $escapeCharacter.$separator, $value);
            }

        }
        if (!empty($enclosure)) {
            return $enclosure.$value.$enclosure;
        }

        return $value;

    }
}
