<?php

namespace App\Controller;

use App\Entity\{Block,Page};
use App\Entity\Enums\BlockTypeEnum;
use App\Form\BlockDynamicType;
use App\Factory\BlockFactory;
use App\Service\{BlockRenderingService, PositionManager};
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('', name: 'app_block_')]
class BlockController extends AbstractController
{
    #[Route('/page/{id}/block/add', name: 'add', requirements: ['id' => '\d+'])]
    #[IsGranted('ROLE_ADMIN')]
    public function add(
        Page $page,
        Request $request,
        BlockFactory $factory,
        PositionManager $positionManager,
        EntityManagerInterface $em
    ): Response {
        $form = $this->createForm(BlockDynamicType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupération du type choisi
            $rawData = $request->request->all()['block_dynamic'];
            $type = BlockTypeEnum::from($rawData['type']);

            $blockData = $rawData['block'];
            $block = $factory->create($type, $blockData);

            $block->setPage($page)
                ->setPosition($positionManager->getNextPosition(Block::class, ['page' => $page]));

            $em->persist($block);
            $em->flush();

            $this->addFlash('success', 'Bloc ajouté avec succès.');
            return $this->redirectToRoute('app_page_show', ['fullSlug' => $page->getFullSlug()]);
        }

        return $this->render('block/add.html.twig', [
            'form' => $form,
            'page' => $page,
        ]);
    }

    #[Route('/block/{id}/preview', name: 'block_preview', requirements: ['id' => '\d+'])]
    #[IsGranted('ROLE_ADMIN')]
    public function preview(Block $block, BlockRenderingService $renderer): Response
    {
        return new Response($renderer->render($block));
    }
}
