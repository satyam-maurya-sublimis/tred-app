<?php

namespace App\Form\Cms;

use App\Entity\Cms\CmsLandingPage;
use App\Entity\Master\MstCity;
use App\Entity\Master\MstProductCategory;
use App\Entity\Master\MstProductType;
use App\Entity\Master\MstState;
use App\Form\Master\MstProductTypeType;
use App\Service\CommonHelper;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class CmsLandingPageType extends AbstractType
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
            ->add('mstProductCategory', EntityType::class,[
                'label' => 'label.product_category',
                'class' => MstProductCategory::class,
                'required'=>true
            ])
            ->add('cmsLandingPageBannerTitle', TextareaType::class,[
                'label' => 'label.description',
                'required'=> false,
                'attr' => [
                    'class' => 'textarea',
                ]
            ])
            ->add('cmsLandingPageMediaType', ChoiceType::class,[
                'label' => 'label.type',
                'choices' => $this->commonHelper->mediaType(),
                'data' => 'image',
                'required' => false,
            ])
            ->add('cmsLandingPageImage', FileType::class,[
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
            ->add('cmsLandingPageImageName', TextType::class,[
                'label' => 'label.file_name',
                'required'=> false,
                'help' => 'Only image name without file extension.'
            ])

            ->add('cmsLandingPageImageAlt', TextType::class,[
                'label' => 'label.alt',
                'required'=> false,
            ])

            ->add('cmsLandingPageImageTitle', TextType::class,[
                'label' => 'label.image_title',
                'required'=> false,
            ])
            ->add('cmsLandingPageVideo', TextType::class,[
                'label' => 'label.video',
                'required'=> false,
            ])

            ->add('cmsLandingPageVideoPath', TextType::class,[
                'label' => 'label.embedded_url',
                'required'=> false,
            ])
            ->add('isActive', CheckboxType::class,[
                'required'=> false,
                'label' => 'label.is_active',
            ])
            ->add('cmsLandingPageContents', CollectionType::class,[
                'label'=>false,
                'entry_type' => CmsLandingPageContentType::class,
                'entry_options' => [
                    'label' => false,
                ],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference'=>false,

            ])
        ;

        $refreshProductType = function ($form, $data) {
            $formStateOptions = [
                'label' => 'label.product_type',
                'class' => MstProductType::class,
                'required' => false,
                'query_builder' => function (EntityRepository $dr) use ($data) {
                    if( array_key_exists('mstProductCategory', $data) )
                    {
                        $productCategoryId = $data["mstProductCategory"];
                    }else{
                        $productCategoryId = $data->getMstProductCategory()?$data->getMstProductCategory()->getId():null;
                    }
                    return $dr->createQueryBuilder('s')->andWhere('s.mstProductCategory =:mstProductCategory')->setParameter('mstProductCategory', $productCategoryId);
                },
            ];
            $form->add('mstProductType', EntityType::class,$formStateOptions);
        };
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($refreshProductType) {
            $form = $event->getForm();
            $data = $event->getData();
            $refreshProductType ($form, $data);
        });
        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($refreshProductType) {
            $form = $event->getForm();
            $data = $event->getData();
            if (array_key_exists('mstProductCategory', $data)) {
                $refreshProductType($form, $data);
            }
        });
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CmsLandingPage::class,
            'image_required' => false,
        ]);
        $resolver->addAllowedValues('image_required', array(true,false));
    }
}
