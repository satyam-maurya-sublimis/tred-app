<?php

namespace App\Form\Master;

use App\Entity\Master\MstProjectType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class MstProjectTypeType extends AbstractType
{
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
            ->add('projectType', TextType::class,[
                'label' => 'label.property_type_new',
                'required' => true,
            ])
            ->add('projectTypeDescription', TextareaType::class,[
                'label' => 'label.description',
                'required'=> false,
                'attr'=>[
                    "class"=>'textarea'
                ]
            ])
            ->add('projectTypeMediaType', ChoiceType::class,[
                'label' => 'label.type',
                'choices' => $this->mediaType(),
                'data' => 'image',
                'required' => true,
            ])
            ->add('projectTypeImageName', TextType::class,[
                'label' => 'label.file_name',
                'required'=> false,
            ])
            ->add('projectTypeImage', FileType::class,[
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
                'attr' => [
                    'class' => 'custom-file-input'
                ],
            ])
            ->add('projectTypeVideo', TextType::class,[
                'label' => 'label.video',
                'required'=> false,
            ])

            ->add('projectTypeVideoPath', TextType::class,[
                'label' => 'label.embedded_url',
                'required'=> false,
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
            'data_class' => MstProjectType::class,
            'image_required' => false,
        ]);
        $resolver->addAllowedValues('image_required', array(true,false));
    }
}
