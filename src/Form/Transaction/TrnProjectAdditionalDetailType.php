<?php

namespace App\Form\Transaction;

use App\Entity\Transaction\TrnProjectAdditionalDetail;
use App\Entity\Transaction\TrnProjectRoomConfiguration;
use App\Service\CommonHelper;
use Doctrine\ORM\EntityRepository;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class TrnProjectAdditionalDetailType extends AbstractType
{
    private $commonHelper;
    private $token;

    public function __construct(CommonHelper $commonHelper,TokenStorageInterface $token )
    {
        $this->commonHelper = $commonHelper;
        $this->token = $token;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('trnProjectRoomConfigurations', EntityType::class,[
                'label' => 'label.room_configuration',
                'class' => TrnProjectRoomConfiguration::class,
                'placeholder' => 'placeholder.form.select',
                'multiple'=> true,
                'required' => true,
                'choice_label' => function(TrnProjectRoomConfiguration $trnProjectRoomConfiguration) {
                    $mstRoomConfiguration = $trnProjectRoomConfiguration->getMstRoomConfiguration();
                    if ($mstRoomConfiguration){
                        return sprintf('%s :%s - %s', $mstRoomConfiguration->getRoomConfiguration(),$trnProjectRoomConfiguration->getMstProjectAreaCategory(), $trnProjectRoomConfiguration->getAreaValue());
                    }
                },
                'query_builder' => function (EntityRepository $dr) use ($options) {
                    $user = $this->token->getToken()->getUser();
                    return $dr->createQueryBuilder('e')
                        ->innerJoin('e.trnProject','f')
                        ->andWhere('e.isActive = :active')
                        ->andWhere('f.isActive = :active')
                        ->andWhere('f.id = :projectId')
                        ->andWhere('e.createdBy = :user')
                        ->setParameter('active',1)
                        ->setParameter('user', $user)
                        ->setParameter('projectId', $options['projectId']);
                }
            ])
            ->add('additionalDetailType', ChoiceType::class,[
                'label' => 'label.project_additional_detail_type',
                'choices' => $this->commonHelper->additionalDetailType(),
                'required' => true,
                'placeholder'=>"Select Detail Type"
            ])
            ->add('additionalDetail', CKEditorType::class, [
                'label' => 'label.project_additional_detail',
                'attr' => [
                    'class' => 'textarea'
                ],
                'required' => false
            ])
            ->add('isActive', CheckboxType::class, [
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
            'data_class' => TrnProjectAdditionalDetail::class,
            'projectId' => null,
        ]);
    }
}
