<?php

namespace App\Form\Master;

use App\Entity\Master\MstServiceCharges;
use App\Service\CommonHelper;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MstServiceChargesType extends AbstractType
{
    private $commonHelper;
    public function __construct(CommonHelper $commonHelper)
    {
        $this->commonHelper = $commonHelper;
    }
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('serviceCharges', TextType::class,[
                'label' => 'label.service_charges',
                'attr' => [
                    'class' => 'col-sm-3'
                ]
            ])
            ->add('serviceChargesAmountType', ChoiceType::class,[
                'label' => 'label.service_charges_amount_type',
                'choices' => $this->commonHelper->numberType(),
                'placeholder' => 'placeholder.form.select',
                'required' => true,
                'attr' => [
                    'class' => 'col-sm-3'
                ]
            ])
            ->add('serviceChargesValue', NumberType::class,[
                'required' => true,
                'label' => 'label.value',
                'attr' => [
                    'class' => 'col-sm-3'
                ]
            ])
            ->add('isActive', CheckboxType::class,[
                'label' => 'label.is_active',
                'required' => false,
                'attr' => [
                    'checked' => 'checked'
                ]
            ])
        ;
    }
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MstServiceCharges::class,
        ]);
    }
}
