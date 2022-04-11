<?php

namespace App\Form\Cms;

use App\Entity\Cms\CmsPressRoom;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CmsPressRoomType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('articleDateTime', DateTimeType::class,[
                'label' => 'label.date',
                'years' => range(date('Y')-1, date('Y')+1),
                'data' => new \DateTime(),
                ])
            ->add('articleHeading', TextType::class,[
                'label' => 'label.title',
                'attr' => [
                    'class' => 'col-sm-3'
                ]
            ])
            ->add('articleContent', TextareaType::class,[
                'label' => 'label.content',
                'attr' => [
                    'class' => 'textarea'
                ]
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
            'data_class' => CmsPressRoom::class,
        ]);
    }
}
