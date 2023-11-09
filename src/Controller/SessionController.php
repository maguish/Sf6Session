<?php

namespace App\Controller;

use App\Service\MasterMind;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SessionController extends AbstractController
{
    #[Route('/session', name: 'app_session')]
    public function index(Request $request): Response
    {
        $session = $request->getSession();

        if (!$session->has('mastermind')) {
            $session->set('mastermind', new MasterMind());
        }

        $jeu = $session->get('mastermind');

        $codeTrouve = false;

        if ($request->isMethod('GET')) {
            $proposition = $request->query->get('proposition');

            if ($proposition !== null) {
                $result = $jeu->test($proposition);

                if ($result['bon'] == $jeu->getTaille()) {
                    $codeTrouve = true;
                    // Réinitialiser le code pour une nouvelle partie
                    $session->set('mastermind', new MasterMind());
                } else {
                    $tabEssaies = $jeu->getEssais();
                }
            }
        }

        return $this->render('session/accueil.html.twig', [
            'jeu' => $jeu,
            'tabEssaies' => $jeu->getEssais(),
            'codeTrouve' => $codeTrouve,
        ]);
    }

}
