<?php

namespace Engine\BlogBundle\Form\Type\Admin;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Engine\UserBundle\Entity\User;

class CategoryEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("category_name", Type\TextType::class, [
                    "required" => true,
                    "constraints" => [new NotBlank()]
                ]
            )
            ->add('save', SubmitType::class, ['label' => 'Save']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        /*$resolver->setDefaults(array(
            "data_class" => CategoryEditType::class,
        ));*/
    }

    public function getName()
    {
        return 'category_bundle_edit_type';
    }
}
