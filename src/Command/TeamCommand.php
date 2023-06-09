<?php

namespace EventTao\Command;


use EventTao\Api\Equipe;
use EventTao\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class TeamCommand extends Command
{

    private $customTeams;
    private $main;

    public function __construct(Main $main)
    {
        parent::__construct("team", "Gérer les équipes personnalisées", "/team create|join|leave|remove [nom]");
        $this->customTeams = new Equipe(); // Remplacez CustomTeams par la classe correspondante
        $this->main = $main;

    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) : bool {
        if(!$sender instanceof Player) {
            $sender->sendMessage(TextFormat::RED . "Cette commande ne peut être exécutée que par un joueur");
            return true;
        }

        if(count($args) < 1) {
            return false;
        }

        switch(strtolower($args[0])) {
            case "create":
                if(count($args) < 4) {
                    $sender->sendMessage(TextFormat::RED . "Usage: /team create <nom> <couleur> <nom_affiché>");
                    return true;
                }

                $name = strtolower($args[1]);
                $color = $args[2];
                $displayName = implode(" ", array_slice($args, 3));

                if($this->customTeams->getTeam($name) !== null) {
                    $sender->sendMessage(TextFormat::RED . "Une équipe avec ce nom existe déjà");
                    return true;
                }

                $team = $this->customTeams->createTeam($name, $color, $displayName);
                $sender->sendMessage(TextFormat::GREEN . "L'équipe \"" . $team->getDisplayName() . "\" a été créée");
                break;

            case "join":
                if(count($args) < 2) {
                    $sender->sendMessage(TextFormat::RED . "Usage: /team join <nom>");
                    return true;
                }

                $name = strtolower($args[1]);
                $team = $this->customTeams->getTeam($name);

                if($team === null) {
                    $sender->sendMessage(TextFormat::RED . "Cette équipe n'existe pas");
                    return true;
                }

                $currentTeam = $this->customTeams->getTeamOfPlayer($sender);
                if($currentTeam !== null) {
                    $sender->sendMessage(TextFormat::RED . "Vous êtes déjà dans une équipe");
                    return true;
                }

                $this->customTeams->addToTeam($sender, $team);
                $sender->sendMessage(TextFormat::GREEN . "Vous avez rejoint l'équipe \"" . $team->getDisplayName() . "\"");
                // Création d'un score pour le joueur dans l'équipe

                break;

            case "leave":
                $team = $this->customTeams->getTeamOfPlayer($sender);

                if($team === null) {
                    $sender->sendMessage(TextFormat::RED . "Vous n'êtes dans aucune équipe");
                    return true;
                }

                $this->customTeams->removeFromTeam($sender, $team);
                $sender->sendMessage(TextFormat::GREEN . "Vous avez quitté l'équipe \"" . $team->getDisplayName() . "\"");
                break;

            case "remove":
                if(count($args) < 2) {
                    $sender->sendMessage(TextFormat::RED . "Usage: /team remove <nom>");
                    return true;
                }

                if(!$sender->isOp()) {
                    $sender->sendMessage(TextFormat::RED . "Vous n'avez pas la permission de supprimer des équipes");
                    return true;
                }

                $name = strtolower($args[1]);
                $team = $this->customTeams->getTeam($name);

                if($team === null) {
                    $sender->sendMessage(TextFormat::RED . "Cette équipe n'existe pas");
                    return true;
                }

                unset($this->customTeams[$name]);
                $sender->sendMessage(TextFormat::GREEN . "L'équipe \"" . $team->getDisplayName() . "\" a été supprimée");
                break;

            case "list":
                $teams = $this->customTeams->getAllTeams();
                $sender->sendMessage(TextFormat::AQUA . "Liste des équipes :");
                foreach($teams as $team) {
                    $sender->sendMessage($team->getColor() . $team->getDisplayName() . TextFormat::GRAY . " (" . count($team->getPlayers()) . ")");
                }
                break;

            case "info":
                $team = $this->customTeams->getTeamOfPlayer($sender);

                if($team === null) {
                    $sender->sendMessage(TextFormat::RED . "Vous n'êtes dans aucune équipe");
                    return true;
                }

                $players = array_map(function(Player $player) { return $player->getName(); }, $team->getPlayers());
                $sender->sendMessage(TextFormat::AQUA . "Informations sur l'équipe \"" . $team->getDisplayName() . "\":");
                $sender->sendMessage($team->getColor() . "Couleur : " . $team->getColor());
                $sender->sendMessage("Joueurs : " . implode(", ", $players));
                break;

            default:
                return false;
        }

        return true;
    }
}