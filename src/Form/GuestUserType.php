<?php

namespace App\Form;

use App\Entity\GuestUser;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GuestUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('email',EmailType::class)
            ->add('phone')
            ->add('address')
            ->add('pincode', ChoiceType::class,[
                'choices' =>[
                    '734101' => '734101',
                    '734102' => '734102',
                    '734104' => '734104',
                    '734105' => '734105'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => GuestUser::class,
        ]);
    }
}
