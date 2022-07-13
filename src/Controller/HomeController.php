<?php

namespace App\Controller;

use App\Entity\Todoitems;
use App\Entity\User;
use App\Form\TodoType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class HomeController extends AbstractController
{
    function __construct(EntityManagerInterface $em, Security $security)
    {
        $this->em = $em;
        $this->security = $security;
    }
    /**
     * @Route("/home", name="app_home")
     */
    public function index(Request $request): Response
    {
        $todo = new Todoitems();
        $form = $this->createForm(TodoType::class, $todo);
        $form->handleRequest($request);
        //dd($form);

        if ($form->isSubmitted() && $form->isValid()) {
            //$user = new User();
            /**@var User $user */
            $user = $this->security->getUser(); //getting current logged in user
            // dd($user);
            $todo->setUser($user);
            $this->em->persist($todo);
            $this->em->flush();
        }

        return $this->render('home/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
