<?php

namespace App\Form\SystemApp;

use App\Entity\Master\MstDesignation;
use App\Entity\Master\MstSalutation;
use App\Entity\SystemApp\AppUserInfo;
use App\Entity\Transaction\TrnVendorPartnerDetails;
use App\Entity\Transaction\TrnVendorPartnerOffices;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class AppUserInfoVendorPartnerType extends AbstractType
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('userEmail', EmailType::class,[
                'label' => 'label.email',
                'required' => true
            ])
            ->add('userFirstName', TextType::class,[
                'label' => 'label.firstName',
                'required' => true
            ])
            ->add('userLastName', TextType::class,[
                'label' => 'label.lastName',
                'required' => true
            ])
            ->add('trnVendorPartnerDetails', EntityType::class, [
                'class' => TrnVendorPartnerDetails::class,
                'placeholder' => 'placeholder.form.select',
                'label' => 'label.vendor_partner_name',
                'required' => true
            ])
            ->add('trnVendorPartnerOffices', EntityType::class, [
                'class' => TrnVendorPartnerOffices::class,
                'placeholder' => 'placeholder.form.select',
                'label' => 'label.office_name',
                'required' => true
            ])
            ->add('mstSalutation', EntityType::class, [
                'class' => MstSalutation::class,
                'placeholder' => 'placeholder.form.select',
                'label' => 'label.salutation',
                'required' => true
            ])
            ->add('mstDesignation', EntityType::class, [
                'class' => MstDesignation::class,
                'placeholder' => 'placeholder.form.select',
                'label' => 'label.designation',
                'required' => true
            ])
            ->add('isAccessToVendorPortal', CheckboxType::class,[
                'label' => 'label.enable_access_to_vendor_portal',
                'required' => false
            ])
            ->add('userAvatarImagePath', FileType::class, [
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
            ->add('mobileNoCountryCode', TextType::class,[
                'label' => 'label.contact_no_country_code',
                'attr' => [
                    'class' => 'col-sm-3'
                ],
                'required' => true
            ])
            ->add('userMobileNumber', TextType::class,[
                'label' => 'label.mobile',
                'required' => true
            ])
        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AppUserInfo::class,
        ]);
    }
}
