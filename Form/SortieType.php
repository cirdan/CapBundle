<?php

namespace SF\CapBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date','date',array('widget' => 'choice', 'label'=>'run.form.date','format' => 'ddMMMMyyyy','years'=>range(date('Y')-5,date('Y'))))
            ->add('time','time',array('label'=>'run.form.time'))
            ->add('duration','integer',array('label'=>'run.form.duration'))
            ->add('distance','integer',array('label'=>'run.form.distance'))
            ->add('comment','textarea',array('label'=>'run.form.comment','required'=>false))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SF\CapBundle\Entity\Sortie'
        ));
    }

    public function getName()
    {
        return 'sf_capbundle_sortietype';
    }
}
