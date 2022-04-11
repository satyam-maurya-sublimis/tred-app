<?php

namespace App\Form\SystemApp;

use App\Entity\SystemApp\AppRole;
use App\Entity\SystemApp\AppUser;
use App\Entity\SystemApp\AppUserCategory;
use App\Repository\SystemApp\AppRoleRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AppUserVendorPartnerType extends AbstractType
{
    private $appRolesRepository;

    public function __construct(AppRoleRepository $appRolesRepository)
    {

        $this->appRolesRepository = $appRolesRepository;

    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('userName', TextType::class,[
                'label' => 'label.username',
                'required' => true
            ])
            ->add('userPassword', PasswordType::class,[
                'label' => 'label.password',
                'required' => $options['password_required'],
                'empty_data'=> '',
            ])
            ->add('appUserInfo', AppUserInfoVendorPartnerType::class,[
                'label' => false,
                'required' => true
            ])
            ->add('appUserCategory', EntityType::class,[
                'label' => 'label.usercategory',
                'class' => AppUserCategory::class,
                'required'      => true,
            ])
//            ->add('userRole', EntityType::class,[
//                'class' => AppRole::class,
//                'required'      => true,
//                'multiple'      => true,
//                'expanded'      => true,
//                'label'         => 'label.role',
//                'query_builder' => function (EntityRepository $er) use ($options) {
//                    $roles = $options['data']->getUserRole();
//                    if ($roles){
//                        $dql  =  $er->createQueryBuilder('c')
//                            ->where('c.roleName in (:value)')
//                            ->andwhere('c.isActive = :active')
//                            ->setParameter('value',$roles)
//                            ->setParameter('active',1);
//                    }else{
//                        $dql =  $er->createQueryBuilder('c')
//                            ->where('c.roleName = :value')
//                            ->andwhere('c.isActive = :active')
//                            ->setParameter('value','ROLE_VENDOR_USER')
//                            ->setParameter('active',1);
//                    }
//                    return $dql;
//                },
//                'choice_label' => function(AppRole $appRole) {
//                    if ($appRole){
//                        return sprintf('%s', $appRole->getRoleDescription());
//                    }
//                },
//            ])
            ->add('userRole', ChoiceType::class,[
                'choices'       => $this->appRolesRepository->getVendorRoles(),
                'required'      => true,
                'multiple'      => true,
                'expanded'      => true,
                'label'         => 'label.role',
            ])
            ->add('isActive', CheckboxType::class,[
                'label' => 'label.is_active',
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AppUser::class,
            'password_required' => false,
        ]);
        $resolver->addAllowedValues('password_required', array(true,false));
    }
}
