<?php

namespace App\Form\Form;

use App\Entity\Form\FormFurnitureEnquiry;
use App\Entity\Master\MstCity;
use App\Entity\Master\MstCountry;
use App\Entity\Master\MstFurnitureCategory;
use App\Entity\Master\MstLeadStatus;
use App\Entity\Master\MstProductSubType;
use App\Entity\Master\MstSalutation;
use App\Entity\Master\MstState;
use App\Entity\Master\MstProductType;
use App\Entity\Transaction\TrnFurniture;
use App\Entity\Transaction\TrnFurnitureProductCatalog;
use App\Repository\Master\MstProductCategoryRepository;
use App\Service\CommonHelper;
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

class FormFurnitureEnquiryType extends AbstractType
{
    private $mstProductCategoryRepository;

    public function __construct(MstProductCategoryRepository $mstProductCategoryRepository)
    {
        $this->mstProductCategoryRepository = $mstProductCategoryRepository;
    }
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
            ->add('furnitureEnquiryFullName', TextType::class, [
                'label' => 'label.name',
                'required' => true
            ])
//            ->add('furnitureEnquiryMiddleName', TextType::class, [
//                'label' => 'label.middleName',
//                'required' => false
//            ])
//            ->add('furnitureEnquiryLastName', TextType::class, [
//                'label' => 'label.lastName',
//                'required' => true
//            ])
//            ->add('furnitureEnquiryPhoneNumber', TelType::class, [
//                'label' => 'label.phone',
//                'required' => false,
//                'attr' => [
//                    'maxlength' => '10',
//                    'minlength' => '10'
//                ]
//            ])
            ->add('furnitureEnquiryMobileNumber', TelType::class, [
                'label' => 'label.mobile',
                'required' => true,
                'attr' => [
                    'maxlength' => '10',
                    'minlength' => '10'
                ]
            ])
            ->add('furnitureEnquiryEmailAddress', EmailType::class, [
                'label' => 'label.email_primary',
                'required' => true,

            ])
            ->add('furnitureEnquiryDescription', TextareaType::class, [
                'label' => 'label.description',
                'required' => false
            ])
            ->add('mstLeadStatus', EntityType::class, [
                'label' => 'label.lead_status',
                'class' => MstLeadStatus::class,
                'placeholder' => 'placeholder.form.select',
                'required' => true
            ])
            ->add('furnitureEnquiryAddressOne', TextType::class, [
                'label' => 'label.addressOne',
                'required' => false,
            ])
            ->add('furnitureEnquiryAddressTwo', TextType::class, [
                'label' => 'label.addressTwo',
                'required' => false,
            ])
            ->add('furnitureEnquiryPincode', TextType::class, [
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
            ->add('mstProductType', EntityType::class, [
                'class' => MstProductType::class,
                'placeholder' => 'placeholder.form.select',
                'label' => 'label.product_type',
                'required' => false,
                'query_builder' => function (EntityRepository $dr) {
                    $mstProductCategory = $this->mstProductCategoryRepository->findOneBy(["isActive"=>true,"productCategorySlugName"=>"furniture"]);
                    return $dr->createQueryBuilder('e')
                        ->andWhere('e.isActive = :active')
                        ->setParameter('active',1)
                        ->andWhere('e.mstProductCategory = :productCategory')
                        ->setParameter('productCategory', $mstProductCategory);
                },
            ])
            ->add('mstProductSubType', EntityType::class, [
                'class' => MstProductSubType::class,
                'placeholder' => 'placeholder.form.select',
                'label' => 'label.product_subtype',
                'required' => false,
            ])
            ->add('mstFurnitureCategory', EntityType::class, [
                'class' => MstFurnitureCategory::class,
                'placeholder' => 'placeholder.form.select',
                'label' => 'label.furniture_category',
                'required' => false,
            ])
            ->add('trnFurniture', EntityType::class, [
                'class' => TrnFurniture::class,
                'placeholder' => 'placeholder.form.select',
                'label' => 'label.furniture_button',
                'required' => false,
                'choice_label' => function(TrnFurniture $trnFurniture) {
                    $mstFurnitureCategory = $trnFurniture->getMstFurnitureCategory();
                    if ($mstFurnitureCategory){
                        return sprintf('%s - %s', $mstFurnitureCategory->getFurnitureCategory(),$trnFurniture->getId());
                    }
                },
            ])
//            ->add('trnFurnitureProductCatalog', EntityType::class, [
//                'class' => TrnFurnitureProductCatalog::class,
//                'placeholder' => 'placeholder.form.select',
//                'label' => 'label.furniture_product_catalog',
//                'required' => false,
//            ])
        ;
        $refreshProductSubType = function ($form, $data) {
            $form->add('mstProductSubType', EntityType::class, [
                'class' => MstProductSubType::class,
                'placeholder' => 'Select..',
                'label' => 'label.product_subtype',
                'required' => false,
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
            ]);
            $form->add('trnFurnitureProductCatalog', EntityType::class, [
                'class' => TrnFurnitureProductCatalog::class,
                'placeholder' => 'Select..',
                'label' => 'label.furniture_product_catalog',
                'required' => false,
                'choice_label' => function(TrnFurnitureProductCatalog $trnFurnitureProductCatalog) {
                    $productCatalogName = $trnFurnitureProductCatalog->getCatalogName();
                    if ($productCatalogName){
                        return sprintf('%s', $productCatalogName);
                    }
                },
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
                'required' => false,
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
                    if (is_array($data)) {
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
                    if (is_array($data)) {
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
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($refreshState,$refreshCity,$refreshProductSubType,$refreshFurnitureCategory) {
            $form = $event->getForm();
            $data = $event->getData();
            $refreshState ($form, $data);
            $refreshCity ($form, $data);
            $refreshProductSubType ($form, $data);
            $refreshFurnitureCategory ($form, $data);
        });
        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($refreshState,$refreshCity,$refreshProductSubType,$refreshFurnitureCategory) {
            $form = $event->getForm();
            $data = $event->getData();
            if (isset($data["mstCountry"])) {
                $refreshState($form, $data);
            }
            if (isset($data["mstState"])) {
                $refreshCity($form, $data);
            }
            if (isset($data['mstProductType'])) {
                $refreshProductSubType ($form, $data);
            }
            if (isset($data['mstProductSubType'])) {
                $refreshFurnitureCategory ($form, $data);
            }
        });
    }
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FormFurnitureEnquiry::class,
        ]);
    }
}
