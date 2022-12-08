<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Chaton;
use App\Form\ChatonType;
use App\Form\ChatonSupprimerType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChatonsController extends AbstractController
{
    /**
     * @Route("/chatons/{idCategorie}", name="app_chatons_voir")
     */
    public function index($idCategorie, ManagerRegistry $doctrine): Response
    {
        $categorie = $doctrine->getRepository(Categorie::class)->find($idCategorie);
        //si on n'a rien trouvé -> 404
        if (!$categorie) {
            throw $this->createNotFoundException("Aucune catégorie avec l'id $idCategorie");
        }

        return $this->render('chatons/index.html.twig', [
            'categorie' => $categorie,
            "chatons" => $categorie->getChatons()
        ]);
    }

    /**
     * @Route("/chaton/ajouter/", name="app_chaton_ajouter")
     */
    public function ajouterChaton(ManagerRegistry $doctrine, Request $request)
    {
        $chaton = new Chaton();

        $form = $this->createForm(ChatonType::class, $chaton);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($chaton);
            $em->flush();

            //retour à l'accueil
            return $this->redirectToRoute("app_chatons_voir", ["idCategorie" => $chaton->getCategorie()->getId()]);
        }

        return $this->render("chatons/ajouter.html.twig", [
            'formulaire' => $form->createView()
        ]);
    }

    /**
     * @Route("/chaton/modifier/{id}", name="app_chaton_modifier")
     */
    public function modifier($id, ManagerRegistry $doctrine, Request $request): Response{
        //créer le formulaire sur le même principe que dans ajouter
        //mais avec un chaton existant
        $chaton = $doctrine->getRepository(Chaton::class)->find($id);

        //je vais gérer le fait que l'id n'existe pas
        if (!$chaton){
            throw $this->createNotFoundException("Pas de chaton avec l'id $id");
        }

        //Si j'arrive là c'est qu'elle existe en BDD
        //à partir de ça je crée le formulaire
        $form=$this->createForm(ChatonType::class, $chaton);

        //On gère le retour du formulaire tout de suite
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            //l'objet chaton est rempli
            //on va utiliser l'entity manager de doctrine
            $em=$doctrine->getManager();
            //on lui dit qu'on veut mettre le chaton dans la table
            $em->persist($chaton);

            //on génère l'appel SQL (update ici)
            $em->flush();

            //on revient à l'accueil
            return $this->redirectToRoute("app_chatons_voir", ["idCategorie" => $chaton->getCategorie()->getId()]);
        }

        return $this->render("chatons/modifier.html.twig",[
            "chaton"=>$chaton,
            "formulaire"=>$form->createView()
        ]);
    }

    /**
     * @Route("/chaton/supprimer/{id}", name="app_chaton_supprimer")
     */
    public function supprimer($id, ManagerRegistry $doctrine, Request $request): Response{
        //créer le formulaire sur le même principe que dans ajouter
        //mais avec une catégorie existante
        $chaton = $doctrine->getRepository(Chaton::class)->find($id);

        //je vais gérer le fait que l'id n'existe pas
        if (!$chaton){
            throw $this->createNotFoundException("Pas de chaton avec l'id $id");
        }

        //Si j'arrive là c'est qu'elle existe en BDD
        //à partir de ça je crée le formulaire
        $form=$this->createForm(ChatonSupprimerType::class, $chaton);

        //On gère le retour du formulaire tout de suite
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            //l'objet chaton est rempli
            //on va utiliser l'entity manager de doctrine
            $em=$doctrine->getManager();
            //on lui dit qu'on supprimer la catégorie
            $em->remove($chaton);

            //on génère l'appel SQL (delete ici)
            $em->flush();

            //on revient à l'accueil
            return $this->redirectToRoute("app_chatons_voir", ["idCategorie" => $chaton->getCategorie()->getId()]);
        }

        return $this->render("chatons/supprimer.html.twig",[
            "chaton"=>$chaton,
            "idCategorie"=>$chaton->getCategorie()->getId(),
            "formulaire"=>$form->createView()
        ]);
    }
}
