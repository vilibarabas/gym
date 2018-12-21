<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class ProfileType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('firstName')
        ->add('lastName')
        ->add('birtday', DateType::class, [
            'years' => range(date('Y'), date('Y') -100),
        ])
        ->add('height')
        ->add('gen', ChoiceType::class, array(
            'choices'  => array(
                'Masculin' => 'M',
                'Feminin' => 'F',
        )))
        ->add('user_id', HiddenType::class, array());
        // ->add('user', HiddenType::class, array());
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Profile'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_profile';
    }


}
