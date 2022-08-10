<?php

declare(strict_types=1);

namespace WorldManager;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class WMCommand extends Command
{
    public function __construct(private MainClass $plugin)
    {
        parent::__construct("world", "§cWelt Managament Command", "", ["wm"]);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof Player) {
            if (!isset($args[0])) {
                $player = $sender;
                $player->sendMessage("§8-=== §cWorldManager §8===-");
                $player->sendMessage("§7/wm create (name) (generator)");
                $player->sendMessage("§7/wm remove (worldName)");
                $player->sendMessage("§7/wm rename (oldName) (newName)");
                $player->sendMessage("§7/wm default (worldName)");
                $player->sendMessage("§7/wm tp (worldName)");
                $player->sendMessage("§7/wm list");
                $player->sendMessage("§8-=== §cWorldManager §8===-");
            }
        } else {
            $sender->sendMessage("§cDu kannst alle Commands nur ingame ausführen!");
        }
    }
}