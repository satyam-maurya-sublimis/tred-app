<?php

namespace App\Form\Transaction;

use App\Entity\Master\MstDepartment;
use App\Entity\Transaction\TrnVendorPartnerDetails;
use App\Entity\Transaction\TrnVendorPartnerOfficeLandLine;
use App\Repository\Master\MstCityRepository;
use App\Repository\Master\MstStateRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
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

class TrnVendorPartnerOfficeLandLineType extends AbstractType
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
            ->add('mstDepartment', EntityType::class, [
                'class' => MstDepartment::class,
                'placeholder' => 'placeholder.form.select',
                'label' => 'label.department',
                'required' => true
            ])
            ->add('contactNoCountryCode', TextType::class, [
                'label' => 'label.contact_no_country_code',
                'required' => false
            ])
            ->add('contactNoCityCode', TextType::class, [
                'label' => 'label.contact_no_city_code',
                'required' => false
            ])
            ->add('contactNo', TextType::class, [
                'label' => 'label.contact_no',
                'required' => false
            ])
            ->add('email', TextType::class, [
                'label' => 'label.email',
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TrnVendorPartnerOfficeLandLine::class,
        ]);
    }
}
