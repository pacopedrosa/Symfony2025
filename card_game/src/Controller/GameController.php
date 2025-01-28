<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\Card;
use App\Service\GameService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\GameRepository;

#[Route('/game')]
class GameController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private GameService $gameService
    ) {}

    #[Route('/', name: 'game_index', methods: ['GET'])]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $searchQuery = $request->query->get('search');
        $queryBuilder = $entityManager->getRepository(Game::class)->createQueryBuilder('g')
            ->leftJoin('g.player1', 'p1')
            ->leftJoin('g.player2', 'p2')
            ->orderBy('g.id', 'DESC');

        if ($searchQuery) {
            $queryBuilder->where('g.id = :searchId OR p1.username LIKE :searchTerm OR p2.username LIKE :searchTerm')
                ->setParameter('searchId', is_numeric($searchQuery) ? $searchQuery : -1)
                ->setParameter('searchTerm', '%' . $searchQuery . '%');
        }

        $games = $queryBuilder->getQuery()->getResult();

        return $this->render('game/index.html.twig', [
            'games' => $games,
            'searchQuery' => $searchQuery
        ]);
    }

    /**
     * Crea una nueva partida
     * - Genera 8 cartas aleatorias
     * - Establece el jugador actual como Player1
     * - Almacena las cartas disponibles para la partida
     */
    #[Route('/new', name: 'game_new', methods: ['GET'])]
    public function new(): Response
    {
        $game = new Game();
        $game->setPlayer1($this->getUser());
        $game->setStatus('open');
        
        // Genera 8 cartas aleatorias para que ambos jugadores vean las mismas
        $availableCards = $this->gameService->getRandomCards(8);
        $cardIds = array_map(function($card) {
            return $card->getId();
        }, $availableCards);
        
        $game->setAvailableCards($cardIds);
        
        $this->entityManager->persist($game);
        $this->entityManager->flush();
        
        return $this->redirectToRoute('game_play', ['id' => $game->getId()]);
    }

    #[Route('/{id}', name: 'game_show', methods: ['GET'])]
    public function show(?Game $game = null): Response
    {
        if (!$game) {
            $this->addFlash('error', 'El juego no existe.');
            return $this->redirectToRoute('game_index');
        }

        return $this->render('game/show.html.twig', [
            'game' => $game,
        ]);
    }

    /**
     * Permite a un segundo jugador unirse a la partida
     * - Verifica que la partida esté abierta
     * - Comprueba que no sea el mismo jugador que la creó
     * - Actualiza el estado según si el jugador 1 ya jugó
     */
    #[Route('/{id}/join', name: 'game_join', methods: ['POST'])]
    public function join(Request $request, Game $game): Response
    {
        // Solo se puede unir si la partida está abierta
        if ($game->getStatus() !== 'open') {
            $this->addFlash('error', 'Esta partida no está disponible.');
            return $this->redirectToRoute('game_index');
        }

        // No puede unirse el mismo jugador que creó la partida
        if ($game->getPlayer1() === $this->getUser()) {
            $this->addFlash('error', 'No puedes unirte a tu propia partida.');
            return $this->redirectToRoute('game_index');
        }

        // Verificar que no hay jugador 2
        if ($game->getPlayer2() !== null) {
            $this->addFlash('error', 'Esta partida ya tiene dos jugadores.');
            return $this->redirectToRoute('game_index');
        }

        // Unir al jugador
        $game->setPlayer2($this->getUser());
        
        // Si el jugador 1 ya seleccionó, la partida queda en espera
        if ($game->getPlayer1Card1() && $game->getPlayer1Card2()) {
            $game->setStatus('waiting');
        }

        $this->entityManager->flush();

        $this->addFlash('success', '¡Te has unido a la partida! Selecciona tus cartas.');
        return $this->redirectToRoute('game_play', ['id' => $game->getId()]);
    }

    /**
     * Maneja la selección de cartas para ambos jugadores
     * - Verifica que el jugador pueda jugar
     * - Permite seleccionar exactamente 2 cartas
     * - Actualiza el estado del juego según las selecciones
     * - Determina el ganador cuando ambos han jugado
     */
    #[Route('/{id}/play', name: 'game_play', methods: ['GET', 'POST'])]
    public function play(Request $request, Game $game, GameRepository $gameRepository): Response
    {
        $currentUser = $this->getUser();
        
        // Verifica que el usuario sea parte de la partida
        if ($currentUser !== $game->getPlayer1() && $currentUser !== $game->getPlayer2()) {
            $this->addFlash('error', 'No eres parte de esta partida.');
            return $this->redirectToRoute('game_index');
        }

        // Verifica que el jugador no haya seleccionado ya sus cartas
        if (($currentUser === $game->getPlayer1() && $game->getPlayer1Card1()) ||
            ($currentUser === $game->getPlayer2() && $game->getPlayer2Card1())) {
            $this->addFlash('error', 'Ya has seleccionado tus cartas.');
            return $this->redirectToRoute('game_show', ['id' => $game->getId()]);
        }

        // Obtiene las cartas disponibles para la partida
        $availableCardIds = $game->getAvailableCards();
        $availableCards = $this->entityManager->getRepository(Card::class)
            ->findBy(['id' => $availableCardIds]);

        if ($request->isMethod('POST')) {
            $selectedCards = $request->request->all('selected_cards');
            
            // Valida que se seleccionen exactamente 2 cartas
            if (!is_array($selectedCards) || count($selectedCards) !== 2) {
                $this->addFlash('error', 'Debes seleccionar exactamente 2 cartas.');
                return $this->redirectToRoute('game_play', ['id' => $game->getId()]);
            }

            // Obtiene las cartas seleccionadas de la base de datos
            $card1 = $this->entityManager->getRepository(Card::class)->find($selectedCards[0]);
            $card2 = $this->entityManager->getRepository(Card::class)->find($selectedCards[1]);

            if (!$card1 || !$card2) {
                $this->addFlash('error', 'Una o ambas cartas seleccionadas no son válidas.');
                return $this->redirectToRoute('game_play', ['id' => $game->getId()]);
            }

            if ($currentUser === $game->getPlayer1()) {
                $game->setPlayer1Card1($card1);
                $game->setPlayer1Card2($card2);
                // Actualiza el estado según si hay jugador 2
                if (!$game->getPlayer2()) {
                    $game->setStatus('open');
                } else {
                    $game->setStatus('waiting');
                }
            } else {
                $game->setPlayer2Card1($card1);
                $game->setPlayer2Card2($card2);
                
                // Si ambos jugadores han seleccionado, determina el ganador
                if ($game->getPlayer1Card1() && $game->getPlayer1Card2()) {
                    $game->setStatus('finished');
                    $winner = $this->gameService->determineWinner($game);
                    if ($winner) {
                        $game->setWinner($winner);
                    }
                }
            }

            $this->entityManager->flush();
            
            $this->addFlash('success', 'Has seleccionado tus cartas correctamente.');
            return $this->redirectToRoute('game_show', ['id' => $game->getId()]);
        }

        return $this->render('game/play.html.twig', [
            'game' => $game,
            'cards' => $availableCards
        ]);
    }

    #[Route('/game/my-games', name: 'game_my_games', methods: ['GET'])]
    public function myGames(): Response
    {
        $user = $this->getUser();
        $games = $this->entityManager->getRepository(Game::class)->findByPlayer($user);
        
        // Calcular estadísticas
        $stats = [
            'total' => count($games),
            'wins' => 0,
            'losses' => 0,
            'draws' => 0
        ];
        
        foreach ($games as $game) {
            if ($game->getStatus() === 'finished') {
                if (!$game->getWinner()) {
                    $stats['draws']++;
                } elseif ($game->getWinner() === $user) {
                    $stats['wins']++;
                } else {
                    $stats['losses']++;
                }
            }
        }

        return $this->render('game/my_games.html.twig', [
            'games' => $games,
            'stats' => $stats
        ]);
    }

    #[Route('/create', name: 'game_create', methods: ['POST'])]
    public function create(Request $request, GameService $gameService): Response
    {
        $game = new Game();
        $game->setPlayer1($this->getUser());
        $game->setStatus('waiting');
        $gameService->dealInitialCards($game);
        
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($game);
        $entityManager->flush();
        
        return $this->redirectToRoute('app_game_show', ['id' => $game->getId()]);
    }

    #[Route('/{id}/close', name: 'game_close', methods: ['POST'])]
    public function close(Request $request, Game $game, EntityManagerInterface $entityManager): Response
    {
        // Permitir cerrar si es admin o el creador de la partida
        if (!$this->isGranted('ROLE_ADMIN') && $game->getPlayer1() !== $this->getUser()) {
            throw $this->createAccessDeniedException('No tienes permiso para cerrar esta partida.');
        }

        if ($this->isCsrfTokenValid('close'.$game->getId(), $request->request->get('_token'))) {
            $game->setStatus('finished');
            $entityManager->flush();
            
            $this->addFlash('success', 'Partida cerrada correctamente');
        }

        return $this->redirectToRoute('game_index');
    }

    #[Route('/{id}/delete', name: 'game_delete', methods: ['POST'])]
    public function delete(Request $request, Game $game, EntityManagerInterface $entityManager): Response
    {
        // Permitir borrar si es admin o el creador de la partida
        if (!$this->isGranted('ROLE_ADMIN') && $game->getPlayer1() !== $this->getUser()) {
            throw $this->createAccessDeniedException('No tienes permiso para eliminar esta partida.');
        }

        if ($this->isCsrfTokenValid('delete'.$game->getId(), $request->request->get('_token'))) {
            $entityManager->remove($game);
            $entityManager->flush();
            
            $this->addFlash('success', 'Partida eliminada correctamente');
        }

        return $this->redirectToRoute('game_index');
    }

    #[Route('/{id}/rematch', name: 'game_rematch', methods: ['POST'])]
    public function rematch(Request $request, Game $game, EntityManagerInterface $entityManager): Response
    {
        if ($game->getStatus() !== 'finished' || 
            ($game->getPlayer1() !== $this->getUser() && $game->getPlayer2() !== $this->getUser())) {
            throw $this->createAccessDeniedException('No puedes iniciar una revancha para esta partida.');
        }

        if (!$this->isCsrfTokenValid('rematch'.$game->getId(), $request->request->get('_token'))) {
            return $this->redirectToRoute('game_show', ['id' => $game->getId()]);
        }

        // Verificar si ya existe una partida abierta entre estos jugadores
        $existingGame = $entityManager->getRepository(Game::class)->createQueryBuilder('g')
            ->where('g.status IN (:statuses)')
            ->andWhere('(g.player1 = :player1 AND g.player2 = :player2) OR (g.player1 = :player2 AND g.player2 = :player1)')
            ->setParameter('statuses', ['waiting', 'open'])
            ->setParameter('player1', $game->getPlayer1())
            ->setParameter('player2', $game->getPlayer2())
            ->getQuery()
            ->getOneOrNullResult();

        if ($existingGame) {
            // Si ya existe una partida, redirigir a ella
            if ($this->getUser() === $existingGame->getPlayer1() && !$existingGame->getPlayer1Card()) {
                return $this->redirectToRoute('game_play', ['id' => $existingGame->getId()]);
            } elseif ($this->getUser() === $existingGame->getPlayer2() && !$existingGame->getPlayer2Card()) {
                return $this->redirectToRoute('game_play', ['id' => $existingGame->getId()]);
            } else {
                return $this->redirectToRoute('game_show', ['id' => $existingGame->getId()]);
            }
        }

        // Si no existe una partida, crear una nueva
        $newGame = new Game();
        $newGame->setPlayer1($this->getUser());
        $newGame->setPlayer2($this->getUser() === $game->getPlayer1() ? $game->getPlayer2() : $game->getPlayer1());
        $newGame->setStatus('open');
        
        // Generamos números aleatorios del 1 al 10
        $availableNumbers = range(1, 10);
        shuffle($availableNumbers);
        $newGame->setAvailableCards($availableNumbers);

        $entityManager->persist($newGame);
        $entityManager->flush();
        
        $this->addFlash('success', '¡Nueva partida creada! Es tu turno de elegir carta.');
        
        return $this->redirectToRoute('game_play', ['id' => $newGame->getId()]);
    }
} 