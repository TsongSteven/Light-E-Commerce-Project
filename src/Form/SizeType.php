<?php

namespace App\Form;

use App\Entity\Size;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class SizeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('s',CheckboxType::class,['required'=> false])
            ->add('m',CheckboxType::class,['required'=> false])
            ->add('l',CheckboxType::class,['required'=> false])
            ->add('xl',CheckboxType::class,['required'=> false])
            ->add('xxl',CheckboxType::class,['required'=> false])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Size::class,
        ]);
    }
}
