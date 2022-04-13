<?php

namespace App\Form\Form;

use App\Entity\Form\FormEnquiryTopAgents;
use App\Entity\Master\MstCity;
use App\Entity\Master\MstCountry;
use App\Entity\Master\MstLeadStatus;
use App\Entity\Master\MstProductCategory;
use App\Entity\Master\MstSalutation;
use App\Entity\Master\MstState;
use App\Entity\Organization\OrgCompany;
use App\Entity\Transaction\TrnVendorPartnerDetails;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormEnquiryTopAgentsType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('mstSalutation', EntityType::class, [
                'label' => 'label.salutation',
                'class' => MstSalutation::class,
                'placeholder' => 'placeholder.form.select',
                'required' => false
            ])
            ->add('enquiryFirstName', TextType::class, [
                'label' => 'label.firstName',
                'required' => true
            ])
            ->add('enquiryMiddleName', TextType::class, [
                'label' => 'label.middleName',
                'required' => false
            ])
            ->add('enquiryLastName', TextType::class, [
                'label' => 'label.lastName',
                'required' => true
            ])
            ->add('enquiryMobileNumber', TelType::class, [
                'label' => 'label.mobile',
                'required' => true,
                'attr' => [
                    'maxlength' => '10',
                    'minlength' => '10'
                ]
            ])
            ->add('enquiryEmailAddress', EmailType::class, [
                'label' => 'label.email_primary',
                'required' => true,

            ])
            ->add('mstLeadStatus', EntityType::class, [
                'label' => 'label.lead_status',
                'class' => MstLeadStatus::class,
                'placeholder' => 'placeholder.form.select',
                'required' => true
            ])
            ->add('enquiryAddressOne', TextType::class, [
                'label' => 'label.addressOne',
                'required' => false,
            ])
            ->add('enquiryAddressTwo', TextType::class, [
                'label' => 'label.addressTwo',
                'required' => false,
            ])
            ->add('enquiryPincode', TextType::class, [
                'label' => 'label.pincode',
                'required' => false,
                'attr' => [
                    'maxlength' => '12',
                    'minlength' => '4'
                ]
            ])
            ->add('mstCountry', EntityType::class, [
                'class' => MstCountry::class,
                'placeholder' => 'placeholder.form.select',
                'label' => 'label.country',
                'data' => $options['data']->getMstCountry(),
                'required' => false,
                'attr' => [
                    'class' => 'mstcountry'
                ]
            ])
            ->add('trnVendorPartnerDetails', EntityType::class, [
                'class' => TrnVendorPartnerDetails::class,
                'placeholder' => 'Select..',
                'label' => 'label.vendor_partner',
                'required' => false,
                'query_builder' => function (EntityRepository $dr) {
                    return $dr->createQueryBuilder('e')
                        ->andWhere('e.isActive = :active')
                        ->setParameter('active',1);
                },
            ]);
        /**
         * @param $form
         * @param $data
         */
        $refreshLocation = function ($form, $data) {
            $formStateOptions = [
                'label' => 'label.state',
                'class' => MstState::class,
                'required' => false,
                'query_builder' => function (EntityRepository $dr) use ($data) {
                    if (is_array($data)) {
                        $country_id = $data["mstCountry"];
                    } else {
                        $country_id = $data->getMstCountry() ? $data->getMstCountry()->getId() : null;
                    }
                    return $dr->createQueryBuilder('s')->andWhere('s.mstCountry =:country')->setParameter('country', $country_id);
                },
            ];
            $formCityOptions = [
                'label' => 'label.city',
                'class' => MstCity::class,
                'required' => false,
                'query_builder' => function (EntityRepository $dr) use ($data) {
                    if (is_array($data)) {
                        $state_id = $data["mstState"];
                    } else {
                        $state_id = $data->getMstState() ? $data->getMstState()->getId() : null;
                    }
                    return $dr->createQueryBuilder('c')->andWhere('c.mstState =:state')->setParameter('state', $state_id);
                },
            ];
            $form
                ->add('mstState', EntityType::class, $formStateOptions, [
                ])
                ->add('mstCity', EntityType::class, $formCityOptions, [
                ]);
        };
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($refreshLocation) {
            $form = $event->getForm();
            $data = $event->getData();
            $refreshLocation ($form, $data);
        });
        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($refreshLocation) {
            $form = $event->getForm();
            $data = $event->getData();
            if (isset($data["mstCountry"])) {
                $refreshLocation($form, $data);
            }
        });
    }
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FormEnquiryTopAgents::class,
        ]);
    }
}
