<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/product/create", name="product_create")
     * @param Request $request
     * @return Response
     */
    public function create(Request $request)
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Ajouter le produit en BDD
            $manager = $this->getDoctrine()->getManager();

            $manager->persist($product);
            $manager->flush();
        }

        return $this->render('product/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/product/{id}", name="product_show")
     * @param Product $product
     * @return Response
     */
    public function show(Product $product)
    {
        return $this->render('product/show.html.twig', [
            'product' => $product
        ]);
    }

    /**
     * Créer la route /product où on affichera tous les produits de la base de données.
     * On utilisera les cards de Bootstrap.
     *
     * @Route("/product", name="product_list")
     * @param ProductRepository $repository
     * @return Response
     */
    public function list(ProductRepository $repository)
    {
        $products = $repository->findAll();

        return $this->render('product/list.html.twig', [
           'products' => $products
        ]);
    }

    /**
     * @Route("/product/edit/{id}", name="product_edit")
     * @param Request $request
     * @param Product $product
     * @return Response
     */
    public function edit(Request $request, Product $product)
    {
        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Le persist est optionnel
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Le produit '.$product->getId().' a bien été modifié.');

            return $this->redirectToRoute('product_list');
        }

        return $this->render('product/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/product/delete/{id}", name="product_delete", methods={"POST"})
     * @param Request $request
     * @param Product $product
     * @return RedirectResponse
     */
    public function delete(Request $request, Product $product)
    {
        if (!$this->isCsrfTokenValid('delete', $request->get('token'))) {
            return $this->redirectToRoute('product_list');
        }

        // $em = $entityManager
        $em = $this->getDoctrine()->getManager();
        $em->remove($product);
        $em->flush();

        $this->addFlash('success', 'Le produit '.$product->getName().' a bien été supprimé');

        return $this->redirectToRoute('product_list');
    }
}
