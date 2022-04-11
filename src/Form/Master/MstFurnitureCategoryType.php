<?php

namespace App\Form\Master;

use App\Entity\Master\MstFurnitureCategory;
use App\Entity\Master\MstProductSubType;
use App\Entity\Master\MstProductType;
use App\Repository\Master\MstProductCategoryRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MstFurnitureCategoryType extends AbstractType
{
    private $mstProductCategoryRepository;

    public function __construct(MstProductCategoryRepository $mstProductCategoryRepository)
    {
        $this->mstProductCategoryRepository = $mstProductCategoryRepository;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('furnitureCategory', TextType::class,[
                'label' => 'label.furniture_category',
                'required' => true,
            ])
            ->add('furnitureCategorySlugName', TextType::class,[
                'label' => 'label.slug_name',
                'required' => true,
            ])
            ->add('isActive', CheckboxType::class,[
                'label' => 'label.is_active',
                'required' => false,
                'attr' => [
                    'checked' => 'checked'
                ]
            ]);


        $refreshProductType = function ($form, $data) {
            $form->add('mstProductType', EntityType::class, [
                'class' => MstProductType::class,
                'placeholder' => 'placeholder.form.select',
                'label' => 'label.product_type',
                'required' => true,
                'query_builder' => function (EntityRepository $dr) use ($data) {
                    $mstProductCategory = $this->mstProductCategoryRepository->findOneBy(["isActive"=>true,"productCategorySlugName"=>"furniture"]);
                    return $dr->createQueryBuilder('e')
                        ->andWhere('e.isActive = :active')
                        ->setParameter('active',1)
                        ->andWhere('e.mstProductCategory = :productCategory')
                        ->setParameter('productCategory', $mstProductCategory);
                },
            ]);
        };
        $refreshProductSubType = function ($form, $data) {
            $form->add('mstProductSubType', EntityType::class, [
                'class' => MstProductSubType::class,
                'placeholder' => 'Select..',
                'label' => 'label.product_subtype',
                'required' => true,
                'query_builder' => function (EntityRepository $dr) use ($data) {

                    if (null === $data) {
                        $mstProductTypeId = null;
                    } elseif (null != $data && is_array($data)) {
                        $mstProductTypeId = $data["mstProductType"];
                    } else {
                        $mstProductTypeId = $data->getMstProductType()?$data->getMstProductType()->getId():null;
                    }
                    return $dr->createQueryBuilder('e')
                        ->innerJoin('e.mstProductType','f')
                        ->andWhere('e.isActive = :active')
                        ->andWhere('f.isActive = :active')
                        ->setParameter('active',1)
                        ->andWhere('f.id = :productType')
                        ->setParameter('productType', $mstProductTypeId);
                },
            ])
            ;
        };

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($refreshProductType,$refreshProductSubType) {
            $form = $event->getForm();
            $data = $event->getData();
            $refreshProductType ($form, $data);
            $refreshProductSubType ($form, $data);
        });
        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($refreshProductType,$refreshProductSubType) {
            $form = $event->getForm();
            $data = $event->getData();
            if (array_key_exists('mstProductCategory', $data)) {
                $refreshProductType ($form, $data);
            }
            if (array_key_exists('mstProductType', $data)) {
                $refreshProductSubType ($form, $data);
            }
        });
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MstFurnitureCategory::class,
        ]);
    }
}