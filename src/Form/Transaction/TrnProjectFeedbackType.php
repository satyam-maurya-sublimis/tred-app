<?php

namespace App\Form\Transaction;

use App\Entity\Transaction\TrnProjectFeedback;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrnProjectFeedbackType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('topic', TextType::class, [
                'label' => 'label.project_property_feedback_topic',
                'required' => true
            ])
            ->add('feedback', CKEditorType::class, [
                'label' => 'label.project_property_feedback',
                'attr' => [
                    'class' => 'textarea'
                ],
            ])

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
