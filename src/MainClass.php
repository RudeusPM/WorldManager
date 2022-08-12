<?php

declare(strict_types=1);

namespace WorldManager;

use pocketmine\plugin\PluginBase;

class MainClass extends PluginBase
{
    public function onEnable(): void
    {
        $manager = new WManager($this);
        $manager->registerWorldGenerator();
        $manager->loadAllWorlds();
        $manager->registerCommands();
    }
}