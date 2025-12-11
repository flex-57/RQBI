<?php

namespace App\Tests\Controller;

use App\Entity\Page;
use App\Entity\BlockText;
use App\Tests\BaseWebTestCase;

class PageControllerTest extends BaseWebTestCase
{
    public function testShowPublishedPage(): void
    {
        // Arrange : Crée une page publiée avec un block
        $page = new Page();
        $page->setTitle('Ma page test');
        $page->setIsPublished(true);

        $block = new BlockText();
        $block->setContent('Contenu de test');
        $block->setPage($page);
        $block->setPosition(1);


        $this->em->persist($page);
        $this->em->persist($block);
        $this->em->flush();

        // Act : Va sur la page
        $this->client->request('GET', '/page/ma-page-test');

        // Assert : Vérifie le titre et le contenu
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Ma page test');
    }

    public function testShowUnpublishedPageAsAdmin(): void
    {
        // Arrange : Crée une page non publiée
        $page = new Page();
        $page->setTitle('Page privée');
        $page->setIsPublished(false);

        $this->em->persist($page);
        $this->em->flush();

        // Act : Connecte un admin et va sur la page
        $this->client->loginUser($this->createAdminUser());
        $this->client->request('GET', '/page/page-privee');

        // Assert
        $this->assertResponseIsSuccessful();
    }

    public function testShowUnpublishedPageAsVisitor(): void
    {
        // Arrange
        $page = new Page();
        $page->setTitle('Page privée');
        $page->setIsPublished(false);
        $this->em->persist($page);
        $this->em->flush();

        // Act : Visiteur non connecté
        $this->client->request('GET', '/page/page-privee');

        // Assert : Doit retourner 404
        $this->assertResponseStatusCodeSame(404);
    }
}
