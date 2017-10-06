<?php

namespace LavasecoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('firstName')->add('lastName')->add('phoneNumber');
    }

    public function getParent() {
        return 'fos_user_registration';
    }

    public function getName() {
        return 'app_user_registration';
    }

}
