<?php

namespace App\Form;

use App\Entity\SizeQtyAttributes;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class SizeQtyAttrType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('old_price',TextType::class,['label'=>false ,'attr'=>['class'=>'form-control', 'placeholder' => 'Old Price']])
            ->add('new_price',TextType::class,['label'=>false ,'attr'=>['class'=>'form-control', 'placeholder' => 'New Price']])
            ->add('attrName',TextType::class,['label'=>false ,'attr'=>['class'=>'form-control', 'placeholder' => 'Size/Weight']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SizeQtyAttributes::class,
        ]);
    }
}
