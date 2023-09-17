<?php

namespace App\Entity;

use App\Repository\TeamRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TeamRepository::class)]
class Team
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['show_team'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Unique]
    #[Groups(['show_team'])]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'teamOne', targetEntity: Game::class, orphanRemoval: true)]
    private Collection $gamesHome;

    #[ORM\OneToMany(mappedBy: 'teamTwo', targetEntity: Game::class, orphanRemoval: true)]
    private Collection $gamesAway;

    public function __construct()
    {
        $this->gamesHome = new ArrayCollection();
        $this->gamesAway = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Game>
     */
    public function getGamesHome(): Collection
    {
        return $this->gamesHome;
    }

    public function addGameHome(Game $game): static
    {
        if (!$this->gamesHome->contains($game)) {
            $this->gamesHome->add($game);
            $game->setTeamOne($this);
        }

        return $this;
    }

    public function removeGameHome(Game $game): static
    {
        if ($this->gamesHome->removeElement($game)) {
            // set the owning side to null (unless already changed)
            if ($game->getTeamOne() === $this) {
                $game->setTeamOne(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Game>
     */
    public function getGamesAway(): Collection
    {
        return $this->gamesAway;
    }

    public function addGamesAway(Game $gamesAway): static
    {
        if (!$this->gamesAway->contains($gamesAway)) {
            $this->gamesAway->add($gamesAway);
            $gamesAway->setTeamTwo($this);
        }

        return $this;
    }

    public function removeGamesAway(Game $gamesAway): static
    {
        if ($this->gamesAway->removeElement($gamesAway)) {
            // set the owning side to null (unless already changed)
            if ($gamesAway->getTeamTwo() === $this) {
                $gamesAway->setTeamTwo(null);
            }
        }

        return $this;
    }
}
