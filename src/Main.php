<?php

declare(strict_types=1);

namespace EventTao;

use EventTao\Api\CustomTeams;
use EventTao\Command\TeamCommand;
use EventTao\Events\Chat;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;


class Main extends PluginBase{


    protected function onEnable(): void
    {

        $command = $this->getServer()->getCommandMap();
        $command->register("equipe", new TeamCommand($this));
        $this->getServer()->getPluginManager()->registerEvents(new Chat($this),$this);

    }
}