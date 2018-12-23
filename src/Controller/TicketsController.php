<?php

namespace App\Controller;

use App\Form\TicketsType;
use App\Entity\Tickets;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TicketsController extends AbstractController
{
    /**
     * @Route("/new_tickets", name="new_tickets")
     */
    public function new_tickets(Request $request)
    {
        // 1) build the form
        $ticket = new Tickets();
        $form = $this->createForm(TicketsType::class, $ticket);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            // 4) save the User!
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($ticket);
            $entityManager->flush();

            return $this->redirectToRoute('home');
        }
        return $this->render(
            'tickets/index.html.twig',
            array('form' => $form->createView())
        );
    }
}
