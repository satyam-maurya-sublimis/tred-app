<?php

namespace App\Form\Cms;

use App\Entity\Cms\CmsFaq;
use App\Entity\Product\PrdProduct;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CmsFaqType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('faq', TextType::class,[
                'label' => 'label.faq',
                'attr' => [
                    'class' => 'col-sm-3',
                ]
            ])
            ->add('prdProduct', EntityType::class,[
                'label' => 'label.product',
                'class' => PrdProduct::class,
                'placeholder' => 'help.form.select',
                'required' => false,
                'attr' => [
                    'class' => 'col-sm-3',
                ]
            ])
            ->add('isActive', CheckboxType::class,[
                'required'=> false,
                'label' => 'label.is_active',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CmsFaq::class,
        ]);
    }
}
