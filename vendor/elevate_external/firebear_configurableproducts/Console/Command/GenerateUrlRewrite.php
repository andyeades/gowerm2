<?php
/**
 * @copyright: Copyright © 2017 Firebear Studio. All rights reserved.
 * @author   : Firebear Studio <fbeardev@gmail.com>
 */

namespace Firebear\ConfigurableProducts\Console\Command;

use Exception;
use Firebear\ConfigurableProducts\Model\UrlGenerator;
use Magento\Backend\App\Area\FrontNameResolver;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Product\Visibility;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\App\State;
use Magento\Framework\Console\Cli;
use Magento\Store\Model\StoreManagerInterface;
use Magento\UrlRewrite\Model\Exception\UrlAlreadyExistsException;
use Magento\UrlRewrite\Model\UrlPersistInterface;
use Magento\UrlRewrite\Service\V1\Data\UrlRewrite;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command prints list of available currencies
 */
class GenerateUrlRewrite extends Command
{
    const VISIBILITY = 'visibility';

    /**
     * @var State
     */
    protected $state;
    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var UrlPersistInterface
     */
    private $urlPersist;

    /**
     * Generates url rewrites for different scopes.
     *
     * @var ProductScopeRewriteGenerator
     */
    private $rewriteGenerator;

    /**
     * GenerateUrlRewrite constructor.
     *
     * @param CollectionFactory $collectionFactory
     * @param StoreManagerInterface $storeManager
     * @param UrlPersistInterface $urlPersist
     * @param UrlGenerator $rewriteGenerator
     * @param State $state
     *
     * @internal param CollectionFactory $сollectionFactory
     */
    public function __construct(
        CollectionFactory $collectionFactory,
        StoreManagerInterface $storeManager,
        UrlPersistInterface $urlPersist,
        UrlGenerator $rewriteGenerator,
        State $state
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->storeManager = $storeManager;
        $this->urlPersist = $urlPersist;
        $this->rewriteGenerator = $rewriteGenerator;
        $this->state = $state;
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    public function configure()
    {
        $options = [
            new InputOption(
                self::VISIBILITY,
                '',
                InputOption::VALUE_OPTIONAL,
                __('Visibility id of the product i.e. 1 = VISIBILITY_NOT_VISIBLE, 2 = VISIBILITY_IN_CATALOG, 
                3 = VISIBILITY_IN_SEARCH, 4 = VISIBILITY_BOTH'),
                1
            )
        ];
        $this->setName('firebear:url-rewrite:generate')
            ->setDescription('Generate Url Rewrites for hidden products')
            ->setDefinition($options);

        parent::configure();
    }

    /**
     * {@inheritdoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $isAreaCode = 0;
        try {
            if ($this->state->getAreaCode()) {
                $isAreaCode = 1;
            }
        } catch (Exception $e) {
            $isAreaCode = 0;
        }
        if (!$isAreaCode) {
            $this->state->setAreaCode(FrontNameResolver::AREA_CODE);
        }
        $stores = $this->storeManager->getStores();

        $visibility = $input->getOption(self::VISIBILITY);

        if (!isset(Visibility::getOptionArray()[$visibility])) {
            $output->writeln('<error>' . __('Wrong Visibility Passed') . '</error>');
            return Cli::RETURN_FAILURE;
        }

        $productCollection = $this->collectionFactory->create();
        $productCollection
            ->addFieldToFilter('type_id', ['eq' => 'simple'])
            ->addFieldToFilter(
                'visibility',
                ['eq' => $visibility]
            )
            ->addAttributeToSelect(['name', 'url_path', 'url_key', 'visibility']);

        $productsCount = $productCollection->getSize();
        /** @var ProgressBar $progressBar */
        $progressBar = new ProgressBar($output, $productsCount);
        $output->writeln('<info>Found ' . $productsCount . ' products</info>');
        foreach ($productCollection as $product) {
            foreach ($stores as $store) {
                $filterData = [
                    UrlRewrite::ENTITY_ID => $product->getId(),
                    UrlRewrite::ENTITY_TYPE => UrlGenerator::ENTITY_TYPE,
                    UrlRewrite::STORE_ID => $store->getId(),
                ];
                $rewrite = $this->urlPersist->findOneByData($filterData);

                if (!$rewrite) {
                    $product->setStoreId($store->getId());
                    try {
                        $this->urlPersist->replace($this->generateUrls($product));
                    } catch (UrlAlreadyExistsException $e) {
                        $output->writeln('<error>' . $e->getMessage() . '</error>');
                        if ($output->getVerbosity() >= OutputInterface::VERBOSITY_VERBOSE) {
                            $output->writeln($e->getTraceAsString());
                        }
                    } catch (Exception $e) {
                        $output->writeln('<error>' . $e->getMessage() . '</error>');
                        if ($output->getVerbosity() >= OutputInterface::VERBOSITY_VERBOSE) {
                            $output->writeln($e->getTraceAsString());
                        }
                    }
                }
                $progressBar->advance();
            }
        }
        $progressBar->finish();
        $output->writeln("");
        $output->writeln('<info>Done</info>');
        return Cli::RETURN_SUCCESS;
    }

    /**
     * Generate product urls.
     *
     * @param Product $product
     *
     * @return array|UrlRewrite[]
     */
    private function generateUrls(Product $product)
    {
        $storeId = $product->getStoreId();

        $productCategories = $product->getCategoryCollection()
            ->addAttributeToSelect('url_key')
            ->addAttributeToSelect('url_path');

        $urls = $this->rewriteGenerator->isGlobalScope($storeId)
            ? $this->rewriteGenerator->generateForGlobalScope($productCategories, $product)
            : $this->rewriteGenerator->generateForSpecificStoreView($storeId, $productCategories, $product);

        return $urls;
    }
}
