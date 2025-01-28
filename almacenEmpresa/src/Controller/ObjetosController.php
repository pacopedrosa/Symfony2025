<?php

namespace App\Controller;

use App\Entity\Objetos;
use App\Form\ObjetosType;
use App\Repository\ObjetosRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/objetos')]
final class ObjetosController extends AbstractController
{
    #[Route(name: 'app_objetos_index', methods: ['GET'])]
    public function index(ObjetosRepository $objetosRepository): Response
    {
        return $this->render('objetos/index.html.twig', [
            'objetos' => $objetosRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_objetos_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $objeto = new Objetos();
        $form = $this->createForm(ObjetosType::class, $objeto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($objeto);
            $entityManager->flush();

            return $this->redirectToRoute('app_objetos_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('objetos/new.html.twig', [
            'objeto' => $objeto,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_objetos_show', methods: ['GET'])]
    public function show(Objetos $objeto): Response
    {
        return $this->render('objetos/show.html.twig', [
            'objeto' => $objeto,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_objetos_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Objetos $objeto, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ObjetosType::class, $objeto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_objetos_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('objetos/edit.html.twig', [
            'objeto' => $objeto,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_objetos_delete', methods: ['POST'])]
    public function delete(Request $request, Objetos $objeto, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$objeto->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($objeto);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_objetos_index', [], Response::HTTP_SEE_OTHER);
    }
}
