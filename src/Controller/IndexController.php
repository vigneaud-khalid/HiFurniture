<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Entity\Product;
use App\Entity\Category;
use App\Entity\Commande;
use App\Entity\Reservation;
use App\Service\ServiceEcom;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class IndexController extends AbstractController
{

    #[Route('/', name: 'index')]
    public function index(ServiceEcom $serviceEcom, Request $request): Response
    {
        $user = $this->getUser();
        $entityManager = $this->getDoctrine()->getManager();
        $productRepository = $entityManager->getRepository(Product::class);
        $products = $productRepository->findAll();
        $categoryRepository = $entityManager->getRepository(Category::class);
        $categories = $categoryRepository->findAll(); 
        $descriptor= [
            "name" =>"all categories",
            "description" =>"All our products are published on this page.",
        ];
        shuffle($products);
        $date = new \DateTime('now');
        return $this->render('index/index.html.twig', [
            'controller_name'=>'IndexController',
            'user' =>$user,
            'products' =>$products,
            'date' => $date,
            'descriptor' => $descriptor,
            'categories' => $categories
        ]);
    }

    #[Route('/category/{categoryName}', name: 'index_category')]
    public function indexCategory(string $categoryName): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $categoryRepository = $entityManager->getRepository(Category::class);
        $categories = $categoryRepository->findAll(); 

        $category = $categoryRepository->findOneBy(['name' => $categoryName]);

        if(!$category){
            return $this->redirect($this->generateUrl('index'));
        }
        $products = $category->getProducts()->toArray();
        $descriptor= [
            "name" =>$categoryName,
            "description" =>"All our products are published on this page.",
        ];
        shuffle($products);
        $date = new \DateTime('now');
        return $this->render('index/index.html.twig', [
            'controller_name'=>'IndexController',
            'products' =>$products,
            'date' => $date,
            'descriptor' => $descriptor,
            'categories' => $categories
        ]);
    }

    #[Route('/tag/display/{tagName}', name: 'index_tag')]
        public function indexTag(string $tagName = ""): Response{
        //Cette fonction a pour objectif de publier la liste des Products li?? ?? un Tag particulier, renseign?? via notre barre d'adresse (param??tre de route)
        //Pour dialoguer avec notre BDD, nous r??cup??rons l'Entity Manager et le Repository pertinent (Tag)
        $entityManager = $this->getDoctrine()->getManager();
        $tagRepository = $entityManager->getRepository(Tag::class);
        //On r??cup??re la liste des Cat??gories pour notre navbar
        $categoryRepository = $entityManager->getRepository(Category::class);
        $categories = $categoryRepository->findAll();
        //Nous recherchons le tag dont le $name correspond
        $tag = $tagRepository->findOneBy(['name' => $tagName]);
        //Si aucun r??sultat n'est rendu, nous retournons ?? l'index
        if(!$tag){
            return $this->redirect($this->generateUrl('index'));
        }
        //Si nous avons r??cup??r?? un Tag, nous r??cup??rons la liste des Products li??s sous forme de simple array PHP
        $products = $tag->getProducts()->toArray();
        shuffle($products); //On m??lange l'ordre des Products index??s
        //Nous pr??parons la description de notre Tag via un tableau
        $tagDescription = ['name' => $tag->getName(), 'description' => 'Notre tag ' . $tag->getName(),];
        //Nous rendons notre tableau de Products ?? notre page index.html.twig
        return $this->render('index/index.html.twig', [
            'categories' => $categories,
            'products' => $products,
            'descriptor' => $tagDescription, //Le nom et la description de notre Tag
        ]);
    }


    #[Route('/display/{productId}', name: 'product_display')]
    public function productDisplay(Request $request, int $productId): Response
    {
        $user = $this->getUser();
        $entityManager = $this->getDoctrine()->getManager();
        $categoryRepository = $entityManager->getRepository(Category::class);
        $categories = $categoryRepository->findAll(); 
        $commandeRepository = $entityManager->getRepository(Commande::class);
        $productRepository = $entityManager->getRepository(Product::class);
        // $product = $productRepository->findOneBy(['id' => $productId]);
        $product = $productRepository->find($productId);

        if(!$product){
            return $this->redirect($this->generateUrl('index'));
        }

        $buyForm = $this->createFormBuilder()
            ->add('quantity', IntegerType::class, [
                'label' => 'Quantity',
                'attr' => [
                    'min' => 1,
                ]
            ])
            ->add('validate', SubmitType::class, [
                'label' => 'Buy',
                'attr' => [
                    'class' =>'w3-button w3-black w3-margin-bottom',
                    'style' =>'margin-top:5px',
                ]
            ])
            ->getForm();
            $buyForm->handleRequest($request);
            if($request->isMethod('post') && $buyForm->isValid() && ($product->getStock()>0) && $user){
                $data = $buyForm->getData();
                $quantity = $data['quantity'];
                $reservation = new Reservation($product);
                //Nous recherchons une Commande valide
            $commande = $commandeRepository->findOneBy(['status' => 'panier','user'=>$user]);
            if(!$commande){ //Si la Commande n'existe pas, nous la cr??ons
                $commande = new Commande;
                $commande->setUser($user);
                $commande->setStatus('panier');
                $commande->setLivraison('Pearce Square 27, Wellington NZ');
            }
            //Nous lions la Commande ?? notre R??servation
            $reservation->setCommande($commande);
            //Mous poss??dons notre Product, nous d??cr??mentons son stock de la quantiy indiqu??e
            if($product->getStock() > $quantity){ //Si le $stock du Produit est sup??rieur ?? la quantity demand??e
                $reservation->setQuantity($quantity); //La quantit?? de la R??servation est celle de notre variable $quantity
                $product->setStock($product->getStock() - $quantity);
            } else { //Si la quantity est sup??rieure au $stock disponible
                $reservation->setQuantity($product->getStock()); //La $quantity retenue pour notre $reservation est le stock restant de notre r??f??rence Produit
                $product->setStock(0); //Nous mettons le stock Product ?? 0
            }

                //Une fois que le $stock est d??cr??ment??, nous persistons le Product, la Reservation, et la Commande
                $entityManager->persist($reservation);
                $entityManager->persist($commande);            
                $entityManager->persist($product);
                $entityManager->flush();
                return $this->redirect($this->generateUrl('product_display',['productId'=>$productId]));
            }

            $date = new \DateTime('now');
            return $this->render('index/product_display.html.twig', [
                'controller_name'=>'IndexController',
                'categories' => $categories,
                'product' =>$product,
                'date' => $date,
                'buyForm' => $buyForm->createView()
            ]);
    }


    #[Route('buyproduct/{productId}', name: 'buy_product')]
    public function buyProduct(int $productId=0): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $productRepository = $entityManager->getRepository(Product::class);
        // $product = $productRepository->findOneBy(['id' => $productId]);
        $product = $productRepository->find($productId);
        if(!$product){
            return $this->redirect($this->generateUrl('index'));
        }
        if($product->getStock()>0){
        $product->setStock($product->getStock()-1);
        $entityManager->persist($product);
        $entityManager->flush();
        }
        return $this->redirect($this->generateUrl('product_display',['productId'=>$productId]));
    }
}
