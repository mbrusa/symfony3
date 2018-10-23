<?php

namespace BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class UserType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('role',ChoiceType::class, array(
                    "label" => "Tipo:",
                    "choices" => array(
                        "Administrador" => "ROLE_ADMIN",
                        "Usuario" => "ROLE_USER",
                    ),
                    "required" => "required",
                    "attr" => array(
                        "class"=>"form-name form-control",
                    )
                ))
                ->add('name',TextType::class, array(
                    "label" => "Nombre:",
                    "required" => "required",
                    "attr" => array(
                        "class"=>"form-name form-control",
                    )
                ))
                ->add('surname',TextType::class, array(
                    "label" => "Apellido:",
                    "required" => "required",
                    "attr" => array(
                        "class"=>"form-name form-control",
                    )
                ))
                ->add('email',TextType::class, array(
                    "label" => "Email:",
                    "required" => "required",
                    "attr" => array(
                        "class"=>"form-name form-control",
                    )
                ))
                ->add('password',TextType::class, array(
                    "label" => "Contraseña:",
                    "required" => "required",
                    "attr" => array(
                        "class"=>"form-name form-control",
                    )
                ))
                ->add('imagen', FileType::class, array(
                    "label" => "Imagen:",
                    "required" => false,
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
            'data_class' => 'BlogBundle\Entity\User'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'blogbundle_user';
    }


}
