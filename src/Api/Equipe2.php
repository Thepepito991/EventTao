<?php

namespace EventTao\Api;
use pocketmine\player\Player;

class Equipe2 {

    private $name;
    private $color;
    private $displayName;
    private $members = [];

    public function __construct(string $name, string $color, string $displayName) {
        $this->name = $name;
        $this->color = $color;
        $this->displayName = $displayName;
    }

    public function getName() : string {
        return $this->name;
    }

    public function getColor() : string {
        return $this->color;
    }

    public function getDisplayName() : string {
        return $this->displayName;
    }

    public function isMember(Player $player) : bool {
        return in_array($player->getName(), $this->members);
    }

    public function addMember(Player $player) : void {
        $this->members[] = $player->getName();

        // Ajouter le joueur à l'équipe sur le tableau de bord

    }

    public function removeMember(Player $player) : void {
        $index = array_search($player->getName(), $this->members);
        if($index !== false) {
            unset($this->members[$index]);

            // Supprimer le joueur de l'équipe sur le tableau de bord



        }
    }

}
