<?php

namespace App\Controller;

use App\Entity\UserDetails;
use App\Entity\User;
use App\Form\UserDetailsType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterController extends AbstractController
{
    /**
     * @Route("/register", name="register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $user = new User();
        $userdetails = new UserDetails();
        $form = $this->createForm(UserDetailsType::class, $userdetails);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            $password = $request->request->get('user_details')['user']['__name__']['password'];
            $username = $request->request->get('user_details')['user']['__name__']['username'];
            $encodedPassword = $encoder->encodePassword($user, $password);
            $user->setPassword($encodedPassword);
            $user->setUserName($username);
            //$userdetails->setUsername($user);
            $userdetails->setUser($user);

            $em = $this->getDoctrine()->getManager();
            $em->persist($userdetails);
            $em->flush();

            return $this->redirectToRoute("main");
        }
        return $this->render('register/register.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
