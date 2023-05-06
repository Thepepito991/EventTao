<?php

namespace EventTao\Api;

use pocketmine\player\Player;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Server;
use pocketmine\utils\TextFormat;


class Equipe {

    private $teams = [];
    private $equipes = [];





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
        return array_keys($this->equipes);
    }

    /**
     * Récupère les joueurs appartenant à l'équipe spécifiée.
     *
     * @param string $teamName
     * @return array
     */
    public function getPlayers(string $teamName): array {
        if (isset($this->equipes[$teamName])) {
            return $this->equipes[$teamName];
        } else {
            return [];
        }
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

    /**
     * Ajoute un joueur à une équipe spécifiée.
     *
     * @param string $teamName
     * @param string $playerName
     */
    public function addPlayerToTeam(string $playerName, string $teamName) {
        if (!isset($this->equipes[$teamName])) {
            $this->equipes[$teamName] = [];
        }

        $this->equipes[$teamName][] = $playerName;
    }

    /**
     * Supprime un joueur d'une équipe spécifiée.
     *
     * @param string $teamName
     * @param string $playerName
     * @return bool
     */
    public function removePlayerFromTeam(string $playerName, string $teamName): bool {
        if (isset($this->equipes[$teamName])) {
            $index = array_search($playerName, $this->equipes[$teamName]);
            if ($index !== false) {
                unset($this->equipes[$teamName][$index]);
                return true;
            }
        }
        return false;
    }

}






