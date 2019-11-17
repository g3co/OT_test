<?php

namespace AppBundle\Controller;

use AppBundle\Service\Interfaces\IJokeApi;
use AppBundle\Service\Interfaces\IJokeSaver;
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
     * @param IJokeSaver $saver
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function formAction(Request $request, IJokeApi $api, IJokeSaver $saver)
    {
        $email = $request->get('email');
        if (!$email) {
            return $this->returnToMainPage('Error: empty email');
        }

        $category = $request->get('category');
        if (!$category) {
            return $this->returnToMainPage('Error: empty category');
        }

        $joke = $api->getJoke($category);

        $message = \Swift_Message::newInstance()
            ->setSubject('Случайная шутка из ' . $category)
            ->setFrom('admin@myobr.ru')
            ->setTo($email)
            ->setBody($joke);
        $this->get('mailer')->send($message);

        $saver->save($joke);

        return $this->returnToMainPage('Joke has sent to ' . $email);
    }

    /**
     * Redirect to main page with flash message
     * @param string $message
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function returnToMainPage(string $message)
    {
        $this->addFlash('notice', $message);

        return $this->redirectToRoute('homepage', [], 301);
    }
}
