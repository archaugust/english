<?php
namespace ContentBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class ContentFrontController extends Controller {
	
	public function pageAction($id) {
		$content = $this->getDoctrine()->getRepository('ContentBundle:Content')->find($id)->getContent();
		
		return $this->render('content/page.html.twig', array(
				'content' => $content
		));
	}
}