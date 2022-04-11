<?php

namespace App\Form\Transaction;

use App\Entity\Master\MstCurrency;
use App\Entity\Master\MstFurnitureCategory;
use App\Entity\Master\MstProductCategory;
use App\Entity\Master\MstProductSubType;
use App\Entity\Master\MstProductType;
use App\Entity\Product\PrdBrand;
use App\Entity\Product\PrdOption;
use App\Entity\Transaction\TrnFurniture;
use App\Form\Media\MdaFurnitureType;
use App\Repository\Master\MstProductCategoryRepository;
use App\Service\CommonHelper;
use Doctrine\ORM\EntityRepository;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class TrnFurnitureType extends AbstractType
{

    private $commonHelper;
    private $mstProductCategoryRepository;

    public function __construct(CommonHelper $commonHelper, MstProductCategoryRepository $mstProductCategoryRepository)
    {
        $this->commonHelper = $commonHelper;
        $this->mstProductCategoryRepository = $mstProductCategoryRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('prdBrand', EntityType::class,[
                'label' => 'label.brand',
                'class' => PrdBrand::class,
                'placeholder' => 'placeholder.form.select',
                'required' => false,
                'query_builder' => function (EntityRepository $dr){
                    return $dr->createQueryBuilder('e')
                        ->innerJoin('e.mstProductCategory', 'pc')
                        ->where("pc.productCategorySlugName = :slugname")
                        ->andWhere('e.isActive = :active')
                        ->andWhere('pc.isActive = :active')
                        ->setParameter('slugname','furniture')
                        ->setParameter('active',1)
                        ;
                }
            ])
            ->add('mstCurrency', EntityType::class,[
                'label' => 'label.currency',
                'class' => MstCurrency::class,
                'placeholder' => 'placeholder.form.select',
                'required' => false,
                'query_builder' => function (EntityRepository $dr){
                    return $dr->createQueryBuilder('e')->andWhere('e.isActive = :active')->setParameter('active',1);
                }
            ])

//            ->add('prdOption', EntityType::class,[
//                'label' => 'label.option',
//                'class' => PrdOption::class,
//                'placeholder' => 'placeholder.form.select',
//                'multiple'=>true,
//                'required' => true
//            ])
            ->add('furnitureName', TextType::class,[
                'label' => 'label.furniture_product_name',
                'required'=> false,
            ])
            ->add('furniturePrice', MoneyType::class,[
                'label' => 'label.price_starting_from',
                'currency'=>false,
                'required' => false,
            ])
            ->add('canonicalUrl', TextareaType::class,[
                'label' => 'label.canonical_url',
                'required'=> false,
            ])
            ->add('metaTitle', TextType::class,[
                'label' => 'label.meta_title',
                'required'=> false,
            ])
            ->add('seoSchema', TextareaType::class,[
                'label' => 'label.schema',
                'required'=> false,
            ])

            ->add('metaDescription', TextareaType::class,[
                'label' => 'label.meta_description',
                'required'=> false,
            ])

            ->add('metaKeyword', TextareaType::class,[
                'label' => 'label.meta_keyword',
                'required'=> false,
            ])

            ->add('focusKeyPhrase', TextareaType::class,[
                'label' => 'label.focus_key_phrase',
                'required'=> false,
            ])

            ->add('keyPhraseSynonyms', TextareaType::class,[
                'label' => 'label.key_phrase_synonyms',
                'required'=> false,
            ])

            ->add('ogTitle', TextType::class,[
                'label' => 'label.social_title',
                'required'=> false,
            ])
            ->add('ogDescription', TextareaType::class,[
                'label' => 'label.social_description',
                'required'=> false,
            ])
            ->add('ogType', TextType::class,[
                'label' => 'label.social_type',
                'data' => 'Furniture',
                'required'=> false,
                'attr' => [
                    'readonly' => 'readonly'
                ]
            ])
            ->add('ogImage', FileType::class,[
                'mapped' => false,
                'label' => 'Upload Image File',
                'required' => false,
                'constraints' => [
                    new File([
                            'maxSize' => '3000k',
                            'maxSizeMessage' => 'The maximum file upload size is 3 mb.',
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
            ->add('mdaFurniture', CollectionType::class, [
                'entry_type' => MdaFurnitureType::class,
                'required' => false,
                'label' => false,
                'entry_options' => [
                    'label' => false,
                ],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference'=>false,
            ])
            ->add('isActive', CheckboxType::class, [
                'label' => 'label.is_active',
                'required' => false,
                'attr' => [
                    'checked' => 'checked'
                ]
            ])
        ;

        $refreshProductType = function ($form, $data) {
            $form->add('mstProductType', EntityType::class, [
                'class' => MstProductType::class,
                'placeholder' => 'placeholder.form.select',
                'label' => 'label.product_type',
                'required' => true,
                'query_builder' => function (EntityRepository $dr) use ($data) {
                    $mstProductCategory = $this->mstProductCategoryRepository->findOneBy(["isActive"=>true,"productCategorySlugName"=>"furniture"]);
                    return $dr->createQueryBuilder('e')
                        ->andWhere('e.isActive = :active')
                        ->setParameter('active',1)
                        ->andWhere('e.mstProductCategory = :productCategory')
                        ->setParameter('productCategory', $mstProductCategory);
                },
            ]);
        };
        $refreshProductSubType = function ($form, $data) {
            $form->add('mstProductSubType', EntityType::class, [
                'class' => MstProductSubType::class,
                'placeholder' => 'Select..',
                'label' => 'label.product_subtype',
                'required' => true,
                'query_builder' => function (EntityRepository $dr) use ($data) {
                    if (null === $data) {
                        $mstProductTypeId = null;
                    } elseif (null != $data && is_array($data)) {
                        $mstProductTypeId = $data["mstProductType"];
                    } else {
                        $mstProductTypeId = $data->getMstProductType()?$data->getMstProductType()->getId():null;
                    }
                    return $dr->createQueryBuilder('e')
                        ->innerJoin('e.mstProductType','f')
                        ->andWhere('e.isActive = :active')
                        ->andWhere('f.isActive = :active')
                        ->setParameter('active',1)
                        ->andWhere('f.id = :productType')
                        ->setParameter('productType', $mstProductTypeId);
                },
            ])
            ;
        };
        $refreshFurnitureCategory = function ($form, $data) {
            $form->add('mstFurnitureCategory', EntityType::class, [
                'class' => MstFurnitureCategory::class,
                'placeholder' => 'Select..',
                'label' => 'label.furniture_category',
                'required' => true,
                'query_builder' => function (EntityRepository $dr) use ($data) {
                    if (null === $data) {
                        $mstProductSubTypeId = null;
                    } elseif (null != $data && is_array($data)) {
                        $mstProductSubTypeId = $data["mstProductSubType"];
                    } else {
                        $mstProductSubTypeId = $data->getMstProductSubType()?$data->getMstProductSubType()->getId():null;
                    }
                    return $dr->createQueryBuilder('e')
                        ->innerJoin('e.mstProductSubType','f')
                        ->andWhere('e.isActive = :active')
                        ->andWhere('f.isActive = :active')
                        ->setParameter('active',1)
                        ->andWhere('f.id = :mstProductSubType')
                        ->setParameter('mstProductSubType', $mstProductSubTypeId);
                },
            ])
            ;
        };

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($refreshProductType,$refreshProductSubType,$refreshFurnitureCategory) {
            $form = $event->getForm();
            $data = $event->getData();
            $refreshProductType ($form, $data);
            $refreshProductSubType ($form, $data);
            $refreshFurnitureCategory ($form, $data);
        });
        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($refreshProductType,$refreshProductSubType,$refreshFurnitureCategory) {
            $form = $event->getForm();
            $data = $event->getData();
            if (isset($data["mstProductCategory"])) {
                $refreshProductType ($form, $data);
            }
            if (isset($data['mstProductType'])) {
                $refreshProductSubType ($form, $data);
            }
            if (isset($data['mstProductSubType'])) {
                $refreshFurnitureCategory ($form, $data);
            }
        });

    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TrnFurniture::class,
        ]);
    }
}
