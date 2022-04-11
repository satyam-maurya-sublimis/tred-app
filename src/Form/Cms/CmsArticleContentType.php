<?php

namespace App\Form\Cms;

use App\Entity\Cms\CmsArticleContent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class CmsArticleContentType extends AbstractType
{
    private $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    private function mediaType()
    {
        $media = array (
            'Image' => 'image',
            'Video' => 'video'
        );
        return $media;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('articleContent', TextareaType::class,[
                'label' => 'label.content_block',
                'required' => false,
                'attr' => [
                    'class' => 'textarea'
                ]
            ])
//            ->add('mediaType', HiddenType::class,[
//                'data' => 'image'
//            ])

            ->add('mediaType', ChoiceType::class,[
                'label' => 'label.media_type',
                'choices' => $this->mediaType(),
//                'placeholder' => 'placeholder.form.select',
                'required' => false,
            ])

//            ->add('articleTitle', TextType::class,[
//                'label' => 'label.title',
//                'required' => false,
//            ])

            ->add('articleContentImage', FileType::class,[
                'mapped' => false,
                'label' => 'Upload Image File',
                'required' => $options['image_required'],
                'constraints' => [
                    new File([
                            'maxSize' => '1024k',
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

            ->add('articleContentImageSetName', TextType::class,[
                'label' => 'label.file_name',
                'required'=> false,
            ])

            ->add('articleContentImageAlt', TextType::class,[
                'label' => 'label.alt',
                'required'=> false,
            ])

            ->add('articleContentImageTitle', TextType::class,[
                'label' => 'label.title',
                'required'=> false,
            ])

            ->add('removeContentImage', CheckboxType::class,[
                'mapped' => false,
                'required'=> false,
                'label' => 'label.delete_image',
                'help' => 'Select if you do not need the content block image'
            ])

            ->add('articleContentVideo', TextType::class,[
                'label' => 'label.video',
                'required'=> false,
            ])

            ->add('articleContentVideoPath', TextType::class,[
                'label' => 'label.embedded_url',
                'required'=> false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CmsArticleContent::class,
            'image_required' => false,
        ]);
        $resolver->addAllowedValues('image_required', array(true,false));
    }
}
