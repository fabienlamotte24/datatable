<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ClientType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('nom', TextType::class, array(
            'label'             => 'Nom',
            'required'          => true     
        ))
        ->add('prenom', TextType::class, array(
            'label'             => 'Prénom',
            'required'          => true     
        ))
        ->add('age', IntegerType::class, array(
            'label'             => 'Age',
            'required'          => false
        ))
        ->add('email', EmailType::class, array(
            'label'             => 'Email',
            'required'          => true
        ))
        ->add('flagMajeur', ChoiceType::class, array(
            'label'             => 'Etes-vous majeur ?',
            'choices'           => array(
                'OUI'           => true,
                'NON'           => false
            ),
            'required'          => false
        ))
        ;
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Client'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_client';
    }


}
