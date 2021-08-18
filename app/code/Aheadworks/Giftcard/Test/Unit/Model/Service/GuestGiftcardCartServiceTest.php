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
namespace Aheadworks\Giftcard\Test\Unit\Model\Service;

use Aheadworks\Giftcard\Model\Service\GuestGiftcardCartService;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Aheadworks\Giftcard\Api\GiftcardCartManagementInterface;
use Magento\Quote\Model\QuoteIdMaskFactory;
use Magento\Quote\Model\QuoteIdMask;

/**
 * Class GuestGiftcardCartServiceTest
 * Test for \Aheadworks\Giftcard\Model\Service\GuestGiftcardCartService
 *
 * @package Aheadworks\Giftcard\Test\Unit\Model\Service
 */
class GuestGiftcardCartServiceTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var GuestGiftcardCartService
     */
    private $object;

    /**
     * @var GiftcardCartManagementInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $giftcardCartManagementMock;

    /**
     * @var QuoteIdMaskFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    private $quoteIdMaskFactoryMock;

    /**
     * Init mocks for tests
     *
     * @return void
     */
    protected function setUp(): void
    {
        $objectManager = new ObjectManager($this);
        $this->giftcardCartManagementMock = $this->getMockForAbstractClass(GiftcardCartManagementInterface::class);
        $this->quoteIdMaskFactoryMock = $this->getMockBuilder(QuoteIdMaskFactory::class)
            ->setMethods(['create'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->object = $objectManager->getObject(
            GuestGiftcardCartService::class,
            [
                'giftcardCartManagement' => $this->giftcardCartManagementMock,
                'quoteIdMaskFactory' => $this->quoteIdMaskFactoryMock
            ]
        );
    }

    /**
     * Testing of get method
     */
    public function testGet()
    {
        $cartId = 1;
        $quoteId = 1;
        $expectedValue = [];

        $quoteIdMaskMock = $this->getMockBuilder(QuoteIdMask::class)
            ->setMethods(['load', 'getQuoteId'])
            ->disableOriginalConstructor()
            ->getMock();
        $quoteIdMaskMock->expects($this->once())
            ->method('load')
            ->with($cartId, 'masked_id')
            ->willReturnSelf();
        $quoteIdMaskMock->expects($this->once())
            ->method('getQuoteId')
            ->willReturn($quoteId);

        $this->quoteIdMaskFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($quoteIdMaskMock);

        $this->giftcardCartManagementMock->expects($this->once())
            ->method('get')
            ->with($quoteId)
            ->willReturn($expectedValue);

        $this->assertEquals($expectedValue, $this->object->get($cartId));
    }

    /**
     * Testing of set method
     */
    public function testSet()
    {
        $cartId = 1;
        $giftcardCode = 'gccode';
        $quoteId = 1;
        $expectedValue = true;

        $quoteIdMaskMock = $this->getMockBuilder(QuoteIdMask::class)
            ->setMethods(['load', 'getQuoteId'])
            ->disableOriginalConstructor()
            ->getMock();
        $quoteIdMaskMock->expects($this->once())
            ->method('load')
            ->with($cartId, 'masked_id')
            ->willReturnSelf();
        $quoteIdMaskMock->expects($this->once())
            ->method('getQuoteId')
            ->willReturn($quoteId);

        $this->quoteIdMaskFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($quoteIdMaskMock);

        $this->giftcardCartManagementMock->expects($this->once())
            ->method('set')
            ->with($quoteId, $giftcardCode)
            ->willReturn($expectedValue);

        $this->assertEquals($expectedValue, $this->object->set($cartId, $giftcardCode));
    }

    /**
     * Testing of remove method
     */
    public function testRemove()
    {
        $cartId = 1;
        $giftcardCode = 'gccode';
        $quoteId = 1;
        $expectedValue = true;

        $quoteIdMaskMock = $this->getMockBuilder(QuoteIdMask::class)
            ->setMethods(['load', 'getQuoteId'])
            ->disableOriginalConstructor()
            ->getMock();
        $quoteIdMaskMock->expects($this->once())
            ->method('load')
            ->with($cartId, 'masked_id')
            ->willReturnSelf();
        $quoteIdMaskMock->expects($this->once())
            ->method('getQuoteId')
            ->willReturn($quoteId);

        $this->quoteIdMaskFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($quoteIdMaskMock);

        $this->giftcardCartManagementMock->expects($this->once())
            ->method('remove')
            ->with($quoteId, $giftcardCode)
            ->willReturn($expectedValue);

        $this->assertEquals($expectedValue, $this->object->remove($cartId, $giftcardCode));
    }
}
