<?php

namespace App\Controller;

use App\Repository\CarRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/car')]
final class CarController extends AbstractController
{
    #[Route('', name: 'app_index')]
    public function index(CarRepository $carRepository): Response
    {
        $cars = $carRepository->findAll();

        return $this->render('car/index.html.twig', [
            'cars' => $cars,
        ]);
    }

    #[Route('/details', name: 'app_details')]
    public function show(): Response
    {
        return $this->render('car/details.html.twig', [
        ]);
    }
}
