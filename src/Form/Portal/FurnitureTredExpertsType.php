<?php

namespace App\Form\Portal;

use App\Entity\Form\FormFurnitureEnquiry;
use App\Entity\Master\MstCity;
use App\Entity\Master\MstCountry;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
class FurnitureTredExpertsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('furnitureEnquiryFullName', TextType::class, [
                'label' => 'Full Name',
                'required' => true,
            ])
            ->add('furnitureEnquiryEmailAddress', EmailType::class, [
                'label' => 'label.email_primary',
                'required' => true,
                'attr'=>[
                    'pattern'=> '[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,63}$'
                ]
            ])
            ->add('furnitureEnquiryMobileNumber', NumberType::class,[
                'label' => 'label.mobile',
                'required'=>true,
                'attr' => [
                    'pattern' => "[0-9]{10}",
                    'maxlength' => 10,
                    'minlength' => 10,
                    'title' => "Please enter valid mobile number"
                ]
            ])
            ->add('mstCountry', EntityType::class, [
                'label' => false,
                'class' => MstCountry::class,
                'required' => true,
                'choice_label'=>'phoneCode',
                'query_builder' => function (EntityRepository $er){
                    return $er->createQueryBuilder('c')->andWhere('c.id =:country')->setParameter('country',101);
                }
            ])
            ->add('mstCity', EntityType::class, [
                'class' => MstCity::class,
                'label' => 'label.city',
                'required' => false,
                'query_builder' => function (EntityRepository $er){
                    return $er->createQueryBuilder('s')->andWhere('s.mstCountry =:country')->setParameter('country',101);
                }
            ])
//            ->add('isMeetingSchduleWithTredExpert', CheckboxType::class,[
//                'required'=> true,
//                'label' => 'label.is_meeting_schdule_with_tred_Expert',
//                'label_attr'=>[
//                    "class"=>'checkbox-lbl'
//                ],
//                'attr' => [
//                    'checked' => 'checked'
//                ]
//            ])
//            ->add('furnitureEnquiryBudget', TextType::class, [
//                'label' => 'Approximate Budget',
//                'required' => false,
//            ])
//            ->add('furnitureEnquiryLocation', TextType::class, [
//                'label' => 'Location',
//                'required' => false,
//            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FormFurnitureEnquiry::class,
        ]);
    }
}
