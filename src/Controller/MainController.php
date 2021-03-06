<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function register(Request $request)
    {
        if($this->getUser()){
            return $this->redirectToRoute('tickets_index');
        }
        else{
            return $this->redirectToRoute('app_login');
        }
    }
}