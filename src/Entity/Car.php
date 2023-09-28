<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\CarRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CarRepository::class)]
#[Post(validationContext: ['groups' => ['Default', 'car:write']])]
#[ApiResource(
    operations: [
        new GetCollection(routeName: 'cars_collection'),
        new Get(uriTemplate: '/car/{id}', requirements: ['id' => '\d+']),
        new Post(uriTemplate: '/car/create', validationContext: ['groups' => ['Default', 'car:write']]),
        new Delete(uriTemplate: '/car/delete/{id}', routeName: 'car_delete'),
        new Put(uriTemplate: '/car/update/{id}', requirements: ['id' => '\d+']),
    ],
    order: ['id' => 'DESC']
)]
class Car
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\Length(min: 2, max: 110, groups: ['car:write'])]
    #[Assert\NotBlank(groups: ['car:write'])]
    #[Groups(['car:read', 'car:write'])]
    #[ORM\Column(length: 110)]
    private ?string $brand = null;

    #[Assert\Length(min: 2, max: 110, groups: ['car:write'])]
    #[Assert\NotBlank(groups: ['car:write'])]
    #[Groups(['car:read', 'car:write'])]
    #[ORM\Column(length: 110)]
    private ?string $model = null;

    #[Assert\Length(min: 2, max: 110, groups: ['car:write'])]
    #[Groups(['car:read', 'car:write'])]
    #[ORM\Column(length: 110, nullable: true)]
    private ?string $color = null;

    #[Groups(['car:read', 'reviews:read'])]
    #[ORM\OneToMany(mappedBy: 'car', targetEntity: Reviews::class)]
    private $reviews;

    public function __construct()
    {
        $this->reviews = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): static
    {
        $this->brand = $brand;

        return $this;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(string $model): static
    {
        $this->model = $model;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color): static
    {
        $this->color = $color;

        return $this;
    }

    /**
     * @Groups({"car:read", "reviews:read"})
     */
    public function getReviews()
    {
        return $this->reviews;
    }

    public function setReviews($reviews): static
    {
        $this->reviews = $reviews;

        return $this;
    }
}
