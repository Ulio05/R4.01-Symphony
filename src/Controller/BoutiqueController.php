<?php

namespace App\Controller;

use App\Service\BoutiqueService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class BoutiqueController extends AbstractController
{
    private BoutiqueService $boutiqueService;
    #[Route('/{_locale}/boutique',
        name: 'app_boutique_index',
        requirements: ['_locale' => '%app.supported_locales%'],
    )]
    public function index(BoutiqueService $boutique): Response
    {
        $this->boutiqueService = $boutique;
        $categories = $boutique->findAllCategories();
        return $this->render('boutique/index.html.twig', [
            'controller_name' => 'BoutiqueController',
            'categories' => $categories,
        ]);
    }

    #[Route('/{_locale}/boutique/rayon/{idCategorie}',
        name : 'app_boutique_rayon',
        requirements: ['_locale' => '%app.supported_locales%'],
    )]
    public function rayon(int $idCategorie,BoutiqueService $boutique): Response
    {
        $produit = $boutique->findProduitsByCategorie($idCategorie);
        return $this->render('boutique/rayon.html.twig', [
            'controller_name' => 'BoutiqueController',
            'produit' => $produit,
            'categorie' => $boutique->findCategorieById($idCategorie),
        ]);
    }

    #[ Route(
        path: '/chercher/{recherche}',
        name: 'app_boutique_chercher',
        requirements: ['recherche' => '.+'], // regexp pour avoir tous les car, / compris
        defaults: ['recherche' => ''])]
    public function chercher(BoutiqueService $boutique, string $recherche) : Response {
        $resultat = $boutique->findProduitsByLibelleOrTexte( urldecode($recherche));
        return $this->render('boutique/chercher.html.twig', [
            'controller_name' => 'BoutiqueController',
            'resultat' => $resultat,
            'recherche' => $recherche,
        ]);
    }
}
