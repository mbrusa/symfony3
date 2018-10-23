<?php

namespace BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class EntryType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('title',TextType::class, array(
                    "label" => "Titulo:",
                    "required" => "required",
                    "attr" => array(
                        "class"=>"form-name form-control",
                    )
                ))
                ->add('content',TextAreaType::class, array(
                    "label" => "Contenido:",
                    "required" => "required",
                    "attr" => array(
                        "class"=>"form-name form-control",
                    )
                ))
                ->add('status',ChoiceType::class, array(
                    "label" => "Estado:",
                    "choices" => array(
                        "Publicado" => "publicado",
                        "Pendiente" => "pendiente",
                    ),
                    "required" => "required",
                    "attr" => array(
                        "class"=>"form-name form-control",
                    )
                ))
                ->add('image', FileType::class, array(
                    "label" => "Imagen:",
                    "required" => "required",
                    "attr" => array("class"=>"form-name form-control"),
                    "data_class" => null
                ))
                ->add('category', EntityType::class, array(
                    "class" => 'BlogBundle:Category',
                    "label" => "Categoría:",
                    "required" => "required",
                    "attr" => array(
                        "class"=>"form-name form-control",
                    )
                ))
                ->add('user', EntityType::class, array(
                    "class" => 'BlogBundle:User',
                    "label" => "Usuario:",
                    "required" => "required",
                    "attr" => array(
                        "class"=>"form-name form-control",
                    )
                ))
                ->add('tags',TextType::class, array(
                    "mapped" => false,
                    "label" => "Tags:",
                    "required" => "required",
                    "attr" => array(
                        "class"=>"form-name form-control",
                    )
                ))
                ->add('Guardar', SubmitType::class, array(
                    "attr" => array(
                        "class"=>"form-submit btn btn-success",
                    )
                ));
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BlogBundle\Entity\Entry'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'blogbundle_entry';
    }


}
