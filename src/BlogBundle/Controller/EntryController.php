<?php

namespace BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use BlogBundle\Entity\Entry;
use BlogBundle\Form\EntryType;
use Symfony\Component\HttpFoundation\Session\Session;

class EntryController extends Controller
{
    private $session;
    public function __construct(){
        $this->session= new Session();
    }

    public function indexAction(){
        $em = $this->getDoctrine()->getManager();
        $entry_repo=$em->getRepository("BlogBundle:Entry");
        $entries=$entry_repo->findAll();
//        
//        $et_repo=$em->getRepository("BlogBundle:EntryTag");
//        $ets=$et_repo->findBy(["entry"=>$entry]);
//        foreach ($ets as $et){
        
        
        return $this->render('BlogBundle:Entry:index.html.twig',[
    "entries"=> $entries]
        );
        
    }

    public function addAction(Request $request)
    {
        $entry=new Entry();
        $form= $this->createForm(EntryType::class,$entry);
        $form->handleRequest($request);
        
        if($form->isSubmitted()){
            if ($form->isValid()){
                
                $entry=new Entry();
                $entry->setTitle($form->get('title')->getData());
                $entry->setContent($form->get('content')->getData());
                $entry->setStatus($form->get('status')->getData());
                
                //upload file
                $file=$form["image"]->getData();
                $ext=$file->guessExtension();
                $file_name= time().".".$ext;
                $file->move("uploads",$file_name);
                $entry->setImage($file_name);
                
                $em = $this->getDoctrine()->getManager();
                $category_repo=$em->getRepository("BlogBundle:Category");
                $category=$category_repo->find($form->get('category')->getData());
                $entry->setCategory($category);
                
                $user_repo=$em->getRepository("BlogBundle:User");
                $user=$user_repo->find($form->get('user')->getData());
                $entry->setUser($user);
                
                $entry_repo=$em->getRepository("BlogBundle:Entry");
                $entry_repo->saveEntryTags(
                        $form->get('tags')->getData(),
                        $form->get('title')->getData(),
                        $category,
                        $user,
                        $entry
                        );
                
                
                $em ->persist($entry);
                $flush = $em->flush();
                if($flush==null){
                    $status="La entrada se agregó correctamente";
                }else{
                    $status="ERROR ! No se pudo agregar la entrada";
                }
                $status="Se agrego la entrada";
                $this->session->getFlashBag()->add("status", $status);
                return $this->redirectToRoute("blog_index_entry");
                
            }else{
                $status="Formulario inválido";
            }
            //$this->session->getFlashBag()->add("status", $status);
            
        }
        
        
        return $this->render('BlogBundle:Entry:add.html.twig',array(
            "form"=> $form->createView(),
        ));
    }

     public function editAction(Request $request,$id)
    {
        $em = $this->getDoctrine()->getManager();
        $entry_repo=$em->getRepository("BlogBundle:Entry");
        $entry=$entry_repo->find($id);
        
        $tags="";
        $entryTag=$entry->getEntryTag();
        foreach ($entryTag as $et){
            $tags.=$et->getTag()->getName().",";
        }
        
        
        $form= $this->createForm(EntryType::class,$entry);
        $form->handleRequest($request);
        
        if($form->isSubmitted()){
            if ($form->isValid()){
                
                
                $entry->setTitle($form->get('title')->getData());
                $entry->setContent($form->get('content')->getData());
                $entry->setStatus($form->get('status')->getData());
                
                //upload file
                $file=$form["image"]->getData();
                $ext=$file->guessExtension();
                $file_name= time().".".$ext;
                $file->move("uploads",$file_name);
                $entry->setImage($file_name);
                
                $em = $this->getDoctrine()->getManager();
                $category_repo=$em->getRepository("BlogBundle:Category");
                $category=$category_repo->find($form->get('category')->getData());
                $entry->setCategory($category);
                
                $user_repo=$em->getRepository("BlogBundle:User");
                $user=$user_repo->find($form->get('user')->getData());
                $entry->setUser($user);
                
                
                $entryTag_repo=$em->getRepository("BlogBundle:EntryTag");
                $ets=$entryTag_repo->findBy(["entry"=>$entry]);
                foreach ($ets as $et){
                    $em->remove($et);
                    $em->flush();
                }
                
                
                $entry_repo=$em->getRepository("BlogBundle:Entry");
                $entry_repo->saveEntryTags(
                        $form->get('tags')->getData(),
                        $form->get('title')->getData(),
                        $category,
                        $user,
                        $entry
                        );
                
                $em ->persist($entry);
                
                $flush = $em->flush();
                if($flush==null){
                    $status="La entrada se editó correctamente";
                }else{
                    $status="ERROR ! No se pudo editar la entrada";
                }
                return $this->redirectToRoute("blog_index_entry");
            }else{
                $status="Formulario inválido";
            }
            $this->session->getFlashBag()->add("status", $status);
            
        }
        return $this->render('BlogBundle:Entry:edit.html.twig',array(
            "form"=> $form->createView(),
            "tags"=> $tags
        ));
    }
    
    public function deleteAction($id){
        $em = $this->getDoctrine()->getManager();
        $entry_repo=$em->getRepository("BlogBundle:Entry");
        $entry=$entry_repo->find($id);
        
        $entryTag_repo=$em->getRepository("BlogBundle:EntryTag");
        $ets=$entryTag_repo->findBy(["entry"=>$entry]);
        foreach ($ets as $et){
            $em->remove($et);
            $em->flush();
        }
        
        $em->remove($entry);
        $flush=$em->flush();
        if($flush==null){
            $status="Se eliminó la entrada correctamente";
        }else{
            $status="No se pudo eliminar la entrada";
        }
        $this->session->getFlashBag()->add("status", $status);
        return $this->redirectToRoute("blog_index_entry");
        
    }
    
    public function entradasAction(Request $request) {
        $em = $this->getDoctrine()->getEntityManager();
        $dql = "SELECT e FROM BlogBundle:Entry e";
        $query = $em->createQuery($dql);
 
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $query,
                $request->query->getInt('page', 1),
                10
        );
 
        return $this->render('BlogBundle:Entry:listado.html.twig',
                array('pagination' => $pagination));
    }

}
