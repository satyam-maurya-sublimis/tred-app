<?php

namespace App\Form\Transaction;

use App\Entity\Master\MstPincode;
use App\Entity\Master\MstCity;
use App\Entity\Master\MstState;
use App\Entity\Transaction\TrnTopVendorPartnersLocality;
use App\Repository\Master\MstCityRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrnTopVendorPartnersLocalityType extends AbstractType
{
    private $mstCityRepository;
    /**
     * @param MstCityRepository $mstCityRepository
     */
    public function __construct(MstCityRepository $mstCityRepository)
    {
        $this->mstCityRepository = $mstCityRepository;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('mstState', EntityType::class, [
                'label' => 'label.state',
                'class' => MstState::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->innerJoin('c.mstCountry', 'mstCountry')
                        ->where("mstCountry.country = :country")
                        ->setParameter('country','India');
                },
                'placeholder' => 'placeholder.form.select',
                'required' => true,
                'attr' => [
                    'class' => 'mststate'
                ]
            ])
        ;
        $refreshCity = function ($form, $data){
            $formCityOptions = [
                'label' => 'label.city',
                'class' => MstCity::class,
                'query_builder' => function (EntityRepository $dr) use ($data) {
                    if (null === $data) {
                        $stateId = null;
                    } elseif (null != $data && is_array($data)) {
                        $stateId = $data["mstState"];
                    } else {
                        $stateId = $data->getMstState()?$data->getMstState()->getId():null;
                    }
                    return $dr
                        ->createQueryBuilder('c')
                        ->innerJoin("c.mstState",'d')
                        ->andWhere('d.id = :id')
                        ->setParameter('id', $stateId);
                },
                'attr' => [
                    'class' => 'mstcity'
                ]

            ];
            $form->add('mstCity', EntityType::class,$formCityOptions);
        };
        $refreshPincode = function ($form, $data){
            $formPincodeOptions = [
                'label' => 'label.location',
                'class' => MstPincode::class,
                'choice_label' => function(MstPincode $mstPincode) {
                    return sprintf('%s : (%s)', $mstPincode->getOfficeName(),$mstPincode->getPincode());
                },
                'multiple' => true,
                'required' => false,
                'placeholder' => 'placeholder.form.select',
                'query_builder' => function (EntityRepository $dr) use ($data) {
                    if (null === $data) {
                        $cityId = null;
                    } elseif (null != $data && is_array($data)) {
                        $cityId = $data["mstCity"];
                    } else {
                        $cityId = $data->getMstCity()?$data->getMstCity()->getId():null;
                    }
                    $cityName = null;
                    if ($cityId){
                        $mstCity = $this->mstCityRepository->find($cityId);
                        $cityName = $mstCity->getCity();
                        return $dr
                            ->createQueryBuilder('s')
                            ->andWhere('s.delivery = :delivery')
                            ->andWhere('s.district like :cityName')
                            ->setParameter('delivery', 'Delivery')
                            ->setParameter('cityName', '%'.$cityName.'%');
                    }else{
                        return $dr
                            ->createQueryBuilder('s')
                            ->andWhere('s.delivery = :delivery')
                            ->andWhere('s.district like :cityName')
                            ->setParameter('delivery', 'Delivery')
                            ->setParameter('cityName', $cityName);
                    }
                },
                'attr' => [
                    'class' => 'mstpincode'
                ]
            ];
            $form->add('mstPincode', EntityType::class,$formPincodeOptions);
        };
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($refreshCity, $refreshPincode) {
            $form = $event->getForm();
            $data = $event->getData();
            $refreshCity ($form, $data);
            $refreshPincode ($form, $data);
        });
        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($refreshCity,$refreshPincode) {
            $form = $event->getForm();
            $data = $event->getData();
            if (array_key_exists('mstState', $data)) {
                $refreshCity ($form, $data);
            }
            if (array_key_exists('mstCity', $data)) {
                $refreshPincode ($form, $data);
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TrnTopVendorPartnersLocality::class,
        ]);
    }

}
