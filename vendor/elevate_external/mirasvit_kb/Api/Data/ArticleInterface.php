<?php

namespace Mirasvit\Kb\Api\Data;

interface ArticleInterface extends \Magento\Framework\Api\ExtensibleDataInterface {

    const ARTICLE_ID = 'article_id';
    const NAME = 'name';
    const TEXT = 'text';
    const URL_KEY = 'url_key';
    const META_TITLE = 'meta_title';
    const META_KEYWORDS = 'meta_keywords';
    const META_DESCRIPTION = 'meta_description';
    const IS_ACTIVE = 'is_active';
    const USER_ID = 'user_id';
    const VOTES_SUM = 'votes_sum';
    const VOTES_NUM = 'votes_num';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    const POSITION = 'position';
    const ARTICLE_HEADER_IMAGE = 'article_header_image';
    const ARTICLE_TEMPLATE_OPTION = 'article_template_option';
    const RELATED_ARTICLE_IDS = 'related_article_ids';
    const SHOW_AUTHOR = 'show_author';

    /**
     * @return mixed
     */
    public function getArticleId();

    /**
     * @param mixed $article_id
     */
    public function setArticleId($article_id);

    /**
     * @return mixed
     */
    public function getName();

    /**
     * @param mixed $name
     */
    public function setName($name);

    /**
     * @return mixed
     */
    public function getText();

    /**
     * @param mixed $text
     */
    public function setText($text);

    /**
     * @return mixed
     */
    public function getUrlKey();

    /**
     * @param mixed $url_key
     */
    public function setUrlKey($url_key);

    /**
     * @return mixed
     */
    public function getMetaTitle();

    /**
     * @param mixed $meta_title
     */
    public function setMetaTitle($meta_title);

    /**
     * @return mixed
     */
    public function getMetaKeywords();

    /**
     * @param mixed $meta_keywords
     */
    public function setMetaKeywords($meta_keywords);

    /**
     * @return mixed
     */
    public function getMetaDescription();

    /**
     * @param mixed $meta_description
     */
    public function setMetaDescription($meta_description);

    /**
     * @return mixed
     */
    public function getIsActive();

    /**
     * @param mixed $is_active
     */
    public function setIsActive($is_active);

    /**
     * @return mixed
     */
    public function getUserId();

    /**
     * @param mixed $user_id
     */
    public function setUserId($user_id);

    /**
     * @return mixed
     */
    public function getVotesSum();

    /**
     * @param mixed $votes_sum
     */
    public function setVotesSum($votes_sum);

    /**
     * @return mixed
     */
    public function getVotesNum();

    /**
     * @param mixed $votes_num
     */
    public function setVotesNum($votes_num);

    /**
     * @return mixed
     */
    public function getCreatedAt();

    /**
     * @param mixed $created_at
     */
    public function setCreatedAt($created_at);

    /**
     * @return mixed
     */
    public function getUpdatedAt();

    /**
     * @param mixed $updated_at
     */
    public function setUpdatedAt($updated_at);

    /**
     * @return mixed
     */
    public function getPosition();

    /**
     * @param mixed $position
     */
    public function setPosition($position);

    /**
     * @return mixed
     */
    public function getArticleHeaderImage();

    /**
     * @param mixed $article_header_image
     */
    public function setArticleHeaderImage($article_header_image);

    /**
     * @return mixed
     */
    public function getArticleTemplateOption();

    /**
     * @param mixed $article_template_option
     */
    public function setArticleTemplateOption($article_template_option);

    /**
     * @return mixed
     */
    public function getRelatedArticleIds();

    /**
     * @param mixed $related_article_ids
     */
    public function setRelatedArticleIds($related_article_ids);

    /**
     * @return mixed
     */
    public function getShowAuthor();

    /**
     * @param mixed $show_author
     */
    public function setShowAuthor($show_author);

    /**
     * @return mixed
     */
    public function getAllData();
    /**
     * Retrieve existing extension attributes object or create a new one.
     *
     * @return \Mirasvit\Kb\Api\Data\ArticleExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     *
     * @param \Mirasvit\Kb\Api\Data\ArticleExtensionInterface $extensionAttributes
     *
     * @return $this
     */
    public function setExtensionAttributes(
        \Mirasvit\Kb\Api\Data\ArticleExtensionInterface $extensionAttributes
    );
}
