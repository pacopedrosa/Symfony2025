<?php

namespace App\Controller;

use App\Repository\CompanyRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_main')]
    public function index(CompanyRepository $companyRepository): Response
    {
        $companies = $companyRepository->findAll();

        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
            'companies' => $companies,
        ]);
    }
}
