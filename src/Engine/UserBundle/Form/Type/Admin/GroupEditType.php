<?php

namespace Engine\UserBundle\Form\Type\Admin;

use Doctrine\ORM\EntityRepository;
use Engine\UserBundle\Entity\Group;
use Engine\UserBundle\Entity\Permission;
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
            	"class"=> Permission::class,
	            "choice_label" => 'name',
	            "multiple"=>true,
                "required" => false,
	            "attr"=>['class'=>'form-control select2']
            ])
            ->add("parent", EntityType::class, [
            	"class"=> Group::class,
                'query_builder' => function (EntityRepository $repository) use ($options) {
                    $qb = $repository->createQueryBuilder('ug');
                    if($options['edit_group']){
                        /** @var Group $group */
                        $group = $options['edit_group'];
                        $qb->where($qb->expr()->neq('ug.id', $group->getId()));
                        $childGroups = $group->getChildren();
                        if($childGroups){
                            foreach ($childGroups as $chGroup){
                                $qb->where($qb->expr()->neq('ug.id', $chGroup->getId()));
                            }
                        }
                        return $qb->orderBy('ug.name', 'ASC');
                    }
                    return $qb->orderBy('ug.name', 'ASC');
                },
	            "choice_label" => 'name',
	            "multiple"=>false,
                "required" => false,
	            "attr"=>['class'=>'form-control select2']
            ])

            ->add('save', SubmitType::class, ['label' => 'Save']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            "edit_group" => null
        ]);
    }

    public function getName()
    {
        return 'user_bundle_group_edit_type';
    }
}
