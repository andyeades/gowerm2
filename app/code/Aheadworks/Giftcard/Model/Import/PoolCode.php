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
namespace Aheadworks\Giftcard\Model\Import;

use Magento\Framework\Api\DataObjectHelper;
use Aheadworks\Giftcard\Api\Data\Pool\CodeInterface as PoolCodeInterface;
use Aheadworks\Giftcard\Api\Data\Pool\CodeInterfaceFactory as PoolCodeInterfaceFactory;
use Aheadworks\Giftcard\Model\ResourceModel\Validator\GiftcardIsUnique;
use Magento\Framework\App\RequestInterface;
use Magento\Ui\Component\MassAction\Filter;

/**
 * Class PoolCode
 *
 * @package Aheadworks\Giftcard\Model\Import
 */
class PoolCode extends AbstractImport
{
    /**
     * {@inheritdoc}
     */
    protected $namespace = 'aw_giftcard_pool_code_listing';

    /**
     * {@inheritdoc}
     */
    protected $logFileName = 'aw_gc_pool_codes_import';

    /**
     * @var GiftcardIsUnique
     */
    private $giftcardIsUniqueValidator;

    /**
     * PoolCodeInterfaceFactory
     */
    private $poolCodeFactory;

    /**
     * @param DataObjectHelper $dataObjectHelper
     * @param Filter $filter
     * @param RequestInterface $request
     * @param GiftcardIsUnique $giftcardIsUniqueValidator
     * @param PoolCodeInterfaceFactory $poolCodeFactory
     */
    public function __construct(
        DataObjectHelper $dataObjectHelper,
        Filter $filter,
        RequestInterface $request,
        GiftcardIsUnique $giftcardIsUniqueValidator,
        PoolCodeInterfaceFactory $poolCodeFactory
    ) {
        parent::__construct($dataObjectHelper, $filter, $request);
        $this->giftcardIsUniqueValidator = $giftcardIsUniqueValidator;
        $this->poolCodeFactory = $poolCodeFactory;
    }

    /**
     * {@inheritdoc}
     */
    protected function convertDataToObject($filteredRows)
    {
        $poolCodes = [];
        foreach ($filteredRows as $row) {
            $row = $this->getRowData($row);
            /** @var PoolCodeInterface $poolCode */
            $poolCode = $this->poolCodeFactory->create();
            $this->dataObjectHelper->populateWithArray(
                $poolCode,
                $row,
                PoolCodeInterface::class
            );

            if ($this->giftcardIsUniqueValidator->validate($poolCode->getCode())) {
                $poolCodes[] = $poolCode;
            } else {
                $this->addMessages([
                    __('Code %1 already in use', $poolCode->getCode())
                ]);
            }
        }
        return $poolCodes;
    }

    /**
     * {@inheritdoc}
     */
    protected function getHeaderFields()
    {
        return [
            ['header' => __('Code'), 'field_name' => PoolCodeInterface::CODE, 'required' => true],
            ['header' => __('Gift Code Created'), 'field_name' => PoolCodeInterface::USED, 'required' => true]
        ];
    }
}
