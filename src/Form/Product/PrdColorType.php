<?php

namespace App\Form\Product;

use App\Entity\Product\PrdColor;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class PrdColorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('colorName', TextType::class,[
                'label' => 'label.color',
                'required' => true
            ])
//            ->add('colorValue', TextType::class,[
//                'label' => 'label.value',
//                'required' => false
//            ])
//            ->add('colorImage', FileType::class,[
//                'mapped' => false,
//                'label' => 'Upload File',
//                'required' => $options['image_required'],
//                'constraints' => [
//                    new File([
//                            'maxSize' => '500k',
//                            'maxSizeMessage' => 'The maximum file upload size is 500 kb.',
//                            'mimeTypes' => [
//                                'image/jpg',
//                                'image/png',
//                                'image/jpeg'
//                            ],
//                            'mimeTypesMessage' => 'Please upload a valid jpg or png file.',
//                        ]
//                    )
//                ],
//                'attr' => [
//                    'placeholder' => 'Upload a file..',
//
//                ]
//            ])
            ->add('isActive', CheckboxType::class,[
                'required'=> false,
                'label' => 'label.is_active',
                'attr' => [
                    'checked' => 'checked'
                ]
            ]);


    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PrdColor::class,
            'image_required' => false,
        ]);
        $resolver->addAllowedValues('image_required', array(true,false));
    }
}
