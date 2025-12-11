<?php

namespace App\Controller;

use App\Repository\PageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(PageRepository $repo): Response
    {
        $homepage = $repo->findOneBy(['isHomepage' => true]);
        if (!$homepage) {
            $homepage = $repo->createBasicHomepageIfNotExists();
        }

        return $this->render('page/show.html.twig', [
            'page' => $homepage,
        ]);
    }
}
