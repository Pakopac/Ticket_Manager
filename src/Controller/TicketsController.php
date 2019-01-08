<?php
namespace App\Controller;
use App\Entity\Messages;
use App\Entity\User;
use App\Form\AssignToType;
use App\Form\TicketsType;
use App\Form\MessagesType;
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
            'tickets/new_ticket.html.twig',
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
    public function ticket(Request $request, $slug){
        $repository_tickets = $this->getDoctrine()->getRepository(Tickets::class);
        $ticket = $repository_tickets ->find($slug);

        $repository_messages= $this->getDoctrine()->getRepository(Messages::class);
        $getMessages = $repository_messages->findAll();

        $repository_users= $this->getDoctrine()->getRepository(User::class);
        $getAllUsers = $repository_users->findAll();

        // 1) build the form
        $message = new Messages();
        $user = $this->getUser();
        $form = $this->createForm(MessagesType::class, $message);
        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // 4) save the User!
            $entityManager = $this->getDoctrine()->getManager();
            $user->addMessage($message);
            $ticket->addMessage($message);
            $entityManager->persist($message);
            $entityManager->flush();
            return $this->redirectToRoute('ticket', ['slug' => $slug]);
        }

        $ticketAssign = new Tickets();
        $formAssign = $this->createForm(AssignToType::class,$ticketAssign);
        // 2) handle the submit (will only happen on POST)
        $formAssign->handleRequest($request);
        if ($formAssign->isSubmitted() && $formAssign->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('ticket', ['slug' => $slug]);
        }

        return $this->render(
            'tickets/ticket.html.twig',
            array('ticket' => $ticket,
                'form' => $form->createView(),
                'messages' => $getMessages,
                'users' => $getAllUsers,
                'formAssign' => $formAssign->createView())
        );
    }
    /**
     * @Route("edit/{slug}", name="edit")
     */
    public function edit(){
        return $this->render(
            'tickets/edit.html.twig'
        );
    }
}