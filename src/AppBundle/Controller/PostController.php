<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PostController extends Controller
{
	/**
	*@Route("/post",name="view_post_route")
	*/

	public function viewPostAction() {
		$posts = $this->getDoctrine()->getRepository('AppBundle:Post')->findAll();


		return $this->render("pages/index.html.twig",['posts'=>$posts]);
	}


	/**
	*@Route("/post/create",name="create_post_route")
	*/
	public function createPostAction(Request $request) {
		$post = new Post();
		$form= $this->createFormBuilder($post)
		->add('title', TextType::Class, array('attr'=>array('class'=>'form-control')))
		->add('description', TextareaType::Class, array('attr'=>array('class'=>'form-control')))
		->add('category', TextType::Class, array('attr'=>array('class'=>'form-control')))
		->add('save', SubmitType::Class, array('label'=> 'Create Post','attr'=>array('class'=>'btn btn-primary','style'=>'margin-top:10px')))
		->getForm();
		$form->handleRequest($request);
		if($form->isSubmitted() && $form->isValid()){

			$title= $form['title']->getData();
			$description=  $form['description']->getData();
			$category= $form['category']->getData();

			$post->setTitle($title);
			$post->setTitle($description);
			$post->setTitle($title);

			$em = $this->getDoctrine()->getManager();
			$em->persist($post);
			$em->flush();

			$this->addFlash('message','Post saved Successfully!');
			return $this->redirectToRoute('view_post_route');

		}

		return $this->render("pages/create.html.twig",[
			'form'=> $form->createView()
			]);
	}

	/**
	*@Route("/post/update/{id}",name="update_post_route")
	*/

	public function updatePostAction(Request $request, $id) {
		$posts = $this->getDoctrine()->getRepository('AppBundle:Post')->find($id);
		$posts->setTitle($posts->getTitle());
		$posts->setDescription($posts->getDescription());
		$posts->setCategory($posts->getCategory());

		$form= $this->createFormBuilder($posts)
		->add('title', TextType::Class, array('attr'=>array('class'=>'form-control')))
		->add('description', TextareaType::Class, array('attr'=>array('class'=>'form-control')))
		->add('category', TextType::Class, array('attr'=>array('class'=>'form-control')))
		->add('save', SubmitType::Class, array('label'=> 'Update Post','attr'=>array('class'=>'btn btn-primary','style'=>'margin-top:10px')))
		->getForm();
			$form->handleRequest($request);
			if($form->isSubmitted() && $form->isValid()){

			$title= $form['title']->getData();
			$description=  $form['description']->getData();
			$category= $form['category']->getData();

			$em = $this->getDoctrine()->getManager();
			$posts= $em->getRepository('AppBundle:Post')->find($id);

			$posts->setTitle($title);
			$posts->setDescription($description);
			$posts->setCategory($category);

			$em->flush();

			$this->addFlash('message','Post updted Successfully!');
			return $this->redirectToRoute('view_post_route');

		}
		return $this->render("pages/update.html.twig",[

			'form'=>$form->createView()]);
	}

	/**
	*@Route("/post/delete/{id}",name="delete_post_route")
	*/

	public function deletePostAction($id){
		$em = $this->getDoctrine()->getManager();
		$post= $em->getRepository('AppBundle:Post')->find($id);
		$postTotal= $em->getRepository('AppBundle:Post')->findAll();
		
		$em->remove($post);

		$em->flush();

			$this->addFlash('message','Post Delete Successfully!');
			return $this->redirectToRoute('view_post_route');

	
	
	}

	/**
	*@Route("/post/show/{id}",name="show_post_route")
	*/

	public function showPostAction( $id) {

		$posts = $this->getDoctrine()->getRepository('AppBundle:Post')->find($id);

		return $this->render("pages/view.html.twig",['posts'=> $posts]);

	}

}
