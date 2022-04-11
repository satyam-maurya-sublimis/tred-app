<?php

namespace App\Form\Transaction;

use App\Entity\Master\MstCurrency;
use App\Entity\Master\MstProjectArea;
use App\Entity\Master\MstProjectAreaCategory;
use App\Entity\Transaction\TrnProjectAreaDetail;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
class TrnProjectAreaDetailType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('mstProjectArea', EntityType::class, [
                'class' => MstProjectArea::class,
                'placeholder' => 'placeholder.form.select',
                'label' => 'label.project_area_type',
                'required' => true
            ])
            ->add('mstProjectAreaCategory', EntityType::class, [
                'class' => MstProjectAreaCategory::class,
                'placeholder' => 'placeholder.form.select',
                'label' => 'label.project_area_measurement',
                'required' => true
            ])
            ->add('areaValue', IntegerType::class, [
                'label' => 'label.area_value',
                'required' => false
            ])
            ->add('mstCurrency', EntityType::class, [
                'class' => MstCurrency::class,
                'placeholder' => 'placeholder.form.select',
                'label' => 'label.currency',
                'required' => true,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->where("c.isActive = :active")
                        ->setParameter('active', 1);
                }
            ])
            ->add('price', NumberType::class, [
                'label' => 'label.price_per',
                'required' => true,
            ])
        ;
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TrnProjectAreaDetail::class,
        ]);
    }
}
