<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use AppBundle\Entity\Profile;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class EvolutionType extends AbstractType
{
    private $objectManager;

    /**
     * JustAFormType constructor.
     *
     * @param ObjectManager $objectManager
     */
    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('weight')
            ->add('time', DateType::class, [
                'years' => range(date('Y'), date('Y') -100),
            ])
            ->add('profile', HiddenType::class, array());
        $builder
            ->get('profile')
            ->addModelTransformer(new EntityHiddenTransformer(
                $this->getObjectManager(),
                Profile::class,
                'id'
            ));
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Evolution'
        ));
    }

    public function getObjectManager()
    {
        return $this->objectManager;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_evolution';
    }


}
