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
namespace Aheadworks\Giftcard\Test\Unit\Controller\Cart;

use Aheadworks\Giftcard\Api\GiftcardCartManagementInterface;
use Aheadworks\Giftcard\Controller\Cart\Apply;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Framework\Escaper;
use Magento\Framework\App\Action\Context;
use Magento\Checkout\Model\Session as CheckoutSession;

/**
 * Class ApplyTest
 * Test for \Aheadworks\Giftcard\Controller\Cart\Apply
 *
 * @package Aheadworks\Giftcard\Controller\Cart
 */
class ApplyTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Apply
     */
    private $object;

    /**
     * @var GiftcardCartManagementInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $giftcardCartManagementMock;

    /**
     * @var CheckoutSession|\PHPUnit_Framework_MockObject_MockObject
     */
    private $checkoutSessionMock;

    /**
     * @var Escaper|\PHPUnit_Framework_MockObject_MockObject
     */
    private $escaperMock;

    /**
     * @var RequestInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $requestMock;

    /**
     * Init mocks for tests
     *
     * @return void
     */
    protected function setUp(): void
    {
        $objectManager = new ObjectManager($this);
        $this->giftcardCartManagementMock = $this->getMockForAbstractClass(GiftcardCartManagementInterface::class);
        $this->checkoutSessionMock = $this->getMockBuilder(CheckoutSession::class)
            ->setMethods([])
            ->disableOriginalConstructor()
            ->getMock();
        $this->escaperMock = $this->getMockBuilder(Escaper::class)
            ->setMethods([])
            ->disableOriginalConstructor()
            ->getMock();
        $this->requestMock = $this->getMockForAbstractClass(RequestInterface::class);

        $contextMock = $objectManager->getObject(
            Context::class,
            [
                'request' => $this->requestMock
            ]
        );

        $this->object = $objectManager->getObject(
            Apply::class,
            [
                'context' => $contextMock,
                'giftcardCartManagement' => $this->giftcardCartManagementMock,
                'checkoutSession' => $this->checkoutSessionMock,
                'escaper' => $this->escaperMock
            ]
        );
    }

    /**
     * Testing of execute method
     */
    public function testExecute()
    {
        $giftcardCode = 'gccode';
        $redirectTo = 'multishipping';
        $quoteId = 1;

        $this->requestMock->expects($this->exactly(2))
            ->method('getParam')
            ->withConsecutive(
                ['code'],
                ['redirect_to']
            )
            ->willReturnOnConsecutiveCalls(
                $giftcardCode,
                $redirectTo
            );
        $this->checkoutSessionMock->expects($this->once())
            ->method('getQuoteId')
            ->willReturn($quoteId);

        $this->giftcardCartManagementMock->expects($this->once())
            ->method('set')
            ->with($quoteId, $giftcardCode)
            ->willReturn(true);

        $this->object->execute();
    }
}
