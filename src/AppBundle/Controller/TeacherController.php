<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Sked;
use AppBundle\Entity\Activity;
use AppBundle\Form\ReservationNotesType;

class TeacherController extends Controller
{
    /**
     * @Route("/member/teacher", name="teacher")
     */
    public function teacher()
    {
        if (date('G') < 12) {
            $time_from = 0;
            $time_to = 11;
        } else {
            $time_from = 12;
            $time_to = 23;
        }

        return $this->render('teacher/home.html.twig', array(
            'time_from' => $time_from,
            'time_to' => $time_to
        ));
    }

    /**
     * @Route("/member/teacher/open-slots", name="ajax_teacher_open_slots")
     */
    public function openSlots(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user_id = $this->getUser()->getId();
        $user = $em->getRepository('AppBundle:User')->find($user_id);
        $teacher = $em->getRepository('AppBundle:Teacher')->find($user_id);

        // if submitted
        $data = $request->request->all();

        if ($data != null)
        {
            $date_current = time();
            $date_from = $data['date_from'];
            $date_to = $data['date_to'];
            if ($date_to < $date_from)
                $date_to = $date_from;

            $time_from = $data['time_from'];
            $time_to = $data['time_to'];
            if ($time_to < $time_from)
                $time_to = $time_from;

            $date_ctr = $date_from;

            $error = false;
            while ($date_ctr <= $date_to) {
                if ($time_from == floor($time_from))
                    $time_from_min = 0;
                else
                    $time_from_min = 30;
                
                if ($time_to == floor($time_to))
                    $time_to_min = 0;
                else
                    $time_to_min = 30;

                $start_time = mktime($time_from, $time_from_min, 0, date('n', $date_from), date('j', $date_from), date('Y', $date_from));

                // don't allow past starting time
                if ($start_time < $date_current) {
                    $this->addFlash(
                        'error',
                        'You can not open slots for hours past.'
                    );
                    $error = true;
                }

                // at least 15mins before next slot
                if (($date_current + 1800) > $start_time) {
                    $this->addFlash(
                        'error',
                        'You can not open slots with less than 30 minutes from now.'
                    );
                    $error = true;
                }

                if ($error == false) {
                    // all clear, save to database

                    // CHECK IF SKED DATE RECORD EXISTS
                    $sked = $em->getRepository('AppBundle:Sked')->findOneBy(array('teacher_id' => $user_id, 'date_sked' => $date_ctr));

                    // IF EXISTS, UPDATE
                    if (count($sked) > 0) {
                        $tmp_sked = $sked->getDateSked();
                        $tmp_time = $sked->getTimeSked();

                        // check for 102 codes
                        $tmp_check = explode(':', $tmp_time);
                        if (in_array('3', $tmp_check)) {
                            $this->addFlash(
                                'error',
                                date('F j', $tmp_sked) . " has 706 codes, updating of today's schedule disabled. Please contact Admin if you want to remove them."
                            );
                            return $this->redirectToRoute('teacher_open_slots');
                        }

                        // NO RESERVATION CONFLICTS, UPDATE
                        $start = $time_from * 4;
                        $length = (($time_to - $time_from) * 4) + 2;
                        $replacement = '';
                        for ($ctr = 0; $ctr < ($length / 2); $ctr++) {
                            $code = substr($tmp_time, ($start + ($ctr * 2)), 1);
                            $allowed = array(0, 4, 5);
                            // CLOSED SLOTS ONLY
                            if (in_array($code, $allowed))
                                $replacement .= '1:';
                            else
                                $replacement .= substr($tmp_time, ($start + ($ctr * 2)), 2);
                        }
                        $time_sked = substr(substr_replace($tmp_time, $replacement, $start, $length), 0, 191);

                        $sked->setTimeSked($time_sked);
                        $em->flush();
                    } else {
                        // ELSE INSERT
                        $time_sked = '0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0:0';
                        $start = $time_from * 4;
                        $length = (($time_to - $time_from) * 4) + 2;
                        $replacement = '';
                        for ($ctr = 0; $ctr < ($length / 2); $ctr++)
                            $replacement .= '1:';

                        $time_sked = substr(substr_replace($time_sked, $replacement, $start, $length), 0, 191);

                        $sked = new Sked();
                        $sked
                            ->setTeacher($teacher)
                            ->setDateSked($date_ctr)
                            ->setTimeSked($time_sked)
                            ->setAbsent(0);

                        $em->persist($sked);
                        $em->flush();

                    }

                }
                $date_ctr += 86400;
            }

            if ($error == false)
            {
                $activity = new Activity();

                // insert activity
                $msg = '<b>Opened Slots:</b> ' . date('n月j日', $date_from) . ' - ' . date('n月j日', $date_to) . ', ' . date('G:i', (mktime(0, 0, 0, date('n'), date('j'), date('Y')) + $time_from * 3600)) . ' - ' . date('G:i', (mktime(0, 0, 0, date('n'), date('j'), date('Y')) + $time_to * 3600 + 1500)) . '.';

                $activity
                	->setTimeId(time())
                    ->setMapUserId($user)
                    ->setMapActivityBy($user)
                    ->setMessage($msg);
                $em->persist($activity);
                $em->flush();

                $this->addFlash(
                    'notice',
                    $msg
                );

                $nickname = $this->getUser()->getNickname();
                $message = \Swift_Message::newInstance()
                    ->setSubject($nickname . ' updated Open Slots')
                    ->setFrom('info@latorreenglish.com')
                    ->setTo('webmaster@latorreenglish.com')
                    ->setBody(
                        $this->renderView(
                            'emails/default.html.twig',
                            array('content' => $msg)
                        ),
                        'text/html'
                    );
                $this->get('mailer')->send($message);
            }
        }

        // create form

        return $this->render('teacher/ajax_open_slots.html.twig');
    }

