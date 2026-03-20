<?php

namespace App\Controller;

use App\Service\PanierService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PanierController extends AbstractController
{

    #[Route('{_locale}/panier', name: 'app_panier_index')]
    public function index(PanierService $panier): Response
    {
        return $this->render('panier/index.html.twig', [
            'controller_name' => 'PanierController',
            'panier' => $panier->getContenu(),
            'prixTotal' => $panier->getTotal(),
            'nbProduit' => $panier->getNombreProduits(),
        ]);
    }

    #[Route('{_locale}/panier/ajouter/{idProduit}/{quantite}', name: 'app_panier_ajouter')]
    public function ajouter(int $idProduit, int $quantite,PanierService $panier): Response
    {
        $panier->ajouterProduit($idProduit, $quantite);
        return $this->redirectToRoute('app_panier_index');
    }

    #[Route('{_locale}/panier/enlever/{idProduit}/{quantite}', name: 'app_panier_enlever')]
    public function enlever(int $idProduit, int $quantite,PanierService $panier): Response
    {
        $panier->enleverProduit($idProduit, $quantite);
        return $this->redirectToRoute('app_panier_index');
    }

    #[Route('{_locale}/panier/enlever/{idProduit}', name: 'app_panier_supprimer')]
    public function supprimer(int $idProduit,PanierService $panier): Response
    {
        $panier->supprimerProduit($idProduit);
        return $this->redirectToRoute('app_panier_index');
    }

    #[Route('{_locale}/panier/vider', name: 'app_panier_vider')]
    public function vider(int $idProduit,PanierService $panier): Response
    {
        $panier->vider();
        return $this->redirectToRoute('app_panier_index');
    }


    public function nombreProduits(PanierService $panier): Response {
        return new Response($panier->getNombreProduits());
    }
}
