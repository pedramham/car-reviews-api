<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\OpenApi\Model;
use App\Repository\ReviewsRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\JoinColumn;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

// #[Post(validationContext: ['groups' => ['Default', 'reviews:write']])]
#[ORM\Entity(repositoryClass: ReviewsRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(),
        new Post(uriTemplate: '/reviews/create', routeName: 'create_reviews',
            openapi: new Model\Operation(
                summary: 'Create Reviews',
                description: 'Create Reviews',
                requestBody: new Model\RequestBody(
                    content: new \ArrayObject([
                        'application/json' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'starRating' => ['type' => 'int'],
                                    'reviewText' => ['type' => 'string'],
                                    'carId' => ['type' => 'int'],
                                ],
                            ],
                            'example' => [
                                'starRating' => 2,
                                'reviewText' => 'good',
                                'carId' => 2,
                            ],
                        ],
                    ])
                )
            )),
    ],
    validationContext: ['groups' => [Reviews::class, 'validationGroups']],
)]
class Reviews
{
    /**
     * Return dynamic validation groups.
     *
     * @param self $reviews contains the instance of reviews to validate
     *
     * @return string[]
     */
    public static function validationGroups(self $reviews)
    {
        return ['reviews:write'];
    }

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank(groups: ['first'])]
    #[Assert\Length(min: 2, max: 10, groups: ['reviews:write'])]
    #[Groups(['reviews:read', 'reviews:write'])]
    #[Column(type: 'integer')]
    private ?int $starRating = null;

    #[Groups(['reviews:read'])]
    #[Column(type: 'string')]
    private ?string $reviewText = null;

    #[ORM\ManyToOne(targetEntity: Car::class, inversedBy: 'reviews')]
    #[JoinColumn(nullable: false)]
    private $car;

    public function __construct($id = null)
    {
        $this->id = $id;
    }

    #[Assert\NotBlank]
    #[Groups(['reviews:read', 'reviews:write'])]
    private $carId;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStarRating(): ?int
    {
        return $this->starRating;
    }

    public function setStarRating(int $starRating): static
    {
        $this->starRating = $starRating;

        return $this;
    }

    public function getReviewText(): ?string
    {
        return $this->reviewText;
    }

    public function setReviewText(?string $reviewText): static
    {
        $this->reviewText = $reviewText;

        return $this;
    }

    public function getCar(?int $carId): self
    {
        return $this->carId;
    }

    public function setCar(?Car $car): self
    {
        $this->car = $car;

        return $this;
    }
}
