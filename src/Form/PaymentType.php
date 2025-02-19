<?php

namespace App\Form;

use App\Entity\Payment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Validator\Constraints as Assert;

class PaymentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pack', HiddenType::class, [
                'data' => $options['data']->getPack() ? $options['data']->getPack()->getId() : null,
            ])
            ->add('paymentMethod', ChoiceType::class, [
                'label' => 'Payment Method',
                'choices' => [
                    'PayPal' => 'paypal',
                    'Stripe' => 'stripe',
                ],
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Please select a payment method.']),
                ],
            ])
            ->add('cardNumber', TextType::class, [
                'label' => 'Card Number',
                'attr' => ['class' => 'form-control', 'maxlength' => 16, 'placeholder' => 'Enter card number'],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Card number is required.']),
                    new Assert\Length([
                        'min' => 16,
                        'max' => 16,
                        'exactMessage' => 'Card number must be exactly 16 digits.',
                    ]),
                    new Assert\Regex([
                        'pattern' => '/^\d{16}$/',
                        'message' => 'Card number must contain only digits.',
                    ]),
                ],
            ])
            ->add('cardExpiration', TextType::class, [
                'label' => 'Expiration Date (MM/YY)',
                'attr' => ['class' => 'form-control', 'placeholder' => 'MM/YY'],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Expiration date is required.']),
                    new Assert\Regex([
                        'pattern' => '/^(0[1-9]|1[0-2])\/\d{2}$/',
                        'message' => 'Expiration date must be in MM/YY format.',
                    ]),
                ],
            ])
            ->add('cardCVV', TextType::class, [
                'label' => 'CVV',
                'attr' => ['class' => 'form-control', 'maxlength' => 3, 'placeholder' => 'CVV'],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'CVV is required.']),
                    new Assert\Length([
                        'min' => 3,
                        'max' => 3,
                        'exactMessage' => 'CVV must be exactly 3 digits.',
                    ]),
                    new Assert\Regex([
                        'pattern' => '/^\d{3}$/',
                        'message' => 'CVV must contain only digits.',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Payment::class,
        ]);
    }
}
