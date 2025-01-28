<?php

namespace App\Controller;

use App\Entity\Empresa;
use App\Form\EmpresaType;
use App\Repository\EmpresaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/empresa')]
final class EmpresaController extends AbstractController
{
    #[Route(name: 'app_empresa_index', methods: ['GET'])]
    public function index(EmpresaRepository $empresaRepository): Response
    {
        return $this->render('empresa/index.html.twig', [
            'empresas' => $empresaRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_empresa_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $empresa = new Empresa();
        $form = $this->createForm(EmpresaType::class, $empresa);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($empresa);
            $entityManager->flush();

            return $this->redirectToRoute('app_empresa_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('empresa/new.html.twig', [
            'empresa' => $empresa,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_empresa_show', methods: ['GET'])]
    public function show(Empresa $empresa): Response
    {
        return $this->render('empresa/show.html.twig', [
            'empresa' => $empresa,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_empresa_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Empresa $empresa, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EmpresaType::class, $empresa);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_empresa_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('empresa/edit.html.twig', [
            'empresa' => $empresa,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_empresa_delete', methods: ['POST'])]
    public function delete(Request $request, Empresa $empresa, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$empresa->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($empresa);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_empresa_index', [], Response::HTTP_SEE_OTHER);
    }
}
