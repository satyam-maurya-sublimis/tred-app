<?php

namespace App\Form\Cms;

use App\Entity\Cms\CmsMedia;
use App\Entity\Master\MstMediaCategory;
use App\Service\CommonHelper;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class CmsMediaType extends AbstractType
{
    private $commonHelper;
    public function __construct(CommonHelper $commonHelper)
    {
        $this->commonHelper = $commonHelper;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('mstMediaCategory', EntityType::class,[
                'label' => 'label.category',
                'class' => MstMediaCategory::class,
                'choice_label' => 'indentedName',
                'placeholder' => 'placeholder.form.select',
                'required' => true,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->where('c.parent IS NOT NULL')
                        ->orderBy('c.root, c.lft', 'ASC');
                },
            ])
            ->add('mediaType', ChoiceType::class,[
                'label' => 'label.type',
                'choices' => $this->commonHelper->mediaType(),
                'placeholder' => 'placeholder.form.select',
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
                    'placeholder' => 'Upload a file..',

                ]
            ])
            ->add('mediaAlText', TextType::class,[
                'label' => 'label.alt',
                'required' => false
            ])
            ->add('mediaTitle', TextType::class,[
                'label' => 'label.title',
                'required' => false
            ])
            ->add('sequenceNo', NumberType::class,[
                'required' => true,
                'label' => 'label.seq_no',
            ])
            ->add('isActive', CheckboxType::class,[
                'required'=> false,
                'label' => 'label.is_active',
            ])

        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CmsMedia::class,
            'image_required' => false,
        ]);
        $resolver->addAllowedValues('image_required', array(true,false));
    }
}
