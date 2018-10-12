<?php

namespace Engine\BlogBundle\Form\Type\Admin;

use Engine\BlogBundle\Entity\BlogCategories;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Engine\UserBundle\Entity\User;

class BlogEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $em = $options["entity_manager"];
        $choices = new BlogCategories();
        $choices = $em->getRepository("BlogBundle:BlogCategories")->findAll();
        $selected = [];
        $select = [];
        if(isset($options["selected_id"])) {
            $currentBlog = $em->getRepository("BlogBundle:Blog")->find(["id" => $options["selected_id"]]);
            foreach ($currentBlog->getCategories() as $category) {
                array_push($selected, $em->getRepository("BlogBundle:BlogCategories")->findBy(["category_name" =>
                    $category])
                [0]->getId());
            }
        }
        foreach ($choices as $choice) {
            $select[$choice->getCategoryName()] = $choice->getId();
        }

        $builder
            ->add("categories", Type\ChoiceType::class,array(
                "choices" => $select,
                "multiple" => true,
                "data" => $selected,
                "label" => "Категории"
                )
            )
            ->add("title", Type\TextType::class, [
                    "required" => true,
                    "constraints" => [new NotBlank()],
                    "label" => "Заголовок"
                ]
            )
            ->add("content", Type\TextareaType::class, [
                "required" => true,
                "label" => "Содержание"
            ])
            ->add('image', Type\TextType::class, array(
                'label' => 'Изображение',
                'required'    => false,
                'attr' => ['readonly' => true]
            ))
            ->add("image_input", Type\FileType::class, [
                "required" => false,
                'data_class' => null,
                'label' => 'Основное изображение'
            ])
            ->add("active", Type\CheckboxType::class, [
                "required" => false,
                'data_class' => null,
                'label' => ' '
            ])
            ->add("date", Type\DateType::class, [
                "required" => false,
                'label' => 'Дата создания'
            ])
            ->add("updateDate", Type\DateType::class, [])
            ->add('save', SubmitType::class, ['label' => 'Save']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            "entity_manager" => null,
            "selected_id" => null,
        ]);
    }

    public function getName()
    {
        return 'news_bundle_edit_type';
    }
}