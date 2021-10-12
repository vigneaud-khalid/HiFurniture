<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Admin;
use App\Entity\Client;
use App\Entity\Category;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/admin/register", name="admin_register")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function registerAdmin(Request $request, UserPasswordHasherInterface $passHasher): Response{
        //Cette fonction permettra à un simple utilisateur de créer un compte sur notre Application en remplissant le formulaire présenté.
        //Pour enregistrer l'Utilisateur, nous avons besoin de l'Entity Manager
        $entityManager = $this->getDoctrine()->getManager();
        //Nous récupérons la liste des Categories pour notre navbar
        $categoryRepository = $entityManager->getRepository(Category::class);
        $categories = $categoryRepository->findAll();
        //Nous créons notre formulaire interne
        $userForm = $this->createFormBuilder()
            ->add('username', TextType::class, [
                'label' => 'User Name',
                'attr' => [
                    'class' => 'w3-input w3-border w3-round w3-light-grey',
                ]
            ])
            ->add('matricule', TextType::class, [
                'label' => 'Administrator Personnel Number',
                'attr' => [
                    'class' => 'w3-input w3-border w3-round w3-light-grey',
                ]
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'required' => true,
                'first_options' => [
                    'label' => 'Password',
                    'attr' => [
                        'class' => 'w3-input w3-border w3-round w3-light-grey',
                    ],
                ],
                'second_options' => [
                    'label' => 'Password Confirmation',
                    'attr' => [
                        'class' => 'w3-input w3-border w3-round w3-light-grey',
                    ],
                ],
            ])
            ->add('roles', ChoiceType::class, [
                'label' => 'Privileges',
                'choices' => [
                    'Role: User' => 'ROLE_USER',
                    'Role: Admin' => 'ROLE_ADMIN',
                ],
                'expanded' => false,
                'multiple' => true,
                'attr' => [
                    'class' => 'w3-input w3-border w3-round w3-light-grey',
                ],
            ])
            ->add('register', SubmitType::class, [
                'label' => 'Registration',
                'attr' => [
                    'class' => 'w3-button w3-black w3-margin-bottom',
                    'style' => 'margin-top:10px;'
                ]
            ])
            ->getForm()
        ;
        //Nous traitons les données reçues au sein de notre formulaire
        $userForm->handleRequest($request);
        if($request->isMethod('post') && $userForm->isValid()){
            //On récupère les informations de notre formulaire
            $data = $userForm->getData();
            //Nous créons et renseignons notre Entity User
            $user = new Admin;
            $user->setUsername($data['username']);
            $user->setMatricule($data['matricule']);
            $user->setPassword($passHasher->hashPassword($user, $data['password']));
            $user->setRoles($data['roles']);
            $entityManager->persist($user);
            $entityManager->flush();
            //Après le transfert de l'instance User vers notre BDD, on revient à l'index
            return $this->redirect($this->generateUrl('index'));
        }
        //Si le formulaire n'est pas validé, nous le présentons à l'utilisateur
        return $this->render('index/dataform.html.twig', [
            'categories' => $categories,
            'formName' => 'Administrator Registration',
            'dataForm' => $userForm->createView(),
        ]);
    }



/**
     * @Route("/register", name="register_client")
     */
    public function registerClient(Request $request, UserPasswordHasherInterface $passHasher): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $categoryRepository = $entityManager->getRepository(Category::class);
        $categories= $categoryRepository->findAll();
        $userForm = $this->createFormBuilder()
            ->add('username', TextType::class, [
                'label' => 'User name',
                'attr' => [
                    'class' => 'w3-input w3-border w3-round w3-light-grey',
                    'style' => 'margin-bottom:20px;'
                ]
            ])
            ->add('address',TextareaType::class,[
                'label' => 'Address',
                'attr' => [
                    'class' => 'w3-input w3-border w3-round w3-light-grey',
                    'style' => 'margin-bottom:20px;'
                ],
            ])
            ->add('telephone',TextType::class, [
            'label' => 'Phone Number',
                'attr' => [
                    'class' => 'w3-input w3-border w3-round w3-light-grey',
                    'style' => 'margin-bottom:20px;'
                ],
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'required' => true,
                'first_options' => [
                    'label' => 'Password',
                    'attr' => [
                        'class' => 'w3-input w3-border w3-round w3-light-grey',
                        'style' => 'margin-bottom:20px;'
                    ],
                ],
                'second_options' => [
                    'label' => 'Password confirmation',
                    'attr' => [
                        'class' => 'w3-input w3-border w3-round w3-light-grey',
                        'style' => 'margin-bottom:20px;'
                    ],
                ],
        
          
            ])
            ->add('register', SubmitType::class, [
                'label' => 'Registration',
                'attr' => [
                    'class' => 'w3-button w3-black w3-margin-bottom',
                    'style' => 'margin-top:30px;'
                ]
            ]) 
            ->getForm() 
        ;
        $userForm->handleRequest($request);
        if($request->isMethod('post') && $userForm->isValid() ){
            $data = $userForm->getData();
            $user = new Client;
            $user->setUsername($data['username']);
            $user->setTelephone($data['telephone']);
            $user->setAddress($data['address']);
            $user->setPassword($passHasher->hashPassword($user, $data['password']));
            $user->setRoles(['ROLE_USER']);
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirect($this->generateUrl('index'));
        }
        return $this->render('index/dataform.html.twig', [  
            'categories' => $categories,
            'formName' => 'User Registration',
            'dataForm' => $userForm->createView(),
        ]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
