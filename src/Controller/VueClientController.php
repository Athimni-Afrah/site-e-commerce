<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VueClientController extends AbstractController
{
    /**
     * @Route("/", name="app_vue_client")
     */
    public function index(): Response
    {
        return $this->render('vue_client/index.html.twig', [
            'controller_name' => 'VueClientController',
        ]);
    }
}
