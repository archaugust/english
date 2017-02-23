<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {

        // replace this example code with whatever you need
        return $this->render('default/index.html.twig');
    }

    /**
     * @Route("/member", name="member")
     */
    public function memberAction(Request $request)
    {
        $accountType = $this->getUser()->getAccountType();

        if ($accountType == '') {
            throw $this->createAccessDeniedException();
        }
        else
        {
            switch ($accountType)
            {
                case 'student':
                    return $this->redirectToRoute('student');
                    break;
                case 'teacher':
                    return $this->redirectToRoute('teacher');
                    break;
                case 'admin':
                    return $this->redirectToRoute('admin');
                    break;
                default:
                    throw $this->createAccessDeniedException();
            }
        }
    }


    /**
     * @Route("/admin", name="admin")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function admin()
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');

        return $this->render('admin/index.html.twig', array(
            'header' => 'Dashboard',
            )
        );
    }

    /**
     * @Route("/member/reg-info/{uid}", name="reg_info")
     */
    public function regInfo(Request $request, $uid)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('AppBundle:User')->find($uid);
        
        if (is_null($user)) {
        	$this->addFlash('warning', 'Unknown user.');
        	
        	$this->redirectToRoute('homepage');
        }
        	
        
        $role = $this->getUser()->getAccountType();

        $account_type = $user->getAccountType();

        $age = 0;
        if ($account_type == 'teacher')
        {
            $info = $em->getRepository('AppBundle:Teacher')->find($uid);
            $dob = $info->getDob();
            $age = $this->get('app.default')->age_from_dob($dob);
            if ($age <= 20)
                $age = '～20代';
            else
                if ($age < 30)
                    $age = '20～29代';
                else
                    if ($age < 40)
                        $age = '30～39代';
                    else
                        $age = '40～代';
        }
        else
            $info = $em->getRepository('AppBundle:Student')->find($uid);

        return $this->render('reg-info.html.twig', array(
            'account_type' => $account_type,
            'info' => $info,
            'role' => $role,
            'age' => $age
        ));
    }

    /**
     * @Route("/member/ajax-activity", name="ajax_activity")
     */
    public function ajaxActivity(Request $request)
    {
    	$data = $request->request->all();
    
    	$username = substr($data['uid'],0,20);
    	$page = substr($data['page'],0,3);
    	$user_id = $this->getUser()->getId();
    	$role = $this->getUser()->getAccountType();
    
    	$limit_from = 20 * $page;
    	$limit_to = 20;
    
    	$em = $this->getDoctrine()->getManager();
    
    	switch ($role)
    	{
    		case 'admin':
    			if ($username == '')
    				$query = 'SELECT a
                        FROM AppBundle:Activity a
                        ORDER BY a.activity_id DESC';
    				else
    					$query = 'SELECT a
                        FROM AppBundle:Activity a, AppBundle:User u
                        WHERE (u.id = a.user_id OR u.id = a.activity_by) AND u.nickname LIKE "%'. $username .'%" OR u.username LIKE "%'. $username .'%"
                        ORDER BY a.activity_id DESC';
    					break;
    		default:
    			$query = 'SELECT a
                        FROM AppBundle:Activity a
                        WHERE a.user_id = '. $user_id .' OR a.activity_by = '. $user_id .'
                        ORDER BY a.activity_id DESC';
    			break;
    	}
    
    	$query = $em->createQuery($query);
    	$results = $query->getResult();
    	$total = count($results);
    
    	$query->setMaxResults($limit_to);
    	$query->setFirstResult($limit_from);
    	$results = $query->getResult();
    
    	if ($page == 0)
    	{
    		if (count($results) > 0)
    		{
    			if ($role == 'student')
    				$header = '最新 '. count($results) .' 履歴';
    				else
    					$header = 'Latest '. count($results) .' Activities';
    		}
    		else
    		{
    			if ($role == 'student')
    				$header = '履歴';
    				else
    					$header = 'Activity';
    		}
    	}
    	else
    	{
    		if ($role == 'student')
    			$header = '前の '. ($limit_from+1) .' - '. ($limit_from + $limit_to) .' 履歴';
    			else
    				$header = 'Previous '. ($limit_from+1) .' - '. ($limit_from + $limit_to);
    	}
    
    	if (count($results)>0)
    	{
    		$items = array();
    		foreach ($results as $item) {
    			$items[] = array(
    					'activity_by' => $item->getActivityBy(),
    					'date' => date('n月j日 G:i', $item->getTimeId()),
    					'user_id' => $item->getUserId(),
    					'message' => $item->getMessage(),
    					'nickname_activity_by' => $item->getMapActivityBy()->getNickname(),
    					'nickname_user_id' => ($item->getMapUserId()->getNickname())
    			);
    		}
    	}
    
    	return $this->render('ajax_activity.html.twig', array(
    			'usertype' => $role,
    			'header' => $header,
    			'content' => $items,
    			'page' => $page,
    			'total' => $total,
    	));
    }
    
    /**
     * @Route("/member/ajax-classes", name="ajax_classes")
     */
    public function ajaxClasses(Request $request) {
    	$em = $this->getDoctrine()->getManager();
    	$data = $request->request->all();
    
    	$uid = $data['uid'];
    
    	$role = $this->getUser()->getAccountType();
    	$user_id = $this->getUser()->getId();
    
    	$page = 0;
    	$page = substr($data['page'],0,3);
    
    	$now = time();
    	 
    	$limit_from = 10 * $page;
    	$limit_to = 10;
    
    	if ($role == '')
    		exit;
    		 
    		switch ($role)
    		{
    			case 'admin':
    				if ($uid == '')
    					$query = '
    						SELECT r FROM AppBundle:Reservation r
    						WHERE (r.date_sked + (r.time_sked * 3600)) < '. $now .'
    						ORDER BY r.date_sked + (r.time_sked * 3600) DESC';
    					else
    						$query = '
    						SELECT r FROM AppBundle:Reservation r
    						WHERE r.teacher_id = '. $uid .' AND r.date_sked + (r.time_sked * 3600)) < '. $now .'
    						ORDER BY r.date_sked + (r.date_sked * 3600) DESC';
    						break;
    			case 'teacher':
    				$query = '
    					SELECT r FROM AppBundle:Reservation r
    					WHERE (r.date_sked + (r.time_sked * 3600)) < '. $now .' AND r.teacher_id = '. $user_id .'
    					ORDER BY r.date_sked + (r.time_sked * 3600) DESC';
    				break;
    		}
    		 
    		$query = $em->createQuery($query);
    		$total = count($query->getResult());
    		 
    		$query->setMaxResults($limit_to);
    		$query->setFirstResult($limit_from);
    		 
    		$result = $query->getResult();
    
    		if ($page == 0)
    		{
    			if (count($result) > 0)
    			{
    				$header = 'Latest ';
    				if (count($result) > 1)
    					$header .= count($result) .' Classes';
    					else
    						$header .= 'Classes';
    			}
    			else
    				$header = 'Classes';
    		}
    		else
    			$header = 'Previous '. ($limit_from+1) .' - '. ($limit_from + $limit_to);
    
    			if (count($result)>0)
    			{
    				$items = array();
    				foreach ($result as $item) {
    					$items[] = array(
    							'id' => $item->getReservationId(),
    							'class' => date('n月j日 G:i', ($item->getDateSked() + ($item->getTimeSked() * 3600))),
    							'lesson' => $item->getMode(),
    							'student' => $item->getStudent(),
    							'notes' => $item->getNotes(),
    							'commendation' => $item->getCommendation()
    					);
    				}
    			}
    
    			return $this->render('ajax_classes.html.twig', array(
    					'header' => $header,
    					'items' => $items,
    					'total' => $total,
    					'page' => $page,
    			));
    }
    
}
