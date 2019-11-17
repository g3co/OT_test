<?php

namespace AppBundle\Controller;

use AppBundle\Service\Interfaces\IJokeApi;
use AppBundle\Service\Interfaces\IMailer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request, IJokeApi $api)
    {
        $formHandleUrl = $this->generateUrl('form_handler');

        return $this->render('default/index.html.twig', [
            'form_handler' => $formHandleUrl,
            'categories' => $api->getCategories()
        ]);
    }

    /**
     * Handle form data
     * @Route("/form-handler", name="form_handler", methods={"POST"})
     * @param Request $request
     * @param IJokeApi $api
     * @param IMailer $mailer
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function formAction(Request $request, IJokeApi $api, IMailer $mailer)
    {
        $email = $request->get('email');
        $category = $request->get('category');

        $joke = $api->getJoke($category);

        $message = \Swift_Message::newInstance()
            ->setSubject('Случайная шутка из ' . $category)
            ->setFrom('admin@myobr.ru')
            ->setTo($email)
            ->setBody($joke);
        $this->get('mailer')->send($message);

        $this->addFlash(
            'notice',
            'Joke has sent to ' . $email
        );

        return $this->redirectToRoute('homepage', [], 301);
    }
}