    /**
     * @Route("/member/teacher/close-slots", name="ajax_teacher_close_slots")
     */
    public function closeSlots(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user_id = $this->getUser()->getId();
        $user = $em->getRepository('AppBundle:User')->find($user_id);

        // if submitted
        $data = $request->request->all();

        if ($data != null)
        {
            $date_current = time();
            $date_from = $data['date_from'];
            $date_to = $data['date_to'];
            if ($date_to < $date_from)
                $date_to = $date_from;

            $time_from = $data['time_from'];
            $time_to = $data['time_to'];
            if ($time_to < $time_from)
                $time_to = $time_from;

            $date_ctr = $date_from;

            $error = false;
            while ($date_ctr <= $date_to)
            {
                if ($time_from == floor($time_from))
                    $time_from_min = 0;
                else
                    $time_from_min = 30;
                if ($time_to == floor($time_to))
                    $time_to_min = 0;
                else
                    $time_to_min = 30;
                $start_time = mktime($time_from,$time_from_min,0,date('n',$date_from),date('j',$date_from),date('Y',$date_from));

                // don't allow past starting time
                if ($start_time < $date_current)
                {
                    $this->addFlash(
                        'error',
                        'You can not close slots for hours past.'
                    );
                    $error = true;
                }

                // CHECK IF SKED DATE RECORD EXISTS
                $sked = $em->getRepository('AppBundle:Sked')->findOneBy(array('teacher_id' => $user_id, 'date_sked' => $date_ctr));

                // IF EXISTS, Validate, UPDATE
                if(count($sked)>0)
                {
                    $tmp_sked = $sked->getDateSked();
                    $tmp_time = $sked->getTimeSked();

                    // don't allow less than 3 hrs before next class
                    $tmp_time2 = explode(':',$tmp_time);

                    // check for 102
                    if (in_array('3',$tmp_time2))
                    {
                        $this->addFlash(
                            'error',
                            date('F j',$tmp_sked) ." has 102 codes, updating today's schedule disabled. Please contact Admin if you want to remove them."
                        );
                        $error = true;
                    }

                    $ctr_array = 0;
                    $earliest = 0;
                    for ($ctr_time = 0; $ctr_time < 24; $ctr_time += 0.5)
                    {
                        // get earliest open time
                        if ($tmp_time2[$ctr_array] != 0)
                        {
                            $earliest = $ctr_time;
                            $ctr_time = 25; // end loop
                        }
                        $ctr_array ++;
                    }

                    $close_from = mktime($time_from,$time_from_min,0,date('n',$date_ctr),date('j',$date_ctr),date('Y',$date_ctr));
                    $close_to = mktime($time_to,$time_to_min,0,date('n',$date_ctr),date('j',$date_ctr),date('Y',$date_ctr));

                    $earliest = ($tmp_sked + ($earliest * 3600) - 10800);

                    if (($close_to > $earliest) && time() >= ($close_to - 10800) || ($close_from > $earliest) && time() >= ($close_from - 10800))
                    {
                        $this->addFlash(
                            'error',
                            'You may not close slots within less than 3 hours before the first OPEN or RESERVED slot starts. Please contact our monitoring team to request 703 codes for the slots concerned.'
                        );
                        $error = true;
                    }

                    // CHECK IF NEW SKED CANCELS ANY RESERVATION FIRST
                    $start = $time_from * 4;
                    $length = (($time_to - $time_from) * 4) + 2;
                    $range = substr($tmp_time,$start,$length);
                    if (strpos($range,'2'))
                    {
                        $this->addFlash(
                            'error',
                            'A reservation exists within the specified time range. Please contact an Admin if you wish to transfer the reservations for the slots concerned.'
                        );
                        $error = true;
                    }

                    // ALL CLEAR UPDATE
                    $start = $time_from * 4;
                    $length = (($time_to - $time_from) * 4) + 2;
                    $replacement = '';
                    for ($ctr = 0; $ctr < ($length/2); $ctr++)
                        $replacement .= '0:';
                    $time_sked = substr(substr_replace($tmp_time,$replacement,$start,$length),0,191);

                    $sked->setTimeSked($time_sked);
                    $em->flush();
                }
                $date_ctr += 86400;
            }

            // insert activity
            if ($error == false)
            {
                $activity = new Activity();

                // insert activity
                $msg = '<b>Closed Slots:</b> '. date('n月j日',$date_from) .' - '. date('n月j日', $date_to) .', '. date('G:i',(mktime(0,0,0,date('n'),date('j'),date('Y')) + $time_from*3600)) .' - '. date('G:i',(mktime(0,0,0,date('n'),date('j'),date('Y')) + $time_to*3600)) .'.';

                $activity->setTimeId(time())
                    ->setUserId($user_id)
                    ->setMapUserId($user)
                    ->setActivityBy($user_id)
                    ->setMapActivityBy($user)
                    ->setMessage($msg);
                $em->persist($activity);
                $em->flush();

                $this->addFlash(
                    'notice',
                    $msg
                );

                $nickname = $this->getUser()->getNickname();
                $message = \Swift_Message::newInstance()
                    ->setSubject($nickname . ' updated Closed Slots')
                    ->setFrom('info@latorreenglish.com')
                    ->setTo('webmaster@latorreenglish.com')
                    ->setBody(
                        $this->renderView(
                            'emails/default.html.twig',
                            array('content' => $msg)
                        ),
                        'text/html'
                    );
                $this->get('mailer')->send($message);
            }
        }

        return $this->render('teacher/ajax_close_slots.html.twig');
    }

