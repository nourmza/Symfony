<?php
namespace App\Controller;
use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation;


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
        $author1->setEmail("nour@gmail.com"); // Utilisez "setEmail" pour définir l'email

        // Enregistrez l'entité dans la base de données
        $entityManager->persist($author1);
        $entityManager->flush();

        return $this->redirectToRoute('app_Affiche'); // Redirigez vers la route 'app_Affiche'
    }

    #[Route('/Add', name: 'app_Add')]
    public function Add(Request $request)
    {
        $author = new Author();
        $form = $this->createForm(AuthorType::class, $author);
        $form->add(child: 'Ajouter', type: SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($author);
            $em->flush();
            return $this->redirectToRoute('app_Affiche');
        }
        return $this->render('author/Add.html.twig', ['f' => $form->createView()]);


    }
    #[Route('/edit/{id}', name: 'app_edit')]
    function edit(AuthorRepository $repository, $id,Request $request)
    {
        $author = $repository->find($id);
        $form = $this->createForm(AuthorType::class, $author);
        $form->add(child: 'Edit', type: SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em = flush();
            return $this->redirectToRoute("app_Affiche");

        }
        return $this->render('author/edit.html.twig', ['f' => $form->createView()]);
    }
    #[Route('/delete/{id}', name: 'app_delete')]
    function delete(AuthorRepository $repository, $id, Request $request)
    {
        $author = $repository->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($author);
        $em->flush();

            return $this->redirectToRoute('app_Affiche');

    }

}