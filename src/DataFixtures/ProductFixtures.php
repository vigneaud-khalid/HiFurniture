<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ProductFixturesOLD extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $descript1 = "This article is the most beautiful ";
        $descript2 = " ever designed with sensitive and responsible colors. After a few weeks you will sees this piece of art as an integral part of your living environment... Life can be so beautiful, bright and radiant with the right environment.";
        $categories=["cabinet", "desk", "chair", "sofa", "bed", "lightfixture", "armchair", "dresser", "other"];
        for($cat=0;$cat<count($categories);$cat++){
            $category = new Category();
            $category->setName($categories[$cat]);
            $category->setDescription('Our '.$categories[$cat].'s are among the best '.$categories[$cat].'s ever designed...');
            for($i=1;$i<4;$i++){
                $product = new Product();
                $product->setName($category->getName().' number '.$i.' _ ref:'.rand(1567, 89456));
                $product->setCategory($category);
                $product->setPrice(rand(0, 99999)/100);
                $product->setDescription($descript1.$category->getName().$descript2);
                $product->setStock(rand(6, 24));
                $manager->persist($product);
                $category->addProduct($product);
            }
        }  
        $manager->flush();
    }
}


class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $productArray = [
            ["name" => "Tuillerie", "description" => "Our Tuillerie is the most beautiful armchair ever designed with sensitive and responsible colors. After a few weeks you will sees this piece of art as an integral part of your living environment... Life can be so beautiful, bright and radiant with the right environment.", "price" => 1240.56, "stock" => rand(1, 300), "category" => 'armchair',],
            ["name" => "Anthracite", "description" => "This armchair will set the tone for a sleek contemporary interior. With a fur finish, robust and stable, it is made in France.", "price" => 2379.40, "stock" => rand(1, 300), "category" => 'armchair',],
            ["name" => "Green", "description" => "This armchair will set the tone for a sleek contemporary interior. With a fur finish, robust and stable, it is made in France.", "price" => 2719.85, "stock" => rand(1, 300), "category" => 'armchair',],
            ["name" => "Crapo", "description" => "This armchair is the most beautiful armchair ever designed with sensitive and responsible colors. After a few weeks you will sees this piece of art as an integral part of your living environment... Life can be so beautiful, bright and radiant with the right environment.", "price" => 1567.80, "stock" => rand(1, 300), "category" => 'armchair',],
            ["name" => "Suspendisse", "description" => "Comfortable armchair,  pellentesque is designed ith a fur finish, robust and stable, it is made in France.", "price" => 988.20, "stock" => rand(1, 300), "category" => 'armchair',],

            ["name" => "Nucta", "description" => "This bed for restful nights is the most beautiful bed ever designed with sensitive and responsible colors. After a few weeks you will sees this piece of art as an integral part of your living environment... Life can be so beautiful, bright and radiant with the right environment.", "price" =>4056.89, "stock" => rand(1, 300), "category" => 'bed',],
            ["name" => "Dreamy", "description" => "This beautiful bed for restful nights is the most beautiful bed ever designed with sensitive and responsible colors. After a few weeks you will sees this piece of art as an integral part of your living environment... Life can be so beautiful, bright and radiant with the right environment. ", "price" =>4124.50, "stock" => rand(1, 300), "category" => 'bed',],
            ["name" => "Tempu", "description" => "Create a modern and warm atmosphere in your bedroom with the Tempo 1 adult Bed in natural oak color, size 140x190cm. Its straight lines with a timeless spirit will make it a piece of choice. Its headboard imposes a real comfort for a quality sleep. Moreover, its thickness and the quality of its finishing make it a robust and perennial piece. With a paper decor finish, it is made in France. It also exists in oak relief, oak clay, brown walnut and white glossy colors.", "price" => 119.99, "stock" => rand(1, 300), "category" => 'bed',],
            ["name" => "Jeeney", "description" => "This bed for restful nights is the most beautiful bed ever designed with sensitive and responsible colors. After a few weeks you will sees this piece of art as an integral part of your living environment... Life can be so beautiful, bright and radiant with the right environment.", "price" => 339.80, "stock" => rand(1, 300), "category" => 'bed',],
            ["name" => "TeeBed", "description" => "Create a modern and warm atmosphere in your bedroom with the Tempo 2 adult Bed in natural oak color, size 140x190cm. Its straight lines with a timeless spirit will make it a piece of choice. Its headboard imposes a real comfort for a quality sleep. Moreover, its thickness and the quality of its finishing make it a robust and perennial piece. With a paper decor finish, it is made in France. It also exists in oak relief, oak clay, brown walnut and white glossy colors. ", "price" => 119.99, "stock" => rand(1, 300), "category" => 'bed',],
            ["name" => "Rustica", "description" => "This rustic bed for rural and country nights is the most rustic bed ever dreamed and you will sees this piece of art as an integral part of your rustic environment... Life can be so rustic with the right bed.", "price" => 339.80, "stock" => rand(1, 300), "category" => 'bed',],

            ["name" => "Pellentesque", "description" => "This amazing cabinet is the most beautiful cabinet ever designed with sensitive and responsible colors. After a few weeks you will sees this piece of art as an integral part of your living environment... Life can be so beautiful, bright and radiant with the right environment.", "price" => 440.60, "stock" => rand(1, 300), "category" => 'cabinet',],
            ["name" => "Etiam", "description" => "This article is the most amazing cabinet ever designed with sensitive and responsible colors. After a few weeks you will sees this piece of art as an integral part of your living environment... Life can be so beautiful, bright and radiant with the right environment. ", "price" => 1340.85, "stock" => rand(1, 300), "category" => 'cabinet',],
            ["name" => "Hotel", "description" => "This cabinet is the most beautiful cabinet ever designed with sensitive and responsible colors. After a few weeks you will sees this piece of art as an integral part of your living environment... Life can be so beautiful, bright and radiant with the right environment. ", "price" => 2556.00, "stock" => rand(1, 300), "category" => 'cabinet',],
            ["name" => "Henry", "description" => "This amazing cabinet is the most beautiful cabinet ever designed with sensitive and responsible colors. After a few weeks you will sees this piece of art as an integral part of your living environment... Life can be so beautiful, bright and radiant with the right environment. ", "price" => 876.80, "stock" => rand(1, 300), "category" => 'cabinet',],
            ["name" => "Classico", "description" => "Classico is the most beautiful cabinet ever designed with sensitive and responsible colors. After a few weeks you will sees this piece of art as an integral part of your living environment... Life can be so beautiful, bright and radiant with the right environment. ", "price" => 1543.50, "stock" => rand(1, 300), "category" => 'cabinet',],
            ["name" => "Ludvig", "description" => "This article is the most attractive Ludvig cabinet ever designed with sensitive and responsible colors. After a few weeks you will sees this piece of art as an integral part of your living environment... Life can be so beautiful, bright and radiant with the right environment. ", "price" => 1654.95, "stock" => rand(1, 300), "category" => 'cabinet',],
            ["name" => "Ordero", "description" => "Cabinet Ordero is the most beautiful Ludvig cabinet ever designed with sensitive and responsible colors. After a few weeks you will sees this piece of art as an integral part of your living environment... Life can be so beautiful, bright and radiant with the right environment. ", "price" => 1567.60, "stock" => rand(1, 300), "category" => 'cabinet',],

            ["name" => "Mautios", "description" => "This chair is the most beautiful chair ever designed with sensitive and responsible colors. After a few weeks you will sees this piece of art as an integral part of your living environment... Life can be so beautiful, bright and radiant with the right environment.", "price" => 255.50, "stock" => rand(1, 300), "category" => 'chair',],
            ["name" => "Quentin", "description" => "Quentin is the most beautiful chair ever designed for Quentin with sensitive and responsible colors. After a few weeks you will sees this piece of art as an integral part of your living environment... Life can be so beautiful, bright and radiant with the right environment.", "price" => 940.70, "stock" => rand(1, 300), "category" => 'chair',],
            ["name" => "Louis", "description" => "This royal chair is the most aritocratic and royal chair ever designed for the English Family of with sensitive and responsible colors. After a few weeks you will sees this piece of art as an integral part of your living environment... Life can be so beautiful, bright and radiant with the right environment.", "price" => 112.55, "stock" => rand(1, 300), "category" => 'chair',],
            ["name" => "Collection", "description" => "These chairs could be part of e vast collection. After a few weeks you will sees this piece of art as an integral part of your living environment... Life can be so beautiful, bright and radiant with the right environment.", "price" => 1207.56, "stock" => rand(1, 300), "category" => 'chair',],
            ["name" => "Hawau", "description" => "Revolutionary chair with many aspects. Available in two colors: industrial inspiration with metal legs.", "price" => 179.40, "stock" => rand(1, 300), "category" => 'chair',],

            ["name" => "Donek", "description" => "This is your desk, tastefully finished in oak, it will bring clarity to your workspace. ", "price" => 2560.60, "stock" => rand(1, 300), "category" => 'desk',],
            ["name" => "Vestibulum", "description" => "This desk is the most beautiful desk ever designed with sensitive and responsible colors. After a few weeks you will sees this piece of art as an integral part of your living environment... Life can be so beautiful, bright and radiant with the right environment.", "price" => 1207.60, "stock" => rand(1, 300), "category" => 'desk',],    
            ["name" => "Buro", "description" => "Very practical desk with 4 drawers and 1 storage niche. Allows you to work in complete security with a lockable drawer. Also available in other colors.", "price" => 999.90, "stock" => rand(1, 300), "category" => 'desk',],
            ["name" => "Deskee", "description" => "The Deskie white desk will set the tone for a sleek contemporary interior. With a paper decor finish, its top measures 80.80cm long. Ideal for storing your documents, it has a drawer.Robust and stable, it is made in Sweden.", "price" => 3239.55, "stock" => rand(1, 300), "category" => 'desk',],
            ["name" => "Bureaux", "description" => "Compact and functional, this desk was designed for those who like to see everything in its place! With its top part equipped with niches and shelves and its pedestal with a drawer and a hinged door storage, it will allow you to keep all your material clearly organized.", "price" => 1661.00, "stock" => rand(1, 300), "category" => 'desk',],
            
            ["name" => "Rangefringue", "description" => "This dresser is the most beautiful dresser ever designed with sensitive and responsible colors. After a few weeks you will sees this piece of art as an integral part of your living environment... Life can be so beautiful, bright and radiant with the right environment.", "price" => 3207.95, "stock" => rand(1, 300), "category" => 'dresser',],    
            ["name" => "Rione", "description" => "This white dresser is the most beautiful white dresser ever designed with sensitive and responsible colors. After a few weeks you will sees this piece of art as an integral part of your living environment... Life can be so beautiful, bright and radiant with the right environment.", "price" => 3205.00, "stock" => rand(1, 300), "category" => 'dresser',],    
            ["name" => "Box", "description" => "This dresser is the most beautiful dresser ever designed with sensitive and responsible colors. After a few weeks you will sees this piece of art as an integral part of your living environment... Life can be so beautiful, bright and radiant with the right environment.", "price" => 2056.80, "stock" => rand(1, 300), "category" => 'dresser',],    
            ["name" => "Mine", "description" => "This dresser is the most beautiful dresser ever designed with sensitive and responsible colors. After a few weeks you will sees this piece of art as an integral part of your living environment... Life can be so beautiful, bright and radiant with the right environment.", "price" => 2243.70, "stock" => rand(1, 300), "category" => 'dresser',],    
            ["name" => "Claris", "description" => "This dresser is the most beautiful dresser ever designed with sensitive and responsible colors. After a few weeks you will sees this piece of art as an integral part of your living environment... Life can be so beautiful, bright and radiant with the right environment.", "price" => 1924.60, "stock" => rand(1, 300), "category" => 'dresser',],    

            ["name" => "Real","description" => "This Luxury lightfixture is the most amazing vase ever designed with sensitive and responsible colors. After a few weeks you will sees this piece of art as an integral part of your living environment... Life can be so beautiful, bright and radiant with the right environment.", "price" => 499.55, "stock" => rand(1, 300), "category" => 'lightfixture',],
            ["name" => "Colors","description" => "Glossy lacquer finish... High-end with its silver mirrors silk-screened and made in Switzerland.", "price" => 499.55, "stock" => rand(1, 300), "category" => 'lightfixture',],
            ["name" => "Soleil","description" => "Soleil is the brightest lightfixture ever designed with sensitive and responsible colors. After a few weeks you will sees this piece of art as an integral part of your living environment... Life can be so beautiful, bright and radiant with the right environment.", "price" => 499.55, "stock" => rand(1, 300), "category" => 'lightfixture',],
            ["name" => "Lantern","description" => "Lantern is the most amazing lightfixture ever designed with sensitive and responsible colors. After a few weeks you will sees this piece of art as an integral part of your living environment... Life can be so beautiful, bright and radiant with the right environment.", "price" => 356.60, "stock" => rand(1, 300), "category" => 'lightfixture',],
            
            ["name" => "Blumen", "description" => "This vase is the most beautiful vase ever designed with sensitive and responsible colors. After a few weeks you will sees this piece of art as an integral part of your living environment... Life can be so beautiful, bright and radiant with the right environment.", "price" => 207.80, "stock" => rand(1, 300), "category" => 'other',],    
            ["name" => "Flores", "description" => "This Flores vase is the most amazing vase ever designed with sensitive and responsible colors. After a few weeks you will sees this piece of art as an integral part of your living environment... Life can be so beautiful, bright and radiant with the right environment.", "price" => 120.20, "stock" => rand(1, 300), "category" => 'other',],    
            ["name" => "Gorge", "description" => "This vase is the most beautiful vase ever designed with sensitive and responsible colors. After a few weeks you will sees this piece of art as an integral part of your living environment... Life can be so beautiful, bright and radiant with the right environment.", "price" => 207.80, "stock" => rand(1, 300), "category" => 'other',],    
            ["name" => "Joy", "description" => "This Joy vase is the most amazing vase ever designed with sensitive and responsible colors. After a few weeks you will sees this piece of art as an integral part of your living environment... Life can be so beautiful, bright and radiant with the right environment.", "price" => 120.20, "stock" => rand(1, 300), "category" => 'other',],    
            ["name" => "Bulle", "description" => "This Bulle vase is the most amazing vase ever designed with sensitive and responsible colors. After a few weeks you will sees this piece of art as an integral part of your living environment... Life can be so beautiful, bright and radiant with the right environment.", "price" => 120.20, "stock" => rand(1, 300), "category" => 'other',],    

            ["name" => "Canape", "description" => "Sunday with the family or an evening with friends? This reversible four-seater sofa in grey fabric will become the centerpiece of your home for all your moments of conviviality and relaxation. Its generous foam padding and soft cushions ensure a comfortable and ideal seating position. Are your guests staying overnight? No problem, it unfolds in a single movement into a spacious and ultra-comfortable bed for two. Practical, it is equipped with a box to store your comforters and pillows. Its particle board structure and wooden legs promise lasting strength and stability.", "price" => 479.00, "stock" => rand(1, 300), "category" => 'sofa',],
            ["name" => "Root", "description" => "Luxury sofa with adjustable headrests. Bimaterial, also available in convertible version", "price" => 987.40, "stock" => rand(1, 300), "category" => 'sofa',],
            ["name" => "Romy", "description" => "Sober and elegant, the Romy convertible and reversible corner sofa will bring personality to your living room. Its polyurethane coating is a guarantee of quality and easy to maintain.", "price" => 1529.35, "stock" => rand(1, 300), "category" => 'sofa',],
            ["name" => "Fullist", "description" => "Nice and elegant, the Fullist convertible and reversible corner sofa will bring personality to your living room. Its polyurethane coating is a guarantee of quality and easy to maintain.", "price" => 2352.80, "stock" => rand(1, 300), "category" => 'sofa',],
            ["name" => "Tolbiac", "description" => "The Tolbiac sofa is a beautiful corner sofa with a very comfortable imitation covering. A corner sofa with a lounge bar effect that invites rest and relaxation in your living room. This large family sofa will be ideal in all contemporary interiors with its ultra practical storage space hidden under the meridian. Its metal legs bring even more modernity to its already very contemporary look. When you sit on this sofa, the meridian is on your left.", "price" => 599.70, "stock" => rand(1, 300), "category" => 'sofa',],
            ["name" => "Moelleux", "description" => "Practical for its modularity (left or right angle), the Moelleux 4 seater sofa can also be easily transformed into a bed and will ensure a good night's sleep to your guests.", "price" => 896.40, "stock" => rand(1, 300), "category" => 'sofa',],
       

            
            ["name" => "Torenas", "description" => "This article is the most amazing table ever designed with sensitive and responsible colors. After a few weeks you will sees this piece of art as an integral part of your living environment... Life can be so beautiful, bright and radiant with the right environment. ", "price" => 457.40, "stock" => rand(1, 300), "category" => 'table',],
            ["name" => "Basic", "description" => "This table is the basic table ever designed with sensitive and responsible colors. After a few weeks you will sees this piece of art as an integral part of your living environment... Life can be so beautiful, bright and radiant with the right environment. ", "price" => 1634.25, "stock" => rand(1, 300), "category" => 'table',],
            ["name" => "Bois", "description" => "Bois is a wood table ever designed with sensitive and responsible colors. After a few weeks you will sees this piece of art as an integral part of your living environment... Life can be so beautiful, bright and radiant with the right environment. ", "price" => 2167.45, "stock" => rand(1, 300), "category" => 'table',],
            ["name" => "Duo", "description" => "This article is a double table for double purpose. After a few weeks you will sees this piece of art as an integral part of your living environment... Life can be so beautiful, bright and radiant with the right environment. ", "price" => 1164.10, "stock" => rand(1, 300), "category" => 'table',],

       
        ];
        $categoryArray = ['armchair' => null, 'bed' => null, 'cabinet' => null, 'chair' => null, 'desk' => null,  'dresser' => null, 'lightfixture' => null, 'sofa' => null, 'table' => null, 'other' => null];
        $descript1 = "This article is the most beautiful ";
        $descript2 = " ever designed with sensitive and responsible colors. After a few weeks you will sees this piece of art as an integral part of your living environment... Life can be so beautiful, bright and radiant with the right environment.";
        //Renseignement et implémentation de la liste des Category
        foreach($categoryArray as $key => &$value){ //Nous récupérons le tableau des catégories à implémenter
            //Le & avant $value est une référence ce qui signifie que nous récupérons la variable en tant que telle plutôt que sa valeur, ce qui nous permet de la modifier
            $value = new Category; //A chaque valeur est attribué un nouvel objet Category
            $value->setName($key); //Le nom est celui de la clef de l'index
            // $value->setDescription($lorem); //La description est un lorem générique
            $value->setDescription('Our '.$key.'s are among the best '.$key.'s ever designed...');


            $manager->persist($value); //Demande de persistance
        }
        //Nous lisons le tableau productArray grâce à une boucle foreach et nous persistons chaque nouveau product renseigné par les informations du tableau associatif parcouru
        foreach($productArray as $productData){ //Nous lisons chaque tableau associatif contenu
            $product = new Product; //On crée un nouveau Product
            $product->setName($productData['name']);
            $product->setPrice($productData['price']);
            $product->setStock($productData['stock']);
            $product->setCategory($categoryArray[$productData['category']]); //Nous récupérons l'objet de categoryArray tenu par la clef dont le nom est fourni par la valeur 'category' de $productData
            $product->setDescription($productData['description']);
            //$product->setDescription($descript1.$product->getCategory()->getName().$descript2);
            
            $manager->persist($product);
        }
        $manager->flush();
    }
}

