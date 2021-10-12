<?php

namespace App\Form;

use App\Entity\Tag;
use App\Entity\Product;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Name',
                'attr' => [
                    'class' => 'w3-input w3-border w3-round w3-light-grey',
                    'style' => 'margin-bottom:35px;'
                ]
            ])
            ->add('category', EntityType::class, [
                'label' => 'Category',
                'class' => Category::class,
                'choice_label' => 'name',
                'expanded' => false,
                'multiple' => false,
                'attr' => [
                    'class' => 'w3-input w3-border w3-round w3-light-grey',
                ]
            ])
            ->add('tags', EntityType::class, [
                'label' => 'Tags',
                'class' => Tag::class, //L'Entity que nous choisissons dans notre champ
                'choice_label' => 'name', //L'attribut de l'Entity utilisé comme label
                'expanded' => true, //Affichage cases
                'multiple' => true, 
                'attr' => [
                    'class' => 'w3-input w3-border w3-round w3-light-grey',
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => [
                    'class' => 'w3-input w3-border w3-round w3-light-grey',
                ]
            ])
            ->add('price', NumberType::class, [
                'label' => 'Price',
                'attr' => [
                    'min' => 1,
                    'class' => 'w3-input w3-border w3-round w3-light-grey',
                ]
            ])
            ->add('stock', IntegerType::class, [
                'label' => 'Stock',
                'attr' => [
                    'min' => 1,
                    'class' => 'w3-input w3-border w3-round w3-light-grey',
                ]
            ])
            ->add('validate', SubmitType::class, [
                'label' => 'Validate',
                'attr' => [
                    'class' => 'w3-button w3-black w3-margin-bottom',
                    'style' => 'margin-top:55px;'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
