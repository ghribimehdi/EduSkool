<?php

namespace App\Form;

use App\Entity\SeancePsychologique;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class SeancePsychologiqueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateSeance', DateTimeType::class, [
                'label' => 'Date de la séance',
                'widget' => 'single_text',
            ])
            ->add('typeSeance', TextType::class, [
                'label' => 'Type de séance',
            ])
            ->add('duree', IntegerType::class, [
                'label' => 'Durée (en minutes)',
            ])
            ->add('nom_participant', TextType::class, [
                'label' => 'Nom du participant',
            ])
            ->add('nom_psychologue', TextType::class, [
                'label' => 'Nom du psychologue',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SeancePsychologique::class,
        ]);
    }
}