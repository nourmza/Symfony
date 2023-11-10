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
        $book = $repository->findAll(); //select*
        return $this->render('book/affiche.html.twig', ['book' => $book]);
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
            $author =$book->getAuthorId();
            //incrementataion de l'attribut "nb_books" de l'entite "Author"
            if($author instanceof Author){
                $author->setNbBooks($author->getNbBooks()+1);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($book);
            $em->flush();
            return $this->redirectToRoute('app_AfficheBook');
        }
        return $this->render('book/Add.html.twig', ['f' => $form->createView()]);


    }
}