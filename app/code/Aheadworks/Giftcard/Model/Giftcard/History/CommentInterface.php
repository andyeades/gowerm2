<?php
/**
 * Aheadworks Inc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://aheadworks.com/end-user-license-agreement/
 *
 * @package    Giftcard
 * @version    1.4.6
 * @copyright  Copyright (c) 2021 Aheadworks Inc. (https://aheadworks.com/)
 * @license    https://aheadworks.com/end-user-license-agreement/
 */
namespace Aheadworks\Giftcard\Model\Giftcard\History;

use Aheadworks\Giftcard\Api\Data\Giftcard\History\EntityInterface as HistoryEntityInterface;

/**
 * Interface CommentInterface
 *
 * @package Aheadworks\Giftcard\Model\Giftcard\History
 */
interface CommentInterface
{
    /**
     * Retrieve comment type
     *
     * @return int
     */
    public function getType();

    /**
     * Retrieve comment label
     *
     * @param [] $arguments
     * @return string
     */
    public function getLabel($arguments = []);

    /**
     * Render comment
     *
     * @param HistoryEntityInterface[] $arguments
     * @param string $label
     * @param bool $renderingUrl
     * @return string
     */
    public function renderComment(
        $arguments,
        $label = null,
        $renderingUrl = false
    );
}
