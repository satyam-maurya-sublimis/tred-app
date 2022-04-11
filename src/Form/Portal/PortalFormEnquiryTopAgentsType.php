<?php

namespace App\Form\Portal;

use App\Entity\Form\FormEnquiryTopAgents;
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

class PortalFormEnquiryTopAgentsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, [
                'label' => 'Full Name',
                'required' => true,
                'mapped'=>false,
//                'attr' => [
//                    'pattern' => "[a-zA-z]+([\s][a-zA-Z]+)*",
//                    'oninvalid'=>"setCustomValidity('Please enter alphabet only')",
//                    'oninput'=>"setCustomValidity('')"
//                ]
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
            ->add('isMeetingSchduleWithTredExpert', CheckboxType::class,[
                'required'=> true,
                'label' => 'label.is_meeting_schdule_with_tred_Expert',
                'label_attr'=>[
                    "class"=>'checkbox-lbl'
                ],
                'attr' => [
                    'checked' => 'checked'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FormEnquiryTopAgents::class,
        ]);
    }
}
