<?php

namespace App\Form;

use App\Entity\Item;
use App\Entity\Size;
use App\Entity\SizeQtyAttributes;
use App\Form\SizeType;
use App\Form\SizeQtyAttrType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class ItemUpdateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('image',FileType::class,['data_class' => null,'required' => false,'mapped' => false])
            // ->add('sizes', SizeType::class)
            ->add('sizeQtyAttr', CollectionType::class,[
                'entry_type' => SizeQtyAttrType::class,
                'entry_options' => ['label'=> false],
                'by_reference' => false,
                'allow_add' => true,
                'allow_delete' => true
            ])
            ->add('stock_flag', ChoiceType::class,[
                'choices' => [
                    'In Stock' => true,
                    'Out of Stock' => false
                ]
            ])
            ->add('content',CKEditorType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Item::class,
        ]);
    }
}
