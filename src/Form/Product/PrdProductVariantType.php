<?php

namespace App\Form\Product;

use App\Entity\Master\MstCurrency;
use App\Entity\Product\PrdOptionList;
use App\Entity\Product\PrdProductVariant;
use App\Repository\Product\PrdOptionListRepository;
use App\Repository\Product\PrdProductRepository;
use App\Service\CommonHelper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PrdProductVariantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('variantCollection', CollectionType::class,[
                'mapped' => true,
                'label'=>false,
                'entry_type' => PrdProductVariantCollectionType::class,
                'entry_options' => [
                    'label' => false,
                    'data' => $options['data'],
                ],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference'=>false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PrdProductVariant::class
        ]);
    }
}
