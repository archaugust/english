<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Student;
use AppBundle\Entity\Teacher;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Form\RegistrationType;
use AppBundle\Form\TeacherType;
use AppBundle\Form\TeacherProfileType;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use Symfony\Component\Filesystem\Filesystem;

class RegistrationController extends Controller
{
    /**
     * @Route("/register/{account_type}")
     */
    public function registerMemberAction(Request $request, $account_type)
    {
        /** @var $formFactory \FOS\UserBundle\Form\Factory\FactoryInterface */
        $formFactory = $this->get('fos_user.registration.form.factory');
        /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
        $userManager = $this->get('fos_user.user_manager');
        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');

        $user = $userManager->createUser();
        $user->setEnabled(true);

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        $form = $formFactory->createForm();
        $form->setData($user);

        $form->get('date_registered')->setData(time());
        $form->get('account_type')->setData($account_type);
        $form->get('uid')->setData('-');
        $form->get('username')->setData(time());
        $form->get('age')->setData(0);

        return $this->render('default/register_member.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/register-teacher-2", name="register_teacher_2")
     */
    public function registerTeacher2Action(Request $request)
    {
        $teacher = new Teacher();
        $form = $this->createForm(TeacherType::class, $teacher);
        $form->handleRequest($request);
        $username = $this->get('session')->get('username');
        if ($username == '') die();

        $teacher->setPoints(50);

        if ($form->isSubmitted() && $form->isValid()) {

            $file = $teacher->getAvatar();
            $fileName = $this->get('app.avatar_uploader')->upload($file);

            $rootDir = $this->container->getParameter('kernel.root_dir');

            $fs = new Filesystem();
            if ($fs->exists($rootDir .'/../web/images/avatar'))
                $avatarDir = $rootDir .'/../web/images/avatar';
            else
                $avatarDir = $rootDir .'/../public_html/images/avatar';

            $this->smart_resize_image($avatarDir .'/'. $fileName, null, 400, 400, false, 'file', true, false, 80);

            $data = $request->request->get('teacher');

            $em = $this->getDoctrine()->getManager();

            $user = $em->getRepository('AppBundle:User')->findOneBy(array('username' => $username));

            $id = $user->getId();
            $uid = 'T-'. str_pad($id, 8, '0', STR_PAD_LEFT);
            $nickname = $user->getNickname();
            $email = $user->getEmail();
            $age = $this->age_from_dob($data['dob']);

            $user->setUsername($email);
            $user->setAge($age);
            $user->setUid($uid);

            $teacher
                ->setId($id)
                ->setUser($user)
                ->setAvatar($fileName)
                ->setNickname($nickname)
               ;

            $em->persist($teacher);
            $em->flush();

            $this->addFlash(
                'notice',
                'Application complete.'
            );

            return $this->render('default/register_teacher_end.html.twig');
        }

        return $this->render('default/register_teacher.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/register-student", name="register_student")
     */
    public function registerStudentAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $student = new Student();
        $username = $this->get('session')->get('username');

        $user = $em->getRepository('AppBundle:User')->findOneBy(array('username' => $username));

        $id = $user->getId();
        $uid = 'S-'. str_pad($id, 8, '0', STR_PAD_LEFT);

        $email = $user->getEmail();
        $nickname = $user->getNickname();
        $user->setUsername($email);
        $user->setUid($uid);

        $student
            ->setId($id)
            ->setDob('')
            ->setUser($user)
            ->setPoints(0)
            ->setFullname($nickname);

        $em->persist($student);
        $em->flush();

        // replace this example code with whatever you need
        return $this->render('default/register_student.html.twig');
    }

    /**
     * @Route("/member/teacher/profile-edit", name="teacher_profile_edit")
     */
    public function editTeacherProfileAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user_id = $this->getUser()->getId();
        $teacher = $em->getRepository('AppBundle:Teacher')->find($user_id);
        $avatar = $teacher->getAvatar();
        $teacher->setAvatar('');

        $form = $this->createForm(TeacherProfileType::class, $teacher);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $request->request->get('teacher_profile');
            $file = $teacher->getAvatar();
            if ($file != null)
            {
                $rootDir = $this->container->getParameter('kernel.root_dir');

                $fs = new Filesystem();
                if ($fs->exists($rootDir .'/../web/images/avatar'))
                    $avatarDir = $rootDir .'/../web/images/avatar';
                else
                    $avatarDir = $rootDir .'/../public_html/images/avatar';

                if ($avatar != '')
                    $fs->remove($avatarDir .'/'. $avatar);

                $fileName = $this->get('app.avatar_uploader')->upload($file);

                $this->smart_resize_image($avatarDir .'/'. $fileName, null, 400, 400, false, 'file', true, false, 80);

                $teacher->setAvatar($fileName);
            }
            else
                $teacher->setAvatar($avatar);

            $user = $em->getRepository('AppBundle:User')->find($user_id);
            $user->setNickname($data['nickname']);

            $age = $this->age_from_dob($data['dob']);
            $user->setAge($age);

            $em->persist($teacher);
            $em->flush();

            $this->addFlash(
                'notice',
                'Profile updated.'
            );

            return $this->redirectToRoute('teacher_profile_edit');
        }

        return $this->render('teacher/profile_edit.html.twig', array(
            'form' => $form->createView(),
            'avatar' => $avatar,
        ));
    }

    /**
     * @Route("/member/student/profile-edit", name="student_profile_edit")
     */
    public function editStudentProfileAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $user_id = $user->getId();
        $student = $em->getRepository('AppBundle:Student')->find($user_id);

        $data = $request->request->all();
        $email = @$data['email'];

        if (!empty($email)) {
            if ($email != $user->getEmail())
            {
                $check = $em->getRepository('AppBundle:User')->findBy(array('email'=>$email));

                if (count($check) > 0)
                {
                    $this->addFlash(
                        'error',
                        'Email address already in use by another account.'
                    );
                    return $this->redirectToRoute('student_profile_edit');
                }
            }
            $age = $this->age_from_dob($data['dob']);
            $user
                ->setAge($age)
                ->setEmail($email);
            $em->persist($user);


            $em->flush();

            $this->addFlash(
                'notice',
                'Profile updated.'
            );

            return $this->redirectToRoute('student_profile_edit');
        }

        $info = array(
            'email' => $user->getEmail(),
            'nickname' => $user->getNickname(),
            'dob' => $student->getDob()
        );

        return $this->render('student/profile_edit.html.twig', array(
            'student' => $info,
        ));
    }

