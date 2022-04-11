<?php

namespace App\Form\Cms;

use App\Entity\Cms\CmsAdvertisement;
use App\Entity\Cms\CmsPage;
use App\Service\CommonHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class CmsAdvertisementType extends AbstractType
{
    private $em;
    private $commonHelper;
    public function __construct(EntityManagerInterface $em, CommonHelper $commonHelper )
    {
        $this->em = $em;
        $this->commonHelper = $commonHelper;

    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('cmsPage', EntityType::class,[
                'label' => 'label.page',
                'class' => CmsPage::class,
                'data' => $options['data']->getCmsPage(),
            ])
            ->add('advertisementName', TextType::class,[
                'label' => 'label.advertisement',
                'attr' => [
                    'maxlength' => '150'
                ]
            ])
            ->add('advertisementDescription', TextareaType::class,[
                'label' => 'label.description',
                'required'=> false,
                'attr' => [
                    'class' => 'textarea',
                ]
            ])
            ->add('advertisementValidFromDate', DateType::class,[
                'label' => 'label.valid_from_date',
                'years' => range(date('Y'), date('Y')),
                'placeholder' => [
                    'year' => 'Year', 'month' => 'Month', 'day' => 'Day',
                ]
            ])
            ->add('advertisementValidToDate', DateType::class,[
                'label' => 'label.valid_to_date',
                'years' => range(date('Y'), date('Y')+2),
                'placeholder' => [
                    'year' => 'Year', 'month' => 'Month', 'day' => 'Day',
                ]
            ])
            ->add('advertisementMediaType', ChoiceType::class,[
                'label' => 'label.type',
                'choices' => $this->commonHelper->mediaType(),
                'data' => 'image',
                'required' => true,
            ])

            ->add('advertisementDesktopImage', FileType::class,[
                'mapped' => false,
                'required' => $options['image_required'],
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

            ->add('advertisementTabletImage', FileType::class,[
                'mapped' => false,
                'required' => $options['image_required'],
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

            ->add('advertisementMobileImage', FileType::class,[
                'mapped' => false,
                'required' => $options['image_required'],
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

            ->add('advertisementImageSetName', TextType::class,[
                'label' => 'label.file_name',
                'required'=> false,
                'help' => 'Only image name without file extension.'
            ])

            ->add('advertisementImageAlt', TextType::class,[
                'label' => 'label.alt',
                'required'=> false,
            ])

            ->add('advertisementImageTitle', TextType::class,[
                'label' => 'label.image_title',
                'required'=> false,
            ])
            ->add('advertisementVideo', TextType::class,[
                'label' => 'label.video',
                'required'=> false,
            ])

            ->add('advertisementVideoPath', TextType::class,[
                'label' => 'label.embedded_url',
                'required'=> false,
            ])
            ->add('position', TextType::class,[
                'required' => true,
                'label' => 'label.seq_no',
            ])
            ->add('advertisementUrl', TextType::class,[
                'required' => false,
                'label' => 'label.advertisement_url',
            ])
            ->add('isActive', CheckboxType::class,[
                'required'=> false,
                'label' => 'label.is_active',
            ])
            ->add('advertisementPosition', ChoiceType::class,[
                'label' => 'label.media_display_position',
                'choices' => $this->commonHelper->adPosition(),
                'required' => true,
                'placeholder'=>"Select Position"
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CmsAdvertisement::class,
            'image_required' => false,
        ]);
        $resolver->addAllowedValues('image_required', array(true,false));
    }
}
