<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('UserBundle::layout.html.twig');
    }
    public function ClientAction(Request $request)
    {

        $m = $this->getDoctrine()->getManager();
        $Produit = $m->getRepository("ProduitBundle:Produit")->findAll();
        $Categorie = $m->getRepository("CategorieBundle:Categorie")->findAll();
        /**
         * @var $paginator \Knp\Component\Pager\Paginator
         */
        $paginator = $this->get('knp_paginator');
        $result = $paginator->paginate(
            $Produit,
            $request->query->getInt('page',1),
            $request->query->getInt('limit',10)

        );
        return $this->render('UserBundle::Page_client.html.twig', array(
            'prod' => $result,
            'cat'=> $Categorie
        ));
    }
    public function FornisseurAction()
    {
        return $this->render('UserBundle::Page_fornisseur.html.twig');
    }
    public function AdminAction()
    {
        return $this->render('UserBundle::Page_admin.html.twig');
    }





}
