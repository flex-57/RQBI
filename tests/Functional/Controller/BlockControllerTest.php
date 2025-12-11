<?php

namespace App\Tests\Controller;

use App\Tests\BaseWebTestCase;

class BlockControllerTest extends BaseWebTestCase
{
    public function testAddTextBlock(): void
    {
        $page = $this->createPage(['title' => 'Page test']);
        $this->client->loginUser($this->createAdminUser());

        $this->client->request('GET', '/page/' . $page->getId() . '/block/add');
        $this->assertResponseIsSuccessful();

        // Soumission avec type=text
        $this->client->submitForm('Ajouter', [
            'block_dynamic[type]' => 'text',
            'block_dynamic[block][content]' => 'Mon contenu texte',
            'block_dynamic[block][isActive]' => true,
        ]);

        $this->assertResponseRedirects('/page/' . $page->getFullSlug());
        $this->client->followRedirect();
        $this->assertSelectorTextContains('.block-text', 'Mon contenu texte');
    }

    public function testAddImageBlock(): void
    {
        $page = $this->createPage(['title' => 'Page image']);
        $this->client->loginUser($this->createAdminUser());

        $this->client->request('POST', '/page/' . $page->getId() . '/block/add');
        $this->assertResponseIsSuccessful();

        $this->client->submitForm('Ajouter', [
            'block_dynamic[type]' => 'image',
            'block_dynamic[block][url]' => 'https://example.com/image.jpg',
            'block_dynamic[block][alt]' => 'Alt text',
            'block_dynamic[block][isActive]' => true,
        ]);

        $this->assertResponseRedirects('/page/' . $page->getFullSlug());
        $this->client->followRedirect();
        $this->assertSelectorExists('img[src="https://example.com/image.jpg"]');
    }

    public function testAddVideoBlock(): void
    {
        $page = $this->createPage(['title' => 'Page video']);
        $this->client->loginUser($this->createAdminUser());

        $this->client->request('POST', '/page/' . $page->getId() . '/block/add');
        $this->assertResponseIsSuccessful();

        $this->client->submitForm('Ajouter', [
            'block_dynamic[type]' => 'video',
            'block_dynamic[block][url]' => 'https://example.com/video.mp4',
            'block_dynamic[block][format]' => 'mp4',
            'block_dynamic[block][isActive]' => true,
        ]);

        $this->assertResponseRedirects('/page/' . $page->getFullSlug());
        $this->client->followRedirect();
        $this->assertSelectorExists('video[src="https://example.com/video.mp4"]');
    }

    public function testPreviewBlock(): void
    {
        $page = $this->createPage(['title' => 'Page preview']);
        $block = $this->createBlock($page, 'text', ['content' => 'Aperçu']);
        $this->client->loginUser($this->createAdminUser());

        $this->client->request('GET', '/block/' . $block->getId() . '/preview');

        $this->assertResponseIsSuccessful();
        $this->assertStringContainsString('Aperçu', (string) $this->client->getResponse()->getContent());
    }

    public function testAddBlockAsVisitor(): void
    {
        $page = $this->createPage(['title' => 'Page visitor test']);

        $this->client->request('GET', '/page/' . $page->getId() . '/block/add');

        $this->assertResponseStatusCodeSame(302);
        $this->assertResponseRedirects('/login');
    }

    public function testPreviewBlockAsVisitor(): void
    {
        $page = $this->createPage(['title' => 'Page visitor preview']);
        $block = $this->createBlock($page, 'text', ['content' => 'Aperçu']);

        $this->client->request('GET', '/block/' . $block->getId() . '/preview');

        $this->assertResponseStatusCodeSame(302);
        $this->assertResponseRedirects('/login');
    }
}
