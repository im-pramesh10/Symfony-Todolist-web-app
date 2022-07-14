<?php

namespace App\Controller;

use App\Entity\Todoitems;
use App\Entity\User;
use App\Form\TodoType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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
        // $todo = $this->em->getRepository(Todoitems::class)->find(6);
        // dd($todo->isCompleted());

        // $items = $this->em->getRepository(Todoitems::class)->findBy(["user" => $user]);
        $todo = new Todoitems();
        $form = $this->createForm(TodoType::class, $todo);
        $form->handleRequest($request);
        //dd($form);

        if ($form->isSubmitted() && $form->isValid()) {
            //$user = new User();
            // dd($user);
            /**@var User $user */

            $user = $this->security->getUser(); //getting current logged in user
            $todo->setUser($user);
            $this->em->persist($todo);
            $this->em->flush();
            return $this->redirectToRoute("app_home");
        }

        return $this->render('home/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/ajaxcontrol/ajax", name="app_ajax")
     */
    public function ajaxcontrol(Request $request)
    {
        if ($request->get("getitems") && $request->get("getitems") == 1) {
            /**@var User $user */
            $user = $this->security->getUser(); //getting current logged in user
            $items = $this->em->getRepository(Todoitems::class)->findBy(["user" => $user]);



            return new JsonResponse([
                'html' => $this->renderView('home/showitemstemplate.html.twig', ['items' => $items])
            ]);
        } else if ($request->get("del")) {
            $id = $request->get("del");
            $todoitem = $this->em->getRepository(Todoitems::class)->find($id);
            //dd($todoitem);
            $this->em->remove($todoitem);
            $this->em->flush();
            return new JsonResponse([
                'status' => 'ok',
                'message' => 'deleted successfully'
            ], 200);
        } else if ($request->get("mark")) {
            $id = $request->get("mark");
            $todoitem = $this->em->getRepository(Todoitems::class)->find($id);
            //dd($todoitem);
            $todoitem->setIsCompleted(true);
            $this->em->persist($todoitem);
            $this->em->flush();
            return new JsonResponse([
                'status' => 'ok',
                'message' => 'marked completed successfully'
            ], 200);
        } else {
            return new JsonResponse([
                'status' => 'Error',
                'message' => 'Error'
            ], 500);
        }
    }
}
