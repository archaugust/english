<?php
namespace AppBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class Builder implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function adminMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');
        $menu->setChildrenAttributes(['class' => 'nav navbar-nav side-nav']);

        $menu->addChild('Dashboard', array('route' => 'admin'))
            ->setAttribute('icon', 'fa fa-fw fa-dashboard');

        $menu->addChild('Menu', array('uri' => '#', 'attributes' => array('icon' => 'fa fa-fw fa-arrows-v', 'collapse' => true, 'collapseData' => 'subMenu')))
            ->setAttribute('icon', 'fa fa-fw fa-list-alt');
        $menu_level_2 = $menu['Menu']->addChild('View All', array('route' => 'admin_menu'));
        $em = $this->container->get('doctrine')->getManager()->getRepository('AppBundle:Menu');
        $result = $em->findBy([]);
        foreach ($result as $item) {
            $item_id = $item->getId();
            $item_title = $item->getTitle();
            $menu_level_2->addChild($item_title, array('route' => 'admin_menu_page', 'routeParameters' => array('id' => $item_id)));
        }

        $menu['Menu']->addChild('Add New Menu', array('route' => 'admin_menu_add'));
        $menu['Menu']->setChildrenAttributes(array('class' => 'collapse','id' => 'subMenu'));
        $menu_level_2->setChildrenAttributes(array('class' => 'nav'));

        $menu->addChild('Content', array('uri' => '#', 'attributes' => array('icon' => 'fa fa-fw fa-arrows-v', 'collapse' => true, 'collapseData' => 'subContent')))
            ->setAttribute('icon', 'fa fa-fw fa-file-text');
        $menu['Content']->addChild('View All', array('route' => 'admin_content'));
        $menu['Content']->addChild('Add New Content', array('route' => 'admin_content_add'));
        $menu['Content']->setChildrenAttributes(array('class' => 'collapse','id' => 'subContent'));

        $menu->addChild('Users', array('uri' => '#', 'attributes' => array('icon' => 'fa fa-fw fa-arrows-v', 'collapse' => true, 'collapseData' => 'subUsers')))
            ->setAttribute('icon', 'fa fa-fw fa-user');
        $menu['Users']->addChild('Teachers', array('route' => 'admin_users', 'routeParameters' => array('type' => 'teacher')));
        $menu['Users']->addChild('Students', array('route' => 'admin_users', 'routeParameters' => array('type' => 'student')));
        $menu['Users']->setChildrenAttributes(array('class' => 'collapse','id' => 'subUsers'));

        return $menu;
    }

    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');
        $menu->setChildrenAttributes(['class' => 'nav navbar-nav','id' => 'main_menu']);

        $em = $this->container->get('doctrine')->getManager()->getRepository('ContentBundle:MenuItem');
        $result = $em->findBy(array('menuId' => 1, 'parentId' => 0), array('sortOrder' => 'ASC'));
        foreach ($result as $item) {
            $title = explode(' | ', $item->getTitle());

            (count($title)>1) ? $subtitle = $title[1] : $subtitle = $title[0];

            $children = $em->findBy(array('parentId' => $item->getId()), array('sortOrder' => 'ASC'));
            if (empty($children))
            {
                $pageType = $item->getPageType();
                if ($pageType == 'text-separator')
                    $uri = '#';
                else
                {
                    if ($pageType == 'url')
                        $uri = $item->getPageTypeId();
                    else
                        $uri = $item->getAlias();
                }
                $menu->addChild($title[0], array('uri' => $uri, 'extras' => array('subtitle'=>$subtitle)));
            }
            else {
                $menu->addChild($title[0], array('uri' => $item->getAlias(), 'extras' => array('subtitle'=>$subtitle)))
                    ->setAttribute('class', 'dropdown')
                    ->setLinkAttribute('class', 'dropdown-toggle')
                    ->setLinkAttribute('data-toggle', 'dropdown');

                foreach ($children as $child)
                {
                    $pageType = $child->getPageType();
                    if ($pageType == 'text-separator')
                        $childUri = '#';
                    else
                    {
                        if ($pageType == 'url')
                            $childUri = $child->getPageTypeId();
                        else
                            $childUri = $item->getAlias() .'/'. $child->getAlias();
                    }
                    $menu[$title[0]]->addChild($child->getTitle(), array('uri' => $childUri));
                }
                $menu[$title[0]]->setChildrenAttributes(array('class' => 'dropdown-menu'));
            }

        }
        return $menu;
    }
}