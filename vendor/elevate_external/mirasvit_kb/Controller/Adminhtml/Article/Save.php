<?php
/**
 * Mirasvit
 *
 * This source file is subject to the Mirasvit Software License, which is available at https://mirasvit.com/license/.
 * Do not edit or add to this file if you wish to upgrade the to newer versions in the future.
 * If you wish to customize this module for your needs.
 * Please refer to http://www.magentocommerce.com for more information.
 *
 * @category  Mirasvit
 * @package   mirasvit/module-kb
 * @version   1.0.69
 * @copyright Copyright (C) 2020 Mirasvit (https://mirasvit.com/)
 */



namespace Mirasvit\Kb\Controller\Adminhtml\Article;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Store\Model\StoreFactory;
use Mirasvit\Kb\Helper\Image;
use Mirasvit\Kb\Model\ArticleFactory;

class Save extends \Mirasvit\Kb\Controller\Adminhtml\Article
{
    /**
     * @var Image
     */
    protected $imageHelper;

    /**
     * @var StoreFactory
     */
    protected $storeFactory;
    /**
     * @var \Magento\Backend\App\Action\Context
     */
    private $context;
    /**
     * @var \Magento\Framework\Registry
     */
    private $registry;
    /**
     * @var \Mirasvit\Kb\Model\ArticleFactory
     */
    protected $articleFactory;
    /**
     * @var \Magento\Backend\Model\Session
     */
    protected $backendSession;
    /**
     * @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface
     */
    private $localeDate;
    /**
     * @var \Mirasvit\Kb\Helper\Data
     */
    protected $kbData;
    /**
     * @var \Mirasvit\Kb\Helper\Tag
     */
    protected $kbTag;
    /**
     * @var \Mirasvit\Kb\Model\CategoryFactory
     */
    private $categoryFactory;
    /**
     * @var \Mirasvit\Kb\Api\Service\Article\ArticleManagementInterface
     */
    protected $articleManagement;

    /**
     * Save constructor.
     *
     * @param Context $context
     * @param Registry $registry
     * @param ArticleFactory $articleFactory
     * @param Image $imageHelper,
     * @param \Mirasvit\Kb\Api\Service\Article\ArticleManagementInterface $articleManagement
     * @param \Mirasvit\Kb\Model\ArticleFactory $articleFactory
     * @param \Mirasvit\Kb\Model\CategoryFactory $categoryFactory
     * @param \Mirasvit\Kb\Helper\Tag $kbTag
     * @param \Mirasvit\Kb\Helper\Data $kbData
     * @param \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Backend\App\Action\Context $context
     */
    public function __construct(
        Context $context,
        Registry $registry,
        ArticleFactory $articleFactory,
        Image $imageHelper,
        \Mirasvit\Kb\Api\Service\Article\ArticleManagementInterface $articleManagement,
        \Mirasvit\Kb\Model\CategoryFactory $categoryFactory,
        \Mirasvit\Kb\Helper\Tag $kbTag,
        \Mirasvit\Kb\Helper\Data $kbData,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate


    ) {
        $this->articleManagement = $articleManagement;
        $this->articleFactory    = $articleFactory;
        $this->categoryFactory   = $categoryFactory;
        $this->kbTag             = $kbTag;
        $this->kbData            = $kbData;
        $this->localeDate        = $localeDate;
        $this->registry          = $registry;
        $this->context           = $context;
        $this->backendSession    = $context->getSession();
        $this->resultFactory     = $context->getResultFactory();

        $this->imageHelper = $imageHelper;

        parent::__construct($articleManagement, $articleFactory,$categoryFactory, $kbTag, $kbData, $localeDate, $registry, $context);
    }
    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function execute()
    {
        if ($data = $this->getRequest()->getParams()) {
            $model = $this->_initModel();


            if (!empty($data['article_header_image'])) {
                try {
                    $this->imageHelper->uploadImage($data, 'article_header_image', Image::TEMPLATE_MEDIA_TYPE_ARTICLE, $data['article_header_image']);
                } catch (Exception $exception) {
                    $data['article_header_image'] = isset($data['article_header_image']['value']) ? $data['article_header_image']['value'] : '';
                }
            }
            if ($this->getRequest()->getParam('article_header_image')['delete']) {
                $data['article_header_image'] = '';
            }
            // set data
            if (!empty($data)) {
                $model->addData($data);
            }




            if (!empty($data['categories'])) {
                $data['category_ids'] = explode(',', $data['categories']);
                if (!is_array($data['store_ids'])) {
                    $data['store_ids'] = explode(',', $data['store_ids']);
                }

                $categoryIds = [];
                $articleStoreIds = $this->articleManagement->getAvailableStores($model, $data['category_ids']);

                if (empty($data['store_ids'])) {
                    $data['store_ids'] = $articleStoreIds;
                } else {
                    if (in_array(0, $articleStoreIds)) { // if for all stores
                        $categoryIds = $data['store_ids'];
                    } elseif (in_array(0, $data['store_ids'])) {
                        $categoryIds = [0];
                    } else {
                        foreach ($data['store_ids'] as $key => $storeId) {
                            if (in_array($storeId, $articleStoreIds)) {
                                $categoryIds[] = $data['store_ids'][$key];
                            }
                        }
                    }
                    $data['store_ids'] = array_unique($categoryIds);
                }
            }

            $model->addData($data);

            //@todo move to model _afterSave
            if (!empty ($model->getArticleId())) {
                $this->kbTag->removeUnusedTags($model, $data['tags']);
            }
            $this->kbTag->setTags($model, $data['tags']);

            //@todo kbHelper
            $this->kbData->setRating($model);

            try {
                $model->save();
                $this->messageManager->addSuccessMessage(__('Article was successfully saved'));
                $this->backendSession->setFormData(false);

                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', ['id' => $model->getId()]);

                    return;
                }
                $this->_redirect('*/*/');

                return;
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                $this->backendSession->setFormData($data);
                $this->_redirect('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);

                return;
            }
        }
        $this->messageManager->addErrorMessage(__('Unable to find article to save'));
        $this->_redirect('*/*/');
    }
    /**
     * @param $author
     * @param $data
     *
     * @return $this
     */
    public function prepareData($author, $data)
    {
        // upload image


        return $this;
    }
}
