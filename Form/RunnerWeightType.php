<?php

namespace SF\CapBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RunnerWeightType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('weight')
            ->add('date')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SF\CapBundle\Entity\RunnerWeight'
        ));
    }

    public function getName()
    {
        return 'sf_capbundle_runnerweighttype';
    }
}
