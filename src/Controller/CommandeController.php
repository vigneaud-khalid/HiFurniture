<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Commande;
use App\Entity\Reservation;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


#[Route('/commande')]
#[Security('is_granted("ROLE_USER")')]
class CommandeController extends AbstractController
{
    #[Route('/', name: 'client_backoffice')]
    public function clientBackoffice(): Response
    {
        $user = $this->getUser();
        $entityManager = $this->getDoctrine()->getManager();
        $commandeRepository = $entityManager->getRepository(Commande::class);
        $commandes = $commandeRepository->findAll();
        $categoryRepository = $entityManager->getRepository(Category::class);
        $categories = $categoryRepository->findAll();
        $activeCommandes = $commandeRepository->findBy(['status'=>'panier', 'user'=>$user]);
        $archivedCommandes = $commandeRepository->findBy(['status'=>'validee', 'user'=>$user]);
        return $this->render('commande/client_backoffice.html.twig', [  
            'categories' => $categories,
            'activeCommandes' =>$activeCommandes,
            'archivedCommandes' =>$archivedCommandes,
            'reservationDelete'=> 'reservation_delete',
            'orderValidate'=> 'order_validate',
            'orderDelete'=> 'order_delete',
        ]);
    }
  
    #[Route('/reservation/delete/{reservationId}', name: 'reservation_delete')]
    public function deleteReservation(int $reservationId=0): Response
    {
        $user = $this->getUser();
        $entityManager = $this->getDoctrine()->getManager();
        $reservationRepository = $entityManager->getRepository(Reservation::class);
        $reservation= $reservationRepository->find($reservationId);
       if(!$reservation || !($reservation->getCommande()) ||($reservation->getCommande()->getStatus() != 'panier')
            || ($reservation->getcommande()->getUser() != $user)) 
       {
        return $this->redirect($this->generateUrl('client_backoffice'));
        }
        $product = $reservation->getProduct();
        $reservation->restituteStock();
        //nous retirons la réservation de la commande
        $commande = $reservation->getCommande();
        $commande->removeReservation($reservation); 
        $entityManager->persist($product);
        $entityManager->remove($reservation);
        // Vérification que la commande est vide, si oui suppression
        if($commande->getReservations()->isEmpty()){
            $entityManager->remove($commande);
        }
        $entityManager->flush();
        return $this->redirect($this->generateUrl('client_backoffice'));
    }

    #[Route('/order/delete/{commandeId}', name: 'order_delete')]
    public function deleteOrder(int $commandeId=0): Response
    {
        $user = $this->getUser();
        $entityManager = $this->getDoctrine()->getManager();
        $commandeRepository = $entityManager->getRepository(Commande::class);
        $commande= $commandeRepository->find($commandeId);
       if(!$commande || ($commande->getStatus() != 'panier') || ($commande->getUser() != $user)) 
       {
        return $this->redirect($this->generateUrl('client_backoffice'));
        }
        foreach($commande->getReservations() as $reservation){
            $product = $reservation->getProduct();
            $reservation->restituteStock();                  // à la place des 4 lignes précédentes
            $entityManager->persist($product);
            $entityManager->remove($reservation);
        }
        $entityManager->remove($commande);
        $entityManager->flush();
        return $this->redirect($this->generateUrl('client_backoffice'));
    }

    #[Route('/validate/{commandeId}', name: 'order_validate')]
    public function validateOrder(int $commandeId=0): Response
    {
        $user = $this->getUser();
        $entityManager = $this->getDoctrine()->getManager();
        $commandeRepository = $entityManager->getRepository(Commande::class);
        $commande = $commandeRepository->find($commandeId);
       if(!$commande ||($commande->getStatus() != 'panier') || ($commande->getUser() != $user))  
       {
        return $this->redirect($this->generateUrl('client_backoffice'));
        }
        $commande->setStatus('validee');
        $entityManager->persist($commande);
        $entityManager->flush();
        return $this->redirect($this->generateUrl('client_backoffice'));
        }


}
