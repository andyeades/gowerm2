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

namespace Mageplaza\Blog\Block;

use Exception;
use Magento\Cms\Model\Template\FilterProvider;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\Url;
use Magento\Framework\Encryption\EncryptorInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Phrase;
use Magento\Framework\Registry;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Framework\View\Design\Theme\ThemeProviderInterface;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Store\Model\StoreManagerInterface;
use Mageplaza\Blog\Block\Adminhtml\Post\Edit\Tab\Renderer\Category as CategoryOptions;
use Mageplaza\Blog\Block\Adminhtml\Post\Edit\Tab\Renderer\Tag as TagOptions;
use Mageplaza\Blog\Block\Adminhtml\Post\Edit\Tab\Renderer\Topic as TopicOptions;
use Mageplaza\Blog\Helper\Data as HelperData;
use Mageplaza\Blog\Helper\Image;
use Mageplaza\Blog\Model\CategoryFactory;
use Mageplaza\Blog\Model\CommentFactory;
use Mageplaza\Blog\Model\Config\Source\AuthorStatus;
use Mageplaza\Blog\Model\LikeFactory;
use Mageplaza\Blog\Model\PostFactory;
use Mageplaza\Blog\Model\PostLikeFactory;

/**
 * Class Frontend
 *
 * @package Mageplaza\Blog\Block
 */
class Frontend extends Template
{
    /**
     * @var FilterProvider
     */
    public $filterProvider;

    /**
     * @var HelperData
     */
    public $helperData;

    /**
     * @var StoreManagerInterface
     */
    public $store;

    /**
     * @var CommentFactory
     */
    public $cmtFactory;

    /**
     * @var LikeFactory
     */
    public $likeFactory;

    /**
     * @var CustomerRepositoryInterface
     */
    public $customerRepository;

    /**
     * @var
     */
    public $commentTree;

    /**
     * @var Registry
     */
    protected $coreRegistry;

    /**
     * @var DateTime
     */
    public $dateTime;

    /**
     * @var PostFactory
     */
    protected $postFactory;

    /**
     * @var CategoryFactory
     */
    protected $categoryFactory;

    /**
     * @var Url
     */
    protected $customerUrl;

    /**
     * @var CategoryOptions
     */
    protected $categoryOptions;

    /**
     * @var TopicOptions
     */
    protected $topicOptions;

    /**
     * @var TagOptions
     */
    protected $tagOptions;

    /**
     * @var PostLikeFactory
     */
    protected $postLikeFactory;

    /**
     * @var AuthorStatus
     */
    protected $authorStatusType;

    /**
     * @var ThemeProviderInterface
     */
    protected $themeProvider;

    /**
     * @var EncryptorInterface
     */
    public $enc;

    /**
     * Frontend constructor.
     *
     * @param Context $context
     * @param FilterProvider $filterProvider
     * @param CommentFactory $commentFactory
     * @param LikeFactory $likeFactory
     * @param CustomerRepositoryInterface $customerRepository
     * @param Registry $coreRegistry
     * @param HelperData $helperData
     * @param Url $customerUrl
     * @param CategoryFactory $categoryFactory
     * @param PostFactory $postFactory
     * @param DateTime $dateTime
     * @param PostLikeFactory $postLikeFactory
     * @param CategoryOptions $category
     * @param TopicOptions $topic
     * @param TagOptions $tag
     * @param ThemeProviderInterface $themeProvider
     * @param EncryptorInterface $enc
     * @param AuthorStatus $authorStatus
     * @param array $data
     */
    public function __construct(
        Context $context,
        FilterProvider $filterProvider,
        CommentFactory $commentFactory,
        LikeFactory $likeFactory,
        CustomerRepositoryInterface $customerRepository,
        Registry $coreRegistry,
        HelperData $helperData,
        Url $customerUrl,
        CategoryFactory $categoryFactory,
        PostFactory $postFactory,
        DateTime $dateTime,
        PostLikeFactory $postLikeFactory,
        CategoryOptions $category,
        TopicOptions $topic,
        TagOptions $tag,
        ThemeProviderInterface $themeProvider,
        EncryptorInterface $enc,
        AuthorStatus $authorStatus,
        array $data = []
    ) {
        $this->filterProvider     = $filterProvider;
        $this->cmtFactory         = $commentFactory;
        $this->likeFactory        = $likeFactory;
        $this->customerRepository = $customerRepository;
        $this->helperData         = $helperData;
        $this->coreRegistry       = $coreRegistry;
        $this->dateTime           = $dateTime;
        $this->categoryFactory    = $categoryFactory;
        $this->postFactory        = $postFactory;
        $this->customerUrl        = $customerUrl;
        $this->postLikeFactory    = $postLikeFactory;
        $this->categoryOptions    = $category;
        $this->topicOptions       = $topic;
        $this->tagOptions         = $tag;
        $this->authorStatusType   = $authorStatus;
        $this->themeProvider      = $themeProvider;
        $this->store              = $context->getStoreManager();
        $this->enc                = $enc;

        parent::__construct($context, $data);
    }

