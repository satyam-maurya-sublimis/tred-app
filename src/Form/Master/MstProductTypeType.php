<?php

namespace App\Form\Master;

use App\Entity\Master\MstProductCategory;
use App\Entity\Master\MstProductType;
use App\Service\CommonHelper;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Constraints\File;

class MstProductTypeType extends AbstractType
{
    private $commonHelper;

    public function __construct(CommonHelper $commonHelper )
    {
        $this->commonHelper = $commonHelper;

    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('mstProductCategory', EntityType::class, [
                'class' => MstProductCategory::class,
                'placeholder' => 'placeholder.form.select',
                'label' => 'label.product_category',
                'required' => true,
            ])
            ->add('productType', TextType::class,[
                'label' => 'label.product_type',
                'required' => true,
            ])
            ->add('productTypeSlugName', TextType::class,[
                'label' => 'label.slug_name',
                'required' => true,
            ])
            ->add('productTypeDescription', TextareaType::class,[
                'label' => 'label.description',
                'required'=> false,
                'attr'=>[
                    "class"=>'textarea'
                ]
            ])
            ->add('productTypeMediaType', ChoiceType::class,[
                'label' => 'label.type',
                'choices' => $this->commonHelper->mediaType(),
                'data' => 'image',
                'required' => false,
            ])
            ->add('productTypeImageName', TextType::class,[
                'label' => 'label.file_name',
                'required'=> false,
            ])
            ->add('productTypeImage', FileType::class,[
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
            ->add('productTypeVideo', TextType::class,[
                'label' => 'label.video',
                'required'=> false,
            ])

            ->add('productTypeVideoPath', TextType::class,[
                'label' => 'label.embedded_url',
                'required'=> false,
            ])
            ->add('productTypePosition', TextType::class,[
                'required' => false,
                'label' => 'label.seq_no',
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
            'data_class' => MstProductType::class,
            'image_required' => false,
        ]);
        $resolver->addAllowedValues('image_required', array(true,false));
    }
}
