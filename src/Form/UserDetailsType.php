<?php

namespace App\Form;

use App\Entity\User;
use App\Form\UserType;
use App\Entity\UserDetails;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class UserDetailsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('email')
            ->add('mobile')
            ->add('address')
            ->add('pincode')
            ->add('user', CollectionType::class,[
                'entry_type' => UserType::class,
                'entry_options' => ['label'=> false],
                'by_reference' => false,
                'mapped' => false,
                'allow_add' => true,
                'allow_delete' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserDetails::class,
        ]);
    }
}
