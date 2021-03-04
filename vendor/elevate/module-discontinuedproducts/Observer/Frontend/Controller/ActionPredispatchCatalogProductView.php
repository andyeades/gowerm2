<?php declare(strict_types=1);


namespace Elevate\Discontinuedproducts\Observer\Frontend\Controller;


use Magento\Framework\App\Response\RedirectInterface as RedirectFactory;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\Registry;

class ActionPredispatchCatalogProductView implements \Magento\Framework\Event\ObserverInterface
{

    /**
     * @var Registry
     */
    private $registry;

    /**
     * @var ManagerInterface
     */
    private $messageManager;

    /**
     * @var RedirectFactory
     */
    private $resultRedirectFactory;

    /**
     * @var
     */
    protected $productTypeConfigurable;
    /**
     * @var
     */
    protected $productRepository;
    /**
     * @var
     */
    protected $storeManager;

    /**
     * @var
     */
    protected $categoryRepository;

    protected $redirect;

    protected $redirectInterface;
    /**
     * @param Registry $registry
     * @param ManagerInterface $messageManager
     * @param RedirectFactory $resultRedirectFactory
     * @param \Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable $productTypeConfigurable
     * @param \Magento\Catalog\Model\ProductRepository $productRepository
     * @param \Magento\Catalog\Model\CategoryRepository $categoryRepository
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager,
     *  @param \Magento\Framework\App\Response\Http $redirect,
     * @param \Magento\Framework\App\Response\RedirectInterface $redirectInterface
     */
    public function __construct(
        Registry $registry,
        ManagerInterface $messageManager,
        RedirectFactory $resultRedirectFactory,
        \Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable $productTypeConfigurable,
        \Magento\Catalog\Model\ProductRepository $productRepository,
        \Magento\Catalog\Model\CategoryRepository $categoryRepository,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\Response\Http $redirect,
        \Magento\Framework\App\Response\RedirectInterface $redirectInterface

    ) {
        $this->registry = $registry;
        $this->messageManager = $messageManager;
        $this->resultRedirectFactory = $resultRedirectFactory;
        $this->productTypeConfigurable = $productTypeConfigurable;
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
        $this->storeManager = $storeManager;
        $this->redirect = $redirect;
        $this->redirectInterface = $redirectInterface;

    }

    /**
     * Execute observer
     *
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(
        \Magento\Framework\Event\Observer $observer
    ) {
        $request = $observer->getEvent()->getRequest();
        $controller = $observer->getControllerAction();
        $product_id = $request->getParam('id');
        $base_url = $this->storeManager->getStore()->getBaseUrl();
        // Configurable? or Simple?

        //Will be Either the Configurable, or simple - depending on which has been selected

        $product = $this->productRepository->getById($product_id, false, $this->storeManager->getStore()->getId());
        if ($product->getTypeId() == \Magento\Catalog\Model\Product\Type::TYPE_SIMPLE) {
           // echo "Simple Product<br>";
        }
        $product_name = $product->getName();
        $product_discontinued_flag = $product->getProductDiscontinued();

        if (!empty($product_discontinued_flag)) {
            //echo 'Product Discontinued';

            $discontinued_action = $product->getDiscontinuedAction();

            if (!empty($discontinued_action)) {
                $discontinued_action_text = $product->getAttributeText('discontinued_action');
                $discontinued_action_value = $product->getDiscontinuedActionValue();

                //echo $discontinued_action_text.'<br>';
                //echo $discontinued_action_value.'<br>';

                if (strcmp($discontinued_action_text, 'Stay on current page') === 0) {

                    // Do Nothing but will need to disable some stuff on product page.

                } else if (strcmp($discontinued_action_text, 'Redirect to Category') === 0) {


                    // Can Either Be a Category Id or a URL (the URL is relative to the root)
                    $categoryUrl = $this->getCategoryUrl($discontinued_action_value);

                    if (empty($categoryUrl)) {
                        $url = $base_url . $discontinued_action_value;
                    } else {
                        $url = $base_url . $categoryUrl;
                    }

                    $this->setUrl($product_name.' is no longer available, perhaps you might be interested in these similar products', $url);

                } else if (strcmp($discontinued_action_text, 'Redirect to Product') === 0) {

                    //Get By Sku
                    $redirect_product = $this->productRepository->get($discontinued_action_value);

                    $redirect_product_url = $redirect_product->getProductUrl();


                    $url = $redirect_product_url;
                    $this->setUrl($product_name.' is no longer available, perhaps you might be interested in this product', $url);

                } else if (strcmp($discontinued_action_text, 'Redirect to Page/Url') === 0) {

                    // Possibly need to Change this so it isn't root relative (i.e. subdomains etc).

                    $url = $base_url . $discontinued_action_value;

                    $this->setUrl($product_name.' is no longer available', $url);
                }
            }
        }
    }

    public function getCategoryUrl($category_id) {
        // Is Numeric Check

        if (is_numeric($category_id)) {
            $category = $this->categoryRepository->get($category_id);

            $category_url = $category->getUrlPath();

            return $category_url;

        } else {

            return NULL;
        }

    }

    public function getProductUrl($product_id) {

    }
    public function setUrl($message,$url) {

        $this->messageManager->addErrorMessage(__($message));
        $this->redirect->setRedirect($url,301);
        $this->redirect->sendResponse();
    }

}

