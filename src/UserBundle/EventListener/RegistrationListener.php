<?php
namespace UserBundle\EventListener;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Listener responsible to change the redirection at the end of the password resetting
 */
class RegistrationListener implements EventSubscriberInterface
{
    private $router;

    public function __construct(UrlGeneratorInterface $router)
    {
        $this->router = $router;
    }

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            FOSUserEvents::REGISTRATION_SUCCESS => array('onRegistrationSuccess',-10),
        );
    }

    public function onRegistrationSuccess(FormEvent $event)
    {
        $request = Request::createFromGlobals();;
        $data = $request->request->get('fos_user_registration_form');

        if ($data['account_type'] == 'teacher')
            $url = $this->router->generate('register_teacher_2');
        else
            $url = $this->router->generate('register_student');

        $event->setResponse(new RedirectResponse($url));
    }
}