    /**
     * @return HelperData
     */
    public function getBlogHelper()
    {
        return $this->helperData;
    }

    /**
     * @return bool
     */
    public function isBlogEnabled()
    {
        return $this->helperData->isEnabled();
    }

    public function wpautop( $pee, $br = true ) {
        $pre_tags = array();

        if ( trim( $pee ) === '' ) {
            return '';
        }

        // Just to make things a little easier, pad the end.
        $pee = $pee . "\n";

        /*
         * Pre tags shouldn't be touched by autop.
         * Replace pre tags with placeholders and bring them back after autop.
         */
        if ( strpos( $pee, '<pre' ) !== false ) {
            $pee_parts = explode( '</pre>', $pee );
            $last_pee  = array_pop( $pee_parts );
            $pee       = '';
            $i         = 0;

            foreach ( $pee_parts as $pee_part ) {
                $start = strpos( $pee_part, '<pre' );

                // Malformed HTML?
                if ( false === $start ) {
                    $pee .= $pee_part;
                    continue;
                }

                $name              = "<pre wp-pre-tag-$i></pre>";
                $pre_tags[ $name ] = substr( $pee_part, $start ) . '</pre>';

                $pee .= substr( $pee_part, 0, $start ) . $name;
                $i++;
            }

            $pee .= $last_pee;
        }
        // Change multiple <br>'s into two line breaks, which will turn into paragraphs.
        $pee = preg_replace( '|<br\s*/?>\s*<br\s*/?>|', "\n\n", $pee );

        $allblocks = '(?:table|thead|tfoot|caption|col|colgroup|tbody|tr|td|th|div|dl|dd|dt|ul|ol|li|pre|form|map|area|blockquote|address|math|style|p|h[1-6]|hr|fieldset|legend|section|article|aside|hgroup|header|footer|nav|figure|figcaption|details|menu|summary)';

        // Add a double line break above block-level opening tags.
        $pee = preg_replace( '!(<' . $allblocks . '[\s/>])!', "\n\n$1", $pee );

        // Add a double line break below block-level closing tags.
        $pee = preg_replace( '!(</' . $allblocks . '>)!', "$1\n\n", $pee );

        // Add a double line break after hr tags, which are self closing.
        $pee = preg_replace( '!(<hr\s*?/?>)!', "$1\n\n", $pee );

        // Standardize newline characters to "\n".
        $pee = str_replace( array( "\r\n", "\r" ), "\n", $pee );

        // Find newlines in all elements and add placeholders.
        $pee = self::wp_replace_in_html_tags( $pee, array( "\n" => ' <!-- wpnl --> ' ) );

        // Collapse line breaks before and after <option> elements so they don't get autop'd.
        if ( strpos( $pee, '<option' ) !== false ) {
            $pee = preg_replace( '|\s*<option|', '<option', $pee );
            $pee = preg_replace( '|</option>\s*|', '</option>', $pee );
        }

        /*
         * Collapse line breaks inside <object> elements, before <param> and <embed> elements
         * so they don't get autop'd.
         */
        if ( strpos( $pee, '</object>' ) !== false ) {
            $pee = preg_replace( '|(<object[^>]*>)\s*|', '$1', $pee );
            $pee = preg_replace( '|\s*</object>|', '</object>', $pee );
            $pee = preg_replace( '%\s*(</?(?:param|embed)[^>]*>)\s*%', '$1', $pee );
        }

        /*
         * Collapse line breaks inside <audio> and <video> elements,
         * before and after <source> and <track> elements.
         */
        if ( strpos( $pee, '<source' ) !== false || strpos( $pee, '<track' ) !== false ) {
            $pee = preg_replace( '%([<\[](?:audio|video)[^>\]]*[>\]])\s*%', '$1', $pee );
            $pee = preg_replace( '%\s*([<\[]/(?:audio|video)[>\]])%', '$1', $pee );
            $pee = preg_replace( '%\s*(<(?:source|track)[^>]*>)\s*%', '$1', $pee );
        }

        // Collapse line breaks before and after <figcaption> elements.
        if ( strpos( $pee, '<figcaption' ) !== false ) {
            $pee = preg_replace( '|\s*(<figcaption[^>]*>)|', '$1', $pee );
            $pee = preg_replace( '|</figcaption>\s*|', '</figcaption>', $pee );
        }

        // Remove more than two contiguous line breaks.
        $pee = preg_replace( "/\n\n+/", "\n\n", $pee );

        // Split up the contents into an array of strings, separated by double line breaks.
        $pees = preg_split( '/\n\s*\n/', $pee, -1, PREG_SPLIT_NO_EMPTY );

        // Reset $pee prior to rebuilding.
        $pee = '';

        // Rebuild the content as a string, wrapping every bit with a <p>.
        foreach ( $pees as $tinkle ) {
            $pee .= '<p>' . trim( $tinkle, "\n" ) . "</p>\n";
        }

        // Under certain strange conditions it could create a P of entirely whitespace.
        $pee = preg_replace( '|<p>\s*</p>|', '', $pee );

        // Add a closing <p> inside <div>, <address>, or <form> tag if missing.
        $pee = preg_replace( '!<p>([^<]+)</(div|address|form)>!', '<p>$1</p></$2>', $pee );

        // If an opening or closing block element tag is wrapped in a <p>, unwrap it.
        $pee = preg_replace( '!<p>\s*(</?' . $allblocks . '[^>]*>)\s*</p>!', '$1', $pee );

        // In some cases <li> may get wrapped in <p>, fix them.
        $pee = preg_replace( '|<p>(<li.+?)</p>|', '$1', $pee );

        // If a <blockquote> is wrapped with a <p>, move it inside the <blockquote>.
        $pee = preg_replace( '|<p><blockquote([^>]*)>|i', '<blockquote$1><p>', $pee );
        $pee = str_replace( '</blockquote></p>', '</p></blockquote>', $pee );

        // If an opening or closing block element tag is preceded by an opening <p> tag, remove it.
        $pee = preg_replace( '!<p>\s*(</?' . $allblocks . '[^>]*>)!', '$1', $pee );

        // If an opening or closing block element tag is followed by a closing <p> tag, remove it.
        $pee = preg_replace( '!(</?' . $allblocks . '[^>]*>)\s*</p>!', '$1', $pee );

        // Optionally insert line breaks.
        if ( $br ) {
            // Replace newlines that shouldn't be touched with a placeholder.
            $pee = preg_replace_callback( '/<(script|style|svg).*?<\/\\1>/s', 'self::_autop_newline_preservation_helper', $pee );

            // Normalize <br>
            $pee = str_replace( array( '<br>', '<br/>' ), '<br />', $pee );

            // Replace any new line characters that aren't preceded by a <br /> with a <br />.
            $pee = preg_replace( '|(?<!<br />)\s*\n|', "<br />\n", $pee );

            // Replace newline placeholders with newlines.
            $pee = str_replace( '<WPPreserveNewline />', "\n", $pee );
        }

        // If a <br /> tag is after an opening or closing block tag, remove it.
        $pee = preg_replace( '!(</?' . $allblocks . '[^>]*>)\s*<br />!', '$1', $pee );

        // If a <br /> tag is before a subset of opening or closing block tags, remove it.
        $pee = preg_replace( '!<br />(\s*</?(?:p|li|div|dl|dd|dt|th|pre|td|ul|ol)[^>]*>)!', '$1', $pee );
        $pee = preg_replace( "|\n</p>$|", '</p>', $pee );

        // Replace placeholder <pre> tags with their original content.
        if ( ! empty( $pre_tags ) ) {
            $pee = str_replace( array_keys( $pre_tags ), array_values( $pre_tags ), $pee );
        }

        // Restore newlines in all elements.
        if ( false !== strpos( $pee, '<!-- wpnl -->' ) ) {
            $pee = str_replace( array( ' <!-- wpnl --> ', '<!-- wpnl -->' ), "\n", $pee );
        }

        return $pee;

    }

