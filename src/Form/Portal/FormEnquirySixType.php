<?php

namespace App\Form\Portal;

use App\Entity\Form\FormEnquiryContactUs;
use App\Entity\Master\MstCity;
use App\Entity\Master\MstCountry;
use App\Entity\Master\MstProductCategory;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormEnquirySixType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, [
                'label' => 'Full Name',
                'required' => true,
                'mapped'=>false,
            ])
            ->add('enquiryEmailAddress', EmailType::class, [
                'label' => "Email ID",
                'required' => true,
                'attr'=>[
                    'pattern'=> '[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,63}$'
                ]
            ])
            ->add('enquiryMobileNumber', NumberType::class,[
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
//            ->add('mstProductCategory', EntityType::class, [
//                'class' => MstProductCategory::class,
//                'placeholder' => 'Select Service',
//                'label' => false,
//                'required' => true,
//                'query_builder' => function (EntityRepository $er){
//                    return $er->createQueryBuilder('s')
//                        ->andWhere('s.isActive =:active')
//                        ->andWhere('s.isService =:active')
//                        ->setParameter('active',1);
//                },
//                'row_attr'=>[
//                    'class'=>'cust-select'
//                ]
//            ])
//            ->add('enquiryDescription', TextareaType::class, [
//                'label' => false,
//                'required' => false,
//            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FormEnquiryContactUs::class,
        ]);
    }
}
