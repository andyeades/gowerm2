<?php

namespace Elevate\LinkedProducts\Cron;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class LinkedProductsFromGroupCode extends Command
{
    
    private $objectManager;   
  
    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectmanager,
        \Magento\Catalog\Api\ProductRepositoryInterface $productrepository,
        \Magento\Framework\App\State $state,
        \Psr\Log\LoggerInterface $logger
    )
    {
        $this->objectManager = $objectmanager;
        $this->productRepository = $productrepository;
        $this->state = $state;
        $this->logger = $logger;
        parent::__construct();
    }
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('elevate:products:regeneratelinkedproducts')->setDescription('Regenerate Linked Products from Group Code');
    }
    
    /**
     * {@inheritdoc}
     */
    public function execute(InputInterface $input = NULL, OutputInterface $output = NULL)
	{                     
        $this->state->setAreaCode(\Magento\Framework\App\Area::AREA_FRONTEND); // or \Magento\Framework\App\Area::AREA_ADMINHTML, depending on your needs

        $productCollection = $this->objectManager->create('Magento\Catalog\Model\ResourceModel\Product\Collection');
        $productCollection->addAttributeToSelect('*');
        $productCollection->setStoreId(0);
        $productCollection->addAttributeToFilter('group_code', array('notnull' => true), 'left');
        //  $productCollection->addAttributeToFilter('sku', array('in' => array('shake-take-choc-coc-mint')));
        $productCollection->load();

        foreach ($productCollection as $product){
           
           
           
           
           echo $product->getSku().": ".$product->getName(). "-" . $product->getPrice() . "\n";

                $linkDataAll = [];
                $skuarr = [];
     
     
             $group_code = $product->getData('group_code');   

     
             $productCollection2 = $this->objectManager->create('Magento\Catalog\Model\ResourceModel\Product\Collection');
        $productCollection2->addAttributeToSelect('sku');
        $productCollection2->setStoreId(0);
        $productCollection2->addAttributeToFilter('group_code', array('eq' => $group_code));
 $productCollection2->addAttributeToFilter('visibility', '4');
    $productCollection2->addAttributeToFilter('status',\Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED);
         
        $productCollection2->load();                                                    
        $n = 0;                                           
               foreach ($productCollection2 as $product2){ 
                 

                    /** @var  \Magento\Catalog\Api\Data\ProductLinkInterface $productLinks */
                    $productLinks = $this->objectManager->create('Magento\Catalog\Api\Data\ProductLinkInterface');
                            echo $product2->getSku()."\n";
                    $linkData = $productLinks //Magento\Catalog\Api\Data\ProductLinkInterface
                        ->setSku($product->getSku())
                        ->setLinkedProductSku($product2->getSku())
                        //->setLinkType("matchingfaucets");
                        ->setLinkType('linkedproducts')
                        ->setPosition($n+1);
                        $n++;
                           $linkDataAll[] = $linkData;
                           }   
                 
          

       
            if($linkDataAll) {
                print(count($linkDataAll)); //gives 3
                $product->setProductLinks($linkDataAll);
            }
            
            $product->save();
           
            unset($linkDataAll);
            unset($skuarr);
          
        }


	}
}