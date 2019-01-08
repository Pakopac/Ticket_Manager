<?php
namespace App\Controller;
use App\Entity\User;
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
        $user = $this->getUser();
        $form = $this->createForm(TicketsType::class, $ticket);
        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // 4) save the User!
            $entityManager = $this->getDoctrine()->getManager();
            $user->addTicket($ticket);
            $entityManager->persist($ticket);
            $entityManager->flush();
            return $this->redirectToRoute('home');
        }
        return $this->render(
            'tickets/index.html.twig',
            array('form' => $form->createView())
        );
    }
    /**
     * @Route("/view_tickets", name="view_tickets")
     */
    public function view_tickets(){
        $repository = $this->getDoctrine()->getRepository(Tickets::class);
        $user = $this->getUser();
        if(in_array("ROLE_ADMIN",$user->getRoles())){
            $ticket = $repository->findAll();
        }
        else{
            $ticket = $user->getTickets();
        }
        return $this->render(
            'tickets/view_tickets.html.twig',
            array('tickets' => $ticket,
                'user' => $user,)
        );
    }
    /**
     * @Route("/ticket/{slug}", name="ticket")
     */
    public function ticket($slug){
        $repository = $this->getDoctrine()->getRepository(Tickets::class);
        $ticket = $repository ->find($slug);
        return $this->render(
            'tickets/ticket.html.twig',
            array('ticket' => $ticket)
        );
    }
}