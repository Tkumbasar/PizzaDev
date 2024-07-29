<?php

namespace App\Form;

use App\Entity\Chef;
use App\Entity\Menu;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Validator\Constraints as Assert; // N'oubliez pas d'importer les contraintes

class MenuType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Le titre ne peut pas être vide.',
                    ]),
                    new Assert\Length([
                        'max' => 255,
                        'maxMessage' => 'Le titre ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                ],
            ])
            ->add('price', MoneyType::class, [
                'label' => 'Prix',
                'currency' => 'EUR',
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Le prix ne peut pas être vide.',
                    ]),
                    new Assert\Positive([
                        'message' => 'Le prix doit être positif.',
                    ]),
                ],
            ])
            ->add('description', TextType::class, [
                'label' => 'Description',
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'La description ne peut pas être vide.',
                    ]),
                ],
            ])
            ->add('products', EntityType::class, [
                'class' => Product::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => false,
                'label' => 'Produits',
            ])
            ->add('imageFile', VichImageType::class, [
                'label' => 'Image',
                'required' => false,
                'constraints' => [
                    new Assert\Image([
                        'mimeTypesMessage' => 'Veuillez télécharger un fichier image valide.',
                        'maxSize' => '5M',
                        'maxSizeMessage' => 'L\'image ne peut pas dépasser 5 Mo.',
                    ]),
                ],
            ]);
}

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Menu::class,
        ]);
    }
}
