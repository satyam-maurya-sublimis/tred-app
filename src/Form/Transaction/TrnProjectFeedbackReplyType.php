<?php

namespace App\Form\Transaction;

use App\Entity\Transaction\TrnProjectFeedback;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrnProjectFeedbackReplyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('tredRemark', CKEditorType::class, [
                'label' => 'label.remarks',
                'attr' => [
                    'class' => 'textarea'
                ],
            ])
//            ->add('isApproved', CheckboxType::class, [
//                'label' => 'label.is_approved',
//                'required' => false,
//            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TrnProjectFeedback::class,
            'projectId' => null,
        ]);
    }
}
