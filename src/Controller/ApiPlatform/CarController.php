<?php

namespace App\Controller\ApiPlatform;

use App\Controller\Response\ApiResponse;
use App\Entity\Car;
use App\Entity\Reviews;
use App\Repository\CarRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class CarController extends AbstractController
{
    public function __construct(private CarRepository $carRepository, private EntityManagerInterface $entityManager, private SerializerInterface $serializer)
    {
    }

    public function carsCollection(): ApiResponse
    {
        try {
            $cars = $this->carRepository->findAll();

            foreach ($cars as $car) {
                $reviews = $this->entityManager->createQueryBuilder()
                    ->select('r')
                    ->from(Reviews::class, 'r')
                    ->where('r.car = :car')
                    ->andWhere('r.starRating > 6')
                    ->setParameter('car', $car)
                    ->setMaxResults(5)
                    ->getQuery()
                    ->getResult();

                $car->setReviews($reviews);
            }

            $data = $this->serializer->serialize($cars, 'json', ['groups' => ['car:read', 'reviews:read']]);

            return new ApiResponse(
                $data,
                'Cars',
                true,
                Response::HTTP_OK,
                [],
                false,
                true
            );
        } catch (\Exception $exception) {
            return new ApiResponse('There was a problem saving the data. Please check api document. Make sure the necessary fields are submitted correctly .',
                $exception->getMessage(),
                false,
                (int) $exception->getCode()
            );
        }
    }

    public function delete(Car $car, ManagerRegistry $doctrine): Response
    {
        try {
            $entityManager = $doctrine->getManager();

            foreach ($car->getReviews() as $review) {
                $entityManager->remove($review);
            }

            // Remove the car from the database
            $entityManager->remove($car);
            $entityManager->flush();

            return new ApiResponse(
                $car,
                'Car deleted successfully',
                true,
                Response::HTTP_OK
            );
        } catch (\Exception $exception) {
            return new ApiResponse('There was a problem saving the data. Please check api document. Make sure the necessary fields are submitted correctly .',
                $exception->getMessage(),
                false,
                (int) $exception->getCode()
            );
        }
    }
}
