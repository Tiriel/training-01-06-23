<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/book')]
class BookController extends AbstractController
{
    #[Route('', name: 'app_book_index')]
    public function index(BookRepository $repository): Response
    {
        return $this->render('book/index.html.twig', [
            'books' => $repository->findAll()
        ]);
    }

    #[Route('/{!id}', name: 'app_book_show', requirements: ['id' => '\d+'], defaults: ['id' => 1], methods: ['GET'])]
    public function show(int $id, BookRepository $repository): Response
    {
        return $this->render('book/show.html.twig', [
            'book' => $repository->find($id),
        ]);
    }

    #[Route('/new', name: 'app_book_new')]
    public function new(Request $request, BookRepository $repository): Response
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $repository->save($book, true);

            return $this->redirectToRoute('app_book_show', ['id' => $book->getId()]);
        }

        return $this->render('book/new.html.twig', [
            'form' => $form,
        ]);
    }
}
