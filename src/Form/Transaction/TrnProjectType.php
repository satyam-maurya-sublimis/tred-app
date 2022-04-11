<?php

namespace App\Form\Transaction;

use App\Entity\Master\MstAreaInCity;
use App\Entity\Master\MstCity;
use App\Entity\Master\MstCurrency;
use App\Entity\Master\MstHighlights;
use App\Entity\Master\MstPincode;
use App\Entity\Master\MstPossession;
use App\Entity\Master\MstProductFeature;
use App\Entity\Master\MstProjectAmenities;
use App\Entity\Master\MstProjectArea;
use App\Entity\Master\MstProjectType;
use App\Entity\Master\MstPropertyIn;
use App\Entity\Master\MstPropertyTransactionCategory;
use App\Entity\Master\MstPropertyType;
use App\Entity\Master\MstRating;
use App\Entity\Master\MstRoomConfiguration;
use App\Entity\Master\MstState;
use App\Entity\Master\MstCountry;
use App\Entity\Master\MstProductCategory;
use App\Entity\Master\MstProductSubType;
use App\Entity\Master\MstProductType;
use App\Entity\Master\MstVendorType;
use App\Entity\Organization\OrgCompany;
use App\Entity\Transaction\TrnProject;
use App\Entity\Transaction\TrnProjectAdditionalDetail;
use App\Entity\Transaction\TrnVendorPartnerDetails;
use App\Repository\Transaction\TrnVendorPartnerDetailsRepository;
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
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Repository\Master\MstCityRepository;
use App\Repository\Master\MstStateRepository;
use Symfony\Component\Validator\Constraints\File;

class TrnProjectType extends AbstractType
{
    /**
     * @var MstStateRepository
     */
    private $mstStateRepository;
    /**
     * @var MstCityRepository
     */
    private $mstCityRepository;
    private $commonHelper;
    /**
     * MstCityType constructor.
     * @param MstStateRepository $mstStateRepository
     * @param MstCityRepository $mstCityRepository
     */
    public function __construct(MstStateRepository $mstStateRepository, MstCityRepository $mstCityRepository, CommonHelper $commonHelper)
    {
        $this->mstStateRepository = $mstStateRepository;
        $this->mstCityRepository = $mstCityRepository;
        $this->commonHelper = $commonHelper;
    }

