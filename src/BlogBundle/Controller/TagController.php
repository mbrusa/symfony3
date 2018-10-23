<?php

namespace BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use BlogBundle\Entity\Tag;
use BlogBundle\Form\TagType;
use BlogBundle\Form\TagType2;
use Symfony\Component\HttpFoundation\Session\Session;

class TagController extends Controller
{
    private $session;
    public function __construct(){
        $this->session= new Session();
    }

    public function indexAction(){
        $em = $this->getDoctrine()->getManager();
        $tag_repo=$em->getRepository("BlogBundle:Tag");
        $tags=$tag_repo->findAll();
        
        return $this->render('BlogBundle:Tag:index.html.twig',array(
            "tags"=> $tags,
        ));
        
    }

    public function addAction(Request $request)
    {
        $tag= new Tag();
        $form= $this->createForm(TagType::class,$tag);
        $form->handleRequest($request);
        
        if($form->isSubmitted()){
            if ($form->isValid()){
                
                $em = $this->getDoctrine()->getManager();
                $tag=new Tag();
                $tag->setName($form->get('name')->getData());
                $tag->setDescription($form->get('description')->getData());
                
                $em ->persist($tag);
                $flush = $em->flush();
                if($flush==null){
                    $status="La etiqueta se agregó correctamente";
                }else{
                    $status="ERROR ! No se pudo agregar la etiqueta";
                }
                $status="Se agrego la etiqueta";
                $this->session->getFlashBag()->add("status", $status);
                return $this->redirectToRoute("blog_index_tags");
            }
            
        }
        
        
        return $this->render('BlogBundle:Tag:add.html.twig',array(
            "form"=> $form->createView(),
        ));
    }
    
    public function add2Action(Request $request)
    {
        $tag= new Tag();
        $form= $this->createForm(TagType2::class,$tag);
        $form->handleRequest($request);
        
        if($form->isSubmitted()){
           
                
                $fecha=$form->get('fecha')->getData();
                echo $fecha->getYear();
                
              die();
           
            
        }
        
        
        return $this->render('BlogBundle:Tag:add2.html.twig',array(
            "form"=> $form->createView(),
        ));
    }
    
    public function deleteAction($id){
        $em = $this->getDoctrine()->getManager();
        $tag_repo=$em->getRepository("BlogBundle:Tag");
        $tag=$tag_repo->find($id);
        
        $et_repo=$em->getRepository("BlogBundle:EntryTag");
        $ets=$et_repo->findBy(["tag"=>$tag]);
        foreach ($ets as $et){
            $em->remove($et);
            $em->flush();
        }
        
        
        
        
        $em->remove($tag);
        $flush=$em->flush();
        if($flush==null){
            $status="Se eliminó la etiqueta correctamente";
        }else{
            $status="No se pudo eliminar la etiqueta";
        }
        $this->session->getFlashBag()->add("status", $status);
        return $this->redirectToRoute("blog_index_tags");
        
    }
    public function borrarAction(){
                
        return $this->render('BlogBundle:Tag:borrar.html.twig');
        
    }
}
