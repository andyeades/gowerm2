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
namespace Aheadworks\Giftcard\Test\Unit\Controller\Card;

use Aheadworks\Giftcard\Controller\Card\Index;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Page\Config;
use Magento\Framework\View\Page\Title;

/**
 * Class IndexTest
 * Test for \Aheadworks\Giftcard\Controller\Card\Index
 *
 * @package Aheadworks\Giftcard\Controller\Card
 */
class IndexTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Index
     */
    private $object;

    /**
     * @var PageFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    private $resultPageFactoryMock;

    /**
     * Init mocks for tests
     *
     * @return void
     */
    protected function setUp(): void
    {
        $objectManager = new ObjectManager($this);
        $this->resultPageFactoryMock = $this->getMockBuilder(PageFactory::class)
            ->setMethods(['create'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->object = $objectManager->getObject(
            Index::class,
            [
                'resultPageFactory' => $this->resultPageFactoryMock,
            ]
        );
    }

    /**
     * Testing of execute method
     */
    public function testExecute()
    {
        $titleMock = $this->getMockBuilder(Title::class)
            ->setMethods(['set'])
            ->disableOriginalConstructor()
            ->getMock();
        $pageConfigMock = $this->getMockBuilder(Config::class)
            ->setMethods(['getTitle'])
            ->disableOriginalConstructor()
            ->getMock();
        $resultPageMock = $this->getMockBuilder(Page::class)
            ->setMethods(['getConfig'])
            ->disableOriginalConstructor()
            ->getMock();
        $resultPageMock->expects($this->once())
            ->method('getConfig')
            ->willReturn($pageConfigMock);
        $pageConfigMock->expects($this->once())
            ->method('getTitle')
            ->willReturn($titleMock);
        $this->resultPageFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($resultPageMock);

        $this->assertSame($resultPageMock, $this->object->execute());
    }
}
