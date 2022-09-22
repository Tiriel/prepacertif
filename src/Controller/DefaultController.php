<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'app_default_index')]
    public function index(PostRepository $repository): Response
    {
        return $this->render('default/index.html.twig', [
            'posts' => $repository->findBy([], ['publicationDate' => 'DESC'], 6)
        ]);
    }
}
