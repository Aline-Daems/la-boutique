<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;

use App\Classe\Mail;
use Doctrine\ORM\EntityManagerInterface;
use mysql_xdevapi\BaseResult;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class RegisterController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager ) {
        $this ->entityManager = $entityManager;
    }
    #[Route('/register', name: 'register')]

    public function index(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $notification = null;
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);

        $form->handleRequest($request);
        if ($form ->isSubmitted() && $form->isValid()){
            $user = $form->getData();
            $search_email = $this->entityManager->getRepository(User::class)->findOneByEmail($user->getEmail());

            if(!$search_email){
                $password = $encoder->encodePassword($user,$user->getPassword());
                $user->setPassword($password);

                $this->entityManager->persist($user);
                $this->entityManager->flush();

                $mail = new Mail();
                $content = 'Bonjour'.$user->getFirstname()."<br/> Bienvenue sur la boutique Tiny and pretty";
                $mail->send($user->getEmail(), $user->getFirstname(), 'bienvenue sur Tiny and Pretty', $content);
                $notification='Votre inscription a été validée';
            }else {
                $notification='Votre inscription a été refusée. Email déjà existant';
            }


        }
        return $this->render('register/index.html.twig', [
            'form' => $form->createView(),
            'notification' => $notification
        ]);
    }
}
