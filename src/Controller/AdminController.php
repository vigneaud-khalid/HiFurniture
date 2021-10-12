<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Form\TagType;
use App\Entity\Product;
use App\Entity\Category;
use App\Entity\Commande;
use App\Form\ProductType;
use App\Entity\Reservation;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin')]
#[Security('is_granted("ROLE_ADMIN")')]

class AdminController extends AbstractController
{
    #[Route('/', name: 'backoffice_display')]
    public function backofficeDisplay(): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $categoryRepository = $entityManager->getRepository(Category::class);
        $categories = $categoryRepository->findAll();
        $productRepository = $entityManager->getRepository(Product::class);
        $products = $productRepository->findAll();
        $tagRepository = $entityManager->getRepository(Tag::class);
        $tags = $tagRepository->findAll();
        return $this->render('admin/backoffice_display.html.twig', [  
            'categories' => $categories,
            'products' =>$products,
            'tags' => $tags,
        ]);
    }

    #[Route('/category/regenerate', name: 'regenerate_category')]
    public function regenerateCategories(): Response{
        //Cette fonction vide la table Category avant de persister six catégories (armoire, bureau, chaise, lit, canape, et autre)
        $descript = "This article is the most beautiful ";
        $lorem = " ever designed with sensitive and responsible colors. After a few weeks you will sees this piece of art as an integral part of your living environment... Life can be so beautiful, bright and radiant with the right environment.";
        $categoryNames = ["cabinet", "desk", "chair", "sofa", "bed", "lightfixture", "armchair", "dresser", "other"];
        //Nous devons tout d'abord pouvoir dialoguer avec notre BDD via l'Entity Manager et le Repository pertinent
        $entityManager = $this->getDoctrine()->getManager();
        $categoryRepository = $entityManager->getRepository(Category::class);
        $productRepository = $entityManager->getRepository(Product::class);
        //Nous récupérons la liste des Category mais également des Products
        $categories = $categoryRepository->findAll();
        $products = $productRepository->findAll();
        //Nous coupons les liens entre nos Products et nos Category
        foreach($products as $product){
            $product->setCategory(null); //Le Produit ici n'a donc plus de Category liée
        }
        foreach($categories as $category){ //Pour chaque catégorie...
            $entityManager->remove($category); //...Nous faisons une demande de suppression
        }
        //Nous créons nos nouvelles Catégories que nous persistons
        foreach($categoryNames as $categoryName){
            $category = new Category;
            $category->setName($categoryName);
            $category->setDescription('Our '.$categoryName.'s are among the best '.$categoryName.'s ever designed...');
            $entityManager->persist($category);
        }
        $entityManager->flush(); //Nous appliquons toutes nos requêtes
        return $this->redirect($this->generateUrl('backoffice_display')); //Nous revenons au backoffice
    }


    #[Route('/product/create', name: 'create_product')]
    public function createProduct(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        // $productRepository = $entityManager->getRepository(Product::class);
        // $products = $productRepository->findAll();
        $categoryRepository = $entityManager->getRepository(Category::class);
        $categories = $categoryRepository->findAll(); 
        $product = new Product;
        $productForm = $this->createForm(ProductType::class, $product);
        $productForm->handleRequest($request);
        if($request->isMethod('post') && $productForm->isValid()){
            if($product->getPrice() > 0){
            $entityManager->persist($product);
            $entityManager->flush();
            return $this->redirect($this->generateUrl('index'));
            }else {
                // Flashbag :   Invalid price !
            }
      }

      return $this->render('index/dataform.html.twig', [
        'controller_name'=>'IndexController',
        'categories' => $categories,
        'formName' =>'Product creation',
        'dataForm' => $productForm->createView()
        ]);
    }


    #[Route('/product/update/{productId}', name: 'update_product')]
    public function updateProduct(Request $request, int $productId=0): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $categoryRepository = $entityManager->getRepository(Category::class);
        $categories = $categoryRepository->findAll();
        $productRepository = $entityManager->getRepository(Product::class);
        $product = $productRepository->find($productId);
            if(!$product){
            return $this->redirect($this->generateUrl('index'));
            }
            
            $productForm = $this->createForm(ProductType::class, $product);
            $productForm->handleRequest($request);
            if($request->isMethod('post') && $productForm->isValid()){
                if($product->getPrice() > 0){
                $entityManager->persist($product);
                $entityManager->flush();
                }else {
                    // Flashbag :   Invalid price !
                }
                return $this->redirect($this->generateUrl('index'));
          }
          return $this->render('index/dataform.html.twig', [
            'controller_name'=>'IndexController',
            'categories' => $categories,
            'formName' =>'Product creation',
            'dataForm' => $productForm->createView()
            ]);    
    }


    #[Route('/product/delete/{productId}', name: 'delete_product')]
    public function deleteProduct(int $productId=0)
    {
        $entityManager = $this->getDoctrine()->getManager();
    //    $categoryRepository = $entityManager->getRepository(Category::class);
    //     $categories = $categoryRepository->findAll();
        $productRepository = $entityManager->getRepository(Product::class);
        $product = $productRepository->find($productId);
            if($product){
                $entityManager->remove($product);
                $entityManager->flush();
                //
                // message de confirmation de la suppresssion
            }            
            // return $this->redirect($this->generateUrl('index'));
            return $this->redirect($this->generateUrl('backoffice_display'));  //   MIEUX ????

    }

    #[Route('/tag/create', name: 'create_tag')]
    public function createTag(Request $request): Response{
        //Cette fonction a pour objectif de créer une nouveau Tag (nouvelle instance d'Entity Tag)
        //Nous faisons appel à notre EntityManager
        $entityManager = $this->getDoctrine()->getManager();
        //Récupération des Category pour notre navbar
        $categoryRepository = $entityManager->getRepository(Category::class);
        $categories = $categoryRepository->findAll();
        //Nous créons un nouvel objet Tag et nous le lions à notre nouveau formulaire
        $tag = new Tag;
        $tagForm = $this->createForm(TagType::class, $tag);
        //Nous transmettons la Request à notre nouveau formulaire
        $tagForm->handleRequest($request);
        //Si notre formulaire est bien rempli, nous persistons le Tag lié
        if($request->isMethod('post') && $tagForm->isValid()){
            $entityManager->persist($tag);
            $entityManager->flush();
            //
            // message de confirmation de la création

            // return $this->redirect($this->generateUrl('index'));
            return $this->redirect($this->generateUrl('backoffice_display'));  //   MIEUX ????

        }
        //Si le formulaire n'est pas validé, nous le publions
        return $this->render('index/dataform.html.twig', [
            'categories' => $categories,
            'formName' => 'Tag Creation',
            'dataForm' => $tagForm->createView(),
        ]);
    }

    #[Route('/tag/update/{tagId}', name: 'update_tag')]
    public function updateTag(Request $request, int $tagId = 0): Response{
        //Cette fonction permet de modifier les attributs d'un Tag indiqué via son ID dans notre barre d'adresse
        //Nous récupérons l'Entity Manager et le Repository pertinent (Tag)
        $entityManager = $this->getDoctrine()->getManager();
        $tagRepository = $entityManager->getRepository(Tag::class);
        //Nous récupérons la liste des Catégories pour notre navbar
        $categoryRepository = $entityManager->getRepository(Category::class);
        $categories = $categoryRepository->findAll();
        //Nous récupérons notre Tag selon l'ID transmise via paramètre de route
        $tag = $tagRepository->find($tagId);
        //Si aucun Tag n'est trouvé, nous revenons au backoffice (ou l'index)
        if(!$tag){
            return $this->redirect($this->generateUrl('backoffice_display'));
        }

        //Code dessous récupéré de createTag():
        $tagForm = $this->createForm(TagType::class, $tag);
        //Nous transmettons la Request à notre nouveau formulaire
        $tagForm->handleRequest($request);
        //Si notre formulaire est bien rempli, nous persistons le Tag lié
        if($request->isMethod('post') && $tagForm->isValid()){
            $entityManager->persist($tag);
            $entityManager->flush();
            return $this->redirect($this->generateUrl('backoffice_display'));
        }
        //Nous préparons une exception permettant la persistance de $tag même s'il en existe déjà un de même nom
        $exceptionName = $tag->getName();
        //Code dessous récupéré de createTag():
        $tagForm = $this->createForm(TagType::class, $tag);
        //Nous transmettons la Request à notre nouveau formulaire (les valeurs de $tag changent)
        $tagForm->handleRequest($request);
        //Si notre formulaire est bien rempli, nous persistons le Tag lié
        if($request->isMethod('post') && $tagForm->isValid()){
            //Nous récupérons la liste des Tag possédant le nom de notre $tag
            $duplicatas = $tagRepository->findBy(['name' => $tag->getName()]);
            //Si notre tableau est vide OU que le nom de notre tag est couvert par l'exception
            if(!$duplicatas || ($tag->getName() == $exceptionName)){
                //On se charge de la mise en BDD
                $entityManager->persist($tag);
                $entityManager->flush();
                //
                // message de confirmation de la création
                return $this->redirect($this->generateUrl('backoffice_display'));  //   MIEUX ????
            } else {
                //Flashbag "Un Tag du même nom existe déjà"
            }
            return $this->redirect($this->generateUrl('backoffice_display'));
        }
        //Si le formulaire n'est pas validé, nous le publions
        return $this->render('index/dataform.html.twig', [
            'categories' => $categories,
            'formName' => 'Tag Edition',
            'dataForm' => $tagForm->createView(),
        ]);
    }

    #[Route('/tag/delete/{tagId}', name: 'delete_tag')]
    public function deleteTag(int $tagId = 0){
        //Cette fonction permet la suppression d'un Tag indiqué de notre Base de Données
        //Nous avons besoin de l'Entity Manager et du Repository pertinent (Tag)
        $entityManager = $this->getDoctrine()->getManager();
        $tagRepository = $entityManager->getRepository(Tag::class);
        //Nous récupérons notre Tag indiqué ou retournons à l'index le cas échéant
        $tag = $tagRepository->find($tagId);
        if(!$tag){
            return $this->redirect($this->generateUrl('backoffice_display'));
        }
        //Si nous possédons notre Tag, nous procédons à sa suppression de la BDD puis nous retournons à l'index
        $entityManager->remove($tag); //Requête de suppression
        $entityManager->flush();
        return $this->redirect($this->generateUrl('backoffice_display'));
    }

    #[Route('/commande', name: 'admin_backoffice')]
    public function adminBackoffice(): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $commandeRepository = $entityManager->getRepository(Commande::class);
        $commandes = $commandeRepository->findAll();
        $categoryRepository = $entityManager->getRepository(Category::class);
        $categories = $categoryRepository->findAll();
        $activeCommandes = $commandeRepository->findBy(['status'=>'panier']);
        $archivedCommandes = $commandeRepository->findBy(['status'=>'validee']);
        return $this->render('commande/client_backoffice.html.twig', [  
            'categories' => $categories,
            'activeCommandes' =>$activeCommandes,
            'archivedCommandes' =>$archivedCommandes,
            'reservationDelete'=> 'admin_reservation_delete',
            'orderValidate'=> 'admin_order_validate',
            'orderDelete'=> 'admin_order_delete',
        ]);
    }
  
    #[Route('/commande/reservation/delete/{reservationId}', name: 'admin_reservation_delete')]
    public function deleteReservation(int $reservationId=0): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $reservationRepository = $entityManager->getRepository(Reservation::class);
        $reservation= $reservationRepository->find($reservationId);
       if(!$reservation || !($reservation->getCommande()) ||($reservation->getCommande()->getStatus() != 'panier')) 
       {
        return $this->redirect($this->generateUrl('admin_backoffice'));
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
        return $this->redirect($this->generateUrl('admin_backoffice'));
    }

    #[Route('/order/delete/{commandeId}', name: 'admin_order_delete')]
    public function deleteOrder(int $commandeId=0): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $commandeRepository = $entityManager->getRepository(Commande::class);
        $commande= $commandeRepository->find($commandeId);
       if(!$commande || ($commande->getStatus() != 'panier')) 
       {
        return $this->redirect($this->generateUrl('admin_backoffice'));
        }
        foreach($commande->getReservations() as $reservation){
            $product = $reservation->getProduct();
            $reservation->restituteStock();                  // à la place des 4 lignes précédentes
            $entityManager->persist($product);
            $entityManager->remove($reservation);
        }
        $entityManager->remove($commande);
        $entityManager->flush();
        return $this->redirect($this->generateUrl('admin_backoffice'));
    }

    #[Route('/validate/{commandeId}', name: 'admin_order_validate')]
    public function validateOrder(int $commandeId=0): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $commandeRepository = $entityManager->getRepository(Commande::class);
        $commande = $commandeRepository->find($commandeId);
       if(!$commande ||($commande->getStatus() != 'panier')) 
       {
        return $this->redirect($this->generateUrl('admin_backoffice'));
        }
        $commande->setStatus('validee');
        $entityManager->persist($commande);
        $entityManager->flush();
        return $this->redirect($this->generateUrl('admin_backoffice'));
        }


}
