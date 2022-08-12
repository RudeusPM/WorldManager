<?php

declare(strict_types=1);

namespace WorldManager\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use WorldManager\MainClass;
use WorldManager\WManager;

class WMCommand extends Command
{
    public function __construct(private MainClass $plugin)
    {
        parent::__construct("world", "§cWelt Managament Command", null, ["wm"]);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        $manager = new WManager($this->plugin);

        if ($sender instanceof Player) {
            $player = $sender;
            if (!isset($args[0])) {
                $player->sendMessage("§8-=== §cWorldManager §8===-");
                $player->sendMessage("§7/wm create (name) (generator)");
                $player->sendMessage("§7/wm remove (worldName)");
                $player->sendMessage("§7/wm rename (oldName) (newName)");
                $player->sendMessage("§7/wm default (worldName)");
                $player->sendMessage("§7/wm tp (worldName)");
                $player->sendMessage("§7/wm list");
                $player->sendMessage("§8-=== §cWorldManager §8===-");
            } elseif ($args[0] === "create") {
                if (isset($args[1]) and isset($args[2])) {
                    $name = $args[1];
                    $generator = $args[2];

                    $manager->generate($sender, $name, $generator);
                } else {
                    $player->sendMessage("§7/wm create (name) (generator)");
                }
            } elseif ($args[0] === "remove") {
                if (isset($args[1])) {
                    $name = $args[1];

                    $manager->delete($player, $name);
                } else {
                    $player->sendMessage("§7/wm remove (worldName)");
                }
            } elseif ($args[0] === "rename") {
                if (isset($args[1]) and isset($args[2])) {
                    $oldName = $args[1];
                    $newName = $args[2];

                    $manager->rename($player, $oldName, $newName);
                } else {
                    $player->sendMessage("§7/wm rename (oldName) (newName)");
                }
            } elseif ($args[0] === "default") {
                if (isset($args[1])) {
                    $name = $args[1];

                    $manager->setDefaultWorld($player, $name);
                } else {
                    $player->sendMessage("§7/wm default (worldName)");
                }
            } elseif ($args[0] === "tp") {
                if (isset($args[1])) {
                    $name = $args[1];

                    $manager->teleport($player, $name);
                } else {
                    $player->sendMessage("§7/wm tp (worldName)");
                }
            } elseif ($args[0] === "list") {
                $manager->getAllWorlds($player);
            }
        } else {
            $sender->sendMessage("§cDu kannst alle Commands nur ingame ausführen!");
        }
    }
}