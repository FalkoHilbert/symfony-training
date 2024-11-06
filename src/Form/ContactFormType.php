<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContactFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fullname', TextType::class, [
                'label' => 'Fullname',
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 2, 'max' => 50]),
                ]
            ])
            ->add('email', TextType::class, [
                'label' => 'Email',
                'constraints' => [
                    new NotBlank(),
                    new Email()
                ]
            ])
            ->add('subject', TextType::class, [
                'label' => 'Subject',
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 5, 'max' => 255]),
                ]
            ])
            ->add('message', TextareaType::class, [
                'label' => 'Message',
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 10, 'max' => 1024]),

                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
