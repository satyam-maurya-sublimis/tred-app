<?php

namespace App\Form\Portal;

use App\Entity\Form\FormEnquiry;
use App\Entity\Master\MstCity;
use App\Entity\Master\MstCountry;
use App\Service\CommonHelper;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormEnquiryThreeType extends AbstractType
{
    private $commonHelper;

    public function __construct(CommonHelper $commonHelper)
    {
        $this->commonHelper = $commonHelper;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, [
                'label' => 'Name',
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
            ->add('enquiryMobileNumber', TelType::class, [
                'label' => "Mobile Number",
                'required' => true,
                'attr' => [
                    'maxlength' => '10',
                    'minlength' => '10'
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
            ->add('mstCity', EntityType::class, [
                'class' => MstCity::class,
                'placeholder' => 'City',
                'label' => false,
                'required' => true,
                'query_builder' => function (EntityRepository $er){
                    return $er->createQueryBuilder('s')->andWhere('s.mstCountry =:country')->setParameter('country',101);
                },
                'row_attr'=>[
                    'class'=>'cust-select'
                ]
            ])
            ->add('enquiryHomeLoanAmount', TextType::class, [
                'label' => 'Home Loan Amount',
                'required' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FormEnquiry::class,
        ]);
    }
}
