<?php

namespace App\Controller;

use App\Repository\CategorieRepository;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;


class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="app_admin")
     *
     */
    public function index(UserRepository $userrepository, ProduitRepository $produitRepository, CategorieRepository $categorieRepository): Response
    {
        $TotalUser= $userrepository->getTotalUser();
        $TotalProduit= $produitRepository->getTotalProduit();
        $TotalCategorie = $categorieRepository->getTotalCategorie();



        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
            'TotalUser' => $TotalUser,
            'TotalProduit' => $TotalProduit,
            'produits' => $produitRepository->findAll(),
            'TotalCategorie' => $TotalCategorie,
            'categorie'=> $categorieRepository->findAll(),

        ]);
    }
}
