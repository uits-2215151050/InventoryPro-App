<?php

namespace App\Entity;

use App\Repository\InventoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InventoryRepository::class)]
#[ORM\Index(name: 'idx_inventory_fulltext', columns: ['title', 'description'], flags: ['fulltext'])]
class Inventory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 500, nullable: true)]
    private ?string $imageUrl = null;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $isPublic = false;

    #[ORM\Column(type: Types::INTEGER)]
    private int $version = 1;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updatedAt = null;

    // Relationships
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'inventories')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $creator = null;

    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'inventories')]
    private ?Category $category = null;

    #[ORM\ManyToMany(targetEntity: Tag::class, inversedBy: 'inventories')]
    private Collection $tags;

    #[ORM\ManyToMany(targetEntity: User::class)]
    #[ORM\JoinTable(name: 'inventory_writers')]
    private Collection $writers;

    #[ORM\OneToMany(targetEntity: Item::class, mappedBy: 'inventory', orphanRemoval: true)]
    private Collection $items;

    #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'inventory', orphanRemoval: true)]
    private Collection $comments;

    // Custom ID format (JSON array of elements)
    #[ORM\Column(type: Types::JSON, nullable: true)]
    private ?array $customIdFormat = null;

    #[ORM\Column(type: Types::INTEGER)]
    private int $sequenceCounter = 0;

    // === Custom String Fields ===
    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $customString1State = false;
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $customString1Name = null;
    #[ORM\Column(length: 500, nullable: true)]
    private ?string $customString1Description = null;
    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $customString1ShowInTable = true;
    #[ORM\Column(type: Types::SMALLINT)]
    private int $customString1Order = 0;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $customString2State = false;
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $customString2Name = null;
    #[ORM\Column(length: 500, nullable: true)]
    private ?string $customString2Description = null;
    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $customString2ShowInTable = true;
    #[ORM\Column(type: Types::SMALLINT)]
    private int $customString2Order = 1;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $customString3State = false;
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $customString3Name = null;
    #[ORM\Column(length: 500, nullable: true)]
    private ?string $customString3Description = null;
    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $customString3ShowInTable = true;
    #[ORM\Column(type: Types::SMALLINT)]
    private int $customString3Order = 2;

    // === Custom Multiline Text Fields ===
    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $customText1State = false;
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $customText1Name = null;
    #[ORM\Column(length: 500, nullable: true)]
    private ?string $customText1Description = null;
    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $customText1ShowInTable = false;
    #[ORM\Column(type: Types::SMALLINT)]
    private int $customText1Order = 3;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $customText2State = false;
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $customText2Name = null;
    #[ORM\Column(length: 500, nullable: true)]
    private ?string $customText2Description = null;
    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $customText2ShowInTable = false;
    #[ORM\Column(type: Types::SMALLINT)]
    private int $customText2Order = 4;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $customText3State = false;
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $customText3Name = null;
    #[ORM\Column(length: 500, nullable: true)]
    private ?string $customText3Description = null;
    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $customText3ShowInTable = false;
    #[ORM\Column(type: Types::SMALLINT)]
    private int $customText3Order = 5;

    // === Custom Numeric Fields ===
    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $customNumber1State = false;
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $customNumber1Name = null;
    #[ORM\Column(length: 500, nullable: true)]
    private ?string $customNumber1Description = null;
    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $customNumber1ShowInTable = true;
    #[ORM\Column(type: Types::SMALLINT)]
    private int $customNumber1Order = 6;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $customNumber2State = false;
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $customNumber2Name = null;
    #[ORM\Column(length: 500, nullable: true)]
    private ?string $customNumber2Description = null;
    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $customNumber2ShowInTable = true;
    #[ORM\Column(type: Types::SMALLINT)]
    private int $customNumber2Order = 7;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $customNumber3State = false;
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $customNumber3Name = null;
    #[ORM\Column(length: 500, nullable: true)]
    private ?string $customNumber3Description = null;
    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $customNumber3ShowInTable = true;
    #[ORM\Column(type: Types::SMALLINT)]
    private int $customNumber3Order = 8;

    // === Custom Link Fields ===
    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $customLink1State = false;
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $customLink1Name = null;
    #[ORM\Column(length: 500, nullable: true)]
    private ?string $customLink1Description = null;
    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $customLink1ShowInTable = false;
    #[ORM\Column(type: Types::SMALLINT)]
    private int $customLink1Order = 9;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $customLink2State = false;
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $customLink2Name = null;
    #[ORM\Column(length: 500, nullable: true)]
    private ?string $customLink2Description = null;
    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $customLink2ShowInTable = false;
    #[ORM\Column(type: Types::SMALLINT)]
    private int $customLink2Order = 10;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $customLink3State = false;
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $customLink3Name = null;
    #[ORM\Column(length: 500, nullable: true)]
    private ?string $customLink3Description = null;
    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $customLink3ShowInTable = false;
    #[ORM\Column(type: Types::SMALLINT)]
    private int $customLink3Order = 11;

    // === Custom Boolean Fields ===
    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $customBool1State = false;
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $customBool1Name = null;
    #[ORM\Column(length: 500, nullable: true)]
    private ?string $customBool1Description = null;
    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $customBool1ShowInTable = true;
    #[ORM\Column(type: Types::SMALLINT)]
    private int $customBool1Order = 12;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $customBool2State = false;
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $customBool2Name = null;
    #[ORM\Column(length: 500, nullable: true)]
    private ?string $customBool2Description = null;
    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $customBool2ShowInTable = true;
    #[ORM\Column(type: Types::SMALLINT)]
    private int $customBool2Order = 13;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $customBool3State = false;
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $customBool3Name = null;
    #[ORM\Column(length: 500, nullable: true)]
    private ?string $customBool3Description = null;
    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $customBool3ShowInTable = true;
    #[ORM\Column(type: Types::SMALLINT)]
    private int $customBool3Order = 14;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->writers = new ArrayCollection();
        $this->items = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    // Basic getters and setters
    public function getId(): ?int
    {
        return $this->id;
    }
    public function getTitle(): ?string
    {
        return $this->title;
    }
    public function setTitle(string $title): static
    {
        $this->title = $title;
        return $this;
    }
    public function getDescription(): ?string
    {
        return $this->description;
    }
    public function setDescription(?string $description): static
    {
        $this->description = $description;
        return $this;
    }
    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }
    public function setImageUrl(?string $imageUrl): static
    {
        $this->imageUrl = $imageUrl;
        return $this;
    }
    public function isPublic(): bool
    {
        return $this->isPublic;
    }
    public function setIsPublic(bool $isPublic): static
    {
        $this->isPublic = $isPublic;
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
    public function getCreator(): ?User
    {
        return $this->creator;
    }
    public function setCreator(?User $creator): static
    {
        $this->creator = $creator;
        return $this;
    }
    public function getCategory(): ?Category
    {
        return $this->category;
    }
    public function setCategory(?Category $category): static
    {
        $this->category = $category;
        return $this;
    }

    public function getCustomIdFormat(): ?array
    {
        return $this->customIdFormat;
    }
    public function setCustomIdFormat(?array $customIdFormat): static
    {
        $this->customIdFormat = $customIdFormat;
        return $this;
    }
    public function getSequenceCounter(): int
    {
        return $this->sequenceCounter;
    }
    public function setSequenceCounter(int $sequenceCounter): static
    {
        $this->sequenceCounter = $sequenceCounter;
        return $this;
    }
    public function incrementSequenceCounter(): int
    {
        return ++$this->sequenceCounter;
    }

    // Tags
    public function getTags(): Collection
    {
        return $this->tags;
    }
    public function addTag(Tag $tag): static
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
        }
        return $this;
    }
    public function removeTag(Tag $tag): static
    {
        $this->tags->removeElement($tag);
        return $this;
    }

    // Writers
    public function getWriters(): Collection
    {
        return $this->writers;
    }
    public function addWriter(User $user): static
    {
        if (!$this->writers->contains($user)) {
            $this->writers->add($user);
        }
        return $this;
    }
    public function removeWriter(User $user): static
    {
        $this->writers->removeElement($user);
        return $this;
    }
    public function hasWriteAccess(User $user): bool
    {
        if ($this->isPublic)
            return true;
        if ($this->creator === $user)
            return true;
        if ($user->isAdmin())
            return true;
        return $this->writers->contains($user);
    }

    // Items
    public function getItems(): Collection
    {
        return $this->items;
    }
    public function addItem(Item $item): static
    {
        if (!$this->items->contains($item)) {
            $this->items->add($item);
            $item->setInventory($this);
        }
        return $this;
    }
    public function removeItem(Item $item): static
    {
        if ($this->items->removeElement($item)) {
            if ($item->getInventory() === $this) {
                $item->setInventory(null);
            }
        }
        return $this;
    }

    // Comments
    public function getComments(): Collection
    {
        return $this->comments;
    }
    public function addComment(Comment $comment): static
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setInventory($this);
        }
        return $this;
    }
    public function removeComment(Comment $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            if ($comment->getInventory() === $this) {
                $comment->setInventory(null);
            }
        }
        return $this;
    }

    // Custom field accessor helpers
    public function getCustomFieldConfig(): array
    {
        $fields = [];
        $fieldDefs = [
            ['type' => 'string', 'prefix' => 'customString', 'count' => 3],
            ['type' => 'text', 'prefix' => 'customText', 'count' => 3],
            ['type' => 'number', 'prefix' => 'customNumber', 'count' => 3],
            ['type' => 'link', 'prefix' => 'customLink', 'count' => 3],
            ['type' => 'bool', 'prefix' => 'customBool', 'count' => 3],
        ];
        foreach ($fieldDefs as $def) {
            for ($i = 1; $i <= $def['count']; $i++) {
                $stateGetter = 'is' . ucfirst($def['prefix']) . $i . 'State';
                if (method_exists($this, $stateGetter) && $this->$stateGetter()) {
                    $nameGetter = 'get' . ucfirst($def['prefix']) . $i . 'Name';
                    $descGetter = 'get' . ucfirst($def['prefix']) . $i . 'Description';
                    $showGetter = 'is' . ucfirst($def['prefix']) . $i . 'ShowInTable';
                    $orderGetter = 'get' . ucfirst($def['prefix']) . $i . 'Order';
                    $fields[] = [
                        'key' => $def['prefix'] . $i,
                        'type' => $def['type'],
                        'name' => $this->$nameGetter(),
                        'description' => $this->$descGetter(),
                        'showInTable' => $this->$showGetter(),
                        'order' => $this->$orderGetter(),
                    ];
                }
            }
        }
        usort($fields, fn($a, $b) => $a['order'] <=> $b['order']);
        return $fields;
    }

    // Dynamic getters/setters for custom fields (generated pattern)
    public function isCustomString1State(): bool
    {
        return $this->customString1State;
    }
    public function setCustomString1State(bool $state): static
    {
        $this->customString1State = $state;
        return $this;
    }
    public function getCustomString1Name(): ?string
    {
        return $this->customString1Name;
    }
    public function setCustomString1Name(?string $name): static
    {
        $this->customString1Name = $name;
        return $this;
    }
    public function getCustomString1Description(): ?string
    {
        return $this->customString1Description;
    }
    public function setCustomString1Description(?string $desc): static
    {
        $this->customString1Description = $desc;
        return $this;
    }
    public function isCustomString1ShowInTable(): bool
    {
        return $this->customString1ShowInTable;
    }
    public function setCustomString1ShowInTable(bool $show): static
    {
        $this->customString1ShowInTable = $show;
        return $this;
    }
    public function getCustomString1Order(): int
    {
        return $this->customString1Order;
    }
    public function setCustomString1Order(int $order): static
    {
        $this->customString1Order = $order;
        return $this;
    }

    public function isCustomString2State(): bool
    {
        return $this->customString2State;
    }
    public function setCustomString2State(bool $state): static
    {
        $this->customString2State = $state;
        return $this;
    }
    public function getCustomString2Name(): ?string
    {
        return $this->customString2Name;
    }
    public function setCustomString2Name(?string $name): static
    {
        $this->customString2Name = $name;
        return $this;
    }
    public function getCustomString2Description(): ?string
    {
        return $this->customString2Description;
    }
    public function setCustomString2Description(?string $desc): static
    {
        $this->customString2Description = $desc;
        return $this;
    }
    public function isCustomString2ShowInTable(): bool
    {
        return $this->customString2ShowInTable;
    }
    public function setCustomString2ShowInTable(bool $show): static
    {
        $this->customString2ShowInTable = $show;
        return $this;
    }
    public function getCustomString2Order(): int
    {
        return $this->customString2Order;
    }
    public function setCustomString2Order(int $order): static
    {
        $this->customString2Order = $order;
        return $this;
    }

    public function isCustomString3State(): bool
    {
        return $this->customString3State;
    }
    public function setCustomString3State(bool $state): static
    {
        $this->customString3State = $state;
        return $this;
    }
    public function getCustomString3Name(): ?string
    {
        return $this->customString3Name;
    }
    public function setCustomString3Name(?string $name): static
    {
        $this->customString3Name = $name;
        return $this;
    }
    public function getCustomString3Description(): ?string
    {
        return $this->customString3Description;
    }
    public function setCustomString3Description(?string $desc): static
    {
        $this->customString3Description = $desc;
        return $this;
    }
    public function isCustomString3ShowInTable(): bool
    {
        return $this->customString3ShowInTable;
    }
    public function setCustomString3ShowInTable(bool $show): static
    {
        $this->customString3ShowInTable = $show;
        return $this;
    }
    public function getCustomString3Order(): int
    {
        return $this->customString3Order;
    }
    public function setCustomString3Order(int $order): static
    {
        $this->customString3Order = $order;
        return $this;
    }

    // Text fields
    public function isCustomText1State(): bool
    {
        return $this->customText1State;
    }
    public function setCustomText1State(bool $state): static
    {
        $this->customText1State = $state;
        return $this;
    }
    public function getCustomText1Name(): ?string
    {
        return $this->customText1Name;
    }
    public function setCustomText1Name(?string $name): static
    {
        $this->customText1Name = $name;
        return $this;
    }
    public function getCustomText1Description(): ?string
    {
        return $this->customText1Description;
    }
    public function setCustomText1Description(?string $desc): static
    {
        $this->customText1Description = $desc;
        return $this;
    }
    public function isCustomText1ShowInTable(): bool
    {
        return $this->customText1ShowInTable;
    }
    public function setCustomText1ShowInTable(bool $show): static
    {
        $this->customText1ShowInTable = $show;
        return $this;
    }
    public function getCustomText1Order(): int
    {
        return $this->customText1Order;
    }
    public function setCustomText1Order(int $order): static
    {
        $this->customText1Order = $order;
        return $this;
    }

    public function isCustomText2State(): bool
    {
        return $this->customText2State;
    }
    public function setCustomText2State(bool $state): static
    {
        $this->customText2State = $state;
        return $this;
    }
    public function getCustomText2Name(): ?string
    {
        return $this->customText2Name;
    }
    public function setCustomText2Name(?string $name): static
    {
        $this->customText2Name = $name;
        return $this;
    }
    public function getCustomText2Description(): ?string
    {
        return $this->customText2Description;
    }
    public function setCustomText2Description(?string $desc): static
    {
        $this->customText2Description = $desc;
        return $this;
    }
    public function isCustomText2ShowInTable(): bool
    {
        return $this->customText2ShowInTable;
    }
    public function setCustomText2ShowInTable(bool $show): static
    {
        $this->customText2ShowInTable = $show;
        return $this;
    }
    public function getCustomText2Order(): int
    {
        return $this->customText2Order;
    }
    public function setCustomText2Order(int $order): static
    {
        $this->customText2Order = $order;
        return $this;
    }

    public function isCustomText3State(): bool
    {
        return $this->customText3State;
    }
    public function setCustomText3State(bool $state): static
    {
        $this->customText3State = $state;
        return $this;
    }
    public function getCustomText3Name(): ?string
    {
        return $this->customText3Name;
    }
    public function setCustomText3Name(?string $name): static
    {
        $this->customText3Name = $name;
        return $this;
    }
    public function getCustomText3Description(): ?string
    {
        return $this->customText3Description;
    }
    public function setCustomText3Description(?string $desc): static
    {
        $this->customText3Description = $desc;
        return $this;
    }
    public function isCustomText3ShowInTable(): bool
    {
        return $this->customText3ShowInTable;
    }
    public function setCustomText3ShowInTable(bool $show): static
    {
        $this->customText3ShowInTable = $show;
        return $this;
    }
    public function getCustomText3Order(): int
    {
        return $this->customText3Order;
    }
    public function setCustomText3Order(int $order): static
    {
        $this->customText3Order = $order;
        return $this;
    }

    // Number fields
    public function isCustomNumber1State(): bool
    {
        return $this->customNumber1State;
    }
    public function setCustomNumber1State(bool $state): static
    {
        $this->customNumber1State = $state;
        return $this;
    }
    public function getCustomNumber1Name(): ?string
    {
        return $this->customNumber1Name;
    }
    public function setCustomNumber1Name(?string $name): static
    {
        $this->customNumber1Name = $name;
        return $this;
    }
    public function getCustomNumber1Description(): ?string
    {
        return $this->customNumber1Description;
    }
    public function setCustomNumber1Description(?string $desc): static
    {
        $this->customNumber1Description = $desc;
        return $this;
    }
    public function isCustomNumber1ShowInTable(): bool
    {
        return $this->customNumber1ShowInTable;
    }
    public function setCustomNumber1ShowInTable(bool $show): static
    {
        $this->customNumber1ShowInTable = $show;
        return $this;
    }
    public function getCustomNumber1Order(): int
    {
        return $this->customNumber1Order;
    }
    public function setCustomNumber1Order(int $order): static
    {
        $this->customNumber1Order = $order;
        return $this;
    }

    public function isCustomNumber2State(): bool
    {
        return $this->customNumber2State;
    }
    public function setCustomNumber2State(bool $state): static
    {
        $this->customNumber2State = $state;
        return $this;
    }
    public function getCustomNumber2Name(): ?string
    {
        return $this->customNumber2Name;
    }
    public function setCustomNumber2Name(?string $name): static
    {
        $this->customNumber2Name = $name;
        return $this;
    }
    public function getCustomNumber2Description(): ?string
    {
        return $this->customNumber2Description;
    }
    public function setCustomNumber2Description(?string $desc): static
    {
        $this->customNumber2Description = $desc;
        return $this;
    }
    public function isCustomNumber2ShowInTable(): bool
    {
        return $this->customNumber2ShowInTable;
    }
    public function setCustomNumber2ShowInTable(bool $show): static
    {
        $this->customNumber2ShowInTable = $show;
        return $this;
    }
    public function getCustomNumber2Order(): int
    {
        return $this->customNumber2Order;
    }
    public function setCustomNumber2Order(int $order): static
    {
        $this->customNumber2Order = $order;
        return $this;
    }

    public function isCustomNumber3State(): bool
    {
        return $this->customNumber3State;
    }
    public function setCustomNumber3State(bool $state): static
    {
        $this->customNumber3State = $state;
        return $this;
    }
    public function getCustomNumber3Name(): ?string
    {
        return $this->customNumber3Name;
    }
    public function setCustomNumber3Name(?string $name): static
    {
        $this->customNumber3Name = $name;
        return $this;
    }
    public function getCustomNumber3Description(): ?string
    {
        return $this->customNumber3Description;
    }
    public function setCustomNumber3Description(?string $desc): static
    {
        $this->customNumber3Description = $desc;
        return $this;
    }
    public function isCustomNumber3ShowInTable(): bool
    {
        return $this->customNumber3ShowInTable;
    }
    public function setCustomNumber3ShowInTable(bool $show): static
    {
        $this->customNumber3ShowInTable = $show;
        return $this;
    }
    public function getCustomNumber3Order(): int
    {
        return $this->customNumber3Order;
    }
    public function setCustomNumber3Order(int $order): static
    {
        $this->customNumber3Order = $order;
        return $this;
    }

    // Link fields
    public function isCustomLink1State(): bool
    {
        return $this->customLink1State;
    }
    public function setCustomLink1State(bool $state): static
    {
        $this->customLink1State = $state;
        return $this;
    }
    public function getCustomLink1Name(): ?string
    {
        return $this->customLink1Name;
    }
    public function setCustomLink1Name(?string $name): static
    {
        $this->customLink1Name = $name;
        return $this;
    }
    public function getCustomLink1Description(): ?string
    {
        return $this->customLink1Description;
    }
    public function setCustomLink1Description(?string $desc): static
    {
        $this->customLink1Description = $desc;
        return $this;
    }
    public function isCustomLink1ShowInTable(): bool
    {
        return $this->customLink1ShowInTable;
    }
    public function setCustomLink1ShowInTable(bool $show): static
    {
        $this->customLink1ShowInTable = $show;
        return $this;
    }
    public function getCustomLink1Order(): int
    {
        return $this->customLink1Order;
    }
    public function setCustomLink1Order(int $order): static
    {
        $this->customLink1Order = $order;
        return $this;
    }

    public function isCustomLink2State(): bool
    {
        return $this->customLink2State;
    }
    public function setCustomLink2State(bool $state): static
    {
        $this->customLink2State = $state;
        return $this;
    }
    public function getCustomLink2Name(): ?string
    {
        return $this->customLink2Name;
    }
    public function setCustomLink2Name(?string $name): static
    {
        $this->customLink2Name = $name;
        return $this;
    }
    public function getCustomLink2Description(): ?string
    {
        return $this->customLink2Description;
    }
    public function setCustomLink2Description(?string $desc): static
    {
        $this->customLink2Description = $desc;
        return $this;
    }
    public function isCustomLink2ShowInTable(): bool
    {
        return $this->customLink2ShowInTable;
    }
    public function setCustomLink2ShowInTable(bool $show): static
    {
        $this->customLink2ShowInTable = $show;
        return $this;
    }
    public function getCustomLink2Order(): int
    {
        return $this->customLink2Order;
    }
    public function setCustomLink2Order(int $order): static
    {
        $this->customLink2Order = $order;
        return $this;
    }

    public function isCustomLink3State(): bool
    {
        return $this->customLink3State;
    }
    public function setCustomLink3State(bool $state): static
    {
        $this->customLink3State = $state;
        return $this;
    }
    public function getCustomLink3Name(): ?string
    {
        return $this->customLink3Name;
    }
    public function setCustomLink3Name(?string $name): static
    {
        $this->customLink3Name = $name;
        return $this;
    }
    public function getCustomLink3Description(): ?string
    {
        return $this->customLink3Description;
    }
    public function setCustomLink3Description(?string $desc): static
    {
        $this->customLink3Description = $desc;
        return $this;
    }
    public function isCustomLink3ShowInTable(): bool
    {
        return $this->customLink3ShowInTable;
    }
    public function setCustomLink3ShowInTable(bool $show): static
    {
        $this->customLink3ShowInTable = $show;
        return $this;
    }
    public function getCustomLink3Order(): int
    {
        return $this->customLink3Order;
    }
    public function setCustomLink3Order(int $order): static
    {
        $this->customLink3Order = $order;
        return $this;
    }

    // Bool fields
    public function isCustomBool1State(): bool
    {
        return $this->customBool1State;
    }
    public function setCustomBool1State(bool $state): static
    {
        $this->customBool1State = $state;
        return $this;
    }
    public function getCustomBool1Name(): ?string
    {
        return $this->customBool1Name;
    }
    public function setCustomBool1Name(?string $name): static
    {
        $this->customBool1Name = $name;
        return $this;
    }
    public function getCustomBool1Description(): ?string
    {
        return $this->customBool1Description;
    }
    public function setCustomBool1Description(?string $desc): static
    {
        $this->customBool1Description = $desc;
        return $this;
    }
    public function isCustomBool1ShowInTable(): bool
    {
        return $this->customBool1ShowInTable;
    }
    public function setCustomBool1ShowInTable(bool $show): static
    {
        $this->customBool1ShowInTable = $show;
        return $this;
    }
    public function getCustomBool1Order(): int
    {
        return $this->customBool1Order;
    }
    public function setCustomBool1Order(int $order): static
    {
        $this->customBool1Order = $order;
        return $this;
    }

    public function isCustomBool2State(): bool
    {
        return $this->customBool2State;
    }
    public function setCustomBool2State(bool $state): static
    {
        $this->customBool2State = $state;
        return $this;
    }
    public function getCustomBool2Name(): ?string
    {
        return $this->customBool2Name;
    }
    public function setCustomBool2Name(?string $name): static
    {
        $this->customBool2Name = $name;
        return $this;
    }
    public function getCustomBool2Description(): ?string
    {
        return $this->customBool2Description;
    }
    public function setCustomBool2Description(?string $desc): static
    {
        $this->customBool2Description = $desc;
        return $this;
    }
    public function isCustomBool2ShowInTable(): bool
    {
        return $this->customBool2ShowInTable;
    }
    public function setCustomBool2ShowInTable(bool $show): static
    {
        $this->customBool2ShowInTable = $show;
        return $this;
    }
    public function getCustomBool2Order(): int
    {
        return $this->customBool2Order;
    }
    public function setCustomBool2Order(int $order): static
    {
        $this->customBool2Order = $order;
        return $this;
    }

    public function isCustomBool3State(): bool
    {
        return $this->customBool3State;
    }
    public function setCustomBool3State(bool $state): static
    {
        $this->customBool3State = $state;
        return $this;
    }
    public function getCustomBool3Name(): ?string
    {
        return $this->customBool3Name;
    }
    public function setCustomBool3Name(?string $name): static
    {
        $this->customBool3Name = $name;
        return $this;
    }
    public function getCustomBool3Description(): ?string
    {
        return $this->customBool3Description;
    }
    public function setCustomBool3Description(?string $desc): static
    {
        $this->customBool3Description = $desc;
        return $this;
    }
    public function isCustomBool3ShowInTable(): bool
    {
        return $this->customBool3ShowInTable;
    }
    public function setCustomBool3ShowInTable(bool $show): static
    {
        $this->customBool3ShowInTable = $show;
        return $this;
    }
    public function getCustomBool3Order(): int
    {
        return $this->customBool3Order;
    }
    public function setCustomBool3Order(int $order): static
    {
        $this->customBool3Order = $order;
        return $this;
    }
}
