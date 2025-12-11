<?php

namespace App\Tests\Unit\Factory;

use App\Entity\BlockText;
use App\Entity\BlockImage;
use App\Entity\BlockVideo;
use App\Entity\Enums\BlockTypeEnum;
use App\Entity\Enums\VideoFormatEnum;
use App\Factory\BlockFactory;
use App\Factory\SingleBlockFactory\SingleBlockFactoryInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class BlockFactoryTest extends TestCase
{
    private BlockFactory $factory;

    /** @var MockObject&SingleBlockFactoryInterface */
    private SingleBlockFactoryInterface $mockTextFactory;

    /** @var MockObject&SingleBlockFactoryInterface */
    private SingleBlockFactoryInterface $mockImageFactory;

    /** @var MockObject&SingleBlockFactoryInterface */
    private SingleBlockFactoryInterface $mockVideoFactory;

    protected function setUp(): void
    {
        // 1️⃣ Mock Text Factory
        $this->mockTextFactory = $this->createMock(SingleBlockFactoryInterface::class);
        $this->mockTextFactory->method('supports')
            ->willReturnCallback(fn($type) => $type === BlockTypeEnum::TEXT);
        $this->mockTextFactory->method('create')
            ->willReturnCallback(fn($type, $data) => (new BlockText())->setContent($data['content'] ?? ''));

        // 2️⃣ Mock Image Factory
        $this->mockImageFactory = $this->createMock(SingleBlockFactoryInterface::class);
        $this->mockImageFactory->method('supports')
            ->willReturnCallback(fn($type) => $type === BlockTypeEnum::IMAGE);
        $this->mockImageFactory->method('create')
            ->willReturnCallback(fn($type, $data) => (new BlockImage())->setUrl($data['url'] ?? '')->setAlt($data['alt'] ?? ''));

        // 3️⃣ Mock Video Factory
        $this->mockVideoFactory = $this->createMock(SingleBlockFactoryInterface::class);
        $this->mockVideoFactory->method('supports')
            ->willReturnCallback(fn($type) => $type === BlockTypeEnum::VIDEO);
        $this->mockVideoFactory->method('create')
            ->willReturnCallback(fn($type, $data) => (new BlockVideo())
                ->setUrl($data['url'] ?? '')
                ->setFormat($data['format'] ?? VideoFormatEnum::MP4)
                ->setIsAutoplay($data['isAutoplay'] ?? false)
            );

        // 4️⃣ Injecte tous les mocks dans la BlockFactory principale
        $this->factory = new BlockFactory([
            $this->mockTextFactory,
            $this->mockImageFactory,
            $this->mockVideoFactory,
        ]);
    }

    public function testCreateTextBlock(): void
    {
        $data = ['content' => 'Hello Text'];
        $block = $this->factory->create(BlockTypeEnum::TEXT, $data);

        /** @var BlockText $block */
        $this->assertInstanceOf(BlockText::class, $block);
        $this->assertSame('Hello Text', $block->getContent());
    }

    public function testCreateImageBlock(): void
    {
        $data = ['url' => 'https://image.jpg', 'alt' => 'Alt text'];
        $block = $this->factory->create(BlockTypeEnum::IMAGE, $data);

        /** @var BlockImage $block */
        $this->assertInstanceOf(BlockImage::class, $block);
        $this->assertSame('https://image.jpg', $block->getUrl());
        $this->assertSame('Alt text', $block->getAlt());
    }

    public function testCreateVideoBlock(): void
    {
        $data = ['url' => 'https://video.mp4', 'format' => VideoFormatEnum::MP4, 'isAutoplay' => true];
        $block = $this->factory->create(BlockTypeEnum::VIDEO, $data);

        /** @var BlockVideo $block */
        $this->assertInstanceOf(BlockVideo::class, $block);
        $this->assertSame('https://video.mp4', $block->getUrl());
        $this->assertTrue($block->isAutoplay());
        $this->assertSame(VideoFormatEnum::MP4, $block->getFormat());
    }

    public function testUnsupportedTypeThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('No factory found for block type');

        $this->factory->create(BlockTypeEnum::from('unsupported_type'), []);
    }
}