    public function smart_resize_image($file,
                                $string             = null,
                                $width              = 0,
                                $height             = 0,
                                $proportional       = false,
                                $output             = 'file',
                                $delete_original    = true,
                                $use_linux_commands = false,
                                $quality = 80
    ) {
        if ( $height <= 0 && $width <= 0 ) return false;
        if ( $file === null && $string === null ) return false;

        # Setting defaults and meta
        $info                         = $file !== null ? getimagesize($file) : getimagesizefromstring($string);
        $image                        = '';
        $final_width                  = 0;
        $final_height                 = 0;
        list($width_old, $height_old) = $info;
        $cropHeight = $cropWidth = 0;

        # Calculating proportionality
        if ($proportional) {
            if      ($width  == 0)  $factor = $height/$height_old;
            elseif  ($height == 0)  $factor = $width/$width_old;
            else                    $factor = min( $width / $width_old, $height / $height_old );

            $final_width  = round( $width_old * $factor );
            $final_height = round( $height_old * $factor );
        }
        else {
            $final_width = ( $width <= 0 ) ? $width_old : $width;
            $final_height = ( $height <= 0 ) ? $height_old : $height;
            $widthX = $width_old / $width;
            $heightX = $height_old / $height;

            $x = min($widthX, $heightX);
            $cropWidth = ($width_old - $width * $x) / 2;
            $cropHeight = ($height_old - $height * $x) / 2;
        }

        # Loading image to memory according to type
        switch ( $info[2] ) {
            case IMAGETYPE_JPEG:  $file !== null ? $image = imagecreatefromjpeg($file) : $image = imagecreatefromstring($string);  break;
            case IMAGETYPE_GIF:   $file !== null ? $image = imagecreatefromgif($file)  : $image = imagecreatefromstring($string);  break;
            case IMAGETYPE_PNG:   $file !== null ? $image = imagecreatefrompng($file)  : $image = imagecreatefromstring($string);  break;
            default: return false;
        }


        # This is the resizing/resampling/transparency-preserving magic
        $image_resized = imagecreatetruecolor( $final_width, $final_height );
        if ( ($info[2] == IMAGETYPE_GIF) || ($info[2] == IMAGETYPE_PNG) ) {
            $transparency = imagecolortransparent($image);
            $palletsize = imagecolorstotal($image);

            if ($transparency >= 0 && $transparency < $palletsize) {
                $transparent_color  = imagecolorsforindex($image, $transparency);
                $transparency       = imagecolorallocate($image_resized, $transparent_color['red'], $transparent_color['green'], $transparent_color['blue']);
                imagefill($image_resized, 0, 0, $transparency);
                imagecolortransparent($image_resized, $transparency);
            }
            elseif ($info[2] == IMAGETYPE_PNG) {
                imagealphablending($image_resized, false);
                $color = imagecolorallocatealpha($image_resized, 0, 0, 0, 127);
                imagefill($image_resized, 0, 0, $color);
                imagesavealpha($image_resized, true);
            }
        }
        imagecopyresampled($image_resized, $image, 0, 0, $cropWidth, $cropHeight, $final_width, $final_height, $width_old - 2 * $cropWidth, $height_old - 2 * $cropHeight);


        # Taking care of original, if needed
        if ( $delete_original ) {
            if ( $use_linux_commands ) exec('rm '.$file);
            else @unlink($file);
        }

        # Preparing a method of providing result
        switch ( strtolower($output) ) {
            case 'browser':
                $mime = image_type_to_mime_type($info[2]);
                header("Content-type: $mime");
                $output = NULL;
                break;
            case 'file':
                $output = $file;
                break;
            case 'return':
                return $image_resized;
                break;
            default:
                break;
        }

        # Writing image according to type to the output destination and image quality
        switch ( $info[2] ) {
            case IMAGETYPE_GIF:   imagegif($image_resized, $output);    break;
            case IMAGETYPE_JPEG:  imagejpeg($image_resized, $output, $quality);   break;
            case IMAGETYPE_PNG:
                $quality = 9 - (int)((0.9*$quality)/10.0);
                imagepng($image_resized, $output, $quality);
                break;
            default: return false;
        }

        return true;
    }


    public function age_from_dob($dob) {

        list($m,$d,$y) = explode('/', $dob);

        if (($m = (date('m') - $m)) < 0) {
            $y++;
        } elseif ($m == 0 && date('d') - $d < 0) {
            $y++;
        }

        return date('Y') - $y;
    }

}
