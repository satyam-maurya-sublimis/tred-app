<?php

namespace App\Form\Form;

use App\Entity\Form\FormResidentialEnquiry;
use App\Entity\Master\MstCity;
use App\Entity\Master\MstCountry;
use App\Entity\Master\MstLeadStatus;
use App\Entity\Master\MstRoomConfiguration;
use App\Entity\Master\MstSalutation;
use App\Entity\Master\MstState;
use App\Entity\Organization\OrgCompany;
use App\Entity\Product\PrdProduct;
use App\Entity\Transaction\TrnProjectRoomConfiguration;
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

class FormResidentialEnquiryType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
//            ->add('orgCompany', EntityType::class,[
//                'label' => 'label.company',
//                'class' => OrgCompany::class,
//                'placeholder' => 'placeholder.form.select',
//                'required' => 'true'
//            ])
            ->add('mstSalutation', EntityType::class, [
                'label' => 'label.salutation',
                'class' => MstSalutation::class,
                'placeholder' => 'placeholder.form.select',
                'required' => false
            ])
            ->add('residentialEnquiryFirstName', TextType::class, [
                'label' => 'label.firstName',
                'required' => true
            ])
            ->add('residentialEnquiryMiddleName', TextType::class, [
                'label' => 'label.middleName',
                'required' => false
            ])
            ->add('residentialEnquiryLastName', TextType::class, [
                'label' => 'label.lastName',
                'required' => true
            ])
//            ->add('residentialEnquiryPhoneNumber', TelType::class, [
//                'label' => 'label.phone',
//                'required' => false,
//                'attr' => [
//                    'maxlength' => '10',
//                    'minlength' => '10'
//                ]
//            ])
            ->add('residentialEnquiryMobileNumber', TelType::class, [
                'label' => 'label.mobile',
                'required' => true,
                'attr' => [
                    'maxlength' => '10',
                    'minlength' => '10'
                ]
            ])
            ->add('residentialEnquiryEmailAddress', EmailType::class, [
                'label' => 'label.email_primary',
                'required' => true,

            ])
            ->add('residentialEnquiryDescription', TextareaType::class, [
                'label' => 'label.description',
                'required' => false
            ])
            ->add('mstLeadStatus', EntityType::class, [
                'label' => 'label.lead_status',
                'class' => MstLeadStatus::class,
                'placeholder' => 'placeholder.form.select',
                'required' => true
            ])
            ->add('residentialEnquiryAddressOne', TextType::class, [
                'label' => 'label.addressOne',
                'required' => false,
            ])
            ->add('residentialEnquiryAddressTwo', TextType::class, [
                'label' => 'label.addressTwo',
                'required' => false,
            ])
            ->add('residentialEnquiryPincode', TextType::class, [
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
            ->add('mstRoomConfiguration', EntityType::class, [
                'label' => 'Room Type',
                'class' => MstRoomConfiguration::class,
                'required' => false,
                'query_builder' => function (EntityRepository $er){
                    return $er->createQueryBuilder('c')
                        ->andWhere('c.isActive =:active')
                        ->setParameter('active',1);
                },
            ])
            ->add('residentialEnquiryBudget', TextType::class, [
                'label' => 'Approximate Budget',
                'required' => false,
            ])
            ->add('residentialEnquiryLocation', TextType::class, [
                'label' => 'Location',
                'required' => false,
            ])
            ->add('residentialEnquiryTitle', TextType::class, [
                'label' => 'Title',
                'required' => false,
            ])
            ->add('trnProjectRoomConfiguration', EntityType::class, [
                'label' => 'Project Name',
                'class' => TrnProjectRoomConfiguration::class,
                'choice_label' => function(TrnProjectRoomConfiguration $trnProjectRoomConfiguration) {
                    $trnProject = $trnProjectRoomConfiguration->getTrnProject();
                    if ($trnProject){
                        return sprintf('%s - %s', $trnProjectRoomConfiguration->getMstRoomConfiguration()->getRoomConfiguration(),$trnProject->getProjectName());
                    }else{

                    }
                },
                'required' => false,
                'query_builder' => function (EntityRepository $er){
                    return $er->createQueryBuilder('c')->andWhere('c.isActive =:active')->setParameter('active',1);
                },
            ])

        ;
        /**
         * @param $form
         * @param $data
         */
        $refreshState = function ($form, $data) {
            $formStateOptions = [
                'label' => 'label.state',
                'class' => MstState::class,
                'required' => false,
                'placeholder' => 'placeholder.form.select',
                'query_builder' => function (EntityRepository $dr) use ($data) {
                    if (array_key_exists('mstCountry', $data)) {
                        $country_id = $data["mstCountry"];
                    } else {
                        $country_id = $data->getMstCountry() ? $data->getMstCountry()->getId() : null;
                    }
                    return $dr->createQueryBuilder('s')->andWhere('s.mstCountry =:country')->setParameter('country', $country_id);
                },
            ];
            $form
                ->add('mstState', EntityType::class, $formStateOptions, [
                    'label' => 'label.state',
                ]);
        };

        $refreshCity = function ($form, $data) {
            $formCityOptions = [
                'label' => 'label.city',
                'class' => MstCity::class,
                'required' => false,
                'placeholder' => 'placeholder.form.select',
                'query_builder' => function (EntityRepository $dr) use ($data) {
                    if (array_key_exists('mstState', $data)) {
                        $state_id = $data["mstState"];
                    } else {
                        $state_id = $data->getMstState() ? $data->getMstState()->getId() : null;
                    }
                    return $dr->createQueryBuilder('c')->andWhere('c.mstState =:state')->setParameter('state', $state_id);
                },
            ];
            $form
                ->add('mstCity', EntityType::class, $formCityOptions, [
                    'label' => 'label.city',
                ]);
        };
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($refreshState,$refreshCity) {
            $form = $event->getForm();
            $data = $event->getData();
            $refreshState ($form, $data);
            $refreshCity ($form, $data);
        });
        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($refreshState,$refreshCity) {
            $form = $event->getForm();
            $data = $event->getData();
            if (array_key_exists('mstCountry', $data)) {
                $refreshState($form, $data);
            }
            if (array_key_exists('mstState', $data)) {
                $refreshCity($form, $data);
            }
        });
    }
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FormResidentialEnquiry::class,
        ]);
    }
}
