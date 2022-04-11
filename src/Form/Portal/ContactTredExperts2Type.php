<?php

namespace App\Form\Portal;

use App\Entity\Form\FormResidentialEnquiry;
use App\Entity\Master\MstCity;
use App\Entity\Master\MstCountry;
use App\Entity\Master\MstRoomConfiguration;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\PositiveOrZero;
use Symfony\Component\Validator\Constraints\ZeroComparisonConstraintTrait;

class ContactTredExperts2Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, [
                'label' => 'Full Name',
                'required' => true,
                'mapped'=>false,
            ])
            ->add('residentialEnquiryEmailAddress', EmailType::class, [
                'label' => "Email ID",
                'required' => true,
                'attr'=>[
                    'pattern'=> '[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,63}$'
                ]
            ])
            ->add('residentialEnquiryMobileNumber', NumberType::class,[
                'label' => 'label.mobile',
                'required'=>true,
//                'constraints' => [
//                    new Mobile(),
//                ],
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
            ->add('mstRoomConfiguration', EntityType::class, [
                'label' => 'Room Type',
                'class' => MstRoomConfiguration::class,
                'required' => false,
                'query_builder' => function (EntityRepository $er){
                    return $er->createQueryBuilder('c')
                        ->andWhere('c.isActive =:active')
                        ->setParameter('active',1)
                        ->orderBy('c.roomConfiguration','ASC');
                },
            ])
            ->add('residentialEnquiryBudget', TextType::class, [
                'label' => 'Approximate Budget',
                'required' => false,
            ])
            ->add('residentialEnquiryLocation', TextType::class, [
                'label' => 'Location',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FormResidentialEnquiry::class,
        ]);
    }
}
