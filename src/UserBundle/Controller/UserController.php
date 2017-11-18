<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        
        $users = $em->getRepository('UserBundle:User')->findAll();
        
        $res = 'Lista de usuarios <br/>';
        
        foreach($users as $user)
        {
            $res .= 'Usuario: ' . $user->getUsername();
            
            return new Response($res);
        }
        
    }
    
    public function viewAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        
        $users = $em->getRepository('UserBundle:User')->();
    }
}
