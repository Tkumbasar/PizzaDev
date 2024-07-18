<?php

namespace App\Form;

use App\Entity\Customer;
use App\Entity\Order;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('firstname')
            ->add('gender')
            ->add('date_of_birthday', null, [
                'widget' => 'single_text',
            ])
            ->add('phone')
            ->add('city')
            ->add('adress')
            ->add('orders', EntityType::class, [
                'class' => Order::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
            ->add('userCustomer', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Customer::class,
        ]);
    }
}
