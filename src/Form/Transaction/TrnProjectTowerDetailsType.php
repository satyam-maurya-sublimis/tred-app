<?php

namespace App\Form\Transaction;

use App\Entity\Master\MstAreaInCity;
use App\Entity\Master\MstCity;
use App\Entity\Master\MstCurrency;
use App\Entity\Master\MstHighlights;
use App\Entity\Master\MstProductFeature;
use App\Entity\Master\MstProjectAmenities;
use App\Entity\Master\MstProjectArea;
use App\Entity\Master\MstProjectType;
use App\Entity\Master\MstPropertyType;
use App\Entity\Master\MstRating;
use App\Entity\Master\MstState;
use App\Entity\Master\MstCountry;
use App\Entity\Master\MstProductCategory;
use App\Entity\Master\MstProductSubType;
use App\Entity\Master\MstProductType;
use App\Entity\Organization\OrgCompany;
use App\Entity\Transaction\TrnProject;
use App\Entity\Transaction\TrnProjectTowerDetails;
use App\Entity\Transaction\TrnVendorPartnerDetails;
use App\Repository\Transaction\TrnVendorPartnerDetailsRepository;
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
use App\Repository\Master\MstCityRepository;
use App\Repository\Master\MstStateRepository;
use Symfony\Component\Validator\Constraints\File;

class TrnProjectTowerDetailsType extends AbstractType
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

    public function buildYearChoices()
    {
        $distance = 100;
        $yearsBefore = date('Y', mktime(0, 0, 0, date("m"), date("d"), date("Y")));
        $yearsAfter = date('Y', mktime(0, 0, 0, date("m"), date("d"), date("Y") + $distance));
        return array_combine(range($yearsBefore, $yearsAfter), range($yearsBefore, $yearsAfter));
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('trnProject', EntityType::class, [
                'class' => TrnProject::class,
                'placeholder' => 'placeholder.form.select',
                'label' => 'label.project_name',
                'required' => true
            ])
            ->add('towerName', TextType::class, [
                'label' => 'label.tower_name',
                'required' => true
            ])
            ->add('noOfFloors', TextType::class, [
                'label' => 'label.total_number_floor',
                'required' => true
            ])
            ->add('isActive', CheckboxType::class, [
                'label' => 'label.is_active',
                'required' => false,
                'attr' => [
                    'checked' => 'checked'
                ]
            ])

            ->add('mstTowerAmenities', EntityType::class,[
                'label' => 'label.project_amenities',
                'class' => MstProjectAmenities::class,
                'placeholder' => 'placeholder.form.select',
                'multiple'=> true,
                'required' => false
            ])
            ->add('mstTowerFeature', EntityType::class,[
                'label' => 'label.tower_feature',
                'class' => MstProductFeature::class,
                'placeholder' => 'placeholder.form.select',
                'multiple'=> true,
                'required' => false
            ])
            ->add('mstTowerHighlights', EntityType::class,[
                'label' => 'label.highlights',
                'class' => MstHighlights::class,
                'placeholder' => 'placeholder.form.select',
                'multiple'=> true,
                'required' => false
            ])
            ->add('mstTowerRating', EntityType::class,[
                'label' => 'label.rating',
                'class' => MstRating::class,
                'placeholder' => 'placeholder.form.select',
                'multiple'=> false,
                'required' => false
            ])
            ->add('trnProjectTowerFloorPlans', CollectionType::class, [
                'entry_type' => TrnProjectTowerFloorPlanType::class,
                'required' => false,
                'label' => false,
                'entry_options' => [
                    'label' => false,
                ],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference'=>false,
                'required' => true
            ])
            ->add('trnProjectTowerAdditionalDetails', CollectionType::class, [
                'entry_type' => TrnProjectTowerAdditionalDetailType::class,
                'required' => false,
                'label' => false,
                'entry_options' => [
                    'label' => false,
                ],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference'=>false,
            ])
            ->add('trnUploadDocuments', CollectionType::class, [
                'entry_type' => TrnUploadDocumentType::class,
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
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TrnProjectTowerDetails::class,
        ]);
    }
}
