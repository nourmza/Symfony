<?php
namespace App\Controller;
use App\Entity\Author;
use App\Repository\AuthorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class AuthorController extends AbstractController
{
    #[Route('/author', name: 'app_author')]
    public function index(): Response
    {
        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }


    #[Route('/Affiche', name: 'app_Affiche')]
    public function Affiche(AuthorRepository $repository)
    {
        $author = $repository->findAll();
        return $this->render('author/affiche.html.twig', ['author' => $author]);
    }


    #[Route('/Add', name: 'app_Add')]
    public function addStatistique(EntityManagerInterface $entityManager): Response
    {
        // Créez une instance de l'entité Author
        $author1 = new Author();
        $author1->setUsername("nour"); // Utilisez "setUsername" pour définir le nom d'utilisateur
        $author1->setEmail("5obzty@gmail.com"); // Utilisez "setEmail" pour définir l'email

        // Enregistrez l'entité dans la base de données
        $entityManager->persist($author1);
        $entityManager->flush();

        return $this->redirectToRoute('app_Affiche'); // Redirigez vers la route 'app_Affiche'
    }

}