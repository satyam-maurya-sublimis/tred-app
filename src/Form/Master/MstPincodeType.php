<?php

namespace App\Form\Master;

use App\Entity\Master\MstPincode;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
class MstPincodeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
//            ->add('circleName', TextType::class,[
//                'label' => 'label.circle_name',
//            ])
//            ->add('regionName', TextType::class,[
//                'label' => 'label.region_name',
//            ])
//            ->add('divisionName', TextType::class,[
//                'label' => 'label.division_name',
//            ])
            ->add('pincode', IntegerType::class,[
                'label' => 'label.pincode',
            ])
            ->add('officeName', TextType::class,[
                'label' => 'label.location',
            ])
//            ->add('officeType', TextType::class,[
//                'label' => 'label.office_type',
//            ])
//            ->add('delivery', TextType::class,[
//                'label' => 'label.delivery',
//            ])
//            ->add('district', TextType::class,[
//                'label' => 'label.district',
//            ])
//            ->add('stateName', TextType::class,[
//                'label' => 'label.state_name',
//            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MstPincode::class,
        ]);
    }
}
