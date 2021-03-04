<?php

namespace Mirasvit\Kb\Model\Data;

use Mirasvit\Kb\Api\Data\ArticleCategoryInterface;

class ArticleCategory extends \Magento\Framework\Api\AbstractExtensibleObject implements ArticleCategoryInterface
{
    /**
     * @return mixed
     */
    public function getArticleCategoryId() {
        return $this->_get(self::ARTICLE_CATEGORY_ID);

    }

    /**
     * @param mixed $article_category_id
     */
    public function setArticleCategoryId($article_category_id) {
        return $this->setData(self::ARTICLE_CATEGORY_ID, $article_category_id);
    }

    /**
     * @return mixed
     */
    public function getAcArticleId() {
        return $this->_get(self::AC_ARTICLE_ID);

    }

    /**
     * @param mixed $ac_article_id
     */
    public function setAcArticleId($ac_article_id) {
        return $this->setData(self::AC_ARTICLE_ID, $ac_article_id);
    }

    /**
     * @return mixed
     */
    public function getAcCategoryId() {
        return $this->_get(self::AC_CATEGORY_ID);

    }

    /**
     * @param mixed $ac_category_id
     */
    public function setAcCategoryId($ac_category_id) {
        return $this->setData(self::AC_CATEGORY_ID, $ac_category_id);
    }



    public function getAllData() {
        return $this->_data;
    }
    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \Mirasvit\Kb\Api\Data\ArticleCategoryExtensionInterface|null
     */
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * Set an extension attributes object.
     * @param \Mirasvit\Kb\Api\Data\ArticleCategoryExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Mirasvit\Kb\Api\Data\ArticleCategoryExtensionInterface $extensionAttributes
    ) {
        return $this->_setExtensionAttributes($extensionAttributes);
    }

}
