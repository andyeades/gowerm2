<?php

namespace Mirasvit\Kb\Api\Data;

interface ArticleCategoryInterface extends \Magento\Framework\Api\ExtensibleDataInterface {

    const ARTICLE_CATEGORY_ID = 'article_category_id';
    const AC_ARTICLE_ID = 'ac_article_id';
    const AC_CATEGORY_ID = 'ac_category_id';


    /**
     * @return mixed
     */
    public function getArticleCategoryId();

    /**
     * @param mixed $article_category_id
     */
    public function setArticleCategoryId($article_category_id);

    /**
     * @return mixed
     */
    public function getAcArticleId();

    /**
     * @param mixed $ac_article_id
     */
    public function setAcArticleId($ac_article_id);

    /**
     * @return mixed
     */
    public function getAcCategoryId();

    /**
     * @param mixed $ac_category_id
     */
    public function setAcCategoryId($ac_category_id);

    /**
     * @return mixed
     */
    public function getAllData();
    /**
     * Retrieve existing extension attributes object or create a new one.
     *
     * @return \Mirasvit\Kb\Api\Data\ArticleCategoryExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     *
     * @param \Mirasvit\Kb\Api\Data\ArticleCategoryExtensionInterface $extensionAttributes
     *
     * @return $this
     */
    public function setExtensionAttributes(
        \Mirasvit\Kb\Api\Data\ArticleCategoryExtensionInterface $extensionAttributes
    );
}
