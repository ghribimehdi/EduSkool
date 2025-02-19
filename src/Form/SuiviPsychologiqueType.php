<?php

namespace App\Form;

use App\Entity\SuiviPsychologique;
use App\Entity\SeancePsychologique;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class SuiviPsychologiqueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date_suivi', DateTimeType::class, [
                'label' => 'Date du suivi',
                'widget' => 'single_text',
                'required' => true,
            ])
            ->add('suivi_type', ChoiceType::class, [
                'label' => 'Type de suivi',
                'required' => true,
                'choices' => [
                    'Consultation individuelle' => 'individuel',
                    'Thérapie de groupe' => 'groupe',
                    'Suivi à distance' => 'distance',
                    'Évaluation psychologique' => 'evaluation',
                ],
            ])
            ->add('etat_emotionnel', ChoiceType::class, [
                'label' => 'État émotionnel',
                'required' => true,
                'choices' => [
                    'Stable' => 'stable',
                    'Anxieux' => 'anxieux',
                    'Déprimé' => 'deprime',
                    'En colère' => 'en_colere',
                    'Joyeux' => 'joyeux',
                    'Confus' => 'confus',
                ],
            ])
            ->add('nom_participant', TextType::class, [
                'label' => 'Nom du participant',
                'required' => true,
            ])
            ->add('nom_psychologue', TextType::class, [
                'label' => 'Nom du psychologue',
                'required' => true,
            ])
            ->add('seance', EntityType::class, [
                'class' => SeancePsychologique::class,
                'choice_label' => function (SeancePsychologique $seance) {
                    return $seance->getNomParticipant() . ' - ' . $seance->getDateSeance()->format('d/m/Y H:i');
                },
                'label' => 'Séance associée',
                'placeholder' => 'Sélectionnez une séance',
                'required' => true,
                'query_builder' => function (\App\Repository\SeancePsychologiqueRepository $repository) {
                    return $repository->createQueryBuilder('s')
                        ->orderBy('s.dateSeance', 'DESC');
                },
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SuiviPsychologique::class,
        ]);
    }
}