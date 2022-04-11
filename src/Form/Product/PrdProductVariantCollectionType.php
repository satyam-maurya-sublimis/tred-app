<?php

namespace App\Form\Product;

use App\Entity\Master\MstCurrency;
use App\Entity\Master\MstDelivery;
use App\Entity\Product\PrdOptionList;
use App\Entity\Product\PrdPolicy;
use App\Entity\Product\PrdProductVariant;
use App\Entity\Product\PrdProductVariantCondition;
use App\Entity\Product\PrdProductVariantOptionList;
use App\Repository\Product\PrdOptionListRepository;
use App\Repository\Product\PrdProductRepository;
use App\Repository\Product\PrdProductVariantOptionListRepository;
use App\Repository\Transaction\TrnFurnitureRepository;
use App\Service\CommonHelper;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PrdProductVariantCollectionType extends AbstractType
{
    private $commonHelper;
    private $trnFurnitureRepository;
    private $prdOptionListRepository;
    private $prdProductVariantOptionListRepository;
    public function __construct(CommonHelper $commonHelper, TrnFurnitureRepository $trnFurnitureRepository, PrdOptionListRepository $prdOptionListRepository, PrdProductVariantOptionListRepository $prdProductVariantOptionListRepository)
    {
        $this->commonHelper = $commonHelper;
        $this->trnFurnitureRepository = $trnFurnitureRepository;
        $this->prdOptionListRepository = $prdOptionListRepository;
        $this->prdProductVariantOptionListRepository = $prdProductVariantOptionListRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $prdOptions = $this->trnFurnitureRepository->getOptionByProductId($options['data']->getTrnFurniture()->getId());
        foreach ($prdOptions as $i => $option)
        {
            $optionList = $this->prdOptionListRepository->findBy(['prdOption' => $option['id']]);
            $variantOptionListValue = $this->prdProductVariantOptionListRepository->findOneBy(['prdProductVariant' => $options['data']->getId(), 'prdOption' => $option['id']]);

            if ($variantOptionListValue) {
                $variantOptionListId = $variantOptionListValue->getPrdOptionList();
            } else {
                $variantOptionListId = null;
            }
            $builder->add($option['optionCode'], ChoiceType::class, [
                'label' => $option['optionName'],
                'mapped' => true,
                'choices' => $optionList,
                'choice_value' => 'id',
                'data' => $variantOptionListId,
                'choice_label' => 'optionValue',
                'attr' => [
                    'class' => 'select2'
                ]
            ]);
        }

        $builder
            ->add('mstCurrency', EntityType::class,[
                'label' => 'label.currency',
                'class' => MstCurrency::class,
                'required' => true,
                'attr' => [
                    'class' => 'select2'
                ],
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->where('c.isActive =:active')
                        ->setParameter('active','1')
                        ;
                },
            ])
            ->add('productVariantPrice', MoneyType::class,[
                'label' => 'label.price',
                'currency'=>false,
                'required' => true,
            ])
//            ->add('productVariantHeight', TextType::class,[
//                'label' => 'label.height',
//                'required' => false,
//            ])
//            ->add('productVariantWidth', TextType::class,[
//                'label' => 'label.width',
//                'required' => false,
//            ])
//            ->add('productVariantDepth', TextType::class,[
//                'label' => 'label.depth',
//                'required' => false,
//            ])
//            ->add('productVariantWeight', NumberType::class,[
//                'label' => 'label.weight',
//                'required' => false,
//            ])
            ->add('position', TextType::class,[
                'required' => true,
                'label' => 'label.position',
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
            'data_class' => PrdProductVariant::class
        ]);
    }
}
