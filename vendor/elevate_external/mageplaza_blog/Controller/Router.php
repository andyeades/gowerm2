<?php
/**
 * Mageplaza
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Mageplaza.com license that is
 * available through the world-wide-web at this URL:
 * https://www.mageplaza.com/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Mageplaza
 * @package     Mageplaza_Blog
 * @copyright   Copyright (c) Mageplaza (https://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */

namespace Mageplaza\Blog\Controller;

use Magento\Framework\App\Action\Forward;
use Magento\Framework\App\ActionFactory;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\RouterInterface;
use Magento\Framework\Url;
use Mageplaza\Blog\Helper\Data;

/**
 * Class Router
 * @package Mageplaza\Blog\Controller
 */
class Router implements RouterInterface
{
    const URL_SUFFIX_RSS_XML = '.xml';

    /**
     * @var \Magento\Framework\App\ActionFactory
     */
    public $actionFactory;

    /**
     * @var \Mageplaza\Blog\Helper\Data
     */
    public $helper;

    /**
     * @param \Magento\Framework\App\ActionFactory $actionFactory
     * @param \Mageplaza\Blog\Helper\Data $helper
     */
    public function __construct(
        ActionFactory $actionFactory,
        Data $helper
    )
    {
        $this->actionFactory = $actionFactory;
        $this->helper        = $helper;
    }

    /**
     * @param \Magento\Framework\App\RequestInterface $request
     * @return \Magento\Framework\App\ActionInterface|null
     */
    public function match(RequestInterface $request)
    {

      //  https://m2.happybeds.co.uk/blog/category/happy-beds-news/

        $url = $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];


        if (strpos($url,'blog') !== false) {

        } else {
        
            return null;
        }



        if (!$this->helper->isEnabled()) {
            return null;
        }

        $rssAction  = "rss.xml";
        $identifier = trim($request->getPathInfo(), '/');

        $urlSuffix  = $this->helper->getUrlSuffix();


        $routePath = explode('/', $identifier);
        $routeSize = sizeof($routePath);
        if (!$routeSize || ($routeSize > 3) || (array_shift($routePath) != $this->helper->getRoute())) {
            //  return null;
        }





        $request->setModuleName('mpblog')
                ->setAlias(Url::REWRITE_REQUEST_PATH_ALIAS, $identifier . $urlSuffix);
        $controller = array_shift($routePath);

        $url_lookup = str_replace('blog/', '', $identifier);
      //  $url_lookup = str_replace('blog', '', $identifier);
        $url_lookup = ltrim( $url_lookup, '/');

        //load home
        if ((!$controller && 1==2) || $identifier == 'blog') {

            $request->setControllerName('post')
                    ->setActionName('index')
                    ->setPathInfo('/mpblog/post/index')
                    ->setParam('home', 1);


            return $this->actionFactory->create('Magento\Framework\App\Action\Forward');
        }

        //  $action = array_shift($routePath) ?: 'index';
        $action = array();
        //        $identifier = '';

        //   echo Data::TYPE_URLS."<br />";

        $type_lookup = $this->helper->getObjectByParam($url_lookup, 'url_key', 'urls');
        //print_r($type_lookup);

        if($type_lookup){
            $controller = $type_lookup->getType();
            $lookup_id = $type_lookup->getLinkId();
        }

        //post fallabck

        $url_lookup = str_replace('.html', '', $url_lookup);

        if(!is_numeric($lookup_id)){

            //try by post #replace with indexed sections / allow root with priorities
            $type_lookup2 = $this->helper->getObjectByParam($url_lookup, 'url_key');

            $controller = 'post';
            $lookup_id = $type_lookup2->getPostId();

        }


        if(!is_numeric($lookup_id)){
         //   echo "TWO";

            //try by post #replace with indexed sections / allow root with priorities
            $url_lookup = str_replace('category/', '', $url_lookup);
          //  echo "<br>$url_lookup";
            $type_lookup2 = $this->helper->getObjectByParam($url_lookup, 'url_key', Data::TYPE_CATEGORY);


            $controller = Data::TYPE_CATEGORY;
            $lookup_id = $type_lookup2->getCategoryId();
          //  echo $lookup_id;

        }



        if(!is_numeric($lookup_id)){

            return null;
        }


        switch ($controller) {
            case 'post':
                if (!in_array($action, ['index', 'rss'])) {

                    //$post = $this->helper->getObjectByParam($action, 'url_key');
                    $request->setParam('id', $lookup_id);
                    $identifier = $lookup_id;
                    $action = 'view';
                }
                break;
            case 'category':
                if (!in_array($action, ['index', 'rss'])) {
                    //  $category = $this->helper->getObjectByParam($action, 'url_key', Data::TYPE_CATEGORY);
                    // echo "Y";
                    //  echo $lookup_id;
                    //  exit;
                    $request->setParam('id', $lookup_id);
                    $identifier = $lookup_id;
                    $action = 'view';
                }
                break;
            case 'tag':
                $tag = $this->helper->getObjectByParam($action, 'url_key', Data::TYPE_TAG);
                $request->setParam('id', $tag->getId());
                $identifier = $tag->getId();
                $action = 'view';
                break;
            case 'topic':
                $topic = $this->helper->getObjectByParam($action, 'url_key', Data::TYPE_TOPIC);
                $request->setParam('id', $topic->getId());
                $identifier = $topic->getId();
                $action = 'view';
                break;
            case 'sitemap':
                $action = 'index';
                break;
            case 'author':
                $author = $this->helper->getObjectByParam($action, 'url_key', Data::TYPE_AUTHOR);
                $request->setParam('id', $author->getId());
                $identifier = $author->getId();
                $action = 'view';
                break;
            case 'month':
                $request->setParam('month_key', $action);
                $identifier = '';
                $action = 'view';
                break;
            default:
                $post = $this->helper->getObjectByParam($controller, 'url_key');
                $request->setParam('id', $post->getId());
                $identifier = $post->getId();

                $controller = 'post';
                $action     = 'view';
        }

        $request->setControllerName($controller)
                ->setActionName($action)
                ->setPathInfo('/mpblog/' . $controller . '/' . $action);
        $request->setAlias(\Magento\Framework\Url::REWRITE_REQUEST_PATH_ALIAS, $identifier);
        return $this->actionFactory->create(\Magento\Framework\App\Action\Forward::class);

    }

    /**
     * check if action = rss
     * @param $identifier
     * @return bool
     */
    public function isRss($identifier)
    {
        $routePath = explode('/', $identifier);
        $routePath = array_pop($routePath);
        $routePath = explode('.', $routePath);
        $action    = array_shift($routePath);

        return ($action == "rss");
    }

    /**
     * @param $identifier
     * @return bool|null|string
     */
    public function checkRssIdentifier($identifier)
    {
        $length = strlen(self::URL_SUFFIX_RSS_XML);
        if (substr($identifier, -$length) == self::URL_SUFFIX_RSS_XML && $this->isRss($identifier)) {
            $identifier = substr($identifier, 0, strlen($identifier) - $length);

            return $identifier;
        } else {
            return null;
        }
    }
}
