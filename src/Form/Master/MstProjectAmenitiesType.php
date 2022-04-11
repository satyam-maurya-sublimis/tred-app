<?php

namespace App\Form\Master;

use App\Entity\Master\MstCategory;
use App\Entity\Master\MstProjectAmenities;
use App\Entity\Master\MstSubCategory;
use App\Form\Media\MediaIconType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class MstProjectAmenitiesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
//            ->add('mstCategory', EntityType::class, [
//                'class' => MstCategory::class,
//                'placeholder' => 'placeholder.form.select',
//                'label' => 'label.category',
//                'required' => true
//            ])
            ->add('mstSubCategory', EntityType::class, [
                'class' => MstSubCategory::class,
                'placeholder' => 'placeholder.form.select',
                'label' => 'label.subcategory',
                'required' => true,
            ])
            ->add('projectAmenities', TextType::class,[
                'label' => 'label.project_amenity',
                'required' => true,
            ])
            ->add('icon', TextType::class,[
                'label' => 'label.icon',
                'required' => true,
            ])
            ->add('desktopImage', FileType::class, [
                'mapped' => false,
                'required' => false,
                'label' => 'label.desktop_image',
                'attr' => [
                    'class' => 'custom-file-input'
                ],
                'constraints' => [
                    new File([
                            'maxSize' => '2000k',
                            'maxSizeMessage' => 'The maximum file upload size is 2 mb.',
                            'mimeTypes' => [
                                'image/jpg',
                                'image/png',
                                'image/jpeg'
                            ],
                            'mimeTypesMessage' => 'Please upload a valid jpg or png file.',
                        ]
                    )
                ],
            ])
            ->add('tabletImage', FileType::class, [
                'mapped' => false,
                'required' => false,
                'label' => 'label.tablet_image',
                'attr' => [
                    'class' => 'custom-file-input'
                ],
                'constraints' => [
                    new File([
                            'maxSize' => '2000k',
                            'maxSizeMessage' => 'The maximum file upload size is 2 mb.',
                            'mimeTypes' => [
                                'image/jpg',
                                'image/png',
                                'image/jpeg'
                            ],
                            'mimeTypesMessage' => 'Please upload a valid jpg or png file.',
                        ]
                    )
                ],
            ])
            ->add('mobileImage', FileType::class, [
                'mapped' => false,
                'required' => false,
                'label' => 'label.mobile_image',
                'attr' => [
                    'class' => 'custom-file-input'
                ],
                'constraints' => [
                    new File([
                            'maxSize' => '2000k',
                            'maxSizeMessage' => 'The maximum file upload size is 2 mb.',
                            'mimeTypes' => [
                                'image/jpg',
                                'image/png',
                                'image/jpeg'
                            ],
                            'mimeTypesMessage' => 'Please upload a valid jpg or png file.',
                        ]
                    )
                ],
            ])
            ->add('mediaIcon', MediaIconType::class,[
                'required' => false
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
            'data_class' => MstProjectAmenities::class,
        ]);
    }
}
