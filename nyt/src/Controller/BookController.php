<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\FileUploader;
use Knp\Component\Pager\PaginatorInterface;

class BookController extends AbstractController
{

    /**
     * List of books
     * @Route("/", methods={"GET"}, name="app_book_list")
     */
    public function list(PaginatorInterface $paginator, Request $request)
    {
        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository(Book::class);
        $query = $request->query->get('q');
        $search = $repository->searchByTitleOrAuthor($query);
        return $this->render('book/list.html.twig', [
            'books' => $paginator->paginate(
                $search,
                $request->query->getInt('page', 1),
                Book::NUMBER_OF_ITEMS_FOR_PAGE
            ),
            'query' => $query
        ]);
    }

    /**
     * Import book
     * @Route("/import", name="app_book_import")
     */
    public function import(Request $request, FileUploader $fileUploader)
    {
        $id = $request->get('id');
        if (!$id) {
            $this->addFlash(
                'error',
                'Book was not selected!'
            );
            return $this->redirect(
                $this->generateUrl('app_book_list')
            );
        }
        $em = $this->getDoctrine()
            ->getManager();
        $book = $em->getRepository(Book::class)
            ->find($id);
        if (!$book) {
            return $this->redirect(
                $this->generateUrl('app_book_list')
            );
        }
        $oldBookFilename = $book->getFilename();
        $form = $this->createForm(
            BookType::class,
            $book
        );
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $bookFile = $form['book_file']->getData();
            if ($bookFile) {
                $fileName = $fileUploader->upload($bookFile);
                $book->setFilename($fileName);
                $em->persist($book);
                $em->flush();
                $filesystem = new Filesystem();
                $filesystem->remove(
                    $this->getParameter('books_directory') . '/' . $oldBookFilename
                );
                if ($fileName) {
                    $this->addFlash(
                        'success',
                        'Book "' . $book->getTitle() . '" was imported'
                    );
                } else {
                    $this->addFlash(
                        'error',
                        'Book "' . $book->getTitle() . '"" was not imported'
                    );
                }
            } else {
                $this->addFlash(
                    'other',
                    'File was not selected'
                );
            }
            return $this->redirect(
                $this->generateUrl('app_book_list')
            );
        }
        return $this->render('book/import.html.twig', [
            'form' => $form->createView(),
            'book' => $book
        ]);
    }
}