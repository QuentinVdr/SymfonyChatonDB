<?php

namespace App\Controller;

use App\Entity\Proprietaire;
use App\Form\ProprietaireSupprimerType;
use App\Form\ProprietaireType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProprietaireController extends AbstractController
{
    /**
     * @Route("/proprietaires", name="app_proprietaires")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        //On va aller chercher les catégories dans la BDD
        //pour ça on a besoin d'un repository
        $repo = $doctrine->getRepository(Proprietaire::class);
        $proprietaires=$repo->findAll(); //select * transformé en liste de Propriétaires

        return $this->render('proprietaires/index.html.twig', [
            'proprietaires'=>$proprietaires
        ]);
    }

    /**
     * @Route("/proprietaire/chatons/{id}", name="app_proprietaire_chatons")
     */
    public function voirChaton($id, ManagerRegistry $doctrine): Response
    {
        $proprietaire = $doctrine->getRepository(Proprietaire::class)->find($id);
        //si on n'a rien trouvé -> 404
        if (!$proprietaire) {
            throw $this->createNotFoundException("Aucune proprietaire avec l'id $id");
        }

        return $this->render('proprietaires/voirChatons.html.twig', [
            'proprietaire' => $proprietaire,
            "chatons" => $proprietaire->getChatons()
        ]);
    }

    /**
     * @Route("/proprietaire/ajouter", name="app_proprietaire_ajouter")
     */
    public function ajouter(ManagerRegistry $doctrine, Request $request): Response
    {
        //créer le formulaire
        //on crée d'abord une catégorie vide
        $proprietaire=new Proprietaire();
        //à partir de ça je crée le formulaire
        $form=$this->createForm(ProprietaireType::class, $proprietaire);

        //On gère le retour du formulaire tout de suite
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            //l'objet proprietaire est rempli
            //on va utiliser l'entity manager de doctrine
            $em=$doctrine->getManager();
            //on lui dit qu'on veut mettre la proprietaire dans la table
            $em->persist($proprietaire);

            //on génère l'appel SQL (l'insert ici)
            $em->flush();

            //on revient à l'accueil
            return $this->redirectToRoute("app_proprietaires");
        }

        return $this->render("proprietaires/ajouter.html.twig",[
            "formulaire"=>$form->createView()
        ]);
    }

    /**
     * @Route("/proprietaire/modifier/{id}", name="app_proprietaire_modifier")
     */
    public function modifier($id, ManagerRegistry $doctrine, Request $request): Response{
        //créer le formulaire sur le même principe que dans ajouter
        //mais avec un proprietaire existant
        $proprietaire = $doctrine->getRepository(Proprietaire::class)->find($id);

        //je vais gérer le fait que l'id n'existe pas
        if (!$proprietaire){
            throw $this->createNotFoundException("Pas de proprietaire avec l'id $id");
        }

        //Si j'arrive là c'est qu'elle existe en BDD
        //à partir de ça je crée le formulaire
        $form=$this->createForm(ProprietaireType::class, $proprietaire);

        //On gère le retour du formulaire tout de suite
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            //l'objet proprietaire est rempli
            //on va utiliser l'entity manager de doctrine
            $em=$doctrine->getManager();
            //on lui dit qu'on veut mettre le proprietaire dans la table
            $em->persist($proprietaire);

            //on génère l'appel SQL (update ici)
            $em->flush();

            //on revient à l'accueil
            return $this->redirectToRoute("app_proprietaires");
        }

        return $this->render("proprietaires/modifier.html.twig",[
            "proprietaire"=>$proprietaire,
            "formulaire"=>$form->createView()
        ]);
    }

    /**
     * @Route("/proprietaire/supprimer/{id}", name="app_proprietaire_supprimer")
     */
    public function supprimer($id, ManagerRegistry $doctrine, Request $request): Response{
        //créer le formulaire sur le même principe que dans ajouter
        //mais avec une catégorie existante
        $proprietaire = $doctrine->getRepository(Proprietaire::class)->find($id);

        //je vais gérer le fait que l'id n'existe pas
        if (!$proprietaire){
            throw $this->createNotFoundException("Pas de proprietaire avec l'id $id");
        }

        //Si j'arrive là c'est qu'elle existe en BDD
        //à partir de ça je crée le formulaire
        $form=$this->createForm(ProprietaireSupprimerType::class, $proprietaire);

        //On gère le retour du formulaire tout de suite
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            //l'objet proprietaire est rempli
            //on va utiliser l'entity manager de doctrine
            $em=$doctrine->getManager();
            //on lui dit qu'on supprime le proprietaire
            $em->remove($proprietaire);

            //on génère l'appel SQL (delete ici)
            $em->flush();

            //on revient à l'accueil
            return $this->redirectToRoute("app_proprietaires");
        }

        return $this->render("proprietaires/supprimer.html.twig",[
            "proprietaire"=>$proprietaire,
            "formulaire"=>$form->createView()
        ]);
    }
}