    public function buildYearChoices()
    {
        $distance = 100;
        $yearsBefore = date('Y', mktime(0, 0, 0, date("m"), date("d"), date("Y")));
        $yearsAfter = date('Y', mktime(0, 0, 0, date("m"), date("d"), date("Y") + $distance));
        return array_combine(range($yearsBefore, $yearsAfter), range($yearsBefore, $yearsAfter));
    }
    private function yesNoType()
    {
        $yesNo = array (
            'Yes' => '1',
            'No' => '0'
        );
        return $yesNo;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('orgCompany', EntityType::class, [
                'class' => OrgCompany::class,
                'placeholder' => 'placeholder.form.select',
                'label' => 'label.org_company',
                'required' => true
            ])
            ->add('mstVendorType', EntityType::class, [
                'class' => MstVendorType::class,
                'placeholder' => 'Select..',
                'label' => 'label.vendor_type',
                'required' => true,
                'query_builder' => function (EntityRepository $dr) use ($options) {
                    if ($options['vendorTypeId']){
                        return $dr->createQueryBuilder('e')->andWhere('e.isActive = :active')->andWhere('e.id = :id')->setParameter('active',1)->setParameter('id',$options['vendorTypeId']);
                    }else{
                        return $dr->createQueryBuilder('e')->andWhere('e.isActive = :active')->setParameter('active',1);
                    }
                }
            ])
            ->add('mstProductCategory', EntityType::class, [
                'class' => MstProductCategory::class,
                'placeholder' => 'Select..',
                'label' => 'label.product_category',
                'required' => true,
                'query_builder' => function (EntityRepository $dr){
                    return $dr->createQueryBuilder('e')->andWhere('e.isActive = :active')->setParameter('active',1);
                }
            ])
            ->add('mstProductSubType', EntityType::class, [
                'class' => MstProductSubType::class,
                'placeholder' => 'placeholder.form.select',
                'label' => 'label.product_subtype',
                'required' => true,
                'query_builder' => function (EntityRepository $dr){
                    return $dr->createQueryBuilder('e')->andWhere('e.isActive = :active')->setParameter('active',1);
                }
            ])
            ->add('mstProductSubType', EntityType::class, [
                'class' => MstProductSubType::class,
                'placeholder' => 'placeholder.form.select',
                'label' => 'label.product_subtype',
                'required' => true,
                'query_builder' => function (EntityRepository $dr){
                    return $dr->createQueryBuilder('e')->andWhere('e.isActive = :active')->setParameter('active',1);
                }
            ])

            ->add('projectName', TextType::class, [
                'label' => 'label.project_name',
                'required' => true
            ])
            ->add('mstProductSubType', EntityType::class, [
                'class' => MstProductSubType::class,
                'placeholder' => 'placeholder.form.select',
                'label' => 'label.product_subtype',
                'required' => true
            ])
            ->add('mstPropertyType', EntityType::class, [
                'class' => MstProjectType::class,
                'placeholder' => 'placeholder.form.select',
                'label' => 'label.property_type_new',
                'required' => true
            ])

            ->add('mstCountry', EntityType::class,[
                'label' => 'label.country',
                'class' => MstCountry::class,
                'placeholder' => 'placeholder.form.select',
                'multiple'=> false,
                'required' => true,
                'attr' => [
                    'class' => 'mstcountry'
                ]
            ])
            ->add('mstRoomConfiguration', EntityType::class,[
                'label' => 'label.room_configuration',
                'class' => MstRoomConfiguration::class,
                'placeholder' => 'placeholder.form.select',
                'multiple'=> true,
                'required' => true,
                'query_builder'=> function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->where("c.isActive = :active")
                        ->setParameter('active', 1);
                }
            ])
//            ->add('possessionNote', TextType::class,[
//                'label' => 'label.possession_note',
//                'required' => false,
//            ])
//            ->add('mstPossession', EntityType::class,[
//                'label' => 'label.possession_on',
//                'class' => MstPossession::class,
//                'multiple'=> false,
//                'required' => true,
//                'placeholder' => 'Select Possession',
//            ])
//            ->add('possessionOn', ChoiceType::class,[
//                'label' => 'label.possession_on',
//                'choices' => $this->buildYearChoices(),
//                'placeholder' => 'placeholder.form.select',
//                'required' => false
//            ])
            ->add('loading', TextType::class, [
                'label' => 'label.loading',
                'required' => false
            ])
            ->add('carParking', TextType::class, [
                'label' => 'label.car_parking',
                'required' => true
            ])
//            ->add('phase', TextType::class, [
//                'label' => 'label.phase',
//                'required' => false
//            ])
            ->add('openSpacePercentage', TextType::class, [
                'label' => 'label.open_space_percentage',
                'required' => false
            ])
            ->add('approvedBy', TextType::class, [
                'label' => 'label.approved_by',
                'required' => false,
            ])
//            ->add('mapView', TextType::class, [
//                'label' => 'label.map_view',
//                'required' => false
//            ])
//            ->add('locationLatitude', NumberType::class, [
//                'label' => 'label.location_latitude',
//                'required' => false
//            ])
//            ->add('locationLongitude', NumberType::class, [
//                'label' => 'label.location_longitude',
//                'required' => false
//            ])
            ->add('mahaReraRegisterationNo', TextType::class, [
                'label' => 'label.maharera_reg_no',
                'required' => false,
                'attr'=>[
                    'disabled'=>'disabled'
                ]
            ])
//            ->add('superArea', NumberType::class, [
//                'label' => 'label.area',
//                'required' => false
//            ])
//            ->add('mstProjectAreaSuperArea', EntityType::class, [
//                'class' => MstProjectArea::class,
//                'placeholder' => 'placeholder.form.select',
//                'label' => 'label.super_area',
//                'required' => false
//            ])
//            ->add('mstSuperAreaCurrency', EntityType::class, [
//                'class' => MstCurrency::class,
//                'placeholder' => 'placeholder.form.select',
//                'label' => 'label.currency',
//                'required' => false
//            ])
//            ->add('superAreaPricePer', NumberType::class, [
//                'label' => 'label.price_per',
//                'required' => false
//            ])
//            ->add('carpetArea', NumberType::class, [
//                'label' => 'label.area',
//                'required' => false
//            ])
//            ->add('mstProjectAreaCarpetArea', EntityType::class, [
//                'class' => MstProjectArea::class,
//                'placeholder' => 'placeholder.form.select',
//                'label' => 'label.carpet_area',
//                'required' => false
//            ])
//            ->add('mstCurrencyCarpetArea', EntityType::class, [
//                'class' => MstCurrency::class,
//                'placeholder' => 'placeholder.form.select',
//                'label' => 'label.currency',
//                'required' => false
//            ])
//            ->add('carpetAreaPricePer', NumberType::class, [
//                'label' => 'label.price_per',
//                'required' => false
//            ])
            ->add('occupancyCerificate', ChoiceType::class, [
                'choices'  => [
                    'Yes' => true,
                    'No' => false,
                ],
                'label' => 'label.occupancy_certificate',
                'required' => false
            ])
            ->add('commencementCerificate', ChoiceType::class, [
                'choices'  => [
                    'Yes' => true,
                    'No' => false,
                ],
                'label' => 'label.commencement_certificate',
                'required' => false
            ])
            ->add('bankApproved', ChoiceType::class, [
                'choices'  => [
                    'Yes' => true,
                    'No' => false,
                ],
                'label' => 'label.bank_approved',
                'required' => true,
                'placeholder'=>'Select..'
            ])
            ->add('totalNoUnits', IntegerType::class, [
                'label' => 'label.total_no_units',
                'required' => false
            ])
            ->add('totalNoOfTower', IntegerType::class, [
                'label' => 'label.total_number_towers',
                'required' => false
            ])
            ->add('projectOverview', CKEditorType::class, [
                'label' => 'label.project_overview',
                'attr' => [
                    'class' => 'textarea'
                ],
                'required' => false
            ])
//            ->add('mstAmenities', EntityType::class,[
//                'label' => 'label.project_amenities',
//                'class' => MstProjectAmenities::class,
//                'placeholder' => 'placeholder.form.select',
//                'multiple'=> true,
//                'required' => true
//            ])
            ->add('mstProjectFeature', EntityType::class,[
                'label' => 'label.project_feature',
                'class' => MstProductFeature::class,
                'placeholder' => 'placeholder.form.select',
                'multiple'=> true,
                'required' => true
            ])
            ->add('mstProjectHighlights', EntityType::class,[
                'label' => 'label.highlights',
                'class' => MstHighlights::class,
                'placeholder' => 'placeholder.form.select',
                'multiple'=> true,
                'required' => false
            ])
            ->add('mstProjectRating', EntityType::class,[
                'label' => 'label.rating',
                'class' => MstRating::class,
                'placeholder' => 'placeholder.form.select',
                'multiple'=> false,
                'required' => false
            ])
            ->add('trnProjectAdditionalDetail', CollectionType::class, [
                'entry_type' => TrnProjectAdditionalDetailType::class,
                'required' => false,
                'label' => false,
                'entry_options' => [
                    'label' => false,
                ],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference'=>false,
            ])
            ->add('trnUploadDocument', CollectionType::class, [
                'entry_type' => TrnUploadDocumentType::class,
                'required' => false,
                'label' => false,
                'entry_options' => [
                    'label' => false,
                ],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference'=>true,
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
                'data' => 'product',
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
//            ->add('trnProjectAreaDetail', CollectionType::class, [
//                'entry_type' => TrnProjectAreaDetailType::class,
//                'required' => true,
//                'label' => false,
//                'entry_options' => [
//                    'label' => false,
//                ],
//                'allow_add' => true,
//                'allow_delete' => true,
//                'by_reference'=>false,
//            ])
            ->add('trnProjectRoomConfigurations', CollectionType::class, [
                'entry_type' => TrnProjectRoomConfigurationType::class,
                'required' => true,
                'label' => false,
                'entry_options' => [
                    'label' => false,
                ],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference'=>false,
            ])
            ->add('isFeatured', CheckboxType::class, [
                'label' => 'label.is_featured',
                'required' => false,
            ])
            ->add('isActive', CheckboxType::class, [
                'label' => 'label.is_active',
                'required' => false,
                'attr' => [
                    'checked' => 'checked'
                ]
            ])
            ->add('isRera', ChoiceType::class,[
                'label' => 'label.isRera',
                'choices' => $this->yesNoType(),
                'placeholder'=> "Select...",
                'required' => true,
            ])
            ->add('isTredRecommended', ChoiceType::class,[
                'label' => 'label.isTredRecommended',
                'choices' => $this->yesNoType(),
                'placeholder'=> "Select...",
                'required' => false,
            ])
            ->add('isNewProperty', ChoiceType::class,[
                'label' => 'label.isNewProperty',
                'choices' => $this->yesNoType(),
                'placeholder'=> "Select...",
                'required' => false,
            ])
            ->add('trnProjectAmenities', CollectionType::class, [
                'entry_type' => TrnProjectAmenitiesType::class,
                'label' => false,
                'entry_options' => [
                    'label' => false,
                ],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference'=>false,
            ])
            ->add('brochureName', TextType::class,[
                'label' => 'label.brochure_name',
                'required'=> false,
            ])
            ->add('brochure', FileType::class,[
                'mapped' => false,
                'label' => 'Upload Pdf File',
                'required' => false,
                'constraints' => [
                    new File([
                            'maxSize' => '51200k',
                            'maxSizeMessage' => 'The maximum file upload size is 50 mb.',
                            'mimeTypes' => [
                                'application/pdf',
                            ],
                            'mimeTypesMessage' => 'Please upload a valid pdf file.',
                        ]
                    )
                ],
                'attr' => [
                    'placeholder' => 'Upload a file..',
                ]
            ])
            ->add('possessionDate', DateType::class,[
                'label' => 'label.possession_date',
                'widget' => 'single_text',
                'html5' => false,
                'required' => true,
                'format' => 'dd/MM/yyyy',
                'attr' => [
                    'placeholder' => 'DD/MM/YYYY',
                    'class'=>"datepicker",
                ]
            ])
            ->add('possessionMonth', ChoiceType::class,[
                'label' => 'label.possession_month',
                'placeholder' => 'Select..',
                'required' => false,
                'choices' => $this->commonHelper->monthSelection()
            ])
            ->add('possessionYear', ChoiceType::class,[
                'label' => 'label.possession_year',
                'required' => false,
                'placeholder' => 'Select..',
                'choices' => $this->commonHelper->yearSelection(5)
            ])
            ->add('noOfLifts', IntegerType::class, [
                'label' => 'label.no_of_lifts',
                'required' => false
            ])
            ->add('propertyAge', TextType::class, [
                'label' => 'label.property_age',
                'required' => false
            ])
            ->add('mstPropertyIn', EntityType::class, [
                'class' => MstPropertyIn::class,
                'placeholder' => 'placeholder.form.select',
                'label' => 'label.property_in',
                'required' => false
            ])
            ->add('electricityStatus', TextType::class, [
                'label' => 'label.electricity_status',
                'required' => false
            ])
            ->add('waterAvailibility', TextType::class, [
                'label' => 'label.water_availability',
                'required' => false
            ])
            ->add('availabilityFromDate', DateType::class,[
                'label' => 'label.availability_date',
                'widget' => 'single_text',
                'html5' => false,
                'required' => false,
                'format' => 'dd/MM/yyyy',
                'attr' => [
                    'placeholder' => 'DD/MM/YYYY',
                    'class'=>"datepicker",
                ],
                'row_attr' => [
                    'class' => 'datepicker_with_helper'
                ],
                'help' => 'Select the date in case of Rent'
            ])
            ->add('mstPincode', EntityType::class,[
                'label' => 'label.pincode',
                'class' => MstPincode::class,
                'multiple'=> false,
                'required' => false,
                'query_builder' => function (EntityRepository $dr) use ($options) {
                    $data = $options['data'];
                    if (null === $data->getMstPincode()) {
                        $mstPincodeId = null;
                    } elseif (null != $data && is_array($data)) {
                        $mstPincodeId = $data["mstPincode"];
                    } else {
                        $mstPincodeId = $data->getMstPincode()?$data->getMstPincode()->getId():null;
                    }
                    return $dr->createQueryBuilder('s')->andWhere('s.id=:mstPincode')->setParameter('mstPincode', $mstPincodeId);
                },
            ])
        ;

        $location = function ($form, $data) {
            $formStateOptions = [
                'label' => 'label.state',
                'class' => MstState::class,
                'required' => true,
                'query_builder' => function (EntityRepository $dr) use ($data) {
                    if (null === $data) {
                        $country_id = null;
                    } elseif (null != $data && is_array($data)) {
                        $country_id = $data["mstCountry"];
                    } else {
                        $country_id = $data->getMstCountry()?$data->getMstCountry()->getId():null;
                    }
                    return $dr->createQueryBuilder('s')->andWhere('s.mstCountry = :country')->setParameter('country', $country_id);
                },
                'attr' => [
                    'class' => 'mststate'
                ]
            ];
            $formCityOptions = [
                'label' => 'label.city',
                'class' => MstCity::class,
                'required' => true,
                'query_builder' => function (EntityRepository $dr) use ($data) {
                    if (null === $data) {
                        $state_id = null;
                    } elseif (null != $data && is_array($data)) {
                        $state_id = $data["mstState"];
                    } else {
                        $state_id = $data->getMstState()?$data->getMstState()->getId():null;
                    }
                    return $dr->createQueryBuilder('s')->andWhere('s.mstState = :state')->setParameter('state',
                        $state_id);
                },
                'attr' => [
                    'class' => 'mstcity'
                ]
            ];
            $formAreaInCityOptions = [
                'label' => 'label.area_city',
                'class' => MstAreaInCity::class,
                'required' => false,
                'query_builder' => function (EntityRepository $dr) use ($data) {
                    if (null === $data) {
                        $city_id = null;
                    } elseif (null != $data && is_array($data)) {
                        $city_id = $data["mstCity"];
                    } else {
                        $city_id = $data->getMstCity()?$data->getMstCity()->getId():null;
                    }
                    return $dr->createQueryBuilder('s')->andWhere('s.mstCity = :city')->setParameter('city',
                        $city_id);
                },
                'attr' => [
                    'class' => 'mstareaincity'
                ]
            ];
            $form
                ->add('mstState', EntityType::class,$formStateOptions, [
                    'label' => 'label.state',
                ])
                ->add('mstCity', EntityType::class, $formCityOptions, [
                    'label' => 'label.city'
                ])
//                ->add('mstAreaInCity', EntityType::class, $formAreaInCityOptions, [
//                    'label' => 'label.area_city'
//                ])

            ;
        };
        $refreshProductType = function ($form, $data) {

            $form->add('mstProductType', EntityType::class, [
                'class' => MstProductType::class,
                'placeholder' => 'placeholder.form.select',
                'label' => 'label.product_type',
                'required' => true,
                'query_builder' => function (EntityRepository $dr) use ($data) {
                    if (null === $data) {
                        $mstProductCategoryId = null;
                    } elseif (null !== $data && is_array($data)) {
                        $mstProductCategoryId = $data["mstProductCategory"];
                    } else {
                        $mstProductCategoryId = $data->getMstProductCategory()?$data->getMstProductCategory()->getId():null;
                    }
                    return $dr->createQueryBuilder('e')
                        ->andWhere('e.isActive = :active')
                        ->setParameter('active',1)
                        ->andWhere('e.mstProductCategory = :productCategory')
                        ->setParameter('productCategory', $mstProductCategoryId);
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
            ->add('mstProjectStatus', EntityType::class, [
                'class' => MstPropertyType::class,
                'placeholder' => 'placeholder.form.select',
                'label' => 'label.property_type',
                'required' => true,
                'query_builder' => function (EntityRepository $dr) use ($data) {
                    if (null === $data) {
                        return $dr->createQueryBuilder('e')
                            ->andWhere('e.isActive = :active')
                            ->setParameter('active',1);

                    } elseif (null != $data && is_array($data)) {
                        $mstProductTypeId = $data["mstProductType"];
                        if ($mstProductTypeId == 8){
                            return $dr->createQueryBuilder('e')
                                ->andWhere('e.isActive = :active')
                                ->andWhere('e.id = :id')
                                ->setParameter('active',1)
                                ->setParameter('id',2);
                        }else{
                            return $dr->createQueryBuilder('e')
                                ->andWhere('e.isActive = :active')
                                ->setParameter('active',1);
                        }
                    } else {
                        $mstProductTypeSlugName = $data->getMstProductType()?$data->getMstProductType()->getProductTypeSlugName():null;
                        if ($mstProductTypeSlugName == "resale"){
                            return $dr->createQueryBuilder('e')
                                ->andWhere('e.isActive = :active')
                                ->andWhere('e.id = :id')
                                ->setParameter('active',1)
                                ->setParameter('id',2);
                        }else{
                            return $dr->createQueryBuilder('e')
                                ->andWhere('e.isActive = :active')
                                ->setParameter('active',1);

                        }
                    }


                },
            ])
            ;
        };

        $refreshVendorPartnerDetails = function ($form, $data,$options) {

            $form->add('trnVendorPartnerDetails', EntityType::class, [
                'class' => TrnVendorPartnerDetails::class,
                'placeholder' => 'placeholder.form.select',
                'label' => 'label.vendor_partner_name',
                'required' => true,
                'query_builder' => function (EntityRepository $dr) use ($data,$options) {
                    $mstVendorId = $options['vendorId'];
                    if (null === $data) {
                        $mstVendorTypeId = null;
                    } elseif (null !== $data && is_array($data)) {
                        $mstVendorTypeId = $data["mstVendorType"];
                    } else {
                        $mstVendorTypeId = $data->getMstVendorType()?$data->getMstVendorType()->getId():null;
                    }
                    if ($mstVendorId > 0){
                        return $dr->createQueryBuilder('e')
                            ->andWhere('e.isActive = :active')
                            ->andWhere('e.id = :id')
                            ->setParameter('active',1)
                            ->setParameter('id',$mstVendorId);
                    }else{
                        return $dr->createQueryBuilder('e')
                            ->andWhere('e.isActive = :active')
                            ->setParameter('active',1)
                            ->andWhere('e.mstVendorType = :mstVendorTypeId')
                            ->setParameter('mstVendorTypeId', $mstVendorTypeId);
                    }
                },
            ])
            ;
        };
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($location,$refreshProductType,$refreshProductSubType,$refreshVendorPartnerDetails,$options) {
            $form = $event->getForm();
            $data = $event->getData();
            $location ($form, $data);
            $refreshProductType ($form, $data);
            $refreshProductSubType ($form, $data);
            $refreshVendorPartnerDetails ($form, $data,$options);
        });
        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($location,$refreshProductType,$refreshProductSubType,$refreshVendorPartnerDetails,$options) {
            $form = $event->getForm();
            $data = $event->getData();
            if (array_key_exists('mstCountry', $data)) {
                $location($form, $data);
            }
            if (array_key_exists('mstProductCategory', $data)) {
                $refreshProductType ($form, $data);
            }
            if (array_key_exists('mstProductType', $data)) {
                $refreshProductSubType ($form, $data);
            }
            if (array_key_exists('mstVendorType', $data)) {
                $refreshVendorPartnerDetails ($form, $data, $options);
            }

        });

    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TrnProject::class,
            'vendorTypeId' => 0,
            'vendorId' => 0,
        ]);
    }
}
