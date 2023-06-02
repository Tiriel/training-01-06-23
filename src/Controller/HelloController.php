<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class HelloController extends AbstractController
{
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    #[Route('/hello/{name<[a-zA-Z- ]+>?World}', name: 'app_hello_index')]
    public function index(string $name, string $sfVersion): Response
    {
        //$this->denyAccessUnlessGranted('ROLE_MODERATOR');
        if ($this->isGranted('ROLE_ADMIN')) {
            dump($sfVersion);
        }
        return $this->render('hello/index.html.twig', [
            'controller_name' => $name,
        ]);
    }
}
