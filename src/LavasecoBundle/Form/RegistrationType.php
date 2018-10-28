<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace LavasecoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

/**
 * Description of RegistrationType
 *
 * @author diego
 */
class RegistrationType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('firstName')
                ->add('lastName')
                ->add('phoneNumber')
                ->add('roles',  ChoiceType::class, 
                        array('label' => 'Rol', 'required' => true,
                            'choices' => array( 
                                'Vendedor' => 'ROLE_CASHIER',
                                'Operador' => 'ROLE_OPERATOR', 
                                'Mensajero' => 'ROLE_MESSENGER',
                                'Administrador' => 'ROLE_MANAGER',
                            ), 
                    'multiple' => true));
    }

    public function getParent() {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';
    }

    public function getName() {
        return 'Lavaseco_user_registration';
    }

}
