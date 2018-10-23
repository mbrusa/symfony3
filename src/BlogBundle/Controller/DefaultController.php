<?php

namespace BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use BlogBundle\Entity\Entry;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('BlogBundle:Default:index.html.twig');
    }
    
    public function index2Action()
    {
        return $this->render('BlogBundle::layout.html.twig');
    }
    
     public function manyToOneAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entry_repo = $em->getRepository("BlogBundle:Entry");
        $entries= $entry_repo->findAll();
        
        foreach ($entries as $entry){
            echo $entry->getTitle()."<br>";
            echo $entry->getUser()->getName()."<br>";
            echo $entry->getCategory()->getName()."<br>";
        $tags=$entry->getEntryTag();
        foreach ($tags as $tag){
            echo $tag->getTag()->getName()." - ";
        }
        echo "<hr/>";
        }
        
         
        die(); 
        return $this->render('BlogBundle:Default:index.html.twig');
    }
    
     public function manyToOne2Action()
    {
        $em = $this->getDoctrine()->getManager();
        $cate_repo = $em->getRepository("BlogBundle:Category");
        $categories= $cate_repo->findAll();
        
        foreach ($categories as $category){
            echo $category->getName()."<br>";
            
        $entries=$category->getEntries();
        foreach ($entries as $entry){
            echo $entry->getTitle()." - ";
        }
        echo "<hr/>";
        }
        
         
        die(); 
        return $this->render('BlogBundle:Default:index.html.twig');
    }
    
    public function manyToOne3Action()
    {
        $em = $this->getDoctrine()->getManager();
        $user_repo = $em->getRepository("BlogBundle:User");
        $users= $user_repo->findAll();
        
        foreach ($users as $user){
            echo $user->getName()."<br>";
            
        $entries=$user->getEntries();
        foreach ($entries as $entry){
            echo $entry->getTitle()." - ";
        }
        echo "<hr/>";
        }
        
         
        die(); 
        return $this->render('BlogBundle:Default:index.html.twig');
    }
    
    public function manyToOne4Action()
    {
        $em = $this->getDoctrine()->getManager();
        $tag_repo = $em->getRepository("BlogBundle:Tag");
        $tags= $tag_repo->findAll();
        
        foreach ($tags as $tag){
            echo $tag->getName()."<br>";
            
        $entries=$tag->getEntryTag();
        foreach ($entries as $entry){
            echo $entry->getEntry()->getTitle()." - ";
        }
        echo "<hr/>";
        }
        
         
        die(); 
        return $this->render('BlogBundle:Default:index.html.twig');
    }
    
    public function deniedAction()
    {
        return $this->render('BlogBundle:Errors:denied.html.twig');
    }
    
}