    /**
     * @Route("/member/teacher/ajax-schedule", name="ajax_teacher_schedule")
     */
    public function ajaxSchedule(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $today = mktime(0, 0, 0, date("n"), date("j"), date("Y"));

        $data = $request->request->all();
        $teacher = $this->getUser()->getId();

        $week = substr($data['week'], 0, 3);

        if (date('G') < 12) {
            $time_from = 0;
            $time_to = 11;
        } else {
            $time_from = 12;
            $time_to = 23;
        }

        if ($data['time_from'] !== null)
            $time_from = substr($data['time_from'], 0, 2);
        if ($data['time_to'] !== null)
            $time_to = substr($data['time_to'], 0, 2);

        if ($week == '')
            $week = 0;

		// start dates set to today
        $start_date = mktime(0, 0, 0, date("n"), date("j"), date("Y"));
        $start_date = $start_date + ((86400 * 7) * $week);
        $end_date = $start_date + (86400 * 6);
        $week_start = $start_date;
        $week_end = $start_date + (86400 * 6);

        // SKEDs = $sked
        $repository = $this->getDoctrine()
            ->getRepository('AppBundle:Sked');
        $query = $repository->createQueryBuilder('s')
            ->where('s.teacher_id = '. $teacher .' AND s.date_sked >= '. $start_date .' AND s.date_sked <= '. $end_date)
            ->getQuery();
        $results = $query->getResult();

        $sked = array();
        foreach ($results as $sked_info) {
            $date_sked = $sked_info->getDateSked();
            $time_sked = $sked_info->getTimeSked();

            $time_sked = explode(':', $time_sked);

            $ctr_array = 0;
            for ($ctr_time = 0; $ctr_time < 24; $ctr_time += 0.5) {
                // flag slots
                if ($time_sked[$ctr_array] != 0)
                    $sked [$date_sked . '-' . $ctr_time] = $time_sked[$ctr_array];

                $ctr_array++;
            }
        }

        for ($ctr = 0; $ctr <= 6; $ctr++)
            $table_date[$ctr] = $start_date + (86400 * $ctr);

        // RESERVATIONS = $reservations
        $query = $em->createQuery(
            'SELECT r
                FROM AppBundle:Reservation r
                WHERE r.teacher_id = '. $teacher .' AND r.date_sked >= '. $today .' ORDER BY ((r.time_sked*60)+r.date_sked)'
        );
        $results = $query->getResult();
        $reservations = array();

        foreach($results as $tmp) {

            $reservation_id = $tmp->getReservationId();
            $time = date('M j G:i',($tmp->getTimeSked() * 3600) + $tmp->getDateSked());
            $student_id = $tmp->getStudentId();
            $points = $tmp->getPointCost();

            // student name
            $student = $em->getRepository('AppBundle:User')->find($student_id);

            $studentName = $student->getNickname();

            // check if homework already set for class
/*            $homework = $em->getRepository('AppBundle:Users')->find($student_id);
            $query = 'SELECT * FROM #__tutorial_writing WHERE reservation_id = ' . $sked['reservation_id'];
            $db->setQuery($query);
            $tmp = $db->loadObjectList();
            if (count($tmp) > 0)
                echo 'Offered';
*/
            $reservations[] = array('reservation_id' => $reservation_id, 'time' => $time, 'student_id' => $student_id, 'points' => $points, 'student_name' => $studentName);
        }

        $calendar = array();
        $tmp = array();

        $tmp[] = '';
		for ($ctr = 0; $ctr < 7; $ctr++)
        {
            switch (date('D', $table_date[$ctr]))
            {
                case 'Sun':
                     $tmp[] = '<span style="color:#0044cc">SUN</span>';
                    break;
                case 'Mon':
                    $tmp[] = 'MON';
                    break;
                case 'Tue':
                    $tmp[] = 'TUE';
                    break;
                case 'Wed':
                    $tmp[] = 'WED';
                    break;
                case 'Thu':
                    $tmp[] = 'THU';
                    break;
                case 'Fri':
                    $tmp[] = 'FRI';
                    break;
                case 'Sat':
                    $tmp[] = '<span style="color:#238bc0">SAT</span>';
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
            $tmp[] = array('type' => date('G:00', mktime($ctr, 0, 0, 0, 0, 0)));
            $tmp[] = $this->getButton($teacher, $sked, $start_date, $ctr);
            $tmp[] = $this->getButton($teacher, $sked, $start_date + 86400, $ctr);
            $tmp[] = $this->getButton($teacher, $sked, $start_date + 86400 * 2, $ctr);
            $tmp[] = $this->getButton($teacher, $sked, $start_date + 86400 * 3, $ctr);
            $tmp[] = $this->getButton($teacher, $sked, $start_date + 86400 * 4, $ctr);
            $tmp[] = $this->getButton($teacher, $sked, $start_date + 86400 * 5, $ctr);
            $tmp[] = $this->getButton($teacher, $sked, $start_date + 86400 * 6, $ctr);

            $calendar[] = $tmp;

            $tmp = array();
            $tmp[] = array('type' => date('G:30', mktime($ctr, 0, 0, 0, 0, 0)));
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

        return $this->render('teacher/ajax_schedule.html.twig', array(
            'calendar' => $calendar,
            'reservations' => $reservations,
            'dates' => date('n月j日', $week_start) .' - '. date('n月j日', $week_end),
            'week' => $week,
            'time_from' => $time_from,
            'time_to' => $time_to,
        ));
    }
    
    /**
     * @Route("/member/teacher/class/{uid}", name="teacher_class")
     */
    public function teacherClass($uid) {
    	return $this->render($view);
    }

    /**
     * @Route("/member/teacher/lesson-notes/{uid}", name="teacher_lesson_notes")
     */
    public function lessonNotes(Request $request, $uid){
    	$user = $this->getUser();
		$role = $user->getAccountType();
		$em = $this->getDoctrine()->getManager();
    	
		$data = $request->request->all();
		
		
    	// TUTORS ONLY
    	if ($role == 'teacher')
    	{
    		$today = mktime(0, 0, 0, date("n")  , date("j"), date("Y"));
    		$now = time();
    	
    		$reservation = $em->getRepository('AppBundle:Reservation')->find($uid);
    		
    		if ($reservation == null) {
    			$this->addFlash('warning', 'Unknown class.');
    			
    			$this->redirectToRoute('homepage');
    		}
    	
    		$form = $this->createForm(ReservationNotesType::class, $reservation);

    		$class = date('n月j日 G:i',($reservation->getDateSked() + ($reservation->getTimeSked() * 3600)));

    		$form->handleRequest($request);
    		
    		if ($form->isSubmitted() && $form->isValid()) {
    			// insert activity
    			$activity = new Activity();
    			
    			$msg_eng = 'Lesson Notes Added';
    			$msg = '<a data-toggle="tooltip" title="'. $msg_eng .'"><b>レッスンメモは追加されました：</b></a> '. $class .'クラス.';
    			
    			$activity
    				->setTimeId($now)
    				->setMapActivityBy($user)
    				->setMapUserId($user)
    				->setMessage($msg)
    			;
    			
    			$em->persist($activity);
    			
    			// save changes
    			$em->flush();
    			
    			$this->addFlash('notice', 'Lesson notes updated.');
    		}
    		
    		return $this->render('teacher/lesson_notes.html.twig', array(
    			'form' => $form->createView(),
    			'item' => $reservation,
    			'class' => $class,
    		));
    	}
    	else 
    		die;
    }
    
    public function getButton($teacher, $sked, $date, $time)
    {
        $now = time();

        switch (@$sked[$date . '-' . $time]) {
            case '1': {
                if (($now - 330) < ($date + ($time * 3600)))
                    $btn = array('type' => 'open');
                else
                    $btn = array('type' => 'closed');
                break;
            }
            case '2':
                $em = $this->getDoctrine()->getManager();
                $reservation = $em->getRepository('AppBundle:Reservation')->findOneBy(array('teacher_id' => $teacher, 'date_sked' => $date, 'time_sked' => $time));
                $student = $reservation->getStudent()->getUser();

                if ($now < ($date + ($time * 3600) + 1500)) {
                    $btn = array(
                    	'type' => 'reg_info',
                    	'details' => array(
                    		'student_id' => $reservation->getStudentId(),
                    		'tooltip' => $student->getNickname() .' - '. $reservation->getPointCost(),
                    		'student_uid' => $student->getUid()
                	));
                } else {
                    // past reservations
                	$btn = array(
                		'type' => 'feedback',
                		'details' => array(
                			'reservation_id' => $reservation->getReservationId(),
                			'nickname' => $student->getNickname(),
                			'student_uid' => $student->getUid(),
                			'commendation' => $reservation->getCommendation()
                		)
                	);
                }
                break;
            case '3':
                $btn = '<img src="images/code-706.png" alt="706" /></a>';
                break;
            case '4':
                $btn = '<img src="images/code-709.png" alt="709" />';
                break;
            case '5':
                $btn = '<img src="images/code-703.png" alt="703" />';
                break;
            case '9':
                $btn = '<img src="images/code-102x.png" alt="102x" />';
                break;
            default:
                $btn = array('type' => '-');
        }

        return $btn;
    }
}
