<?php

namespace App\Controller;

use App\Entity\Card;
use App\Form\CardType;
use App\Repository\CardRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/card')]
final class CardController extends AbstractController
{
    private const SUITS = ['Corazones', 'Diamantes', 'Tréboles', 'Picas'];
    private const VALUES = [
        '2' => 2,
        '3' => 3,
        '4' => 4,
        '5' => 5,
        '6' => 6,
        '7' => 7,
        '8' => 8,
        '9' => 9,
        '10' => 10,
        'J' => 11,
        'Q' => 12,
        'K' => 13,
        'A' => 14,
    ];

    // Ruta para inicializar la baraja
    #[Route('/init', name: 'app_card_init', methods: ['GET'])]
    public function init(EntityManagerInterface $entityManager): Response
    {
        foreach (self::SUITS as $suit) {
            foreach (self::VALUES as $name => $value) {
                $card = new Card();
                $card->setName($name . ' de ' . $suit);
                $card->setValor($value);
                $entityManager->persist($card);
            }
        }

        $entityManager->flush();

        return new Response('Baraja de póker creada con éxito.');
    }

    #[Route('/', name: 'app_card_index', methods: ['GET'])]
    public function index(CardRepository $cardRepository): Response
    {
        return $this->render('card/index.html.twig', [
            'cards' => $cardRepository->findAll(),
        ]);
    }    

    #[Route('/new', name: 'app_card_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $card = new Card();
        $form = $this->createForm(CardType::class, $card);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($card);
            $entityManager->flush();

            return $this->redirectToRoute('app_card_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('card/new.html.twig', [
            'card' => $card,
            'form' => $form,
        ]);
    }

    #[Route('/choose/{id}', name: 'app_card_choose', methods: ['GET'])]
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

        return $this->render('main/choose.html.twig', [
            'userCard' => $card,
            'systemCard' => $systemCard,
            'result' => $result,
        ]);
    }

    #[Route('/{id}', name: 'app_card_show', methods: ['GET'])]
    public function show(Card $card): Response
    {
        return $this->render('card/show.html.twig', [
            'card' => $card,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_card_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Card $card, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CardType::class, $card);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_card_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('card/edit.html.twig', [
            'card' => $card,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_card_delete', methods: ['POST'])]
    public function delete(Request $request, Card $card, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$card->getId(), $request->request->get('_token'))) {
            $entityManager->remove($card);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_card_index', [], Response::HTTP_SEE_OTHER);
    }
}
