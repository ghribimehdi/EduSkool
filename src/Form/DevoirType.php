<?php

namespace App\Form;

use App\Entity\Devoir;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\File;

class DevoirType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'label' => 'Titre du devoir',
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Le titre ne peut pas être vide.',
                    ]),
                    new Assert\Length([
                        'min' => 5,
                        'max' => 255,
                        'minMessage' => 'Le titre doit comporter au moins {{ limit }} caractères.',
                        'maxMessage' => 'Le titre ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                ],
            ])
            ->add('description', TextType::class, [
                'label' => 'Description du devoir',
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'La description ne peut pas être vide.',
                    ]),
                    new Assert\Length([
                        'min' => 10,
                        'max' => 500,
                        'minMessage' => 'La description doit comporter au moins {{ limit }} caractères.',
                        'maxMessage' => 'La description ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                ],
            ])
            ->add('datelimite', DateTimeType::class, [
                'widget' => 'single_text', 
                'input' => 'datetime',
                'required' => false,       
                'html5' => true,
            ])
            ->add('fichier', FileType::class, [
                'label' => 'Fichier du devoir (PDF)',
                'mapped' => false,
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Veuillez télécharger un fichier.',
                    ]),
                    new File([
                        'maxSize' => '5M',
                        'mimeTypes' => ['application/pdf'],
                        'mimeTypesMessage' => 'Veuillez télécharger un fichier PDF valide.',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Devoir::class,
        ]);
    }
}
