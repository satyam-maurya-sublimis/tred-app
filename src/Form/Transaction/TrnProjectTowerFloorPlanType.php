<?php

namespace App\Form\Transaction;

use App\Entity\Master\MstCurrency;
use App\Entity\Master\MstDenomination;
use App\Entity\Master\MstDeviceType;
use App\Entity\Master\MstProjectArea;
use App\Entity\Master\MstRoomConfiguration;
use App\Entity\Transaction\TrnProjectTowerFloorPlan;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class TrnProjectTowerFloorPlanType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('mstRoomConfiguration', EntityType::class, [
                'class' => MstRoomConfiguration::class,
                'placeholder' => 'placeholder.form.select',
                'label' => 'label.room_configuration',
                'required' => true
            ])
            ->add('noOfBedRoom', IntegerType::class, [
                'label' => 'label.no_of_bedroom',
                'required' => true
            ])
            ->add('noOfBathRooms', IntegerType::class, [
                'label' => 'label.no_of_bathroom',
                'required' => true
            ])
            ->add('noOfBalcony', IntegerType::class, [
                'label' => 'label.no_of_balcony',
                'required' => true
            ])
            ->add('mstProjectSuperArea', EntityType::class, [
                'class' => MstProjectArea::class,
                'placeholder' => 'placeholder.form.select',
                'label' => 'label.project_area',
                'required' => false
            ])
            ->add('superArea', IntegerType::class, [
                'label' => 'label.super_area',
                'required' => false
            ])
            ->add('mstSuperAreaCurrency', EntityType::class, [
                'class' => MstCurrency::class,
                'placeholder' => 'placeholder.form.select',
                'label' => 'label.currency',
                'required' => false
            ])
            ->add('superAreaPerUnit', NumberType::class, [
                'label' => 'label.amount_per_area',
                'required' => false
            ])
            ->add('mstProjectCarpetArea', EntityType::class, [
                'class' => MstProjectArea::class,
                'placeholder' => 'placeholder.form.select',
                'label' => 'label.project_area',
                'required' => false
            ])
            ->add('carpetArea', IntegerType::class, [
                'label' => 'label.carpet_area',
                'required' => false
            ])
            ->add('mstCarpetAreaCurrency', EntityType::class, [
                'class' => MstCurrency::class,
                'placeholder' => 'placeholder.form.select',
                'label' => 'label.currency',
                'required' => false
            ])
            ->add('carpetAreaPerUnit', NumberType::class, [
                'label' => 'label.amount_per_area',
                'required' => false
            ])
            ->add('agreementAmount', NumberType::class, [
                'label' => 'label.agreement_amount',
                'required' => true
            ])
            ->add('mstCurrencyAgreementPrice', EntityType::class, [
                'class' => MstCurrency::class,
                'placeholder' => 'placeholder.form.select',
                'label' => 'label.currency',
                'required' => true
            ])
            ->add('mstDenomination', EntityType::class, [
                'class' => MstDenomination::class,
                'placeholder' => 'placeholder.form.select',
                'label' => 'label.denomination',
                'required' => true
            ])
            ->add('mstDeviceType', EntityType::class, [
                'class' => MstDeviceType::class,
                'placeholder' => 'placeholder.form.select',
                'required' => false,
                'label' => 'label.device_type'
            ])
            ->add('mediaFileName', FileType::class,[
                'mapped' => false,
                'required' => false,
                'label' => 'label.media_file_path',
//                'attr' =>[
//                    'class' => 'custom-file-input'
//                ],
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
        ;
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TrnProjectTowerFloorPlan::class,
        ]);
    }
}
