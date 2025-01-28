<?php

namespace App\Controller;

use App\Entity\Card;
use App\Repository\CardRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/game')]
final class GameController extends AbstractController
{
    #[Route('/', name: 'app_game_index', methods: ['GET'])]
    public function index(CardRepository $cardRepository, Request $request): Response
    {
        // Obtener todas las cartas
        $cards = $cardRepository->findAll();

        // Seleccionar 3 cartas aleatorias para el usuario
        $randomCards = [];
        if (count($cards) > 3) {
            $randomKeys = array_rand($cards, 3);
            foreach ($randomKeys as $key) {
                $randomCards[] = $cards[$key];
            }
        } else {
            $randomCards = $cards;
        }

        // Seleccionar 1 carta aleatoria para el sistema
        $systemCard = $cards[array_rand($cards)];

        // Almacenar la carta del sistema en la sesión
        $session = $request->getSession();
        $session->set('systemCard', $systemCard);

        return $this->render('game/index.html.twig', [
            'cards' => $randomCards,
            'systemCard' => $systemCard,
        ]);
    }

    #[Route('/choose/{id}', name: 'app_game_choose', methods: ['GET'])]
    public function choose(Card $card, Request $request): Response
    {
        // Obtener la carta del sistema desde la sesión
        $session = $request->getSession();
        $systemCard = $session->get('systemCard');

        // Verificar si la carta del sistema es null
        if ($systemCard === null) {
            throw new \Exception('La carta del sistema no se encontró en la sesión.');
        }

        // Comparar los valores de las cartas
        $result = $card->getValor() > $systemCard->getValor() ? 'Ha ganado' : 'Ha perdido';

        return $this->render('game/result.html.twig', [
            'userCard' => $card,
            'systemCard' => $systemCard,
            'result' => $result,
        ]);
    }
}
