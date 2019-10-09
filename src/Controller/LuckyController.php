<?php
namespace App\Controller;

use App\Service\MessageGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class LuckyController extends AbstractController
{
    /**
     * @Route("/lucky/number")
     * @param MessageGenerator $messageGenerator
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function number(MessageGenerator $messageGenerator)
    {
        $number = random_int(0, 100);
        $message = $messageGenerator->getHappyMessage();

        return $this->render('lucky/number.html.twig', [
            'number'    => $number,
            'message'   => $message
        ]);
    }
}