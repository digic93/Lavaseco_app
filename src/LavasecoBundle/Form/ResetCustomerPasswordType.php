<?php

namespace LavasecoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class ResetCustomerPasswordType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('password', RepeatedType::class, array(
                            'type' => PasswordType::class,
                            'invalid_message' => 'La contraseña no cincide.',
                            'options' => array('attr' => array('class' => 'password-field')),
                            'required' => true,
                            'first_options'  => array('label' => 'Contraseña'),
                            'second_options' => array('label' => 'Confirmar Contraseña'),
                        ));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'LavasecoBundle\Entity\Customer'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'lavasecobundle_customer';
    }


}
