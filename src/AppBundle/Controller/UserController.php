<?php
// src/AppBundle/Controller/UserController.php
namespace AppBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
class UserController extends Controller
{
    /**
     * NOTE: I don't return a response in the UsernamePasswordGuardAuthenticator
     *       because I wanted a controller to do the rendering. You could always
     *       return a JsonResponse in UsernamePasswordGuardAuthenticator::onAuthenticationSuccess().
     *       If you do that, this class/method is no longer required.
     */
    public function loginAction()
    {
        $token = $this->get('jwt_coder')->encode([
            'username' => $this->getUser()->getUsername()
        ]);
        return new JsonResponse(['token' => $token]);
    }

    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $users = $em->getRepository('AppBundle:User')->findAll();

        return $this->render('AppBundle:User:index.html.twig', array('users' => $users));

    }

    public function viewAction($id)
    {
        $repository = $this->getDoctrine()->getRepository('UserBundle:User');


        $user = $repository->find($id);

        return new Response('Ususario: '. $user->getUsername());
    }
}