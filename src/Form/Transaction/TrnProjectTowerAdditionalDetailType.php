<?php

namespace App\Form\Transaction;

use App\Entity\Transaction\TrnProjectTowerAdditionalDetail;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class TrnProjectTowerAdditionalDetailType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('additionalDetail', TextareaType::class, [
                'label' => 'label.project_additional_detail',
                'attr' => [
                    'class' => 'textarea'
                ],
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TrnProjectTowerAdditionalDetail::class,
        ]);
    }
}
