<?php

namespace App\Form\Form;

use App\Entity\Form\FormEnquiry;
use App\Entity\Master\MstCity;
use App\Entity\Master\MstCountry;
use App\Entity\Master\MstLeadStatus;
use App\Entity\Master\MstProductCategory;
use App\Entity\Master\MstSalutation;
use App\Entity\Master\MstState;
use App\Entity\Organization\OrgCompany;
use App\Entity\Master\MstProductType;
use App\Service\CommonHelper;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormEnquiryType extends AbstractType
{
    private $commonHelper;

    public function __construct(CommonHelper $commonHelper)
    {
        $this->commonHelper = $commonHelper;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
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
            ->add('enquiryEmailAddress', EmailType::class, [
                'label' => 'label.email_primary',
                'required' => true,

            ])
            ->add('enquiryMobileNumber', TelType::class, [
                'label' => 'label.mobile',
                'required' => true,
                'attr' => [
                    'maxlength' => '10',
                    'minlength' => '10'
                ]
            ])
            ->add('enquiryPhoneNumber', TelType::class, [
                'label' => 'label.phone',
                'required' => false,
                'attr' => [
                    'maxlength' => '10',
                    'minlength' => '10'
                ]
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
            ->add('enquiryCity', TextType::class, [
                'label' => 'label.city',
                'required' => false,
            ])
            ->add('enquiryDescription', TextareaType::class, [
                'label' => 'label.description',
                'required' => false
            ])
            ->add('enquiryHomeLoanAmount', TextType::class, [
                'label' => 'label.home_loan_amount',
                'required' => false,
            ])
            ->add('enquiryBudget', TextType::class, [
                'label' => 'label.budget',
                'required' => false,
            ])
            ->add('enquiryNeed', TextareaType::class, [
                'label' => 'label.enquiry_need',
                'required' => false,
            ])
            ->add('enquiryMovingFrom', TextType::class, [
                'label' => 'label.enquiry_moving_from',
                'required' => false,
            ])
            ->add('enquiryMovingTo', TextType::class, [
                'label' => 'label.enquiry_moving_to',
                'required' => false,
            ])
            ->add('enquiryShiftingType', TextType::class, [
                'label' => 'label.enquiry_shifting_type',
                'required' => false,
            ])
            ->add('enquiryScopeOfWork', ChoiceType::class, [
                'required' => true,
                'multiple' => true,
                'expanded' => true,
                'choices'  => $this->commonHelper->scopeOfWork(),
                'attr' => [
                    'class'=>'custom-checkbox-group'
                ],
                'choice_attr'=>[
                    'class'=>'custom-checkbox'
                ],
                'label_attr'=>[
                    'class'=>'custom-control-label'
                ],
            ])
            ->add('enquiryCarpetArea', TextType::class, [
                'label' => 'label.carpet_area',
                'required' => false,
            ])
            ->add('enquiryMoreDetail', TextareaType::class, [
                'label' => false,
                'required' => true,
                'help'=>'(E.G. how many bedroom, what kind of furniture etc.) total work and location area to give you a proper costing.'
            ])
            ->add('enquiryComments', TextareaType::class, [
                'label' => 'label.comments',
                'required' => false,
            ])
            ->add('mstSalutation', EntityType::class, [
                'label' => 'label.salutation',
                'class' => MstSalutation::class,
                'placeholder' => 'placeholder.form.select',
                'required' => false
            ])
            ->add('mstCountry', EntityType::class, [
                'label' => false,
                'class' => MstCountry::class,
                'required' => false,
                'query_builder' => function (EntityRepository $er){
                    return $er->createQueryBuilder('c')->andWhere('c.id =:country')->setParameter('country',101);
                },
                'row_attr'=>[
                    'class'=>'cust-select'
                ]
            ])
//            ->add('mstCity', EntityType::class, [
//                'class' => MstCity::class,
//                'placeholder' => 'City',
//                'label' => false,
//                'required' => true,
//                'query_builder' => function (EntityRepository $er){
//                    return $er->createQueryBuilder('s')->andWhere('s.mstCountry =:country')->setParameter('country',101);
//                },
//                'row_attr'=>[
//                    'class'=>'cust-select'
//                ]
//            ])
            ->add('mstLeadStatus', EntityType::class,[
                'label' => 'label.lead_status',
                'class' => MstLeadStatus::class,
                'placeholder' => 'placeholder.form.select',
                'required' => 'true'
            ])
            ->add('orgCompany', EntityType::class,[
                'label' => 'label.company',
                'class' => OrgCompany::class,
                'placeholder' => 'placeholder.form.select',
                'required' => 'true'
            ])
            ->add('mstProductCategory', EntityType::class,[
                'label' => 'label.product_category',
                'class' => MstProductCategory::class,
                'placeholder' => 'placeholder.form.select',
                'required' => 'true'
            ])
            ->add('mstProductType', EntityType::class,[
                'label' => 'label.product_type',
                'class' => MstProductType::class,
                'placeholder' => 'placeholder.form.select',
                'required' => 'true'
            ])
            ->get('enquiryScopeOfWork')
            ->addModelTransformer(new CallbackTransformer(
                function ($dataArray) {
                    return explode(",",$dataArray);
                },
                function ($dataString) {
                    return implode(",",$dataString);
                }
            ))
        ;
        /**
         * @param $form
         * @param $data
         */
        $refreshLocation = function ($form, $data) {
            $formStateOptions = [
                'label' => false,
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
                'label' => false,
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

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FormEnquiry::class,
        ]);
    }
}
