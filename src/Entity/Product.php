<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $url;

    /**
     * @ORM\OneToMany(targetEntity=ProductProperty::class, mappedBy="product", orphanRemoval=true)
     */
    private $productProperties;

    /**
     * @ORM\OneToMany(targetEntity=Review::class, mappedBy="product", orphanRemoval=true)
     */
    private $reviews;

    /**
     * @ORM\OneToMany(targetEntity=ProductPhoto::class, mappedBy="product", orphanRemoval=true)
     */
    private $productPhotos;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\OneToOne(targetEntity=Discount::class, mappedBy="product", cascade={"persist", "remove"})
     */
    private $discount;

    /**
     * @ORM\OneToMany(targetEntity=Bought::class, mappedBy="product", orphanRemoval=true)
     */
    private $boughts;

    /**
     * @ORM\OneToMany(targetEntity=Wishlist::class, mappedBy="product", orphanRemoval=true)
     */
    private $wishlists;

    /**
     * @ORM\OneToMany(targetEntity=Compatibility::class, mappedBy="owner", orphanRemoval=true)
     */
    private $compatibilities;

    public function __construct()
    {
        $this->productProperties = new ArrayCollection();
        $this->reviews = new ArrayCollection();
        $this->productPhotos = new ArrayCollection();
        $this->boughts = new ArrayCollection();
        $this->wishlists = new ArrayCollection();
        $this->compatibilities = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return Collection|ProductProperty[]
     */
    public function getProductProperties(): Collection
    {
        return $this->productProperties;
    }

    public function addProductProperty(ProductProperty $productProperty): self
    {
        if (!$this->productProperties->contains($productProperty)) {
            $this->productProperties[] = $productProperty;
            $productProperty->setProduct($this);
        }

        return $this;
    }

    public function removeProductProperty(ProductProperty $productProperty): self
    {
        if ($this->productProperties->removeElement($productProperty)) {
            // set the owning side to null (unless already changed)
            if ($productProperty->getProduct() === $this) {
                $productProperty->setProduct(null);
            }
        }

        return $this;
    }

    public function getReviewsScore(): string
    {
        $score = "-";
        if (count($this->reviews) > 0) {
            $score = 0;
            foreach ($this->reviews as $review) {
                $score += $review->getScore();
            }
            $score = number_format(round($score / $this->getReviewsCount(), 1), 1);
        }
        return $score;
    }

    public function getReviewsCount(): int
    {
        return count($this->reviews);
    }

    /**
     * @return Collection|Review[]
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReview(Review $review): self
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews[] = $review;
            $review->setProduct($this);
        }

        return $this;
    }

    public function removeReview(Review $review): self
    {
        if ($this->reviews->removeElement($review)) {
            // set the owning side to null (unless already changed)
            if ($review->getProduct() === $this) {
                $review->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ProductPhoto[]
     */
    public function getProductPhotos(): Collection
    {
        return $this->productPhotos;
    }

    public function addProductPhoto(ProductPhoto $productPhoto): self
    {
        if (!$this->productPhotos->contains($productPhoto)) {
            $this->productPhotos[] = $productPhoto;
            $productPhoto->setProduct($this);
        }

        return $this;
    }

    public function removeProductPhoto(ProductPhoto $productPhoto): self
    {
        if ($this->productPhotos->removeElement($productPhoto)) {
            // set the owning side to null (unless already changed)
            if ($productPhoto->getProduct() === $this) {
                $productPhoto->setProduct(null);
            }
        }

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDiscount(): ?Discount
    {
        return $this->discount;
    }

    public function setDiscount(Discount $discount): self
    {
        // set the owning side of the relation if necessary
        if ($discount->getProduct() !== $this) {
            $discount->setProduct($this);
        }

        $this->discount = $discount;

        return $this;
    }

    /**
     * @return Collection|Bought[]
     */
    public function getBoughts(): Collection
    {
        return $this->boughts;
    }

    public function addBought(Bought $bought): self
    {
        if (!$this->boughts->contains($bought)) {
            $this->boughts[] = $bought;
            $bought->setProduct($this);
        }

        return $this;
    }

    public function removeBought(Bought $bought): self
    {
        if ($this->boughts->removeElement($bought)) {
            // set the owning side to null (unless already changed)
            if ($bought->getProduct() === $this) {
                $bought->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Wishlist[]
     */
    public function getWishlists(): Collection
    {
        return $this->wishlists;
    }

    public function addWishlist(Wishlist $wishlist): self
    {
        if (!$this->wishlists->contains($wishlist)) {
            $this->wishlists[] = $wishlist;
            $wishlist->setProduct($this);
        }

        return $this;
    }

    public function removeWishlist(Wishlist $wishlist): self
    {
        if ($this->wishlists->removeElement($wishlist)) {
            // set the owning side to null (unless already changed)
            if ($wishlist->getProduct() === $this) {
                $wishlist->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Compatibility[]
     */
    public function getCompatibilities(): Collection
    {
        return $this->compatibilities;
    }

    public function addCompatibility(Compatibility $compatibility): self
    {
        if (!$this->compatibilities->contains($compatibility)) {
            $this->compatibilities[] = $compatibility;
            $compatibility->setOwner($this);
        }

        return $this;
    }

    public function removeCompatibility(Compatibility $compatibility): self
    {
        if ($this->compatibilities->removeElement($compatibility)) {
            // set the owning side to null (unless already changed)
            if ($compatibility->getOwner() === $this) {
                $compatibility->setOwner(null);
            }
        }

        return $this;
    }
}
