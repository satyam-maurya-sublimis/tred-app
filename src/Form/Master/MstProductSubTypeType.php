<?php

namespace App\Form\Master;

use App\Entity\Master\MstProductSubType;
use App\Entity\Master\MstProductType;
use App\Form\Media\MediaIconType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class MstProductSubTypeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('mstProductType', EntityType::class, [
                'class' => MstProductType::class,
                'placeholder' => 'placeholder.form.select',
                'label' => 'label.product_type',
                'required' => true,
                'multiple'=>true,
                'choice_label' => function(MstProductType $mstProductType) {
                    $mstProductCategory = $mstProductType->getMstProductCategory();
                    if ($mstProductCategory){
                            return sprintf('%s - %s', $mstProductCategory->getProductCategory(),$mstProductType->getProductType());
                    }
                },
                'query_builder' => function (EntityRepository $er){
                    return $er->createQueryBuilder('c')->andWhere('c.isActive =:active')->setParameter('active',1);
                },

            ])
            ->add('productSubType', TextType::class,[
                'label' => 'label.product_subtype',
                'required' => true,
            ])
            ->add('productSubTypeSlugName', TextType::class,[
                'label' => 'label.slug_name',
                'required' => true,
            ])
            ->add('mediaIcon', MediaIconType::class,[
                'required' => false
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

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MstProductSubType::class,
        ]);
    }
}