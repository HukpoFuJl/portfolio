<?php

namespace Engine\UserBundle\Form\Type\Admin;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Engine\UserBundle\Entity\User;

class GroupEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("name", null, [
                    "constraints" => [new NotBlank()]
                ]
            )
            ->add("permissions", EntityType::class, [
            	"class"=>'Engine\UserBundle\Entity\Permission',
	            "choice_label" => 'name',
	            "multiple"=>true,
                "required" => false,
	            "attr"=>['class'=>'form-control select2']
            ])
            ->add("parent", EntityType::class, [
            	"class"=>'Engine\UserBundle\Entity\Group',
	            "choice_label" => 'name',
	            "multiple"=>false,
                "required" => false,
	            "attr"=>['class'=>'form-control select2']
            ])

            ->add('save', SubmitType::class, ['label' => 'Save']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {

    }

    public function getName()
    {
        return 'user_bundle_group_edit_type';
    }
}
