<?php

namespace EventTao\Api;

use pocketmine\player\Player;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;


class Equipe {

    private $teams = [];


    public function createTeam(string $name, string $color, string $displayName) : Equipe2 {
        $team = new Equipe2($name, $color, $displayName);
        $this->teams[strtolower($name)] = $team;
        return $team;
    }
    /**
     * Récupère toutes les équipes existantes.
     *
     * @return array
     */
    public function getAllTeams(): array {
        // Code pour récupérer toutes les équipes de votre système
        // Retournez les équipes dans un tableau
        return []; // Remplacez par le code pour récupérer les équipes réelles
    }

    public function getTeam(string $name) : ?Equipe2 {
        return $this->teams[strtolower($name)] ?? null;
    }

    public function getTeamOfPlayer( $player) : ?Equipe2 {
        foreach($this->teams as $team) {
            if($team->isMember($player)) {
                return $team;
            }
        }
        return null;
    }
    public function getTeamName(Player $player): ?string {
        $team = $this->getTeamOfPlayer($player);
        if ($team !== null) {
            return $team->getDisplayName();
        }
        return null;
    }

    public function addToTeam( $player, Equipe2 $team) : void {
        $team->addMember($player);
    }

    public function removeFromTeam(Player $player, Equipe2 $team) : void {
        $team->removeMember($player);
    }

}






