<?php

namespace App\Controller;

use App\Entity\Author;
use App\Entity\Book;
use App\Form\AuthorType;
use App\Form\BookType;
use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function Webmozart\Assert\Tests\StaticAnalysis\true;

class BookController extends AbstractController
{
    #[Route('/book', name: 'app_book')]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }

    #[Route('/Affiche', name: 'app_AfficheBook')]
    public function Affiche(BookRepository $repository)
    {
        //recuperer les livres publié
        $publishedBooks = $this->getDoctrine()->getRepository(book::class)->findBy(['published' => true]);
        //compter les nbre des livres publiés et non publiés
        $numPublishedBooks = count($publishedBooks);
        $numUnpublishedBooks = count($this->getDoctrine()->getRepository(Book::class)->findBy(['published=false']));
        // hedhi mte3 lcondition question3
        if ($numUnpublishedBooks > 0) {
            return $this->render('book/affiche.html.twig', ['publishedBooks' => $publishedBooks=>$numUnpublishedBooks,'$numUnpublishedBooks'=>$numPublishedBooks]);

        } else {
            //afficher un message si aucun livre n' a ete trouvé
            return $this->render('book/no_books_found.html.twig');
        }


    }

    #[Route('/AddBook', name: 'app_AddBook')]
    public function Add(Request $request)
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $form->add(child: 'Ajouter', type: SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //initialisation de l'attribut "published" a true
            $book->setPublished(true);
            //get the association author from the book entity
            $author = $book->getAuthorId();
            //incrementataion de l'attribut "nb_books" de l'entite "Author"
            if ($author instanceof Author) {
                $author->setNbBooks($author->getNbBooks() + 1);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($book);
            $em->flush();
            return $this->redirectToRoute('app_AfficheBook');
        }
        return $this->render('book/Add.html.twig', ['f' => $form->createView()]);


    }

    #[Route('/edit/{ref}', name: 'app_editBook')]
    function edit(BookRepository $repository, $ref, Request $request)
    {
        $author = $repository->find($ref);
        $form = $this->createForm(BookType::class, $book);
        $form->add(child: 'Edit', type: SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute("app_AfficheBook");

        }
        return $this->render('book/edit.html.twig', ['f' => $form->createView()]);
    }

    #[Route('/delete/{ref}', name: 'app_deleteBook')]
    function delete(BookRepository $repository, $id, Request $request)
    {
        $book = $repository->find($ref);
        $em = $this->getDoctrine()->getManager();
        $em->remove($book);
        $em->flush();

        return $this->redirectToRoute('app_AfficheBook');

    }

    public function showBook($ref, BookRepository $repository)
    {
        $book = $repository->find($ref);
        if (!$book) {

            return $this->redirectToRoute('app_AfficheBook');
        }
        return $this->render('book/show.html.twig', ['b' => $book])

}
}