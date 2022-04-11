<?php

namespace App\Form\Cms;

use App\Entity\Cms\CmsLandingPageContent;
use App\Service\CommonHelper;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CmsLandingPageContentType extends AbstractType
{
    private $em;
    private $commonHelper;
    public function __construct(EntityManagerInterface $em, CommonHelper $commonHelper)
    {
        $this->em = $em;
        $this->commonHelper = $commonHelper;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pageContent', TextareaType::class,[
                'label' => 'label.content_block',
                'required' => false,
                'attr' => [
                    'class' => 'textarea'
                ]
            ])
            ->add('pageMediaType', ChoiceType::class,[
                'label' => 'label.media_type',
                'choices' => $this->commonHelper->mediaType(),
                'required' => false,
                'placeholder'=>"Select Media Type"
            ])
            ->add('pageImage', FileType::class,[
                'mapped' => false,
                'required' => true,
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

            ->add('pageImageName', TextType::class,[
                'label' => 'label.file_name',
                'required'=> false,
                'help' => 'Only image name without file extension.'
            ])

            ->add('pageImageAlt', TextType::class,[
                'label' => 'label.alt',
                'required'=> false,
            ])

            ->add('pageImageTitle', TextType::class,[
                'label' => 'label.image_title',
                'required'=> false,
            ])
            ->add('pageVideo', TextType::class,[
                'label' => 'label.video',
                'required'=> false,
            ])

            ->add('pageVideoPath', TextType::class,[
                'label' => 'label.embedded_url',
                'required'=> false,
            ])
            ->add('pageMediaPosition', ChoiceType::class,[
                'label' => 'label.media_display_position',
                'choices' => $this->commonHelper->cmsPageContentMediaPosition(),
                'required' => false,
                'placeholder'=>"Select Position"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CmsLandingPageContent::class,
            'image_required' => false,
        ]);
        $resolver->addAllowedValues('image_required', array(true,false));
    }
}
