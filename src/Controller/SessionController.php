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

        if(!$session->has('mastermind')){
            $session->set('mastermind', new MasterMind());
        } else {
            $jeu = $session->get('mastermind');
            
            $proposition = $request->query->get('proposition');
            $jeu->test($proposition);

            $tabEssaies = $jeu->getEssais();
            return $this->render('session/accueil.html.twig', [
                'jeu' => $jeu,
                'tabEssaies' => $tabEssaies,
            ]);

            $session->set('mastermind', $jeu);

        }

        // Aucun paramètre n'a été fourni, afficher le template de base
        return $this->render('base.html.twig');

    }

}
