<?php

namespace App\Form\Transaction;

use App\Entity\Master\MstCity;
use App\Entity\Master\MstCountry;
use App\Entity\Master\MstNatureOfBusiness;
use App\Entity\Master\MstPincode;
use App\Entity\Master\MstProductType;
use App\Entity\Master\MstProductSubType;
use App\Entity\Master\MstState;
use App\Entity\Master\MstVendorType;
use App\Entity\Transaction\TrnVendorPartnerDetails;
use App\Entity\Organization\OrgCompany;
use App\Repository\Master\MstCityRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class TrnVendorPartnerDetailsType extends AbstractType
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
            ->add('orgCompany', EntityType::class, [
                'class' => OrgCompany::class,
                'placeholder' => 'placeholder.form.select',
                'label' => 'label.org_company',
                'required' => true
            ])
            ->add('vendorPartnerName', TextType::class, [
                'label' => 'label.vendor_partner_name',
                'required' => true
            ])
            ->add('mstVendorType', EntityType::class, [
                'class' => MstVendorType::class,
                'placeholder' => 'placeholder.form.select',
                'label' => 'label.vendor_type',
                'required' => true
            ])
            ->add('mstCitiesOperatingIn', EntityType::class, [
                'label' => 'label.operating_cities',
                'class' => MstCity::class,
                'query_builder' => function (MstCityRepository $er) {
                    $queryBuilder = $er->createQueryBuilder('c');
                    $queryBuilder
                        ->select('c')
                        ->innerJoin('c.mstCountry', 'mstCountry')
                        ->where("mstCountry.country = 'India' ");
                    return $queryBuilder;
                },
                'placeholder' => 'placeholder.form.select',
                'multiple' => true,
                'required' => true
            ])
            ->add('mstProductType', EntityType::class, [
                'label' => 'label.product_type',
                'class' => MstProductType::class,
                'placeholder' => 'placeholder.form.select',
                'multiple' => true,
                'required' => true
            ])
            ->add('mstProductSubType', EntityType::class, [
                'label' => 'label.product_subtype',
                'class' => MstProductSubType::class,
                'placeholder' => 'placeholder.form.select',
                'multiple' => true,
                'required' => true
            ])
//            ->add('experience', TextType::class, [
//                'label' => 'label.experience',
//                'required' => true
//            ])
            ->add('mstNatureOfBusiness', EntityType::class, [
                'label' => 'label.nature_of_business',
                'class' => MstNatureOfBusiness::class,
                'placeholder' => 'placeholder.form.select',
                'multiple' => true,
                'required' => true
            ])
            ->add('gstNumber', TextType::class, [
                'label' => 'label.gst_number',
                'required' => false
            ])
            ->add('legalStatusOfFirm', TextType::class, [
                'label' => 'label.legal_status_of_firm',
                'required' => false
            ])
            ->add('establishmentYear', DateType::class, [
                'label' => 'label.year_of_establishment',
                'widget' => 'single_text',
                'required' => false
            ])
//            ->add('noOfEmployees', TextType::class, [
//                'label' => 'label.no_of_employees',
//                'required' => false
//            ])
//            ->add('annualTurnOver', TextType::class, [
//                'label' => 'label.annual_turn_over',
//                'required' => true
//            ])
            ->add('projectsCompleted', TextType::class, [
                'label' => 'label.projects_completed',
                'required' => false
            ])
            ->add('websiteUrl', TextType::class, [
                'label' => 'label.website_url',
                'required' => false
            ])
            ->add('introduction', TextareaType::class, [
                'label' => 'label.introduction',
                'required' => true,
                'attr' => [
                    'class' => 'textarea'
                ]
            ])
            ->add('companyLogo', FileType::class, [
                'mapped' => false,
                'required' => false,
                'label' => 'label.company_logo',
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
            ->add('isActive', CheckboxType::class, [
                'label' => 'label.is_active',
                'required' => false,
                'attr' => [
                    'checked' => 'checked'
                ]
            ])
//            ->add('isTopPartner', CheckboxType::class, [
//                'label' => 'label.is_top_partner',
//                'help'=>'Check mark this if you want to show in top builder/real estate on site',
//                'required' => false,
//            ]);
            ->add('mstCountry', EntityType::class,[
                'label' => 'label.country',
                'class' => MstCountry::class,
                'multiple'=> false,
                'required' => true,
                'query_builder' => function (EntityRepository $er){
                    return $er->createQueryBuilder('c')->andWhere('c.id =:country')->setParameter('country',101);
                }
            ])
            ->add('mstState', EntityType::class,[
                'label' => 'label.state',
                'class' => MstState::class,
                'placeholder' => 'placeholder.form.select',
                'multiple'=> false,
                'required' => true,
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
        ;
        $refreshCity = function ($form, $data) {
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
                    return $dr->createQueryBuilder('s')->andWhere('s.mstState = :state')->setParameter('state', $state_id);
                },
                'attr' => [
                    'class' => 'mstcity'
                ]
            ];
            $form->add('mstCity', EntityType::class,$formCityOptions);
        };
        $refreshPincode = function ($form, $data){
            $formPincodeOptions = [
                'label' => 'label.pincode',
                'class' => MstPincode::class,
                'choice_label' => function(MstPincode $mstPincode) {
                        return sprintf('%s : (%s)', $mstPincode->getOfficeName(),$mstPincode->getPincode());
                },
                'required' => true,
                'query_builder' => function (EntityRepository $dr) use ($data) {
                    if (null === $data) {
                        $cityId = null;
                    } elseif (null != $data && is_array($data)) {
                        $cityId = $data["mstCity"];
                    } else {
                        $cityId = $data->getMstCity()?$data->getMstCity()->getId():null;
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
            $form->add('mstPincode', EntityType::class,$formPincodeOptions);
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
            if (array_key_exists('mstState', $data)) {
                $refreshCity ($form, $data);
            } if (array_key_exists('mstCity', $data)) {
                $refreshPincode ($form, $data);
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TrnVendorPartnerDetails::class,
        ]);
    }

    public function buildYearChoices()
    {
        $distance = 100;
        $yearsBefore = date('Y', mktime(0, 0, 0, date("m"), date("d"), date("Y") - $distance));
        $yearsAfter = date('Y', mktime(0, 0, 0, date("m"), date("d"), date("Y")));
        return array_combine(range($yearsBefore, $yearsAfter), range($yearsBefore, $yearsAfter));
    }
}
