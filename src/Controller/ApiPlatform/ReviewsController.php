<?php

namespace App\Controller\ApiPlatform;

use App\Controller\Response\ApiResponse;
use App\Entity\Car;
use App\Entity\Reviews;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class ReviewsController extends AbstractController
{
    public function create(Request $request, EntityManagerInterface $entityManager): ApiResponse
    {
        // Get the raw data from the request body
        $payload = $request->getContent();
        // Decode the JSON data into an array
        $data = json_decode($payload, true);

        try {
            $reviewText = $data['reviewText'];
            $starRating = $data['starRating'];
            $carId = $data['carId'];
            $car = $entityManager->getRepository(Car::class)->find($carId);

            $review = new Reviews();
            $review->setCar($car);
            $review->setReviewText($reviewText) ?? null;
            $review->setStarRating($starRating);

            // Save the Review entity to the database
            $entityManager->persist($review);
            $entityManager->flush();

            return new ApiResponse(
                $car,
                'review is submitted',
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
