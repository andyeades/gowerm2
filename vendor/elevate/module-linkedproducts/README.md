# Called in the following way

       /** @var \Magento\Catalog\Model\Product $product */
        return $product->getLinkedProductsProducts();


# Debug

$writer = new \Zend\Log\Writer\Stream(BP . '/var/log/linker.log');
$logger = new \Zend\Log\Logger();
$logger->addWriter($writer);
$logger->info($data);  

/vendor/magento/module-catalog/Model/ResourceModel/Product/Link.php 

Db Queries here - rule out save issues

##ore over
/microcloud/domains/crudev/domains/stage.crucialfitness.co.uk/http/vendor/magento/module-catalog/Model/ResourceModel/Product/Link/Product/Collection.php