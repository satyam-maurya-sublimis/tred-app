<?php

namespace App\Form\Transaction;

use App\Entity\Master\MstCountry;
use App\Entity\Master\MstPincode;
use App\Entity\Master\MstSalutation;
use App\Entity\Master\MstState;
use App\Entity\Master\MstSubscriptionCategory;
use App\Entity\Product\PrdBrand;
use App\Entity\Master\MstCity;
use App\Entity\Master\MstRating;
use App\Entity\Transaction\TrnTopVendorPartners;
use App\Entity\Transaction\TrnVendorPartnerDetails;
use App\Repository\Product\PrdBrandRepository;
use App\Repository\Master\MstCityRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class TrnTopVendorPartnersType extends AbstractType
{
    private $mstCityRepository;
    /**
     * @param MstCityRepository $mstCityRepository
     */
    public function __construct(MstCityRepository $mstCityRepository)
    {
        $this->mstCityRepository = $mstCityRepository;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('trnVendorPartnerDetails', EntityType::class, [
                'label' => 'label.vendor_partner',
                'class' => TrnVendorPartnerDetails::class,
                'placeholder' => 'placeholder.form.select',
                'required' => true
            ])
            ->add('noOfYearsInBusiness', TextType::class, [
                'label' => 'label.business_year',
                'required' => true
            ])
            ->add('teamSize', TextType::class, [
                'label' => 'label.team_size',
                'required' => false
            ])
            ->add('annualTurnOver', TextType::class, [
                'label' => 'label.annual_turn_over',
                'required' => true
            ])
            ->add('numberOfUnitSoldAnnually', TextType::class, [
                'label' => 'label.annual_sold_unit',
                'required' => true
            ])
            ->add('prdBrands', EntityType::class, [
                'label' => 'label.brand',
                'class' => PrdBrand::class,
                'query_builder' => function (PrdBrandRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->innerJoin('c.mstProductCategory', 'pc')
                        ->where("pc.productCategorySlugName = :slugname")
                        ->andwhere("c.isActive = :active")
                        ->andwhere("pc.isActive = :active")
                        ->setParameter('slugname','properties')
                        ->setParameter('active',1)
                        ;
                },
                'placeholder' => 'placeholder.form.select',
                'multiple' => true,
                'required' => true
            ])
//            ->add('mstCities', EntityType::class, [
//                'label' => 'label.geographical_presence',
//                'class' => MstCity::class,
//                'query_builder' => function (MstCityRepository $er) {
//                    return $er->createQueryBuilder('c')
//                        ->innerJoin('c.mstCountry', 'mstCountry')
//                        ->where("mstCountry.country = :country")
//                        ->setParameter('country','India');
//                },
//                'placeholder' => 'placeholder.form.select',
//                'multiple' => true,
//                'required' => false
//            ])
            ->add('mstRating', EntityType::class, [
                'class' => MstRating::class,
                'placeholder' => 'placeholder.form.select',
                'label' => 'label.rating',
                'required' => false,
            ])
            ->add('mstSubscriptionCategory', EntityType::class, [
                'class' => MstSubscriptionCategory::class,
                'placeholder' => 'placeholder.form.select',
                'label' => 'label.subscription_type',
                'required' => true,
            ])
           ->add('isActive', CheckboxType::class, [
                'label' => 'label.is_active',
                'required' => false,
                'attr' => [
                    'checked' => 'checked'
                ]
            ])
            ->add('contactPersonCountry', EntityType::class,[
                'label' => 'label.country',
                'class' => MstCountry::class,
                'multiple'=> false,
                'required' => false,
                'query_builder' => function (EntityRepository $er){
                    return $er->createQueryBuilder('c')->andWhere('c.id =:country')->setParameter('country',101);
                }
            ])
            ->add('contactPersonState', EntityType::class,[
                'label' => 'label.state',
                'class' => MstState::class,
                'placeholder' => 'placeholder.form.select',
                'multiple'=> false,
                'required' => false,
                'query_builder' => function (EntityRepository $er){
                    return $er
                        ->createQueryBuilder('c')
                        ->innerJoin('c.mstCountry','d')
                        ->andWhere('d.id =:country')
                        ->setParameter('country',101);
                },
                'attr' => [
                    'class' => 'mststate'
                ]
            ])
            ->add('contactPersonImage', FileType::class, [
                'mapped' => false,
                'required' => false,
                'label' => 'label.profile_pic',
                'attr' => [
                    'class' => 'custom-file-input'
                ],
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
            ->add('contactPersonName', TextType::class, [
                'label' => 'label.name',
                'required' => false
            ])
            ->add('mstSalutation', EntityType::class, [
                'label' => 'label.salutation',
                'class' => MstSalutation::class,
                'placeholder' => 'placeholder.form.select',
                'required' => false
            ])
            ->add('trnTopVendorPartnersLocalities', CollectionType::class, [
                'entry_type' => TrnTopVendorPartnersLocalityType::class,
                'required' => false,
                'label' => false,
                'entry_options' => [
                    'label' => false,
                ],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference'=>true,
            ])
            ->add('removeContactPersonImage', CheckboxType::class,[
                'mapped' => false,
                'required'=> false,
                'label' => 'label.delete_image',
                'help' => 'Select if you do not need the contact person image'
            ])
        ;
        $refreshCity = function ($form, $data) {
            $formCityOptions = [
                'label' => 'label.city',
                'class' => MstCity::class,
                'required' => false,
                'query_builder' => function (EntityRepository $dr) use ($data) {
                    if (null === $data) {
                        $state_id = null;
                    } elseif (null != $data && is_array($data)) {
                        $state_id = $data["contactPersonState"];
                    } else {
                        $state_id = $data->getContactPersonState()?$data->getContactPersonState()->getId():null;
                    }
                    return $dr->createQueryBuilder('s')->andWhere('s.mstState = :state')->setParameter('state', $state_id);
                },
                'attr' => [
                    'class' => 'mstcity'
                ]
            ];
            $form->add('contactPersonCity', EntityType::class,$formCityOptions);
        };
        $refreshPincode = function ($form, $data){
            $formPincodeOptions = [
                'label' => 'label.pincode',
                'class' => MstPincode::class,
                'choice_label' => function(MstPincode $mstPincode) {
                    return sprintf('%s : (%s)', $mstPincode->getOfficeName(),$mstPincode->getPincode());
                },
                'required' => false,
                'query_builder' => function (EntityRepository $dr) use ($data) {
                    if (null === $data) {
                        $cityId = null;
                    } elseif (null != $data && is_array($data)) {
                        $cityId = $data["contactPersonCity"];
                    } else {
                        $cityId = $data->getContactPersonCity()?$data->getContactPersonCity()->getId():null;
                    }
                    $cityName = null;
                    if ($cityId){
                        $mstCity = $this->mstCityRepository->find($cityId);
                        $cityName = $mstCity->getCity();
                        return $dr
                            ->createQueryBuilder('s')
                            ->andWhere('s.delivery = :delivery')
                            ->andWhere('s.district like :cityName')
                            ->setParameter('delivery', 'Delivery')
                            ->setParameter('cityName', '%'.$cityName.'%');
                    }else{
                        return $dr
                            ->createQueryBuilder('s')
                            ->andWhere('s.delivery = :delivery')
                            ->andWhere('s.district like :cityName')
                            ->setParameter('delivery', 'Delivery')
                            ->setParameter('cityName', $cityName);
                    }
                },
                'attr' => [
                    'class' => 'mstpincode'
                ]
            ];
            $form->add('contactPersonPincode', EntityType::class,$formPincodeOptions);
        };
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($refreshCity,$refreshPincode) {
            $form = $event->getForm();
            $data = $event->getData();
            $refreshCity ($form, $data);
            $refreshPincode ($form, $data);
        });
        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($refreshCity,$refreshPincode) {
            $form = $event->getForm();
            $data = $event->getData();
            if (array_key_exists('contactPersonState', $data)) {
                $refreshCity ($form, $data);
            } if (array_key_exists('contactPersonCity', $data)) {
                $refreshPincode ($form, $data);
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TrnTopVendorPartners::class,
        ]);
    }

}
