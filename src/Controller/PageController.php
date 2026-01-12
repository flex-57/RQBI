<?php

namespace App\Controller;

use App\Entity\Page;
use App\Form\PageType;
use App\Repository\PageRepository;
use App\Service\PositionManager;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/page', name: 'app_page_')]
final class PageController extends AbstractController
{
    #[Route('/add', name: 'add')]
    #[IsGranted('ROLE_ADMIN')]
    public function add(
        Request $request,
        PageRepository $repository,
        PositionManager $positionManager,
    ): Response {
        $page = new Page();

        $form = $this->createForm(PageType::class, $page, [
            'validation_groups' => ['Default', 'create'],
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $page->setPosition($positionManager->getNextPosition(Page::class, ['parent' => $page->getParent()]));

            try {
                $repository->save($page, true);
                $this->addFlash('success', 'Page créée avec succès.');

                return $this->redirectToRoute('app_page_show', ['fullSlug' => $page->getFullSlug()]);
            } catch (UniqueConstraintViolationException) {
                $this->addFlash('error', 'Slug déjà utilisé. Veuillez réessayer.');

                return $this->redirectToRoute('app_page_add');
            }
        }

        return $this->render('page/add.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', requirements: ['id' => '\d+'])]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(
        Page $page,
        Request $request,
        PageRepository $repository,
    ): Response {
        $form = $this->createForm(PageType::class, $page);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $repository->save($page, true);
                $this->addFlash('success', 'Page mise à jour avec succès.');

                return $this->redirectToRoute('app_page_show', ['fullSlug' => $page->getFullSlug()]);
            } catch (UniqueConstraintViolationException) {
                $this->addFlash('error', 'Slug déjà utilisé. Veuillez réessayer.');

                return $this->redirectToRoute('app_page_edit', ['id' => $page->getId()]);
            }
        }

        return $this->render('page/add.html.twig', [
            'form' => $form,
            'page' => $page,
        ]);
    }

    #[Route('/{fullSlug}', name: 'show', requirements: ['fullSlug' => '[a-z0-9\-\/]+'])]
    public function show(string $fullSlug, PageRepository $repository): Response
    {
        $page = $repository->findOneByFullSlugWithBlocks($fullSlug);

        if (!$page) {
            throw $this->createNotFoundException('Page not found');
        }

        return $this->render('page/show.html.twig', [
            'page' => $page,
        ]);
    }
}