    public function get_html_split_regex() {
        static $regex;

        if ( ! isset( $regex ) ) {
            // phpcs:disable Squiz.Strings.ConcatenationSpacing.PaddingFound -- don't remove regex indentation
            $comments =
                '!'             // Start of comment, after the <.
                . '(?:'         // Unroll the loop: Consume everything until --> is found.
                .     '-(?!->)' // Dash not followed by end of comment.
                .     '[^\-]*+' // Consume non-dashes.
                . ')*+'         // Loop possessively.
                . '(?:-->)?';   // End of comment. If not found, match all input.

            $cdata =
                '!\[CDATA\['    // Start of comment, after the <.
                . '[^\]]*+'     // Consume non-].
                . '(?:'         // Unroll the loop: Consume everything until ]]> is found.
                .     '](?!]>)' // One ] not followed by end of comment.
                .     '[^\]]*+' // Consume non-].
                . ')*+'         // Loop possessively.
                . '(?:]]>)?';   // End of comment. If not found, match all input.

            $escaped =
                '(?='             // Is the element escaped?
                .    '!--'
                . '|'
                .    '!\[CDATA\['
                . ')'
                . '(?(?=!-)'      // If yes, which type?
                .     $comments
                . '|'
                .     $cdata
                . ')';

            $regex =
                '/('                // Capture the entire match.
                .     '<'           // Find start of element.
                .     '(?'          // Conditional expression follows.
                .         $escaped  // Find end of escaped element.
                .     '|'           // ...else...
                .         '[^>]*>?' // Find end of normal element.
                .     ')'
                . ')/';
            // phpcs:enable
        }

        return $regex;
    }

