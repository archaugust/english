<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

class RegistrationType extends AbstractType
{
public function buildForm(FormBuilderInterface $builder, array $options)
{
    $builder->add('nickname', TextType::class);
    $builder->add('uid', HiddenType::class);
    $builder->add('age', HiddenType::class);
    $builder->add('date_registered', HiddenType::class);
    $builder->add('gender', ChoiceType::class, array(
        'choices'  => array(
            'Male' => 'Male',
            'Female' => 'Female',
        )));
    $builder->add('account_type', HiddenType::class);
}

public function getParent()
{
    return 'FOS\UserBundle\Form\Type\RegistrationFormType';
}

public function getBlockPrefix()
{
    return 'app_user_registration';
}}