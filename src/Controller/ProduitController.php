<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;




/**
 * @Route("/produit")
 */
class ProduitController extends AbstractController
{
    /**
     * @Route("/", name="app_produit_index", methods={"GET"})
     */
    public function index(ProduitRepository $produitRepository): Response
    {
        return $this->render('produit/index.html.twig', [
            'produits' => $produitRepository->findAll(),
        ]);
    }

    /**
     * @Route("/pdf/{id}", name="app_pdf")
     */
    /*
    public function generatePdfProduit(Produit $produit = null, PdfService $pdf){
        $pdfOptions = new Options();

        $html = $this->render('produit/index.html.twig',
            ['produits' => $produit]);
            $pdf->showPdfFile($html);

    }
    */


    /**
     * @Route("/new", name="app_produit_new", methods={"GET", "POST"})
     */
    public function new(Request $request, ProduitRepository $produitRepository): Response
    {
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

//***********  Ajouter un image
            $image = $form->get('image')->getData();
            if ($image) {
                $originalimage = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $newimageFilename = $originalimage . '-' . uniqid() . '.' . $image->guessExtension();
                try {
                    $image->move(
                        $this->getParameter('photos_directory'),
                        $newimageFilename
                    );

                } catch (FileException $e) {
                }


                $produit->setimage($newimageFilename);
            }
//*********** Ajouter un pdf
                $brochure = $form->get('brochure')->getData();

                if ($brochure) {
                    $originalbrochure = pathinfo($brochure->getClientOriginalName(), PATHINFO_FILENAME);
                    $newbrochureFilename = $originalimage . '-' . uniqid() . '.' . $brochure->guessExtension();
                    try {
                        $brochure->move(
                            $this->getParameter('brochures_directory'),
                            $newimageFilename
                        );

                    } catch (FileException $e) {
                    }


                    $produit->setBrochure($newbrochureFilename);





                }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($produit);
            $entityManager->flush();
//********


                return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->renderForm('produit/new.html.twig', [
                'produit' => $produit,
                'form' => $form,
            ]);

    }


    /**
     * @Route("/{id}", name="app_produit_show", methods={"GET"})
     */
    public function show(Produit $produit): Response
    {
        return $this->render('produit/show.html.twig', [
            'produit' => $produit,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_produit_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Produit $produit, ProduitRepository $produitRepository): Response
    {
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $produitRepository->add($produit, true);
            $image = $form->get('image')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($image) {
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$image->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $image->move(
                        $this->getParameter('produit_diractory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the '$image' property to store the PDF file name
                // instead of its contents
                $produit->setImage($newFilename);
            }

            return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('produit/edit.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_produit_delete", methods={"POST"})
     */
    public function delete(Request $request, Produit $produit, ProduitRepository $produitRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$produit->getId(), $request->request->get('_token'))) {
            $produitRepository->remove($produit, true);
        }

        return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
    }

}
