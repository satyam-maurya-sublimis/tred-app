<?php

namespace App\Form\Transaction;

use App\Entity\Master\MstDeviceType;
use App\Entity\Master\MstUploadDocumentType;
use App\Entity\Transaction\TrnProjectRoomConfiguration;
use App\Entity\Transaction\TrnUploadDocument;
use App\Repository\Master\MstUploadDocumentTypeRepository;
use App\Service\CommonHelper;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Validator\Constraints\File;

class TrnUploadDocumentType extends AbstractType
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
            ->add('mstUploadDocumentType', EntityType::class, [
                'class' => MstUploadDocumentType::class,
                'query_builder' => function (MstUploadDocumentTypeRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->where('u.isActive = 1 ');
                },
                'placeholder' => 'placeholder.form.select',
                'required' => true,
                'label' => 'label.media_file_type',
                'attr' => [
                    'class' => 'uploadDocumentType',
                    'index' => '__index__',
                ]
            ])->add('mstDeviceType', EntityType::class, [
                'class' => MstDeviceType::class,
                'placeholder' => 'placeholder.form.select',
                'required' => true,
                'label' => 'label.device_type'
            ])
            ->add('mediaFileName', FileType::class,[
                'mapped' => false,
                'required' => false,
                'label' => 'label.media_file_path',
//                'attr' =>[
//                    'class' => 'custom-file-input'
//                ],
                'constraints' => [
                    new File([
                            'maxSize' => '7168k',
                            'maxSizeMessage' => 'The maximum file upload size is 2 mb.',
                            'mimeTypes' => [
                                'image/jpg',
                                'image/png',
                                'image/jpeg'
                            ],
                            'mimeTypesMessage' => 'Please upload a valid jpg or png file.',
                        ]
                    )
                ],
            ])
            ->add('mediaName', TextType::class,[
                'label' => 'label.media_name',
                'required' => true
            ])
            ->add('mediaAltText', TextType::class,[
                'label' => 'label.media_alt_text'
            ])
            ->add('mediaTitle', TextType::class,[
                'label' => 'label.media_title'
            ])
            ->add('mediaPath', TextType::class,[
                'label' => 'label.embedded_url',
                'required' => false,
                'help' => 'Please add the Url in case of external video'
            ])
            ->add('position', IntegerType::class,[
                'required' => true,
                'label' => 'label.position',
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
            'data_class' => TrnUploadDocument::class,
            'projectId' => null,
        ]);
    }
}
