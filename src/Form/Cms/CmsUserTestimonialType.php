<?php

namespace App\Form\Cms;

use App\Entity\Cms\CmsUserTestimonial;
use App\Service\CommonHelper;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class CmsUserTestimonialType extends AbstractType
{
    private $commonHelper;
    public function __construct(CommonHelper $commonHelper)
    {
        $this->commonHelper = $commonHelper;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('testimonialFor', ChoiceType::class,[
                'label' => 'label.testimonial_for',
                'choices' => $this->commonHelper->testimonialFor(),
                'required' => true
            ])
            ->add('userImage', FileType::class,[
                'required' => false,
                'mapped' => false,
                'label' => 'Upload File',
                'constraints' => [
                    new File([
                            'maxSize' => '1000k',
                            'maxSizeMessage' => 'The maximum file upload size is 1 mb.',
                            'mimeTypes' => [
                                'image/jpg',
                                'image/png',
                                'image/jpeg'
                            ],
                            'mimeTypesMessage' => 'Please upload a valid jpg or png file.',
                        ]
                    )
                ],
                'attr' => [
                    'placeholder' => 'Upload a file..',

                ]
            ])
            ->add('userFullName', TextType::class,[
                'label' => 'label.name',
                'required' => false,
            ])
            ->add('removeImage', CheckboxType::class,[
                'mapped' => false,
                'required'=> false,
                'label' => 'label.delete_image',
                'help' => 'Select if you do not need the intro image'
            ])
            ->add('userDesignation', TextType::class,[
                'label' => 'label.designation',
                'required' => false,
            ])
            ->add('testimonialDetail', TextareaType::class,[
                'label' => 'label.testimonial',
                'required' => true,
                'attr' => [
                    'class' => 'textarea'
                ]
            ])
            ->add('isActive', CheckboxType::class,[
                'required'=> false,
                'label' => 'Activate for portal',
                'help' => 'If selected, then the above testimonial will be shown on the portal',
                'attr' => [
                    'checked' => 'checked'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CmsUserTestimonial::class,
        ]);
    }
}
