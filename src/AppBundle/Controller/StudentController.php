<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Reservation;
use AppBundle\Entity\Activity;
use AppBundle\Entity\Bookmark;
use AppBundle\Entity\Writing;

class StudentController extends Controller
{

    /**
     * @Route("/member/student", name="student")
     */
    public function studentAction(Request $request)
    {
        $tab = $request->query->get('tab');
        if (empty($tab))
            $tab = '';

        return $this->render('student/home.html.twig', array(
            'tab' => $tab
            ));
    }

	/**
	 * @Route("/member/student/home", name="ajax_student_home")
	 */
	public function ajaxStudentHome()
	{
		return $this->render('student/ajax_home.html.twig');
	}

    /**
     * @Route("/member/student/ajax-schedule", name="ajax_student_schedule")
     */
    public function ajaxStudentSchedule(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user_id = $this->getUser()->getId();

        $today = mktime(0, 0, 0, date("n"), date("j"), date("Y"));
        $now = time();

        $data = $request->request->all();

        $week = substr($data['week'], 0, 2);
        $time_from = substr($data['time_from'], 0, 2);
        $time_to = substr($data['time_to'], 0, 2);

        if ($time_from == '')
        {
            if (date('G') < 12)
            {
                $time_from = 0;
                $time_to = 11;
            }
            else
            {
                $time_from = 12;
                $time_to = 23;
            }
        }

        //get plan
        $plan = $em->getRepository('AppBundle:Plan')->findOneBy(array('student_id' => $user_id));
        $plan = $plan->getPlan();

        // upcoming classes
        $query = $em->createQuery(
            'SELECT r 
              FROM AppBundle:Reservation r 
              WHERE r.student_id = '. $user_id. ' AND (r.date_sked + (r.time_sked * 3600)) >= '. $now .' ORDER BY ((r.time_sked * 3600) + r.date_sked)');
        $result = $query->getResult();

        $classes = array();
        foreach($result as $tmp) {
            $class = array();
            $class_time = (($tmp->getTimeSked()) * 3600) + $tmp->getDateSked();
            $class['datetime'] = date('n月j日', $class_time) . ' ' . date('G:i', $class_time) . '-' . date('G:i', ($class_time + 1500));
            $class['teacher'] = $tmp->getMapTeacherId()->getNickname();
            $class['mode'] = $tmp->getMode();
            $class['point_cost'] = $tmp->getPointCost();
            $class['teacher_id'] = $tmp->getTeacherId();

            switch ($plan) {
                case '2000':
                case '1500':
                case '1000':
                    if (($class_time - $now) > 300)
                        $class['action'] = '<a href="/member/student/cancel-confirm?uid=' . $tmp->getReservationId() . '" rel="modal:open">キャンセル</a>';
                    else
                        $class['action'] = '-';
                    break;
                default:
                    if (($class_time - $now) > 3600)
                        $class['action'] = '<a href="/member/student/cancel-confirm?uid=' . $tmp->getReservationId() . '" rel="modal:open">キャンセル</a>';
                    else
                        $class['action'] = '-';
                    break;
            }

            $classes[] = $class;
        }

        // start dates set to today
        $start_date = mktime(0, 0, 0, date("n")  , date("j"), date("Y"));
        $start_date = $start_date + ((86400*7)*$week);
        $end_date = $start_date + (86400*7);
        $week_start = $start_date;
        $week_end = $start_date + (86400*6);

        // SKEDs
        $query = $em->createQuery('SELECT r FROM AppBundle:Reservation r WHERE r.student_id = '. $user_id .' AND r.date_sked >= '. $start_date .' AND r.date_sked <= '. $end_date);
        $result = $query->getResult();

        $sked = array();
        foreach ($result as $sked_info)
        {
            $date_sked = $sked_info->getDateSked();
            $time_sked = $sked_info->getTimeSked();

            $sked[$date_sked .'-'. $time_sked] = 1;
        }

        for ($ctr = 0; $ctr <= 6; $ctr++)
            $table_date[$ctr] = $start_date + (86400 * $ctr);

        $calendar = array();
        $tmp = array();

        $tmp[] = '';
        for ($ctr = 0; $ctr < 7; $ctr++)
        {
            switch (date('D', $table_date[$ctr]))
            {
                case 'Sun':
                    $tmp[] = '<span style="color:#0044cc">日</span>';
                    break;
                case 'Mon':
                    $tmp[] = '月';
                    break;
                case 'Tue':
                    $tmp[] = '火';
                    break;
                case 'Wed':
                    $tmp[] = '水';
                    break;
                case 'Thu':
                    $tmp[] = '木';
                    break;
                case 'Fri':
                    $tmp[] = '金';
                    break;
                case 'Sat':
                    $tmp[] = '<span style="color:#238bc0">土</span>';
                    break;
            }
        }

        $calendar[] = $tmp;

        $tmp = array();
        $tmp[] = '日時';

        for ($ctr = 0; $ctr < 7; $ctr++)
            $tmp[] = date('n月j日', $table_date[$ctr]);

        $calendar[] = $tmp;

        for ($ctr = $time_from; $ctr <= $time_to; $ctr++) {
            $tmp = array();
            $tmp[] = date('G:00', mktime($ctr, 0, 0, 0, 0, 0));
            $tmp[] = $this->getButtonStudent($user_id, $sked, $start_date, $ctr);
            $tmp[] = $this->getButtonStudent($user_id, $sked, $start_date + 86400, $ctr);
            $tmp[] = $this->getButtonStudent($user_id, $sked, $start_date + 86400 * 2, $ctr);
            $tmp[] = $this->getButtonStudent($user_id, $sked, $start_date + 86400 * 3, $ctr);
            $tmp[] = $this->getButtonStudent($user_id, $sked, $start_date + 86400 * 4, $ctr);
            $tmp[] = $this->getButtonStudent($user_id, $sked, $start_date + 86400 * 5, $ctr);
            $tmp[] = $this->getButtonStudent($user_id, $sked, $start_date + 86400 * 6, $ctr);

            $calendar[] = $tmp;

            $tmp = array();
            $tmp[] = date('G:30', mktime($ctr, 0, 0, 0, 0, 0));
            $tmp[] = $this->getButtonStudent($user_id, $sked, $start_date, $ctr + .5);
            $tmp[] = $this->getButtonStudent($user_id, $sked, $start_date + 86400, $ctr + .5);
            $tmp[] = $this->getButtonStudent($user_id, $sked, $start_date + 86400 * 2, $ctr + .5);
            $tmp[] = $this->getButtonStudent($user_id, $sked, $start_date + 86400 * 3, $ctr + .5);
            $tmp[] = $this->getButtonStudent($user_id, $sked, $start_date + 86400 * 4, $ctr + .5);
            $tmp[] = $this->getButtonStudent($user_id, $sked, $start_date + 86400 * 5, $ctr + .5);
            $tmp[] = $this->getButtonStudent($user_id, $sked, $start_date + 86400 * 6, $ctr + .5);

            $calendar[] = $tmp;
        }

        $tmp = array();

        for ($ctr = 0; $ctr < 7; $ctr++)
            $tmp[] = date('n月j日', $table_date[$ctr]);

        $calendar[] = $tmp;

        return $this->render('student/ajax_schedule.html.twig',array(
            'calendar' => $calendar,
            'plan' => $plan,
            'classes' => $classes,
            'dates' => date('n月j日', $week_start) .' - '. date('n月j日', $week_end),
            'week' => $week,
            'time_from' => $time_from,
            'time_to' => $time_to,
        ));
    }

    /**
     * @Route("/member/student/ajax-bookmarks", name="ajax_student_bookmarks")
     */
    public function ajaxStudentBookmarks()
    {
        $em = $this->getDoctrine()->getManager();
        $user_id = $this->getUser()->getId();
        $student = $em->getRepository('AppBundle:Student')->find($user_id);

        // get lesson credits
        $points = $student->getPoints();
        $now = time();
        $hour = (date('G') * 3600) + (date('i') * 60);
        $date_sked = mktime(0, 0, 0, date("m")  , date("d"), date("Y"));

        if (date('G') < 12)
        {
            $time_from = 0;
            $time_to = 11;
        }
        else
        {
            $time_from = 12;
            $time_to = 23;
        }

        // check time left for next class
        $next_class = '';

        $query = $em->createQuery('
				SELECT r 
					FROM AppBundle:Reservation r
					WHERE r.student_id = '. $user_id .' AND r.date_sked = '. $date_sked .' AND (r.time_sked*3600) > '. $hour .'
					ORDER BY r.time_sked+0 ASC');
        $result = $query->getResult();
        if(count($result) > 0)
        {
            $time_sked = $result[0]->getTimeSked();
            $class = $date_sked + ($time_sked * 3600);

            if ($class > $now)
                $next_class = ($date_sked + (($time_sked) * 3600)) - $now;
        }

        // evaluation
        $query = $em->createQuery('SELECT r FROM AppBundle:Reservation r WHERE r.student_id = '. $user_id .' AND r.commendation = 0 AND (r.date_sked + (r.time_sked * 3600)) < '. $now);
        $result = $query->getResult();
        $pending = count($result);

        $bookmarks = $student->getBookmarks();
        $teachers = array();
        foreach ($bookmarks as $bookmark) {
            $tmp = array(
                'teacher_id' => $bookmark->getTeacherId(),
                'avatar' => $bookmark->getMapTeacherId()->getAvatar(),
                'nickname' => $bookmark->getMapTeacherId()->getNickname(),
                'points' => $bookmark->getMapTeacherId()->getPoints()
            );
            $teachers[] = $tmp;
        }

        return $this->render('student/ajax_bookmarks.html.twig',array(
            'points' => $points,
            'next_class' => $next_class,
            'pending' => $pending,
            'bookmarks' => $teachers,
            'time_from' => $time_from,
            'time_to' => $time_to,
        ));
    }

    /**
     * @Route("/member/student/ajax-student-bookmark-add", name="ajax_student_bookmark_add")
     */
    public function ajaxStudentBookmarkAdd(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $data = $request->request->all();

        $user_id = $this->getUser()->getId();
        $teacher_id = substr($data['uid'],0,6);
        $student = $em->getRepository('AppBundle:Student')->find($user_id);
        $teacher = $em->getRepository('AppBundle:Teacher')->find($teacher_id);

        $test = $em->getRepository('AppBundle:Bookmark')->findOneBy(array('student_id' => $user_id, 'teacher_id' => $teacher_id));

        if (count($test) == 0) {
            $bookmark = new Bookmark();
            $bookmark->setMapTeacherId($teacher);
            $bookmark->setMapStudentId($student);

            $em->persist($bookmark);
            $em->flush();
        }
        return $this->render('student/ajax_notice.html.twig',array(
            'content' => 'ブックマークに追加されました。'
        ));
    }

    /**
     * @Route("/member/student/ajax-student-bookmark-remove", name="ajax_student_bookmark_remove")
     */
    public function ajaxStudentBookmarkRemove(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $data = $request->request->all();

        $user_id = $this->getUser()->getId();
        $teacher_id = substr($data['uid'],0,6);

        $bookmark = $em->getRepository('AppBundle:Bookmark')->findOneBy(array('student_id' => $user_id, 'teacher_id' => $teacher_id));
        $em->remove($bookmark);
        $em->flush();

        return $this->redirectToRoute('ajax_student_bookmarks');
    }

    /**
     * @Route("/member/student/ajax-classes", name="ajax_student_classes")
     */
    public function ajaxStudentClasses(Request $request)
    {
        // table
        return $this->render('student/ajax_notes.html.twig',array(
            'content' => 'adad'
        ));
    }

	/**
	 * @Route("member/student/ajax-tutors", name="ajax_tutors")
	 */
	public function ajaxShowTutors(Request $request)
	{
        $em = $this->getDoctrine()->getManager();

		$hour = (date('G') * 3600) + (date('i') * 60);
		$date_sked = mktime(0, 0, 0, date("m")  , date("d"), date("Y"));

		// get lesson credits
		$user_id = $this->getUser()->getId();
		$student = $em->getRepository('AppBundle:Student')->find($user_id);
		$points = $student->getPoints();
        $now = time();

		// check time left for next class
		$next_class = '';

		$query = $em->createQuery('
				SELECT r 
					FROM AppBundle:Reservation r
					WHERE r.student_id = '. $user_id .' AND r.date_sked = '. $date_sked .' AND (r.time_sked*3600) > '. $hour .'
					ORDER BY r.time_sked+0 ASC');
		$result = $query->getResult();
		if(count($result) > 0)
		{
			$time_sked = $result[0]->getTimeSked();
			$class = $date_sked + ($time_sked * 3600);

			if ($class > $now)
				$next_class = ($date_sked + (($time_sked) * 3600)) - $now;
		}

        // evaluation
        $query = $em->createQuery('SELECT r FROM AppBundle:Reservation r WHERE r.student_id = '. $user_id .' AND r.commendation = 0 AND (r.date_sked + (r.time_sked * 3600)) < '. $now);
        $result = $query->getResult();
        $pending = count($result);

		// show tutors
		$data = $request->request->all();

		$gender = substr($data['gender'],0,25);
		$age = substr($data['age'],0,10);
		$avail = substr($data['avail'],0,1);
		$page = substr($data['page'],0,2);

		if (date('G') < 12)
		{
			$time_from = 0;
			$time_to = 11;
		}
		else
		{
			$time_from = 12;
			$time_to = 23;
		}

		$today = mktime(0, 0, 0, date("n")  , date("j"), date("Y"));

		$limit_from = $page * 10;
		$limit_to = 10;

        $where = " WHERE u.account_type = 'teacher' AND u.enabled = 1";
        if ($gender != '')
            $where .= " AND u.gender = '". $gender ."'";
        if ($age != '')
        {
            switch ($age) {
                case 20:
                    $age_min = 20;
                    $age_max = 30;
                    break;
                case 30:
                    $age_min = 30;
                    $age_max = 40;
                    break;
                case 40:
                    $age_min = 40;
                    $age_max = 80;
                    break;
                default:
                    $age_min = 0;
                    $age_max = 100;
                    break;
            }
            $where .= ' AND (u.age >= '. $age_min .' AND u.age < '. $age_max .')';
        }

		if ($avail == 0) // any day
			$query = "SELECT u FROM AppBundle:User u ". $where;
		else // today only
		{
			$where .= " AND s.date_sked = ". $today ." AND s.time_sked <> '0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0'";

			$query = "SELECT u FROM AppBundle:User u JOIN u.skeds s ". $where;
		}

        $query_total = $em->createQuery($query);
        $results = $query_total->getResult();
        $total = count($results);

        $query = $em->createQuery($query);
        $query->setMaxResults($limit_to);
        $query->setFirstResult($limit_from);
        $results = $query->getResult();

        $content = array();
		if (count($results) > 0)
		{
			foreach ($results as $tutor)
			{
				$tmp = array();

				$tmp['tutor_id'] = $tutor->getId();
				$tmp['nickname'] = $tutor->getNickname();
				$teacher = $em->getRepository('AppBundle:Teacher')->find($tmp['tutor_id']);
				$tmp['avatar'] = $teacher->getAvatar();
                $tmp['points'] = $teacher->getPoints();

				/*
				$tmp['sked'] = array();
				$sked = $em->getRepository('AppBundle:Sked')->findBy(array(
					'teacher_id' => $tmp['tutor_id'],
					'date_sked' => $today));
				// recur all 1 into array and stick to $tmp['sked']
				*/
				$content[] = $tmp;
			}
		}

        return $this->render('student/ajax_tutors.html.twig', array(
			'points' => $points,
			'next_class' => $next_class,
            'pending' => $pending,
			'content' => $content,
			'time_from' => $time_from,
			'time_to' => $time_to,
			'page' => $page,
			'total' => $total,
        ));
	}

    /**
     * @Route("member/student/ajax-tutor-sked", name="ajax_tutor_sked")
     */
	public function ajaxShowTutorSked(Request $request)
	{
		$em = $this->getDoctrine()->getManager();

		$data = $request->request->all();

		$teacher = substr($data['uid'],0,5);
		$time_from = substr($data['time_from'],0,2);
		$time_to = substr($data['time_to'],0,2);
		$offset = substr($data['offset'],0,2);
        @$mode = substr($data['mode'],0,1);

		$tutor = $em->getRepository('AppBundle:Teacher')->find($teacher);

		if (count($tutor) > 0) {
			// start dates set to today
			$start_date = mktime(0, 0, 0, date("n"), date("j"), date("Y"));

			$start_date = $start_date + ((86400 * 7) * $offset);
			$end_date = $start_date + (86400 * 6);

			$week_start = $start_date;
			$week_end = $start_date + (86400 * 6);

			// SKEDs
			$query = $em->createQuery('
				SELECT s
					FROM AppBundle:Sked s
					WHERE s.teacher_id = :uid AND s.date_sked >= :start_date AND s.date_sked <= :end_date');
			$query->setParameter('uid', $teacher);
			$query->setParameter('start_date', $start_date);
			$query->setParameter('end_date', $end_date);
			$results = $query->getResult();

			if (count($results) > 0)
				foreach ($results as $sked_info) {
					$date_sked = $sked_info->getDateSked();
					$time_sked = $sked_info->getTimeSked();

					$time_sked = explode(':', $time_sked);

					$ctr_array = 0;
					for ($ctr_time = 0; $ctr_time < 24; $ctr_time += 0.5) {
						// flag open slots
						if ($time_sked[$ctr_array] != 0)
							$sked [$date_sked . '-' . $ctr_time] = $time_sked[$ctr_array];

						$ctr_array++;
					}
				}

			for ($ctr = 0; $ctr <= 6; $ctr++)
				$table_date[$ctr] = $start_date + (86400 * $ctr);

			$calendar = array();
			$tmp = array();

			$tmp[] = '';
			for ($ctr = 0; $ctr < 7; $ctr++) {
				switch (date('D', $table_date[$ctr])) {
                    case 'Sun':
                        $tmp[] = '<span style="color:#0044cc">日</span>';
                        break;
                    case 'Mon':
                        $tmp[] = '月';
                        break;
                    case 'Tue':
                        $tmp[] = '火';
                        break;
                    case 'Wed':
                        $tmp[] = '水';
                        break;
                    case 'Thu':
                        $tmp[] = '木';
                        break;
                    case 'Fri':
                        $tmp[] = '金';
                        break;
                    case 'Sat':
                        $tmp[] = '<span style="color:#238bc0">土</span>';
                        break;
				}
			}

			$calendar[] = $tmp;

			$tmp = array();
			$tmp[] = '日時';

			for ($ctr = 0; $ctr < 7; $ctr++)
				$tmp[] = date('n月j日', $table_date[$ctr]);

			$calendar[] = $tmp;

			for ($ctr = $time_from; $ctr <= $time_to; $ctr++) {
                $tmp = array();
				$tmp[] = date('G:00', mktime($ctr, 0, 0, 0, 0, 0));
				$tmp[] = $this->getButton($teacher, $sked, $start_date, $ctr);
				$tmp[] = $this->getButton($teacher, $sked, $start_date + 86400, $ctr);
				$tmp[] = $this->getButton($teacher, $sked, $start_date + 86400 * 2, $ctr);
				$tmp[] = $this->getButton($teacher, $sked, $start_date + 86400 * 3, $ctr);
				$tmp[] = $this->getButton($teacher, $sked, $start_date + 86400 * 4, $ctr);
				$tmp[] = $this->getButton($teacher, $sked, $start_date + 86400 * 5, $ctr);
				$tmp[] = $this->getButton($teacher, $sked, $start_date + 86400 * 6, $ctr);

				$calendar[] = $tmp;

				$tmp = array();
				$tmp[] = date('G:30', mktime($ctr, 0, 0, 0, 0, 0));
				$tmp[] = $this->getButton($teacher, $sked, $start_date, $ctr + .5);
				$tmp[] = $this->getButton($teacher, $sked, $start_date + 86400, $ctr + .5);
				$tmp[] = $this->getButton($teacher, $sked, $start_date + 86400 * 2, $ctr + .5);
				$tmp[] = $this->getButton($teacher, $sked, $start_date + 86400 * 3, $ctr + .5);
				$tmp[] = $this->getButton($teacher, $sked, $start_date + 86400 * 4, $ctr + .5);
				$tmp[] = $this->getButton($teacher, $sked, $start_date + 86400 * 5, $ctr + .5);
				$tmp[] = $this->getButton($teacher, $sked, $start_date + 86400 * 6, $ctr + .5);

				$calendar[] = $tmp;
			}

			$tmp = array();

			for ($ctr = 0; $ctr < 7; $ctr++)
				$tmp[] = date('n月j日', $table_date[$ctr]);

			$calendar[] = $tmp;

            // age
            list($m,$d,$y) = explode('/', $tutor->getDob());

            if (($m = (date('m') - $m)) < 0) {
                $y++;
            } elseif ($m == 0 && date('d') - $d < 0) {
                $y++;
            }

            $age = date('Y') - $y;

			return $this->render('student/ajax_tutor_sked.html.twig', array(
				'teacher' => $tutor,
                'age' => $age,
                'uid' => $teacher,
				'calendar' => $calendar,
				'dates' => date('n月j日', $week_start) . ' - ' . date('n月j日', $week_end),
				'time_from' => $time_from,
				'time_to' => $time_to,
                'offset' => $offset,
                'mode' => $mode
			));
		}
		else
			die;
	}

	/**
	 * @Route("member/student/ajax-reservation-confirm", name="ajax_reservation_confirm")
	 */
	public function ajaxReservationConfirm(Request $request)
	{
		$em = $this->getDoctrine()->getManager();
		$data = $request->request->all();

		$user_id = $this->getUser()->getId();
		$date = substr($data['date'],0,10);
		$time = substr($data['time'],0,10);
		$tutor = substr($data['tutor'],0,6);

		// get plan
		$student = $em->getRepository('AppBundle:Plan')->findOneBy(array('student_id' => $user_id));
        $plan = $student->getStudentId();

		if ($tutor != '')
		{
			$teacher = $em->getRepository('AppBundle:Teacher')->find($tutor);
			$points = $teacher->getPoints();
			$tutor = $teacher->getNickname();

			$points_fixed = 60;

			// weekend rate
			$weekend_add = 10;

			if (date('N',$date) > 5)
			{
				$points = $points + $weekend_add .' ('. $points .'+'. $weekend_add .')';
				$points_fixed = $points_fixed + $weekend_add .' ('. $points_fixed .'+'. $weekend_add .')';
			}

			$class = date('n月j日 G:i', ($date + ($time * 3600)));
		}

		return $this->render('student/ajax_reservation_confirm.html.twig', array(
			'class' => $class,
			'tutor' => $tutor,
			'uid' => $teacher,
            'plan' => $plan,
            'points' => $points,
            'points_fixed' => $points_fixed,
		));
	}

	/**
     * @Route("member/student/save-reservation", name="save_reservation")
     */
    public function saveReservationAction(Request $request)
    {
		$now = time();
        $em = $this->getDoctrine()->getManager();

		$user_id = $this->getUser()->getId();

        $data = $request->request->all();

        $tutor_id = substr($data['tutor'],0,10);
        $date = substr($data['date'],0,10);
        $time = substr($data['time'],0,10);
        $mode = substr($data['mode'],0,20);

        $student = $em->getRepository('AppBundle:Student')->find($user_id);
        $teacher = $em->getRepository('AppBundle:Teacher')->find($tutor_id);

        $points_student = $student->getPoints();

        // get lesson point cost
        if ($mode != 'ESL')
            $points_teacher = 60; // fixed points for non-esl
        else
            $points_teacher = $teacher->getPoints();

        // weekend rate
        $weekend_add = 10;

        if (date('N',$date) > 5)
            $points_teacher += $weekend_add;

        // check if student has enough points
        if ($points_student < $points_teacher)
        {
            $this->addFlash(
                'error',
                'このレッスンを予約するにはポイントが足りません。'
            );

            return $this->redirectToRoute('student');
        }

        // check if at least 5.5 minutes before start
        if (($now + 330) > ($date + ($time * 3600)))
        {
            //	$redirect->redirect("members.html","Please reserve at least 5 minutes before start of the lesson. ","warning");
            $this->addFlash(
                'error',
                '少なくとも5分の授業の開始前にご予約ください。'
            );
            return $this->redirectToRoute('student');
        }


        // check if student slot is already reserved
        $result = $em->getRepository('AppBundle:Reservation')->findBy(array(
            'student_id' => $user_id,
            'date_sked' => $date,
            'time_sked' => $time,
        ));

        if (count($result) > 0)
        {
            $this->addFlash(
                'error',
                'その時間は、すでにレッスン予約が入っています。'
            );
            //	$redirect->redirect("members.html","You already have a reservation on that time slot. ","warning");
            return $this->redirectToRoute('student');
        }

        // check if tutor slot is already reserved
        $result = $em->getRepository('AppBundle:Reservation')->findBy(array(
            'teacher_id' => $tutor_id,
            'date_sked' => $date,
            'time_sked' => $time,
        ));

        if (count($result) > 0)
        {
            $this->addFlash(
                'error',
                'その講師はすでにレッスン予約が入っています。更新ボタンを押してください。'
            );
            //	$redirect->redirect("members.html","Tutor is already booked on that time slot. Please refresh your browser.","warning");
            return $this->redirectToRoute('student');
        }

        // no errors, reserve
        // update tutor's sked record
        $sked = $em->getRepository('AppBundle:Sked')->findOneBy(array(
            'teacher_id' => $tutor_id,
            'date_sked' => $date,
        ));
        $time_sked = $sked->getTimeSked();

        $start = $time * 4;
        $replacement = '2';
        $time_sked = substr(substr_replace($time_sked,$replacement,$start,1),0,191);

        $sked->setTimeSked($time_sked);
        $em->flush();

        // insert reservation record
        $reservation = new Reservation();
        $reservation
            ->setTeacher($teacher)
            ->setStudent($student)
            ->setDateSked($date)
            ->setTimeSked($time)
            ->setMode($mode)
            ->setNotes('')
            ->setCommendation('')
            ->setComment('')
            ->setAbsent(0)
            ->setPointCost($points_teacher);
        $em->persist($reservation);
        $em->flush();

        // deduct points
        $student->setPoints($points_student - $points_teacher);
        $em->flush();

        // insert activity
        // eng
        $msg_eng = 'Reservation: '. $teacher->getNickname() .' on '. date('n月j日 G:i', ($date + ($time*3600))) .'. '. $points_teacher .' points used, '. ($points_student - $points_teacher) .' remaining.';
        $msg = '<a href="javascript:void()" title="'. $msg_eng .'"><b>予約:</b></a> ';
        $msg .= '<a rel="modal:open" href="reg-info.html?uid='. $tutor_id .'">'. str_replace('"','',$teacher->getNickname()) .'</a> 日時 ';
        $msg .= date('n月j日 G:i', ($date + ($time*3600))) .'.<br />'. $points_teacher .' points used, '. ($points_student - $points_teacher) .' remaining.';

        $user = $em->getRepository('AppBundle:User')->find($user_id);
        $user_teacher = $em->getRepository('AppBundle:User')->find($tutor_id);

        $activity = new Activity();
        $activity
            ->setTimeId($now)
            ->setMapUserId($user_teacher)
            ->setMapActivityBy($user)
            ->setMessage($msg);

        $em->persist($activity);
        $em->flush();

        // mail admin
        $body = 'An email reminder will be sent 5 minutes before the class starts. <br />
            Lesson Type: '. $mode .'. <br /><br />
            Always check your Schedule tab before the lesson just in case of last minute changes.
            ';

        $student_name = $user->getNickname();
        $student_email = $user->getEmail();

        $teacher_name = $teacher->getNickname();
        $message = \Swift_Message::newInstance()
            ->setSubject($student_name .' booked '. $teacher_name .' on '. date('F j, G:i',($date + ($time*3600))))
            ->setFrom('info@latorreenglish.com')
            ->setTo('webmaster@latorreenglish.com')
            ->setBody(
                $this->renderView(
                    'emails/reservation.html.twig',
                    array('content' => $body)
                ),
                'text/html'
            );
        $this->get('mailer')->send($message);


        // main student
		$body = '
            **********************************************************<br />
            このメールは送信専用メールアドレスから配信されています。<br />
            ご返信いただいてもお答えできませんのでご了承ください。<br />
            ご質問等のある方はお問い合わせフォームからご連絡ください。<br />
            **********************************************************<br />
            <br />
            <strong>'. $student_name .'様</strong><br /><br />
            
            Latorreをいつもご利用いただきありがとうございます。<br />
            レッスンの予約が完了しました。<br />
            
            <strong>講師　　 ：</strong> '. $teacher_name .'<br />
            <strong>予約日時 ：</strong> '. date('n月j日 G:i',($date + ($time*3600))) .'<br /><br />
            
            レッスン開始5分前にお知らせメールを送信致します。<br /><br />
            
            ----<br />
            Latorre English　カスタマーサポート<br />
            http://latorreenglish.com<br />
            ----
            ';

        $message = \Swift_Message::newInstance()
            ->setSubject('【Latorre English】レッスン予約完了（自動配信メール）'. date('n月j日 G:i',($date + ($time*3600))) .'（自動配信メール）')
            ->setFrom('noreply@latorreenglish.com')
            ->setTo($student_email)
            ->setBody(
                $this->renderView(
                    'emails/reservation.html.twig',
                    array('content' => $body)
                ),
                'text/html'
            );
        $this->get('mailer')->send($message);

        $this->addFlash(
            'notice',
            'レッスン予約が完了しました。最新のレッスンスケジュールをご確認ください。'
        );

        return $this->render('student/home.html.twig', array(
            'tab' => 'schedule'
        ));
	}

    /**
     * @Route("member/student/ajax-student-writing-confirm", name="ajax_student_writing_confirm")
     */
    public function ajaxStudentWritingConfirm(Request $request)
    {
        $data = $request->request->all();
        $article = $data['article'];
        $count = str_word_count($article);

        switch (true) {
            case ($count < 100):
                $points = 50;
                break;
            case ($count > 100 && $count <= 150):
                $points = 55;
                break;
            case ($count > 150 && $count <= 200):
                $points = 60;
                break;
            case ($count > 200):
                $points = 65;
                break;
        }

        return $this->render('student/ajax_writing_confirm.html.twig', array(
            'count' => $count,
            'points' => $points
        ));
    }
    /**
     * @Route("member/student/save-writing", name="save_writing")
     */
    public function saveWritingAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $usertype = $this->getUser()->getAccountType();
        $data = $request->request->all();
        $user_id = $this->getUser()->getId();
        $now = time();

        if ($usertype == 'student') {
            $type = $data['type'];
            $teacher_id = substr($data['tutor2'], 0, 2);
            $mode = $data['mode'];
            $article = $data['article'];
            $student_explanation = $data['student_explanation'];

            $student_user = $em->getRepository('AppBundle:User')->find($user_id);
            $teacher_user = $em->getRepository('AppBundle:User')->find($teacher_id);

            $count = str_word_count($article);

            switch (true) {
                case ($count < 100):
                    $points = 50;
                    break;
                case ($count > 100 && $count <= 150):
                    $points = 55;
                    break;
                case ($count > 150 && $count <= 200):
                    $points = 60;
                    break;
                case ($count > 200):
                    $points = 65;
                    break;
            }

            // get tutor
            $teacher = $em->getRepository('AppBundle:Teacher')->find($teacher_id);
            $teacher_nickname = $teacher->getNickname();

            // get points
            $student = $em->getRepository('AppBundle:Student')->find($user_id);

            // check if student has enough points
            if ($student->getPoints() < $points) {
                $this->addFlash(
                    'error',
                    'この文章を送信するにはポイントが足りません。'
                );
                return $this->redirectToRoute('student');
            }

            // no errors, submit
            // insert
            $writing = new Writing();
            $writing
                ->setTeacher($teacher)
                ->setStudent($student)
                ->setType($type)
                ->setArticle($article)
                ->setDateSubmitted($now)
                ->setPointCost($points)
                ->setMode($mode)
                ->setStudentExplanation($student_explanation)
                ->setReservationId(0)
                ->setInstructions('')
                ->setRemarks('')
                ->setStatus('Pending')
                ->setComments('')
                ->setDateOffered(0)
                ->setDateReviewed(0)
                ->setTeacherMessage('');
            $em->persist($writing);

            $student_points_remaining = $student->getPoints() - $points;

            // insert activity
            // eng
            $msg_eng = 'Writing Check: ' . $type . ' article submitted to ' . $teacher_nickname . ' for checking. ' . $points . ' points used, ' . ($student_points_remaining) . ' remaining.';
            $msg = '<a title="' . $msg_eng . '"><b>ライティングチェック： </b></a> 
            <a href="reg-info?uid=' . $teacher_id . '" rel="modal:open">' . $teacher_nickname . '</a>に提出された文章' . $type . '。
            <br />' . $points . '使用ポイント、' . ($student_points_remaining) . '残ポイント。';

            $activity = new Activity();
            $activity
                ->setTimeId($now)
                ->setMapUserId($teacher_user)
                ->setMapActivityBy($student_user)
                ->setMessage($msg);
            $em->persist($activity);

            // deduct points
            $student->setPoints($student_points_remaining);

            $em->flush();

            // mail
            $body = '<br />
                Subject: ' . $type . '. <br /><br />
                Check your Latorre account to read the article submitted to you for checking.
                ';

            // mail monitoring and tutor
            $email = $teacher_user->getEmail();

            $message = \Swift_Message::newInstance()
                ->setSubject($student_user->getNickname() . ' submitted an article to ' . $teacher_nickname . ' for checking.')
                ->setFrom(array('info@latorreenglish.com' => 'Latorre English'))
                ->setTo('webmaster@latorreenglish.com')
                ->setCc($email)
                ->setBody(
                    $this->renderView(
                        'emails/reservation.html.twig',
                        array('content' => $body)
                    ),
                    'text/html'
                );
            $this->get('mailer')->send($message);

            $body = '
                **********************************************************<br />
                このメールは送信専用メールアドレスから配信されています。<br />
                ご返信いただいてもお答えできませんのでご了承ください。<br />
                ご質問等のある方はお問い合わせフォームからご連絡ください。<br />
                **********************************************************<br />
                <br />
                <strong>' . $student->getFullname() . '様</strong><br /><br />
                
                Latorreをいつもご利用いただきありがとうございます。<br />
                
                <strong>講師   ：</strong> ' . $teacher_nickname . '<br />
                <strong>タイトル ：</strong> ' . $type . '<br /><br />
                
                ----<br />
                Latorre　カスタマーサポート<br />
                http://latorreenglish.com<br />
                ----
                ';

            // mail student
            $email = $student_user->getEmail();

            $message = \Swift_Message::newInstance()
                ->setSubject('【Latorre】' . $teacher_nickname . 'に提出された宿題' . $type)
                ->setFrom(array('info@latorreenglish.com' => 'Latorre English'))
                ->setTo($email)
                ->setBody(
                    $this->renderView(
                        'emails/reservation.html.twig',
                        array('content' => $body)
                    ),
                    'text/html'
                );
            $this->get('mailer')->send($message);

            $this->addFlash(
                'notice',
                $teacher_nickname .'に提出された宿題'. $type .'。'
            );
        }
        else
        {
            $this->addFlash(
                'error',
                'Error. Please try again.'
            );
        }

        return $this->render('student/home.html.twig', array(
            'tab' => 'writing'
        ));
    }

    /**
     * @Route("member/student/cancel-confirm", name="student_cancel_confirm")
     */
    public function studentCancelConfirmAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $user_id = $this->getUser()->getId();
        $data = $request->query->all();
        if (empty($data['uid']))
            $data = $request->request->all();

        $reservation_id = $data['uid'];

        // get plan
        $plan = $em->getRepository('AppBundle:Plan')->findOneBy(array('student_id' => $user_id), array('plan_id' => 'DESC'));
        $student = $plan->getMapStudentId();
        $points_student = $student->getPoints();
        $plan = $plan->getPlan();

        if (empty($data['submit']))
        {
            $reservation = $em->getRepository('AppBundle:Reservation')->findOneBy(array('student_id' => $user_id, 'reservation_id' => $reservation_id));

            $class = (($reservation->getTimeSked()) * 3600) + $reservation->getDateSked();
            $class = date('n月j日',$class) .' '. date('G:i',$class) .'-'. date('G:i',($class + 1500));

            return $this->render('student/reservation_cancel_confirm.html.twig', array(
                'reservation' => $reservation,
                'class' => $class
            ));
        }
        else
        {
            switch ($plan)
            {
                case '2000':
                case '1500':
                case '1000':
                    $plan_allowance = 300;
                    break;
                default:
                    $plan_allowance = 3600;
                    break;
            }

            // get reservation info
            $reservation = $em->getRepository('AppBundle:Reservation')->findOneBy(array(
                'student_id' => $user_id,
                'reservation_id' => $reservation_id
            ));

            if (($reservation->getDateSked() + ($reservation->getTimeSked()* 3600) < (time() + $plan_allowance)))
            {
                $this->addFlash(
                    'error',
                    $plan .'ポイントプラン。レッスン開始の'. ($plan_allowance/60) .'分前まで予約をキャンセルすることができます。'
                );

                return $this->redirectToRoute('student', array('tab' => 'schedule'));
            }
            else
            {
                $points = $reservation->getPointCost();
                $tutor_id = $reservation->getTeacherId();
                $teacher = $reservation->getMapTeacherId();
                $date_sked = $reservation->getDateSked();
                $time = $reservation->getTimeSked();

                // get tutor sked
                $schedule = $em->getRepository('AppBundle:Sked')->findOneBy(array('teacher_id' => $tutor_id, 'date_sked' => $date_sked));
                $time_sked = $schedule->getTimeSked();

                $start = $time * 4;

                $replacement = '1';
                $time_sked = substr(substr_replace($time_sked,$replacement,$start,1),0,191);

                // update sked
                $schedule->setTimeSked($time_sked);

                // delete reservation
                $em->remove($reservation);

                // refund points
                $student->setPoints($points_student + $points);
                $em->persist($student);

                // insert activity
                $activity = new Activity();

                // tutor nickname
                $teacher_nickname = $teacher->getNickname();

                $msg_eng = 'Cancelled Reservation: '. $teacher_nickname .' on '. date('n月j日 G:i', ($date_sked + ($time*3600) )) .'. '. $points .' points refunded, '. ($points_student + $points) .' remaining.';

                $msg = '<a href="#" title="'. $msg_eng .'"><b>予約のキャンセル: </b></a>
                 講師名<a href="reg-info?uid='. $tutor_id .'" rel="modal:open">'. $teacher_nickname .'</a>日時'.
                    date('n月j日 G:i', ($date_sked + ($time*3600) )) .'. <br />'. $points .' points refunded, '. ($points_student + $points) .' remaining.';

                $activity
                    ->setTimeId(time())
                    ->setMapUserId($teacher->getId())
                    ->setMapActivityBy($student->getId())
                    ->setMessage($msg);

                $em->persist($activity);
                $em->flush();

                // mail tutor
                $email = $teacher->getId()->getEmail();
                $body = 'Please verify your updated schedule.';

                $message = \Swift_Message::newInstance()
                    ->setSubject('A student cancelled a reservation: '. $teacher_nickname .', '.date('n月j日 G:i', ($date_sked + ($time*3600))))
                    ->setFrom(array('info@latorreenglish.com' => 'Latorre English'))
                    ->setTo($email)
                    ->setCc('webmaster@latorreenglish.com')
                    ->setBody(
                        $this->renderView(
                            'emails/reservation.html.twig',
                            array('content' => $body)
                        ),
                        'text/html'
                    );
                $this->get('mailer')->send($message);

                $body = '
                    **********************************************************<br />
                    このメールは送信専用メールアドレスから配信されています。<br />
                    ご返信いただいてもお答えできませんのでご了承ください。<br />
                    ご質問等のある方はお問い合わせフォームからご連絡ください。<br />
                    **********************************************************<br />
                    <br />
                    <strong>'. $student->getFullname() .'様</strong><br /><br />
                    
                    <strong>講師　 　：</strong>'. $teacher_nickname .'<br />
                    <strong>予約日時 ：</strong> '. date('n月j日 G:i', ($date_sked + ($time*3600) )) .'<br /><br />
                    ----<br />
                    Latorre　カスタマーサポート<br />
                    http://latorreenglish.com<br />
                    ----';

                // mail student
                $email = $student->getId()->getEmail();
                $message = \Swift_Message::newInstance()
                    ->setSubject('【DiscLEO】予約のキャンセル:講師名'. $teacher_nickname .'日時 '. date('n月j日 G:i', ($date_sked + ($time*3600) )))
                    ->setFrom(array('info@latorreenglish.com' => 'Latorre English'))
                    ->setTo($email)
                    ->setCc('webmaster@latorreenglish.com')
                    ->setBody(
                        $this->renderView(
                            'emails/reservation.html.twig',
                            array('content' => $body)
                        ),
                        'text/html'
                    );
            }

            $this->addFlash(
                'notice',
                'Class with '. $teacher_nickname .' on '. date('n月j日 G:i', ($date_sked + ($time*3600) )) .' cancelled.'
            );

            return $this->redirectToRoute('student', array('tab' => 'schedule'));
        }

        return $this->render('student/reservartion_cancel_confirm.html.twig', array(
            'reservation' => $reservation,
        ));
    }

	public function getButton($uid, $sked, $date, $time) {
		$now = time();

		switch (@$sked[$date .'-'. $time])
		{
			case 1:
			{
				if (($now + 300) < ($date + ($time * 3600)))
 					$btn = '<a href="#confirmDiv" onclick="confirmSlot('. $uid .','. $date .','. $time .')"><button type="button" class="btn btn-xs btn-success">OPEN</button></a>';
				else
					$btn = '<button type="button" class="btn btn-xs btn-danger">CLOSED</button>';
				break;
			}
			case 2:
			case 3:
			case 4:
			case 5:
			case 6:
				$btn = '<button type="button" class="btn btn-xs btn-danger">CLOSED</button>';
			break;
			default:
				$btn = '-';
		}
		return $btn;
	}

    public function getButtonStudent($user_id, $sked, $date, $time) {
        $em = $this->getDoctrine()->getManager();
        $now = time();
        switch (@$sked[$date .'-'. $time])
        {
            case '1':
                $tmp = $em->getRepository('AppBundle:Reservation')->findOneBy(array('student_id' => $user_id, 'date_sked' => $date, 'time_sked' => $time));
                $tutor_id = $tmp->getTeacherId();
                $commendation = $tmp->getCommendation();
                $points = $tmp->getPointCost();

                $teacher = $em->getRepository('AppBundle:User')->find($tutor_id);
                $tutor_uid = $teacher->getUid();
                $tutor_nickname = $teacher->getNickname();

                if ($commendation != 0)
                    $btn = '<a href="#" title="'. $tutor_nickname .' - '. $points .' points ">'. $tutor_uid .'</a>';
                else
                {
                    $btn = '<a href="reg-info?uid='. $tutor_id;
                    if ($now > ($date + ($time *3600)))
                        $btn .= '&date='. $date .'&time='. $time;
                    $btn .= '" rel="modal:open" title="'. $tutor_nickname .' - '. $points .' points">'. $tutor_uid .'</a>';
                }
                break;
            default:
                $btn = '-';
        }

        return $btn;
    }
}