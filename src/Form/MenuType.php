<?php

namespace App\Form;

use App\Entity\Chef;
use App\Entity\Comment;
use App\Entity\Menu;
use App\Entity\Order;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MenuType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('price')
            ->add('picture')
            ->add('description')
            ->add('title')
            ->add('products', EntityType::class, [
                'class' => Product::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
            ->add('chef', EntityType::class, [
                'class' => Chef::class,
                'choice_label' => 'id',
            ])
            ->add('comments', EntityType::class, [
                'class' => Comment::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
            ->add('orders', EntityType::class, [
                'class' => Order::class,
                'choice_label' => 'id',
                'multiple' => true,
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
