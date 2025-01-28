<?php

namespace App\Controller;

use App\Entity\RegistroSalida;
use App\Form\RegistroSalidaType;
use App\Repository\RegistroSalidaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/registro/salida')]
final class RegistroSalidaController extends AbstractController
{
    #[Route(name: 'app_registro_salida_index', methods: ['GET'])]
    public function index(RegistroSalidaRepository $registroSalidaRepository): Response
    {
        return $this->render('registro_salida/index.html.twig', [
            'registro_salidas' => $registroSalidaRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_registro_salida_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $registroSalida = new RegistroSalida();
        $form = $this->createForm(RegistroSalidaType::class, $registroSalida);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($registroSalida);
            $entityManager->flush();

            return $this->redirectToRoute('app_registro_salida_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('registro_salida/new.html.twig', [
            'registro_salida' => $registroSalida,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_registro_salida_show', methods: ['GET'])]
    public function show(RegistroSalida $registroSalida): Response
    {
        return $this->render('registro_salida/show.html.twig', [
            'registro_salida' => $registroSalida,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_registro_salida_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, RegistroSalida $registroSalida, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RegistroSalidaType::class, $registroSalida);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_registro_salida_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('registro_salida/edit.html.twig', [
            'registro_salida' => $registroSalida,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_registro_salida_delete', methods: ['POST'])]
    public function delete(Request $request, RegistroSalida $registroSalida, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$registroSalida->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($registroSalida);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_registro_salida_index', [], Response::HTTP_SEE_OTHER);
    }
}
