<?php

namespace App\Controller;

use App\Entity\PersonaContacto;
use App\Form\PersonaContactoType;
use App\Repository\PersonaContactoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/persona')]
final class PersonaContactoController extends AbstractController
{
    #[Route(name: 'app_persona_contacto_index', methods: ['GET'])]
    public function index(PersonaContactoRepository $personaContactoRepository): Response
    {
        return $this->render('persona_contacto/index.html.twig', [
            'persona_contactos' => $personaContactoRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_persona_contacto_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $personaContacto = new PersonaContacto();
        $form = $this->createForm(PersonaContactoType::class, $personaContacto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($personaContacto);
            $entityManager->flush();

            return $this->redirectToRoute('app_persona_contacto_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('persona_contacto/new.html.twig', [
            'persona_contacto' => $personaContacto,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_persona_contacto_show', methods: ['GET'])]
    public function show(PersonaContacto $personaContacto): Response
    {
        return $this->render('persona_contacto/show.html.twig', [
            'persona_contacto' => $personaContacto,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_persona_contacto_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, PersonaContacto $personaContacto, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PersonaContactoType::class, $personaContacto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_persona_contacto_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('persona_contacto/edit.html.twig', [
            'persona_contacto' => $personaContacto,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_persona_contacto_delete', methods: ['POST'])]
    public function delete(Request $request, PersonaContacto $personaContacto, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$personaContacto->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($personaContacto);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_persona_contacto_index', [], Response::HTTP_SEE_OTHER);
    }
}
