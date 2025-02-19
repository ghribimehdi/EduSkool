<?php

namespace App\Form;

use App\Entity\SoumissionDevoir;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Validator\Constraints\File;

class SoumissionDevoirType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fichier', FileType::class, [
                'label' => 'Téléverser votre devoir (PDF)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '40M',
                        'mimeTypes' => ['application/pdf'],
                        'mimeTypesMessage' => 'Veuillez télécharger un fichier PDF valide',
                    ])
                ],
            ])
            ->add('note', IntegerType::class, [
                'label' => 'Note',
                'required' => false,
                'disabled' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SoumissionDevoir::class,
        ]);
    }
}
