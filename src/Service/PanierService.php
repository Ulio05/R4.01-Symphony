<?php
namespace App\Service;

use App\Service\BoutiqueService;
use Symfony\Component\HttpFoundation\RequestStack;

// Service pour manipuler le panier et le stocker en session
class PanierService
{
    ////////////////////////////////////////////////////////////////////////////
    private $session;   // Le service session
    private $boutique;  // Le service boutique
    private $panier;    // Tableau associatif, la clé est un idProduit, la valeur associée est une quantité
    //   donc $this->panier[$idProduit] = quantité du produit dont l'id = $idProduit
    const PANIER_SESSION = 'panier'; // Le nom de la variable de session pour faire persister $this->panier

    // Constructeur du service
    public function __construct(RequestStack $requestStack, BoutiqueService $boutique)
    {
        // Récupération des services session et BoutiqueService
        $this->boutique = $boutique;
        $this->session = $requestStack->getSession();
        // Récupération du panier en session s'il existe, init. à vide sinon
        $this->panier = $this->session->get('panier', []);
    }

    // Renvoie le montant total du panier
    public function getTotal() : float
    {
        $res = 0;
        foreach ($this->panier as $cle => $val) {
            $element = $this->boutique->findProduitById($cle);
            $res += $element->prix * $val;
        }
        return $res;
    }

    // Renvoie le nombre de produits dans le panier
    public function getNombreProduits() : int
    {
        $res = 0;
        foreach ($this->panier as $cle => $val) {
            if ($val !== 0)
                $res++;
        }
        return $res;
    }

    // Ajouter au panier le produit $idProduit en quantite $quantite
    public function ajouterProduit(int $idProduit, int $quantite = 1) : void
    {
        if(isset($this->panier[$idProduit])){
            $this->panier[$idProduit] += $quantite;
        }
        $this->panier += [$idProduit => $quantite];
        $this->session->set('panier', $this->panier);

    }

    // Enlever du panier le produit $idProduit en quantite $quantite
    public function enleverProduit(int $idProduit, int $quantite = 1) : void
    {
        $this->panier[$idProduit] -= $quantite;
        if($this->panier[$idProduit] == 0){
            unset($this->panier[$idProduit]);
        }
        $this->session->set('panier', $this->panier);
    }

    // Supprimer le produit $idProduit du panier
    public function supprimerProduit(int $idProduit) : void
    {
        unset($this->panier[$idProduit]);
        $this->session->set('panier', $this->panier);
    }

    // Vider complètement le panier
    public function vider() : void
    {
        $this->panier = [];
        $this->session->set('panier', $this->panier);
    }

    // Renvoie le contenu du panier dans le but de l'afficher
    //   => un tableau d'éléments [ "produit" => un objet produit, "quantite" => sa quantite ]
    public function getContenu() : array
    {
        $tableau = array();
        $sous_tableau = array();
        foreach ($this->panier as $cle => $val) {
            $sous_tableau['produit'] = $this->boutique->findProduitById($cle);
            $sous_tableau['quantite'] = $val;
            $tableau[] = $sous_tableau;
        }
        return $tableau;
    }

}
