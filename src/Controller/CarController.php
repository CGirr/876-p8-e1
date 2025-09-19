<?php

namespace App\Controller;

use App\Repository\CarRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/details/{id}', name: 'app_car_details', methods: ['GET'])]
    public function show(int $id, CarRepository $carRepository): Response
    {
        $car = $carRepository->find($id);

        if (!$car) {
            return $this->redirectToRoute('app_index');
        }
        return $this->render('car/details.html.twig', [
            'car' => $car,
        ]);
    }

    #[Route('/remove/{id}', name: 'app_car_remove', methods: ['GET'])]
    public function remove(int $id, CarRepository $carRepository, EntityManagerInterface $entityManager): Response
    {
        $car = $carRepository->find($id);

        if (!$car) {
            return $this->redirectToRoute('app_index');
        }

        $entityManager->remove($car);
        $entityManager->flush();

        return $this->redirectToRoute('app_index');
    }
}
