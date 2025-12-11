<?php

namespace App\Tests\Unit\Service;

use App\Repository\PageRepository;
use App\Service\SlugService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\String\Slugger\AsciiSlugger;

class SlugServiceTest extends TestCase
{
    public function testGenerateUniqueSlug(): void
    {
        $mockRepo = $this->createMock(PageRepository::class);
        $mockRepo->method('findSlugsStartingWith')
            ->willReturn(['test', 'test-1']);

        $service = new SlugService($mockRepo, new AsciiSlugger());
        $this->assertSame('test-2', $service->generateUniqueSlug('Test'));
    }
}
