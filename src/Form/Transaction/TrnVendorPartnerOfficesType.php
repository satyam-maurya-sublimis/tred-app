<?php

namespace App\Form\Transaction;

use App\Entity\Master\MstAreaInCity;
use App\Entity\Master\MstCity;
use App\Entity\Master\MstCountry;
use App\Entity\Master\MstDaysOfWeek;
use App\Entity\Master\MstState;
use App\Entity\Master\MstOfficeCategory;
use App\Entity\Transaction\TrnVendorPartnerDetails;
use App\Entity\Transaction\TrnVendorPartnerOffices;
use App\Repository\Master\MstCityRepository;
use App\Repository\Master\MstStateRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Constraints\File;

class TrnVendorPartnerOfficesType extends AbstractType
{
    /**
     * @var MstStateRepository
     */
    private $mstStateRepository;
    /**
     * @var MstCityRepository
     */
    private $mstCityRepository;
    /**
     * MstCityType constructor.
     * @param MstStateRepository $mstStateRepository
     * @param MstCityRepository $mstCityRepository
     */
    public function __construct(MstStateRepository $mstStateRepository, MstCityRepository $mstCityRepository)
    {
        $this->mstStateRepository = $mstStateRepository;
        $this->mstCityRepository = $mstCityRepository;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('trnVendorPartnerDetails', EntityType::class, [
                'class' => TrnVendorPartnerDetails::class,
                'placeholder' => 'placeholder.form.select',
                'label' => 'label.vendor_partner_name',
                'required' => true
            ])
            ->add('officeName', TextType::class, [
                'label' => 'label.office_name',
                'required' => true
            ])
            ->add('mstOfficeCategory', EntityType::class, [
                'class' => MstOfficeCategory::class,
                'placeholder' => 'placeholder.form.select',
                'label' => 'label.office_category',
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
            ->add('address1', TextType::class, [
                'label' => 'label.addressOne',
                'required' => true
            ])
            ->add('address2', TextType::class, [
                'label' => 'label.addressTwo',
                'required' => false
            ])
            ->add('pincode', TextType::class, [
                'label' => 'label.pincode',
                'required' => true
            ])
            ->add('mobileNoCountryCode', TextType::class, [
                'label' => 'label.contact_no_country_code',
                'required' => false
            ])
            ->add('mobileNumber', TextType::class, [
                'label' => 'label.mobile',
                'required' => false
            ])
            ->add('faxNoCountryCode', TextType::class, [
                'label' => 'label.contact_no_country_code',
                'required' => false
            ])
            ->add('faxNoCityCode', TextType::class, [
                'label' => 'label.contact_no_city_code',
                'required' => false
            ])
            ->add('faxNumber', TextType::class, [
                'label' => 'label.fax',
                'required' => false
            ])
            ->add('mstDaysOfWeek', EntityType::class, [
                'label' => 'label.days_of_week',
                'class' => MstDaysOfWeek::class,
                'placeholder' => 'placeholder.form.select',
                'multiple' => true,
                'required' => false
            ])
            ->add('workingTimeFrom', TimeType::class, [
                'label' => 'label.working_time_from',
                'required' => false
            ])
            ->add('workingTimeTo', TimeType::class, [
                'label' => 'label.working_time_to',
                'required' => false
            ])
            ->add('isActive', CheckboxType::class, [
                'label' => 'label.is_active',
                'required' => false,
                'attr' => [
                    'checked' => 'checked'
                ]
            ])
            ->add('mstAreaInCity', EntityType::class, [
                'label' => 'label.area_city',
                'class' => MstAreaInCity::class,
                'placeholder' => 'placeholder.form.select',
                'multiple' => false,
                'required' => false,
            ])
            ->add('trnVendorPartnerOfficeLandLines', CollectionType::class, [
                'entry_type' => TrnVendorPartnerOfficeLandLineType::class,
                'required' => false,
                'label' => false,
                'entry_options' => [
                    'label' => false,
                ],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference'=>false,
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
            $form
                ->add('mstState', EntityType::class,$formStateOptions, [
                    'label' => 'label.state',
                ])
                ->add('mstCity', EntityType::class, $formCityOptions, [
                    'label' => 'label.city'
                ])

            ;
        };
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($location) {
            $form = $event->getForm();
            $data = $event->getData();
            $location ($form, $data);
        });
        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($location) {
            $form = $event->getForm();
            $data = $event->getData();
            if (array_key_exists('mstCountry', $data)) {
                $location($form, $data);
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TrnVendorPartnerOffices::class,
        ]);
    }
}
