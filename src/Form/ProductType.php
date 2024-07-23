<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Menu;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('price')
            ->add('imageFile', VichImageType::class, [
                'required' => false,
                'download_uri' => false,
                'label' => 'Profile Picture',
            ])
            ->add('description')
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'id',
            ])
            ->add('product', EntityType::class, [
                'class' => Menu::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
