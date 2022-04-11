<?php

namespace App\Form\Product;

use App\Entity\Product\PrdOptionList;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PrdOptionListType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('optionValue', TextType::class,[
                'label' => 'label.option_value',
                'required' => true,
            ])
            ->add('optionValueDescription', TextType::class,[
                'label' => 'label.description',
                'required' => true,
            ])
            ->add('position', TextType::class,[
                'required' => true,
                'label' => 'label.seq_no',
            ])
            ->add('isActive', CheckboxType::class,[
                'label' => 'label.is_active',
                'required' => false,
                'attr' => [
                    'checked' => 'checked'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PrdOptionList::class,
        ]);
    }
}
