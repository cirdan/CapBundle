<?php

namespace SF\CapBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CapGoalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('duration')
            ->add('distance')
            ->add('delay')
            ->add('sum')
            ->add('comment')
            ->add('isPublic')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SF\CapBundle\Entity\CapGoal'
        ));
    }

    public function getName()
    {
        return 'sf_capbundle_capgoaltype';
    }
}
