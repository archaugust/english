<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TeacherProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('video_url', TextType::class, array('label' => 'Video URL'))
            ->add('fullname', TextType::class)
            ->add('nickname', TextType::class)
            ->add('gender', ChoiceType::class, array(
                'choices'  => array(
                    'Male' => 'Male',
                    'Female' => 'Female',
                )))
            ->add('dob', TextType::class)
            ->add('phone', TextType::class)
            ->add('description', TextareaType::class)
            ->add('avatar', FileType::class, array(
                'label' => 'Picture (JPG or PNG format)',
                'required' => false
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Teacher',
        ));
    }
}