    public function wp_html_split( $input ) {
        return preg_split( self::get_html_split_regex(), $input, -1, PREG_SPLIT_DELIM_CAPTURE );
    }

    public function _autop_newline_preservation_helper( $matches ) {
        return str_replace( "\n", '<WPPreserveNewline />', $matches[0] );
    }

    public function wp_replace_in_html_tags( $haystack, $replace_pairs ) {
        // Find all elements.
        $textarr = self::wp_html_split( $haystack );
        $changed = false;

        // Optimize when searching for one item.
        if ( 1 === count( $replace_pairs ) ) {
            // Extract $needle and $replace.
            foreach ( $replace_pairs as $needle => $replace ) {
            }

            // Loop through delimiters (elements) only.
            for ( $i = 1, $c = count( $textarr ); $i < $c; $i += 2 ) {
                if ( false !== strpos( $textarr[ $i ], $needle ) ) {
                    $textarr[ $i ] = str_replace( $needle, $replace, $textarr[ $i ] );
                    $changed       = true;
                }
            }
        } else {
            // Extract all $needles.
            $needles = array_keys( $replace_pairs );

            // Loop through delimiters (elements) only.
            for ( $i = 1, $c = count( $textarr ); $i < $c; $i += 2 ) {
                foreach ( $needles as $needle ) {
                    if ( false !== strpos( $textarr[ $i ], $needle ) ) {
                        $textarr[ $i ] = strtr( $textarr[ $i ], $replace_pairs );
                        $changed       = true;
                        // After one strtr() break out of the foreach loop and look at next element.
                        break;
                    }
                }
            }
        }

        if ( $changed ) {
            $haystack = implode( $textarr );
        }

        return $haystack;
    }

    /**
     * @param $content
     *
     * @return string
     * @throws Exception
     */
    public function getPageFilter($content)
    {
        return $this->filterProvider->getPageFilter()->filter((string) $content);
    }

    /**
     * @param $image
     * @param string $type
     *
     * @return string
     * @throws NoSuchEntityException
     */
    public function getImageUrl($image, $type = Image::TEMPLATE_MEDIA_TYPE_POST)
    {
        $imageHelper = $this->helperData->getImageHelper();
        $imageFile   = $imageHelper->getMediaPath($image, $type);

        return $this->helperData->getImageHelper()->getMediaUrl($imageFile);
    }

