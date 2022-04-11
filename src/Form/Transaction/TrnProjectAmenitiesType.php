<?php

namespace App\Form\Transaction;

use App\Entity\Master\MstCity;
use App\Entity\Master\MstProjectAmenities;
use App\Entity\Master\MstState;
use App\Entity\Master\MstSubCategory;
use App\Entity\Transaction\TrnProjectAmenities;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrnProjectAmenitiesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('mstSubCategory', EntityType::class,[
                'label' => 'Amenities Category',
                'required' => true,
                'class' => MstSubCategory::class,
                'placeholder'=> "Select Amenity Category",
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('s')
                        ->andWhere('s.isActive =:active')
                        ->setParameter('active', 1);
                }
            ])
            ->add('trnAmenitiesDescription', TextType::class,[
                'label' => 'label.description',
                'required' => false,
            ])
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

        $refreshAmenities = function ($form, $data) {
            $formAmenitiesOptions = [
                'label' => 'label.project_amenities',
                'class' => MstProjectAmenities::class,
                'required' => true,
                'query_builder' => function (EntityRepository $dr) use ($data) {
                    if(null == $data){
                        $subCategoryId = null;
                    }else{
                        if( array_key_exists('mstSubCategory', $data)){
                            $subCategoryId = $data["mstSubCategory"];
                        }else{
                            $subCategoryId = $data->getMstSubCategory()?$data->getMstSubCategory()->getId():null;
                        }
                    }
                    return $dr->createQueryBuilder('s')
                        ->innerJoin('s.mstSubCategory','msc')
                        ->andWhere('msc.id =:subCategory')
                        ->andWhere('s.isActive =:active')
                        ->setParameter('subCategory', $subCategoryId)
                        ->setParameter('active', 1)
                    ;
                },
            ];
            $form->add('mstProjectAmenities', EntityType::class,$formAmenitiesOptions);
        };
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($refreshAmenities) {
            $form = $event->getForm();
            $data = $event->getData();
            $refreshAmenities ($form, $data);
        });
        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($refreshAmenities) {
            $form = $event->getForm();
            $data = $event->getData();
            if (array_key_exists('mstSubCategory', $data)) {
                $refreshAmenities($form, $data);
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TrnProjectAmenities::class,
        ]);
    }
}
