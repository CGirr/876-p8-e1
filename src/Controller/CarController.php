<?php

namespace App\Controller;

use App\Entity\Car;
use App\Form\CarType;
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

    #[Route('/add', name: 'app_car_add', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $car = new Car();
        $form = $this->createForm(CarType::class, $car);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($car);
            $entityManager->flush();

            return $this->redirectToRoute('app_index');
        }

        return $this->render('car/add.html.twig', [
            'form' => $form,
        ]);
    }
}
