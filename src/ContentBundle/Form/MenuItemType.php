<?php
namespace ContentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

class MenuItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        global $menu_id, $parent_id;
        $menu_id = $options['data']->getMenuId();
        $parent_id = $options['data']->getParentId();

        $builder
            ->add('title', TextType::class, array(
                'required' => true
            ))
            ->add('page_type',EntityType::class,
                array(
                    'class' => 'AppBundle:PageType',
                    'choice_label' => 'pageTypeTitle',
                    'choice_value' => 'pageType',
                    'placeholder' => 'Choose one'
                )
            )
            ->add('parent_id',EntityType::class,
                array(
                    'class' => 'AppBundle:MenuItem',
                    'query_builder' => function(EntityRepository $er) {
                        global $menu_id;
                        return $er->createQueryBuilder('m')
                            ->where('m.menuId = '. $menu_id .' AND m.parentId = 0')
                            ;
                    },
                    'choice_label' => 'Title',
                    'choice_value' => 'Id',
                    'placeholder' => 'Root',
                    'required' => false
                )
            )
            ->add('page_type_id', TextType::class)
            ->add('sort_order',HiddenType::class)
            ->add('save', SubmitType::class, array('label' => 'Save Menu'))
            ->add('saveAndAdd', SubmitType::class, array('label' => 'Save and Add'));
    }
}