<?php


namespace Mirasvit\Kb\Model\Data;

use Mirasvit\Kb\Api\Data\ArticleInterface;

class Article extends \Magento\Framework\Api\AbstractExtensibleObject implements ArticleInterface
{

    /**
     * @return mixed
     */
    public function getArticleId() {
        return $this->_get(self::ARTICLE_ID);

    }

    /**
     * @param mixed $article_id
     */
    public function setArticleId($article_id) {
        return $this->setData(self::ARTICLE_ID, $article_id);
    }

    /**
     * @return mixed
     */
    public function getName() {
        return $this->_get(self::NAME);

    }

    /**
     * @param mixed $name
     */
    public function setName($name) {
        return $this->setData(self::NAME, $name);
    }

    /**
     * @return mixed
     */
    public function getText() {
        return $this->_get(self::TEXT);

    }

    /**
     * @param mixed $text
     */
    public function setText($text) {
        return $this->setData(self::TEXT, $text);
    }

    /**
     * @return mixed
     */
    public function getUrlKey() {
        return $this->_get(self::URL_KEY);

    }

    /**
     * @param mixed $url_key
     */
    public function setUrlKey($url_key) {
        return $this->setData(self::URL_KEY, $url_key);
    }

    /**
     * @return mixed
     */
    public function getMetaTitle() {
        return $this->_get(self::META_TITLE);

    }

    /**
     * @param mixed $meta_title
     */
    public function setMetaTitle($meta_title) {
        return $this->setData(self::META_TITLE, $meta_title);
    }

    /**
     * @return mixed
     */
    public function getMetaKeywords() {
        return $this->_get(self::META_KEYWORDS);

    }

    /**
     * @param mixed $meta_keywords
     */
    public function setMetaKeywords($meta_keywords) {
        return $this->setData(self::META_KEYWORDS, $meta_keywords);
    }

    /**
     * @return mixed
     */
    public function getMetaDescription() {
        return $this->_get(self::META_DESCRIPTION);

    }

    /**
     * @param mixed $meta_description
     */
    public function setMetaDescription($meta_description) {
        return $this->setData(self::META_DESCRIPTION, $meta_description);
    }

    /**
     * @return mixed
     */
    public function getIsActive() {
        return $this->_get(self::IS_ACTIVE);

    }

    /**
     * @param mixed $is_active
     */
    public function setIsActive($is_active) {
        return $this->setData(self::IS_ACTIVE, $is_active);
    }

    /**
     * @return mixed
     */
    public function getUserId() {
        return $this->_get(self::USER_ID);

    }

    /**
     * @param mixed $user_id
     */
    public function setUserId($user_id) {
        return $this->setData(self::USER_ID, $user_id);
    }

    /**
     * @return mixed
     */
    public function getVotesSum() {
        return $this->_get(self::VOTES_SUM);

    }

    /**
     * @param mixed $votes_sum
     */
    public function setVotesSum($votes_sum) {
        return $this->setData(self::VOTES_SUM, $votes_sum);
    }

    /**
     * @return mixed
     */
    public function getVotesNum() {
        return $this->_get(self::VOTES_NUM);

    }

    /**
     * @param mixed $votes_num
     */
    public function setVotesNum($votes_num) {
        return $this->setData(self::VOTES_NUM, $votes_num);
    }

    /**
     * @return mixed
     */
    public function getCreatedAt() {
        return $this->_get(self::CREATED_AT);

    }

    /**
     * @param mixed $created_at
     */
    public function setCreatedAt($created_at) {
        return $this->setData(self::CREATED_AT, $created_at);
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt() {
        return $this->_get(self::UPDATED_AT);

    }

    /**
     * @param mixed $updated_at
     */
    public function setUpdatedAt($updated_at) {
        return $this->setData(self::UPDATED_AT, $updated_at);
    }

    /**
     * @return mixed
     */
    public function getPosition() {
        return $this->_get(self::POSITION);

    }

    /**
     * @param mixed $position
     */
    public function setPosition($position) {
        return $this->setData(self::POSITION, $position);
    }

    /**
     * @return mixed
     */
    public function getArticleHeaderImage() {
        return $this->_get(self::ARTICLE_HEADER_IMAGE);

    }

    /**
     * @param mixed $article_header_image
     */
    public function setArticleHeaderImage($article_header_image) {
        return $this->setData(self::ARTICLE_HEADER_IMAGE, $article_header_image);
    }

    /**
     * @return mixed
     */
    public function getArticleTemplateOption() {
        return $this->_get(self::ARTICLE_TEMPLATE_OPTION);

    }

    /**
     * @param mixed $article_template_option
     */
    public function setArticleTemplateOption($article_template_option) {
        return $this->setData(self::ARTICLE_TEMPLATE_OPTION, $article_template_option);
    }

    /**
     * @return mixed
     */
    public function getRelatedArticleIds() {
        return $this->_get(self::RELATED_ARTICLE_IDS);

    }

    /**
     * @param mixed $related_article_ids
     */
    public function setRelatedArticleIds($related_article_ids) {
        return $this->setData(self::RELATED_ARTICLE_IDS, $related_article_ids);
    }

    /**
     * @return mixed
     */
    public function getShowAuthor() {
        return $this->_get(self::SHOW_AUTHOR);

    }

    /**
     * @param mixed $show_author
     */
    public function setShowAuthor($show_author) {
        return $this->setData(self::SHOW_AUTHOR, $show_author);
    }



    public function getAllData() {
        return $this->_data;
    }
    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \Mirasvit\Kb\Api\Data\ArticleExtensionInterface|null
     */
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * Set an extension attributes object.
     * @param \Mirasvit\Kb\Api\Data\ArticleExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Mirasvit\Kb\Api\Data\ArticleExtensionInterface $extensionAttributes
    ) {
        return $this->_setExtensionAttributes($extensionAttributes);
    }

}
