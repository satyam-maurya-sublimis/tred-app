<?php

namespace App\Form\Master;

use App\Entity\Master\MstFurnitureUniqueValuePreposition;
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

class MstFurnitureUniqueValuePrepositionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('mstProductType', EntityType::class, [
                'class' => MstProductType::class,
                'placeholder' => 'placeholder.form.select',
                'label' => 'label.product_type',
                'required' => true,
                'multiple'=>false,
                'query_builder' => function (EntityRepository $er){
                    return $er->createQueryBuilder('c')
                        ->innerJoin('c.mstProductCategory','d')
                        ->andWhere('c.isActive =:active')
                        ->andWhere('d.isActive =:active')
                        ->andWhere('d.productCategorySlugName =:slugName')
                        ->setParameter('active',1)
                        ->setParameter('slugName','furniture');
                },

            ])
            ->add('uniqueValuePreposition', TextType::class,[
                'label' => 'label.unique_value_preposition',
                'required' => true,
            ])
            ->add('mediaIcon', MediaIconType::class,[
                'required' => false
            ])
            ->add('mediaAlText', TextType::class,[
                'label' => 'label.alt',
                'required' => true
            ])
            ->add('mediaTitle', TextType::class,[
                'label' => 'label.title',
                'required' => true
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
            'data_class' => MstFurnitureUniqueValuePreposition::class,
        ]);
    }
}