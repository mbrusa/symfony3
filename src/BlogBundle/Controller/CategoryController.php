<?php

namespace BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use BlogBundle\Entity\Category;
use BlogBundle\Form\CategoryType;
use Symfony\Component\HttpFoundation\Session\Session;

class CategoryController extends Controller
{
    private $session;
    public function __construct(){
        $this->session= new Session();
    }

    public function indexAction(){
        $em = $this->getDoctrine()->getManager();
        $category_repo=$em->getRepository("BlogBundle:Category");
        $categories=$category_repo->findAll();
        
        return $this->render('BlogBundle:Category:index.html.twig',array(
            "categories"=> $categories,
        ));
        
    }

    public function addAction(Request $request)
    {
        $category= new Category();
        $form= $this->createForm(CategoryType::class,$category);
        $form->handleRequest($request);
        
        if($form->isSubmitted()){
            if ($form->isValid()){
                
                $em = $this->getDoctrine()->getManager();
                $category= new Category();
                $category->setName($form->get('name')->getData());
                $category->setDescription($form->get('description')->getData());
                
                $em ->persist($category);
                $flush = $em->flush();
                if($flush==null){
                    $status="La categoría se agregó correctamente";
                }else{
                    $status="ERROR ! No se pudo agregar la categoría";
                }
                $status="Se agrego la categoría";
                return $this->redirectToRoute("blog_index_category");
            }else{
                $status="Formulario inválido";
            }
            $this->session->getFlashBag()->add("status", $status);
            
        }
        
        
        return $this->render('BlogBundle:Category:add.html.twig',array(
            "form"=> $form->createView(),
        ));
    }
    
    public function deleteAction($id){
        $em = $this->getDoctrine()->getManager();
        $category_repo=$em->getRepository("BlogBundle:Category");
        $category=$category_repo->find($id);
        $em->remove($category);
        $flush=$em->flush();
        if($flush==null){
            $status="Se eliminó la categoría correctamente";
        }else{
            $status="No se pudo eliminar la categoría";
        }
        $this->session->getFlashBag()->add("status", $status);
        return $this->redirectToRoute("blog_index_category");
        
    }
     public function editAction(Request $request,$id)
    {
        $em = $this->getDoctrine()->getManager();
        $category_repo=$em->getRepository("BlogBundle:Category");
        $category=$category_repo->find($id);
        $form= $this->createForm(CategoryType::class,$category);
        $form->handleRequest($request);
        
        if($form->isSubmitted()){
            if ($form->isValid()){
                
                
                $category->setName($form->get('name')->getData());
                $category->setDescription($form->get('description')->getData());
                
                $em ->persist($category);
                $flush = $em->flush();
                if($flush==null){
                    $status="La categoría se editó correctamente";
                }else{
                    $status="ERROR ! No se pudo editar la categoría";
                }
                return $this->redirectToRoute("blog_index_category");
            }else{
                $status="Formulario inválido";
            }
            $this->session->getFlashBag()->add("status", $status);
            
        }
        return $this->render('BlogBundle:Category:edit.html.twig',array(
            "form"=> $form->createView(),
        ));
    }
}
