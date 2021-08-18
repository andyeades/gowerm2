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
 * @package    GiftcardGraphQl
 * @version    1.0.0
 * @copyright  Copyright (c) 2021 Aheadworks Inc. (https://aheadworks.com/)
 * @license    https://aheadworks.com/end-user-license-agreement/
 */
namespace Aheadworks\GiftcardGraphQl\Test\Unit\Model;

use Aheadworks\GiftcardGraphQl\Model\GiftCardProductTypeResolver;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;

/**
 * Class GiftCardProductTypeResolverTest
 * @package Aheadworks\GiftcardGraphQl\Test\Unit\Model
 */
class GiftCardProductTypeResolverTest extends TestCase
{
    /**
     * @var GiftCardProductTypeResolver
     */
    private $resolver;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $objectManager = new ObjectManager($this);
        $this->resolver = $objectManager->getObject(GiftCardProductTypeResolver::class);
    }

    /**
     * Test resolveType method
     *
     * @param array $inputData
     * @param string $result
     * @dataProvider resolveTypeProvider
     */
    public function testResolveType($inputData, $result)
    {
        $this->assertEquals($result, $this->resolver->resolveType($inputData));
    }

    public function resolveTypeProvider()
    {
        return [
            [['type_id' => 'aw_giftcard'], GiftCardProductTypeResolver::AW_GC_PRODUCT],
            [[], ''],
            [['type_id' => 'simple'], '']
        ];
    }
}
