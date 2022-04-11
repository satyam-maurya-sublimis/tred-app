<?php

namespace App\Form\Cms;

use App\Entity\Cms\CmsPageContent;
use App\Service\CommonHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CmsPageContentType extends AbstractType
{
    private $em;
    private $commonHelper;
    public function __construct(EntityManagerInterface $em, CommonHelper $commonHelper)
    {
        $this->em = $em;
        $this->commonHelper = $commonHelper;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('cmsPageContentPosition', ChoiceType::class,[
                'label' => 'label.content_position',
                'choices' => $this->commonHelper->cmsPageContentPosition(),
                'required' => false,
                'placeholder'=>"Select Position"
            ])
            ->add('pageContent', TextareaType::class,[
                'label' => 'label.content_block',
                'required' => true,
                'attr' => [
                    'class' => 'textarea'
                ]
            ])
            ->add('position', TextType::class,[
                'required' => false,
                'label' => 'label.seq_no',
            ])
            ->add('isActive', CheckboxType::class,[
                'required'=> false,
                'label' => 'label.is_active',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CmsPageContent::class,
        ]);
    }
}
