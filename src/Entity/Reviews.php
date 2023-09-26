<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Repository\ReviewsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReviewsRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(),
        new Post(),
    ],
)]
class Reviews
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    # [Groups(['reviews:read', 'reviews:write'])]
    # [ORM\\Column(type: 'integer')]
    private ?int $starRating = null;

    # [Groups(['reviews:read', 'reviews:write'])]
    # [ORM\\Column(type: 'string')]
    private ?string $reviewText = null;

    # [Groups(['reviews:read'])]
    # [ORM\\ManyToOne(targetEntity: Car::class, inversedBy: 'reviews')]
    # [ORM\\JoinColumn(nullable: false)]
    private $car;

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
}
