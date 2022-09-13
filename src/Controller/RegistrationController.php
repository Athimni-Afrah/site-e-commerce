<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\UsersAuthenticator;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
//use Symfony\Contracts\Translation\TranslatorInterface;
//use Symfony\Component\Mailer\Mailer;


class RegistrationController extends AbstractController
{
    private $mailer;


    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(\Swift_Mailer $mailer,UserRepository $userRepository)
    {

        $this->mailer = $mailer;
        $this->userRepository = $userRepository;
    }

    /**
     * @return string
     * @throws \Exception
     */
    private function generateToken()
    {
        return rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
    }

    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, UsersAuthenticator $authenticator, EntityManagerInterface $entityManager,  \Swift_Mailer $mailer): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
        $email= $user->getEmail();
        $nom= $user->getFirstname();

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $pwd = $form->get('plainPassword')->getData();
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $user->setRoles(array("ROLE_USER"));

            $user->setToken($this->generateToken());
            $user->setEnabled(false);
           // $Email =" ";
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $mail = (new \Swift_Message('Bienvenu Smart It Partner'));
            $mail  ->setFrom('a.athamnia@smart-it-partner.com')
                ->setTo($user->getEmail())



                ->setBody(
                    $this->renderView(

                        'send_email/index.html.twig',
                        ['token'=>$user->getToken(),'nom'=>$nom, 'pwd'=>$pwd, 'email'=> $email]
                    ),
                    'text/html'
                );

            $mailer->send($mail);



            // do anything else you need here, like send an email

            /*return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );*/
            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/confirmer-mon-compte/{token}", name="confirm_account")
     * @param string $token
     */
    public function confirmAccount(string $token)
    {
        $user = $this->userRepository->findOneBy(["Token" => $token]);
        if($user) {
          //  $user->setToken(null);
            $user->setEnabled(true);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $this->addFlash("success", "Compte actif !");
            return $this->redirectToRoute("app_login");
        } else {
            $this->addFlash("error", "Ce compte n'exsite pas !");
            return $this->redirectToRoute('app_login');
        }
    }


}
