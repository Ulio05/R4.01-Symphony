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
        ]);
    }

    #[Route('{_locale}/panier/ajouter/{idProduit}/{quantite}', name: 'app_panier_ajouter')]
    public function ajouter(PanierService $panier): Response
    {
        return $this->render('panier/index.html.twig', [
            'controller_name' => 'PanierController',
            'panier' => $panier->getContenu(),
        ]);
    }
}
