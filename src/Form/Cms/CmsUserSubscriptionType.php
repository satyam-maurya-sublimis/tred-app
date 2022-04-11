<?php

namespace App\Form\Cms;

use App\Entity\Cms\CmsUserSubscription;
use App\Repository\SystemApp\AppUserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CmsUserSubscriptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('isSubscriptionActive', CheckboxType::class,[
                'required'=> false,
                'label' => 'label.subscription',
                'help' => 'If un selected, then the above subscription will be deactivated',
                'attr' => [
                    'checked' => 'checked'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CmsUserSubscription::class
        ]);
    }
}
