<?php

namespace App\Controller;

use App\Entity\Comunicacion;
use App\Form\ComunicacionType;
use App\Repository\ComunicacionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/comunicacion')]
final class ComunicacionController extends AbstractController
{
    #[Route(name: 'app_comunicacion_index', methods: ['GET'])]
    public function index(ComunicacionRepository $comunicacionRepository): Response
    {
        return $this->render('comunicacion/index.html.twig', [
            'comunicacions' => $comunicacionRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_comunicacion_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $comunicacion = new Comunicacion();
        $form = $this->createForm(ComunicacionType::class, $comunicacion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($comunicacion);
            $entityManager->flush();

            return $this->redirectToRoute('app_comunicacion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('comunicacion/new.html.twig', [
            'comunicacion' => $comunicacion,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_comunicacion_show', methods: ['GET'])]
    public function show(Comunicacion $comunicacion): Response
    {
        return $this->render('comunicacion/show.html.twig', [
            'comunicacion' => $comunicacion,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_comunicacion_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Comunicacion $comunicacion, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ComunicacionType::class, $comunicacion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_comunicacion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('comunicacion/edit.html.twig', [
            'comunicacion' => $comunicacion,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_comunicacion_delete', methods: ['POST'])]
    public function delete(Request $request, Comunicacion $comunicacion, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$comunicacion->getId(), $request->request->get('_token'))) {
            $entityManager->remove($comunicacion);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_comunicacion_index', [], Response::HTTP_SEE_OTHER);
    }
}
