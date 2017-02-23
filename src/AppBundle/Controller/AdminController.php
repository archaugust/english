<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use JasonGrimes\Paginator;

class AdminController extends Controller
{
    /**
     * @Route("/admin/users/{type}", name="admin_users")
     */
    public function adminUsersAction(Request $request, $type)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AppBundle:User');

        $data = $request->query->all();
        if ($request->request->get('submit')) {
            $itemList = $request->request->get('item');
            if (count($itemList) > 0 && !empty($request->request->get('action'))) {
                if ($request->request->get('action') == 'Block') {
                    $info = 'blocked';
                    $enabled = 0;
                }
                else {
                    $info = 'enabled';
                    $enabled = 1;
                }

                foreach ($itemList as $item)
                {
                    $user = $repository->find($item);
                    $user->setEnabled($enabled);
                }

                $em->flush();

                $this->addFlash(
                    'info',
                    "User(s) $info."
                );
            }
        }
        else {
            if (!empty($data['toggle'])) {
                $user = $repository->find($data['toggle']);
                if ($user->isEnabled()) {
                    $enabled = 0;
                    $info = 'blocked';
                }
                else {
                    $enabled = 1;
                    $info = 'enabled';
                }

                $user->setEnabled($enabled);

                $this->addFlash(
                    'info',
                    "User, ". $user->getUsername() .", $info."
                );

                $em->flush();
            }
        }

        !empty($data['sort']) ? $sortBy = $data['sort'] : $sortBy = 'fullname';
        !empty($data['order']) ? $order = $data['order'] : $order = 'ASC';
        !empty($data['page']) ? $pagenum = $data['page'] : $pagenum = 1;

        $repository = $em->getRepository('AppBundle:'. ucfirst($type));
        $items = $repository->findBy([], array($sortBy => $order));
        $totalItems = count($items);

        $itemsPerPage = 10;
        $urlPattern = $request->getPathInfo() .'?page=(:num)';

        $users = array_slice($items, ($pagenum - 1) * 10, 10);
        $items = array();
        foreach ($users as $user) {
            $items[] = array(
                'id' => $user->getUser()->getId(),
                'uid' => $user->getUser()->getUid(),
                'name' => $user->getFullname(),
                'points' => $user->getPoints(),
                'enabled' => $user->getUser()->isEnabled() ? '1' : '0'
            );
        }

        $paginator = new Paginator($totalItems, $itemsPerPage, $pagenum, $urlPattern);

        return $this->render('admin/users.html.twig', array(
            'items' => $items,
            'paginator' => $paginator,
            'header' => ucfirst($type) .'s',
            'type' => $type
        ));
    }

}