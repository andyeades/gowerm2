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
namespace Aheadworks\Giftcard\Model\DataProcessor;

/**
 * Class PostDataComposite
 *
 * @package Aheadworks\Giftcard\Model\DataProcessor
 */
class PostDataComposite implements PostDataProcessorInterface
{
    /**
     * @var PostDataProcessorInterface[]
     */
    private $processors;

    /**
     * @param array $processors
     */
    public function __construct(array $processors = [])
    {
        $this->processors = $processors;
    }

    /**
     * Prepare entity data for save
     *
     * @param array $data
     * @return array
     */
    public function prepareEntityData($data)
    {
        foreach ($this->processors as $processor) {
            if (!$processor instanceof PostDataProcessorInterface) {
                throw new \InvalidArgumentException(
                    sprintf(
                        'Processor instance %s does not implement required interface.',
                        PostDataProcessorInterface::class
                    )
                );
            }
            $data = $processor->prepareEntityData($data);
        }

        return $data;
    }
}
