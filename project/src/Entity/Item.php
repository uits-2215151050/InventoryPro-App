<?php

namespace App\Entity;

use App\Repository\ItemRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ItemRepository::class)]
#[ORM\UniqueConstraint(name: 'unique_custom_id_per_inventory', columns: ['inventory_id', 'custom_id'])]
#[ORM\Index(name: 'idx_item_fulltext', columns: ['custom_id', 'custom_string1', 'custom_string2', 'custom_string3'], flags: ['fulltext'])]
class Item
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $customId = null;

    #[ORM\Column(type: Types::INTEGER)]
    private int $version = 1;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\ManyToOne(targetEntity: Inventory::class, inversedBy: 'items')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Inventory $inventory = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $createdBy = null;

    #[ORM\OneToMany(targetEntity: Like::class, mappedBy: 'item', orphanRemoval: true)]
    private Collection $likes;

    // === Custom String Fields ===
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $customString1 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $customString2 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $customString3 = null;

    // === Custom Multiline Text Fields ===
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $customText1 = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $customText2 = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $customText3 = null;

    // === Custom Numeric Fields ===
    #[ORM\Column(type: Types::FLOAT, nullable: true)]
    private ?float $customNumber1 = null;

    #[ORM\Column(type: Types::FLOAT, nullable: true)]
    private ?float $customNumber2 = null;

    #[ORM\Column(type: Types::FLOAT, nullable: true)]
    private ?float $customNumber3 = null;

    // === Custom Link Fields ===
    #[ORM\Column(length: 1000, nullable: true)]
    private ?string $customLink1 = null;

    #[ORM\Column(length: 1000, nullable: true)]
    private ?string $customLink2 = null;

    #[ORM\Column(length: 1000, nullable: true)]
    private ?string $customLink3 = null;

    // === Custom Boolean Fields ===
    #[ORM\Column(type: Types::BOOLEAN, nullable: true)]
    private ?bool $customBool1 = null;

    #[ORM\Column(type: Types::BOOLEAN, nullable: true)]
    private ?bool $customBool2 = null;

    #[ORM\Column(type: Types::BOOLEAN, nullable: true)]
    private ?bool $customBool3 = null;

    public function __construct()
    {
        $this->likes = new ArrayCollection();
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    public function getCustomId(): ?string
    {
        return $this->customId;
    }
    public function setCustomId(string $customId): static
    {
        $this->customId = $customId;
        return $this;
    }
    public function getVersion(): int
    {
        return $this->version;
    }
    public function setVersion(int $version): static
    {
        $this->version = $version;
        return $this;
    }
    public function incrementVersion(): static
    {
        $this->version++;
        return $this;
    }
    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }
    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;
        return $this;
    }
    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }
    public function setUpdatedAt(\DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }
    public function getInventory(): ?Inventory
    {
        return $this->inventory;
    }
    public function setInventory(?Inventory $inventory): static
    {
        $this->inventory = $inventory;
        return $this;
    }
    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }
    public function setCreatedBy(?User $createdBy): static
    {
        $this->createdBy = $createdBy;
        return $this;
    }

    public function getLikes(): Collection
    {
        return $this->likes;
    }
    public function getLikeCount(): int
    {
        return $this->likes->count();
    }

    // Custom field getters/setters
    public function getCustomString1(): ?string
    {
        return $this->customString1;
    }
    public function setCustomString1(?string $value): static
    {
        $this->customString1 = $value;
        return $this;
    }
    public function getCustomString2(): ?string
    {
        return $this->customString2;
    }
    public function setCustomString2(?string $value): static
    {
        $this->customString2 = $value;
        return $this;
    }
    public function getCustomString3(): ?string
    {
        return $this->customString3;
    }
    public function setCustomString3(?string $value): static
    {
        $this->customString3 = $value;
        return $this;
    }

    public function getCustomText1(): ?string
    {
        return $this->customText1;
    }
    public function setCustomText1(?string $value): static
    {
        $this->customText1 = $value;
        return $this;
    }
    public function getCustomText2(): ?string
    {
        return $this->customText2;
    }
    public function setCustomText2(?string $value): static
    {
        $this->customText2 = $value;
        return $this;
    }
    public function getCustomText3(): ?string
    {
        return $this->customText3;
    }
    public function setCustomText3(?string $value): static
    {
        $this->customText3 = $value;
        return $this;
    }

    public function getCustomNumber1(): ?float
    {
        return $this->customNumber1;
    }
    public function setCustomNumber1(?float $value): static
    {
        $this->customNumber1 = $value;
        return $this;
    }
    public function getCustomNumber2(): ?float
    {
        return $this->customNumber2;
    }
    public function setCustomNumber2(?float $value): static
    {
        $this->customNumber2 = $value;
        return $this;
    }
    public function getCustomNumber3(): ?float
    {
        return $this->customNumber3;
    }
    public function setCustomNumber3(?float $value): static
    {
        $this->customNumber3 = $value;
        return $this;
    }

    public function getCustomLink1(): ?string
    {
        return $this->customLink1;
    }
    public function setCustomLink1(?string $value): static
    {
        $this->customLink1 = $value;
        return $this;
    }
    public function getCustomLink2(): ?string
    {
        return $this->customLink2;
    }
    public function setCustomLink2(?string $value): static
    {
        $this->customLink2 = $value;
        return $this;
    }
    public function getCustomLink3(): ?string
    {
        return $this->customLink3;
    }
    public function setCustomLink3(?string $value): static
    {
        $this->customLink3 = $value;
        return $this;
    }

    public function getCustomBool1(): ?bool
    {
        return $this->customBool1;
    }
    public function setCustomBool1(?bool $value): static
    {
        $this->customBool1 = $value;
        return $this;
    }
    public function getCustomBool2(): ?bool
    {
        return $this->customBool2;
    }
    public function setCustomBool2(?bool $value): static
    {
        $this->customBool2 = $value;
        return $this;
    }
    public function getCustomBool3(): ?bool
    {
        return $this->customBool3;
    }
    public function setCustomBool3(?bool $value): static
    {
        $this->customBool3 = $value;
        return $this;
    }

    /**
     * Get field value by key (e.g., 'customString1')
     */
    public function getFieldValue(string $key): mixed
    {
        $getter = 'get' . ucfirst($key);
        if (method_exists($this, $getter)) {
            return $this->$getter();
        }
        return null;
    }

    /**
     * Set field value by key (e.g., 'customString1')
     */
    public function setFieldValue(string $key, mixed $value): static
    {
        $setter = 'set' . ucfirst($key);
        if (method_exists($this, $setter)) {
            $this->$setter($value);
        }
        return $this;
    }
}
