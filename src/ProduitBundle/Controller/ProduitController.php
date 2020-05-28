<?php


namespace ProduitBundle\Controller;


use ProduitBundle\Entity\Produit;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ProduitController extends Controller
{
    public function AjouterProduitAction( \Symfony\Component\HttpFoundation\Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $produit = new Produit();
        $form = $this->createForm('ProduitBundle\Form\ProduitType', $produit);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $produit->setImgProd("3.jpg");
            $produit->getUploadFile();
            $em->persist($produit);
            $em->flush();
            return $this->redirectToRoute('produit_Afficher');
        }
        return $this->render('ProduitBundle:Produit:AjouterProduit.html.twig', array(
            'form' => $form->createView(),

        ));
    }


    public function AfficheProduitAction(Request $request)
    {


        $m = $this->getDoctrine()->getManager();
        $Produit = $m->getRepository("ProduitBundle:Produit")->findAll();

        /**
         * @var $paginator \Knp\Component\Pager\Paginator
         */
        $paginator = $this->get('knp_paginator');
        $result = $paginator->paginate(
            $Produit,
            $request->query->getInt('page',1),
            $request->query->getInt('limit',10)

        );

        return $this->render('ProduitBundle:Produit:AfficherProduit.html.twig', array(
            'prod' => $result
        ));
    }


    public function deleteProduitAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $Pro = $em->getRepository('ProduitBundle:Produit')->find($id);
        $em->remove($Pro);
        $em->flush();


        return $this->redirectToRoute('produit_Afficher');
    }



    public function ModifierProduitAction(\Symfony\Component\HttpFoundation\Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $produit = $em->getRepository('ProduitBundle:Produit')->find($id);
        $editForm = $this->createForm('ProduitBundle\Form\ProduitType', $produit);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $em->persist($produit);
            $em->flush();

            return $this->redirectToRoute('produit_Afficher');
        }
        $em = $this->getDoctrine()->getManager();

        return $this->render('ProduitBundle:Produit:ModifierProduit.html.twig', array(
            'Produit' => $produit,
            'form' => $editForm->createView(),
        ));
    }
    public function AfficheCategorieProduitAction( Request $request , $id)
    {


        $m = $this->getDoctrine()->getManager();
        $Produit = $m->getRepository("ProduitBundle:Produit")->findBy(array("id_categorie"=>$id));
        $Categorie = $m->getRepository("CategorieBundle:Categorie")->findAll();

        /**
         * @var $paginator \Knp\Component\Pager\Paginator
         */
        $paginator = $this->get('knp_paginator');
        $result = $paginator->paginate(

            $Produit,
            $request->query->getInt('page',1),
            $request->query->getInt('limit',5)

        );


        return $this->render('ProduitBundle:Produit:AfficherProduitCategorie.html.twig', array(
            'prod' => $result,
            'cat'=> $Categorie

        ));
    }
}