<?php

namespace EventTao\Events;

use EventTao\Api\Equipe;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

class Chat implements Listener{

    public function __construct($pl)
    {
        $this->customTeams = new Equipe();
        $this->plugin = $pl;
        $pl->getServer()->getPluginManager()->registerEvents($this, $pl);
    }

    private $customTeams;
    public function chatequipe(PlayerChatEvent $event){
        $msg = $event->getMessage();
        $player = $event->getPlayer();
        $name = $this->customTeams->getTeamName($player);
        echo $name;
        $event->cancel();
        if ($name !== null){
            Server::getInstance()->broadcastMessage("§8[§1Joueur§8] §8[§a{$name}§8] §c> §f{$msg}");
        }else{
            Server::getInstance()->broadcastMessage("§8[§1Joueur§8] §8[§aSansEquipe§8] §c> §f{$msg}");
        }

    }
}