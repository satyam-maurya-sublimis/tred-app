<?php

namespace App\Form\Transaction;

use App\Entity\Master\MstCurrency;
use App\Entity\Master\MstFurnitureFinish;
use App\Entity\Master\MstProductSubType;
use App\Entity\Master\MstProductType;
use App\Entity\Product\PrdBrand;
use App\Entity\Product\PrdColor;
use App\Entity\Transaction\TrnFurnitureProductCatalog;
use App\Repository\Master\MstProductCategoryRepository;
use App\Service\CommonHelper;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class TrnFurnitureProductCatalogType extends AbstractType
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
            ->add('mstProductType', EntityType::class, [
                'class' => MstProductType::class,
                'placeholder' => 'placeholder.form.select',
                'label' => 'label.product_type',
                'required' => true,
                'query_builder' => function (EntityRepository $dr){
                    return $dr->createQueryBuilder('e')
                        ->innerJoin('e.mstProductCategory','f')
                        ->andWhere('e.isActive = :active')
                        ->andWhere('f.isActive = :active')
                        ->setParameter('active',1)
                        ->andWhere('f.productCategorySlugName = :productCategory')
                        ->setParameter('productCategory', "furniture");
                },
            ])
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
            ->add('mstFurnitureFinish', EntityType::class,[
                'label' => 'label.furniture_finish',
                'class' => MstFurnitureFinish::class,
                'placeholder' => 'placeholder.form.select',
                'required' => true,
                'query_builder' => function (EntityRepository $dr){
                    return $dr->createQueryBuilder('e')->andWhere('e.isActive = :active')->setParameter('active',1);
                }
            ])
            ->add('prdColor', EntityType::class,[
                'label' => 'label.color',
                'class' => PrdColor::class,
                'placeholder' => 'placeholder.form.select',
                'required' => true,
                'multiple' => true,
                'query_builder' => function (EntityRepository $dr){
                    return $dr->createQueryBuilder('e')->andWhere('e.isActive = :active')->setParameter('active',1);
                }
            ])
            ->add('mstCurrency', EntityType::class,[
                'label' => 'label.currency',
                'class' => MstCurrency::class,
                'placeholder' => 'placeholder.form.select',
                'required' => true,
                'query_builder' => function (EntityRepository $dr){
                    return $dr->createQueryBuilder('e')->andWhere('e.isActive = :active')->setParameter('active',1);
                }
            ])

            ->add('catalogName', TextType::class,[
                'label' => 'label.product_name',
                'required'=> true,
            ])
            ->add('furniturePrice', MoneyType::class,[
                'label' => 'label.price',
                'currency'=>false,
                'required' => true,
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
                'data' => 'Furniture Product Catalog',
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
            ->add('isActive', CheckboxType::class, [
                'label' => 'label.is_active',
                'required' => false,
                'attr' => [
                    'checked' => 'checked'
                ]
            ])
            ->add('isFreeHomeDelivery', CheckboxType::class, [
                'label' => 'label.is_free_home_delivery',
                'required' => false,
            ])
            ->add('isFreeInstallation', CheckboxType::class, [
                'label' => 'label.is_free_installation',
                'required' => false,
            ])
//            ->add('trnFurnitureProductCatalogDimensionMedia', CollectionType::class, [
//                'entry_type' => TrnFurnitureProductCatalogDimensionMediaType::class,
//                'required' => false,
//                'label' => false,
//                'entry_options' => [
//                    'label' => false,
//                ],
//                'allow_add' => true,
//                'allow_delete' => true,
//                'by_reference'=>false,
//            ])
        ;
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TrnFurnitureProductCatalog::class,
        ]);
    }
}
