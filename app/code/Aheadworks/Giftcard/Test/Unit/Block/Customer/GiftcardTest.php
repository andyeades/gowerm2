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
namespace Aheadworks\Giftcard\Test\Unit\Block\Customer;

use Aheadworks\Giftcard\Block\Customer\Giftcard;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Template\Context;

/**
 * Class GiftcardTest
 * Test for \Aheadworks\Giftcard\Block\Customer\Giftcard
 *
 * @package Aheadworks\Giftcard\Test\Unit\Block\Customer
 */
class GiftcardTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Giftcard
     */
    private $object;

    /**
     * @var UrlInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $urlBuilderMock;

    /**
     * Init mocks for tests
     *
     * @return void
     */
    protected function setUp(): void
    {
        $objectManager = new ObjectManager($this);
        $this->urlBuilderMock = $this->getMockForAbstractClass(UrlInterface::class);
        $contextMock = $objectManager->getObject(
            Context::class,
            [
                'urlBuilder' => $this->urlBuilderMock
            ]
        );

        $this->object = $objectManager->getObject(
            Giftcard::class,
            [
                'context' => $contextMock
            ]
        );
    }

    /**
     * Testing of getCheckCodeUrl method
     */
    public function testGetCheckCodeUrl()
    {
        $url = 'http://example.com/awgiftcard/card/checkCode';

        $this->urlBuilderMock->expects($this->once())
            ->method('getUrl')
            ->with('awgiftcard/card/checkCode')
            ->willReturn($url);

        $this->assertEquals($url, $this->object->getCheckCodeUrl());
    }
}
