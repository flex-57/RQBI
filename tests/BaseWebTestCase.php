<?php

namespace App\Tests;

use App\Entity\Block;
use App\Entity\Enums\BlockTypeEnum;
use App\Entity\Page;
use App\Entity\User;
use App\Factory\BlockFactory;
use App\Repository\PageRepository;
use App\Service\PositionManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Base test class providing common test helpers and bootstrapping for functional tests.
 *
 * Place helpers here when multiple tests rely on the same fixtures.
 *
 * @param mixed $client Symfony test client
 */
class BaseWebTestCase extends WebTestCase
{
    protected KernelBrowser $client;
    protected EntityManagerInterface $em;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();

        /** @var ManagerRegistry $doctrine */
        $doctrine = static::getContainer()->get('doctrine');

        /** @var EntityManagerInterface $em */
        $em = $doctrine->getManager();
        $this->em = $em;
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->em->close();
        unset($this->em);
    }

    protected function createAdminUser(string $username = 'admin_test'): User
    {
        $userRepository = $this->em->getRepository(User::class);
        $existingUser = $userRepository->findOneBy(['username' => $username]);

        if ($existingUser) {
            return $existingUser;
        }

        $user = new User();
        $user->setUsername($username)
            ->setRoles(['ROLE_ADMIN'])
            ->setIsActive(true);

        /** @var UserPasswordHasherInterface $hasher */
        $hasher = static::getContainer()->get(UserPasswordHasherInterface::class);
        $user->setPassword($hasher->hashPassword($user, 'password'));

        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }

    /**
     * Create and persist a Page.
     * @param array<string, mixed> $data
     */
    protected function createPage(array $data = []): Page
    {
        $page = new Page();
        $page->setTitle($data['title'] ?? 'Test Page')
            ->setIsPublished($data['isPublished'] ?? true)
            ->setIsInMainNav($data['isInMainNav'] ?? false);

        if (isset($data['parent'])) {
            $page->setParent($data['parent']);
        }

        /** @var PageRepository $repository */
        $repository = static::getContainer()->get(PageRepository::class);
        $repository->save($page, true);

        return $page;
    }

    /**
     * Create and persist a Block of a given type (text, image, video) for a page
     * @param array<string, mixed> $data
     */
    protected function createBlock(Page $page, string $type = 'text', array $data = []): Block
    {
        // 1. Récupère les services une fois
        /** @var BlockFactory $factory  */
        $factory = static::getContainer()->get(BlockFactory::class);
        $positionManager = static::getContainer()->get(PositionManager::class);

        // 2. Prépare les données pour la factory
        /** @var PositionManager $positionManager */
        $position = $positionManager->getNextPosition(Block::class, ['page' => $page]);

        // 3. Convertit le string en enum (la factory l'exige)
        $typeEnum = BlockTypeEnum::from($type);

        // 4. Utilise la VRAIE factory
        $block = $factory->create($typeEnum, $data);

        // 5. La factory ne gère pas page/position, tu les set ici
        $block->setPage($page)
            ->setPosition($position)
            ->setIsActive($data['isActive'] ?? true);

        $this->em->persist($block);
        $this->em->flush();

        return $block;
    }
}