    /**
     * @param $urlKey
     * @param null $type
     *
     * @return string
     */
    public function getRssUrl($urlKey, $type = null)
    {
        if (is_object($urlKey)) {
            $urlKey = $urlKey->getUrlKey();
        }

        $urlKey = ($type ? $type . '/' : '') . $urlKey;
        $url    = $this->helperData->getUrl($this->helperData->getRoute() . '/' . $urlKey);

        return rtrim($url, '/') . '.xml';
    }

    /**
     * @param $post
     *
     * @return Phrase|string
     * @throws Exception
     */
    public function getPostInfo($post) {
        $likeCollection = $this->postLikeFactory->create()->getCollection();
        $couldLike = $likeCollection->addFieldToFilter('post_id', $post->getId())->addFieldToFilter('action', '1')->count();

        $html = '';

        $author = $this->helperData->getAuthorByPost($post);
        if ($author && $author->getName() && $this->helperData->showAuthorInfo()) {

            $html .= 'By ' . $this->escapeHtml($author->getName()) . ' ';

            //$aTag = '<a class="mp-info" href="' . $author->getUrl() . '">'. $this->escapeHtml($author->getName()) . '</a>';
            //$html .= __('| <i class="mp-blog-icon mp-blog-user"></i> %1', $aTag);
        }

        $output_icon_indate = 0;

        if ($output_icon_indate != 0) {
            $html = __(
                '<i class="mp-blog-icon mp-blog-calendar-times"></i> %1', $this->getDateFormat($post->getPublishDate())
            );
        } else {
            $html .= "on " . $this->getDateFormat($post->getPublishDate());
        }

        // TODO: Make Controllable in Admin

        $output_categoryposted = 0;

        if ($output_categoryposted != 0) {
            if ($categoryPost = $this->getPostCategoryHtml($post)) {
                $html .= __('| Posted in %1', $categoryPost);
            }
        }

        if ($this->getCommentinPost($post)) {
            $html .= __(
                '| <i class="mp-blog-icon mp-blog-comments" aria-hidden="true"></i> %1', $this->getCommentinPost($post)
            );
        }

        $show_views = 0;

        if ($show_views != 0) {
            if ($post->getViewTraffic()) {
                $html .= __(
                    '| <i class="mp-blog-icon mp-blog-traffic" aria-hidden="true"></i> %1', $post->getViewTraffic()
                );
            }
        }

            if ($couldLike > 0) {
                $html .= __('| <i class="mp-blog-icon mp-blog-thumbs-up" aria-hidden="true"></i> %1', $couldLike);
            }

            return $html;
        }

    /**
     * @param $post
     *
     * @return int
     */
    public function getCommentinPost($post)
    {
        $cmt = $this->cmtFactory->create()->getCollection()->addFieldToFilter('post_id', $post->getId());

        return $cmt->count();
    }

    /**
     * get list category html of post
     *
     * @param $post
     *
     * @return null|string
     */
    public function getPostCategoryHtml($post)
    {
        if (!$post->getCategoryIds()) {
            return null;
        }

        $categories   = $this->helperData->getCategoryCollection($post->getCategoryIds());
        $categoryHtml = [];
        foreach ($categories as $_cat) {
            $categoryHtml[] = '<a class="mp-info" href="' . $this->helperData->getBlogUrl(
                $_cat,
                HelperData::TYPE_CATEGORY
            ) . '">' . $_cat->getName() . '</a>';
        }

        return implode(', ', $categoryHtml);
    }

    /**
     * @param $date
     * @param bool $monthly
     *
     * @return false|string
     * @throws Exception
     */
    public function getDateFormat($date, $monthly = false)
    {
        return $this->helperData->getDateFormat($date, $monthly);
    }

    /**
     * @param $image
     * @param null $size
     * @param string $type
     *
     * @return string
     * @throws NoSuchEntityException
     */
    public function resizeImage($image, $size = null, $type = Image::TEMPLATE_MEDIA_TYPE_POST)
    {
        if (!$image) {
            return $this->getDefaultImageUrl();
        }

        return $this->helperData->getImageHelper()->resizeImage($image, $size, $type);
    }

    /**
     * get default image url
     */
    public function getDefaultImageUrl()
    {
        return $this->getViewFileUrl('Mageplaza_Blog::media/images/mageplaza-logo-default.png');
    }

    /**
     * @return string
     */
    public function getDefaultAuthorImage()
    {
        return $this->getViewFileUrl('Mageplaza_Blog::media/images/no-artist-image.jpg');
    }
}
