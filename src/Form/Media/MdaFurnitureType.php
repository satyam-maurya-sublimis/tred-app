<?php

namespace App\Form\Media;

use App\Entity\Media\MdaFurniture;
use App\Service\CommonHelper;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class MdaFurnitureType extends AbstractType
{
    private $commonHelper;
    public function __construct(CommonHelper $commonHelper)
    {
        $this->commonHelper = $commonHelper;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('mediaType', ChoiceType::class,[
                'label' => 'label.type',
                'choices' => array('Image' => 'image'),
                'placeholder' => 'placeholder.form.select',
                'data' => 'image',
                'required' => true,
            ])
            ->add('mediaName', TextType::class,[
                'label' => 'label.name',
                'required' => true
            ])
            ->add('mediaPath', TextType::class,[
                'label' => 'label.embedded_url',
                'required' => false,
                'help' => 'Please add the Url in case of external video'
            ])
            ->add('mediaFileName', FileType::class,[
                'mapped' => false,
                'label' => 'Upload File',
                'required' => $options['image_required'],
                'constraints' => [
                    new File([
                            'maxSize' => '15000k',
                            'maxSizeMessage' => 'The maximum file upload size is 15 mb.',
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
                    'class'=>'mediaFileNameCls'

                ]
            ])
            ->add('mediaAlText', TextType::class,[
                'label' => 'label.alt',
                'required' => true
            ])
            ->add('mediaTitle', TextType::class,[
                'label' => 'label.title',
                'required' => true
            ])
            ->add('position', NumberType::class,[
                'required' => true,
                'label' => 'label.position',
            ])
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
            'data_class' => MdaFurniture::class,
            'image_required' => false,
        ]);
        $resolver->addAllowedValues('image_required', array(true,false));
    }
}