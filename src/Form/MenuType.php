<?php

namespace App\Form;

use App\Entity\Menu;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class MenuType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre du Menu',
                ])
            ->add('imageFile', VichImageType::class, [
                'required' => false,
                'download_uri' => false,
                'label' => 'Profile Picture',
            ])
            ->add('products', EntityType::class, [
                'class' => Product::class,
                'choice_label' => 'name',
                'multiple' => true,
                'by_reference' => false, 
            ])
            ->add('price', NumberType::class, [
                'label' => 'Prix du Menu',
                'html5' => true,
                'attr' => ['min' => 0],
            ])
            ->add('description', TextType::class, [
                'label' => 'Description du Menu',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Menu::class,
        ]);
    }
}
