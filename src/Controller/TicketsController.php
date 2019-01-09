<?php

namespace App\Controller;

use App\Entity\Messages;
use App\Entity\Tickets;
use App\Controller\MessagesController;
use App\Form\TicketsType;
use App\Form\MessagesType;
use App\Repository\TicketsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/tickets")
 */
class TicketsController extends AbstractController
{
    /**
     * @Route("/", name="tickets_index", methods={"GET"})
     */
    public function index(TicketsRepository $ticketsRepository): Response
    {
        $user = $this->getUser();
        if($user) {
            if(in_array("ROLE_ADMIN",$user->getRoles())) {
                return $this->render('tickets/index.html.twig',
                    ['tickets' => $ticketsRepository->findAll()]);
            }
            else{
                return $this->render('tickets/index.html.twig', ['tickets' => $user->getTickets()]);
            }
        }
        else{
            return $this->redirectToRoute('home');
        }
    }

    /**
     * @Route("/new", name="tickets_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $user = $this->getUser();
        if($user) {
            $ticket = new Tickets();
            $form = $this->createForm(TicketsType::class, $ticket);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $user->addTicket($ticket);
                $entityManager->persist($ticket);
                $entityManager->flush();

                return $this->redirectToRoute('tickets_index');
            }

            return $this->render('tickets/new.html.twig', [
                'ticket' => $ticket,
                'form' => $form->createView(),
            ]);
        }
        else{
            return $this->redirectToRoute('home');
        }
    }

    /**
     * @Route("/{id}", name="tickets_show", methods={"GET","POST"})
     */
    public function show(Tickets $ticket, Request $request): Response
    {
        $repository_messages= $this->getDoctrine()->getRepository(Messages::class);
        $getMessages = $repository_messages->findAll();

        $user = $this->getUser();
        $newMessage = new Messages();
        $form = $this->createForm(MessagesType::class, $newMessage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $user->addMessage($newMessage);
            $ticket->addMessage($newMessage);
            $entityManager->persist($newMessage);
            $entityManager->flush();
            $id = $ticket->getId();
            return $this->redirectToRoute('tickets_show',['id' => $id]);
        }
        return $this->render('tickets/show.html.twig',
            ['ticket' => $ticket,
             'form' => $form->createView(),
             'messages' => $getMessages]);
    }

    /**
     * @Route("/{id}/edit", name="tickets_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Tickets $ticket): Response
    {
        $form = $this->createForm(TicketsType::class, $ticket);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('tickets_index', ['id' => $ticket->getId()]);
        }

        return $this->render('tickets/edit.html.twig', [
            'ticket' => $ticket,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="tickets_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Tickets $ticket): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ticket->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($ticket);
            $entityManager->flush();
        }

        return $this->redirectToRoute('tickets_index');
    }
}
