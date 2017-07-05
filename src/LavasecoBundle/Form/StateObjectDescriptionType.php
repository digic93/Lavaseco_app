<?php

namespace LavasecoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StateObjectDescriptionType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('name')
                ->add('categoryStateObject');
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'LavasecoBundle\Entity\StateObjectDescription'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'lavasecobundle_stateobjectdescription';
    }

}
