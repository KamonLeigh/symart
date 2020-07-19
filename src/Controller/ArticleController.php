<?php

namespace App\Controller;

use App\Entity\Article;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;




class ArticleController extends AbstractController
{
    /**
     * @Route("/", name="article_list")
     * @Method({"GET"})
     */
    public function index()
    {
        $articles = $this->getDoctrine()->getRepository(Article::class)->findAll();
        return $this->render('article/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    
     /**
      * @Route("/article/new", name="new_article")
      * @Method({"GET", "POST"})
      */

      public function new(Request $request) {
        $article = new Article();
        $article = $this->getDoctrine()->getRepository(Article::class)
                    ->find($id);

        $form = $this->createFormBuilder($article)
                ->add('title', TextType::class, array('attr' => array('class' => 'form-control '))) 
                ->add('body', TextareaType::class, array(
                    'required' => false,
                    'attr' => array('class' => 'form-control')
                ))
                ->add('save', SubmitType::class, array(
                    'label' => 'Update',
                    'attr' => array('class' => 'btn btn-primary mt-3')
                ))
                ->getForm();

                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) {
                   
                    $entity = $this->getDoctrine()->getManager();
                
                    $entity->flush();

                    return $this->redirectToRoute('article_list');
                }

                return $this->render('article/edit.html.twig', [
                    'form' => $form->createView()
                ]);

      }

    /**
      * @Route("/article/edit/{id}", name="edit_article")
      * @Method({"GET", "POST"})
      */

      public function edit(Request $request, $id) {
        $article = new Article();
        $article = $this->getDoctrine()->getRepository(Article::class)
                    ->find($id);

        $form = $this->createFormBuilder($article)
                ->add('title', TextType::class, array('attr' => array('class' => 'form-control '))) 
                ->add('body', TextareaType::class, array(
                    'required' => false,
                    'attr' => array('class' => 'form-control')
                ))
                ->add('save', SubmitType::class, array(
                    'label' => 'Update',
                    'attr' => array('class' => 'btn btn-primary mt-3')
                ))
                ->getForm();

                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) {
                    

                    $entity = $this->getDoctrine()->getManager();
                    
                    $entity->flush();

                    $this->addFlash('success', 'Your changes were saved!');

                    return $this->redirectToRoute('article_list');
                }

                return $this->render('article/new.html.twig', [
                    'form' => $form->createView()
                ]);

      }


      

      /**
     * @Route("/article/{id}",  name="article_show")
     */
    //  /article/1
     public function show($id) {
         $article = $this->getDoctrine()->getRepository(Article::class)->find($id);

         return $this->render('article/show.html.twig',[
             'article' => $article,
         ]);

     }

     /**
      * @Route("articles/delete/{id}")
      * @Method({"DELETE"})
      */

      public function delete(Request $requst, $id) {
        $article = $this->getDoctrine()->getRepository(Article::class)
                    ->find($id);
        
        $enity = $this->getDoctrine()->getManager();
        $enity->remove($article);
        $enity->flush();

        $reponse = new Response();
        $reponse->send();
      }


    // /**
    //  * @Route("/article/save")
    //  */

    //  public function save() {
    //      $entity = $this->getDoctrine()->getManager();

    //      $article = new Article();
    //      $article->setTitle('Article 2');
    //      $article->setBody('This the body for article 2');

    //      $entity->persist($article);

    //      $entity->flush();

    //      return new Response('Saves an artile with an id '.$article->getId());
    //  }
}
