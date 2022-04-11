<?php

namespace App\Form\Transaction;

use App\Entity\Master\MstCurrency;
use App\Entity\Master\MstDenomination;
use App\Entity\Master\MstDeviceType;
use App\Entity\Master\MstFacing;
use App\Entity\Master\MstFurnishing;
use App\Entity\Master\MstPreferredTenant;
use App\Entity\Master\MstProjectArea;
use App\Entity\Master\MstProjectAreaCategory;
use App\Entity\Master\MstPropertySaleCategory;
use App\Entity\Master\MstPropertyTransactionCategory;
use App\Entity\Master\MstRoomConfiguration;
use App\Entity\Master\MstUploadDocumentType;
use App\Entity\Transaction\TrnProjectRoomConfiguration;
use App\Form\Seo\SeoContentType;
use App\Repository\Master\MstUploadDocumentTypeRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class TrnProjectRoomConfigurationType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('mstPropertyTransactionCategory', EntityType::class, [
                'class' => MstPropertyTransactionCategory::class,
                'placeholder' => 'placeholder.form.select',
                'label' => 'label.property_transaction_category',
                'required' => true,
                'attr'=> [
                    'class'=>'clsPropertyTransactionCategory',
                ]
            ])
            ->add('mstRoomConfiguration', EntityType::class, [
                'class' => MstRoomConfiguration::class,
                'placeholder' => 'placeholder.form.select',
                'label' => 'label.room_configuration',
                'required' => true,
                'query_builder'=> function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->where("c.isActive = :active")
                        ->setParameter('active', 1);
                },
            ])
            ->add('noOfBedRoom', IntegerType::class, [
                'label' => 'label.no_of_bedroom',
                'required' => true,
            ])
            ->add('noOfBathRooms', IntegerType::class, [
                'label' => 'label.no_of_bathroom',
                'required' => true,
            ])
            ->add('noOfBalcony', IntegerType::class, [
                'label' => 'label.no_of_balcony',
                'required' => false,
            ])
            ->add('mstFacing', EntityType::class, [
                'class' => MstFacing::class,
                'placeholder' => 'placeholder.form.select',
                'label' => 'label.facing',
                'required' => false,
            ])
            ->add('mstProjectArea', EntityType::class, [
                'class' => MstProjectArea::class,
                'placeholder' => 'placeholder.form.select',
                'label' => 'label.project_area_type',
                'required' => true,
            ])
            ->add('mstProjectAreaCategory', EntityType::class, [
                'class' => MstProjectAreaCategory::class,
                'placeholder' => 'placeholder.form.select',
                'label' => 'label.project_area_measurement',
                'required' => true,
            ])
            ->add('areaValue', IntegerType::class, [
                'label' => 'label.project_area_value',
                'required' => true,
            ])
            ->add('mstCurrency', EntityType::class, [
                'class' => MstCurrency::class,
                'placeholder' => 'placeholder.form.select',
                'label' => 'label.currency',
                'required' => true,
                'query_builder'=> function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->where("c.isActive = :active")
                        ->setParameter('active', 1);
                },
            ])
            ->add('price', NumberType::class, [
                'label' => 'label.project_price_per_area',
                'required' => true,
            ])
            ->add('agreementAmount', NumberType::class, [
                'label' => 'label.agreement_amount',
                'required' => true
            ])
            ->add('mstDenomination', EntityType::class, [
                'class' => MstDenomination::class,
                'placeholder' => 'placeholder.form.select',
                'label' => 'label.denomination',
                'required' => true
            ])
//            ->add('mstDeviceType', EntityType::class, [
//                'class' => MstDeviceType::class,
//                'placeholder' => 'placeholder.form.select',
//                'required' => false,
//                'label' => 'label.device_type'
//            ])
            ->add('mstUploadDocumentType', EntityType::class, [
                'class' => MstUploadDocumentType::class,
                'query_builder' => function (MstUploadDocumentTypeRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->where('u.isActive = :active ')
                        ->andwhere('u.id = :value ')
                        ->setParameter('value',1)
                        ->setParameter('active',1)
                        ;

                },
                'placeholder' => 'placeholder.form.select',
                'required' => false,
                'label' => 'label.media_file_type',
                'attr' => [
                    'class' => 'uploadDocumentType',
                ]
            ])
            ->add('mediaFileName', FileType::class,[
                'mapped' => false,
                'required' => false,
                'label' => 'label.media_file_path',
                'constraints' => [
                    new File([
                            'maxSize' => '7168K',
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
            ->add('mediaName', TextType::class,[
                'label' => 'label.media_name',
                'required' => false
            ])
            ->add('mediaAltText', TextType::class,[
                'label' => 'label.media_alt_text',
                'required' => false
            ])
            ->add('mediaTitle', TextType::class,[
                'label' => 'label.media_title',
                'required' => false
            ])
            ->add('rentPerMonth', NumberType::class, [
                'label' => 'label.rent_per_month',
                'required' => true
            ])
            ->add('deposit', NumberType::class, [
                'label' => 'label.deposit',
                'required' => true
            ])
            ->add('floor', IntegerType::class, [
                'label' => 'label.floor',
                'required' => false,
            ])

            ->add('isNegotiable', CheckboxType::class, [
                'label' => 'label.is_negotiable',
                'required' => false,
            ])
            ->add('mstFurnishing', EntityType::class, [
                'class' => MstFurnishing::class,
                'placeholder' => 'placeholder.form.select',
                'label' => 'label.furnishing',
                'required' => false
            ])
            ->add('mstPropertySaleCategory', EntityType::class, [
                'class' => MstPropertySaleCategory::class,
                'placeholder' => 'placeholder.form.select',
                'label' => 'label.property_sale_category',
                'required' => true
            ])
            ->add('mstPreferredTenant', EntityType::class,[
                'label' => 'label.preferred_tenant',
                'class' => MstPreferredTenant::class,
                'placeholder' => 'placeholder.form.select',
                'multiple'=> true,
                'required' => false,
                'query_builder'=> function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->where("c.isActive = :active")
                        ->setParameter('active', 1);
                }
            ])
//            ->add('trnProjectAdditionalDetail', CollectionType::class, [
//                'entry_type' => TrnProjectAdditionalDetailType::class,
//                'required' => false,
//                'label' => false,
//                'entry_options' => [
//                    'label' => false,
//                ],
//                'allow_add' => true,
//                'allow_delete' => true,
//                'by_reference'=>false,
//            ])

            ->add('seoContent', SeoContentType::class,[
                'label' => false,
            ])
            ->add('isActive', CheckboxType::class, [
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
            'data_class' => TrnProjectRoomConfiguration::class,
        ]);
    }
}
