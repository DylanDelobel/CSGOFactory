<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class UserType extends AbstractType
{

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, array(
                'label' => 'Username : ',
                'required' => true,
                'constraints' =>[
                    new Assert\NotBlank([
                        'message' => 'This field can not be empty.'
                    ])
                ],
            ))
            ->add('email', EmailType::class, array(
                'label' => 'Email : ',
                'required' => true,
                'constraints' =>[
                    new Assert\Email([
                        'message'=>'Please, enter a valid email.'
                    ]),
                    new Assert\NotBlank([
                        'message' => 'This field can not be empty.'
                    ])
                ],
            ))
            ->add('password', PasswordType::class, array(
                'label' => 'New password : ',
                'required' => false
            ))
            ->add('repeatPassword', PasswordType::class, array(
                'label' => 'Confirmation : ',
                'mapped' => false,
                'required' => false
            ))
            ->add('submit', SubmitType::class, array(
                'label' => 'Save'
            ))

        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_user';
    }


}
