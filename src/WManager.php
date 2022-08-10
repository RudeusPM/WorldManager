<?php

declare(strict_types=1);

namespace WorldManager;

use pocketmine\player\Player;
use pocketmine\world\generator\GeneratorManager;
use pocketmine\world\WorldCreationOptions;
use WorldManager\generator\void\VoidGenerator;

/**
 * @todo find a methode to remove system path when loading worlds
 */
class WManager
{
    public function __construct(private MainClass $plugin)
    {
    }

    public function generate(Player $player, string $name, string $option): void
    {
        if (!is_file($this->plugin->getServer()->getDataPath() . "worlds/{$name}")) {
            $generator = GeneratorManager::getInstance()->getGenerator($option);
            if ($generator !== null) {
                $this->plugin->getServer()->getWorldManager()->generateWorld($name, WorldCreationOptions::create()
                    ->setSeed(0)
                    ->setGeneratorClass($generator->getGeneratorClass())
                );
            }
        } else {
            $player->sendMessage("§8[§cWorldManager§8] §7Die Welt {$name} gibt es bereits!");
        }
    }

    /**
     * Display Name kann nicht mehr geändert werden NUR Folder Name!!
     */
    public function rename(Player $player, string $oldName, string $newName): void
    {
        if ($this->plugin->getServer()->getWorldManager()->isWorldGenerated($oldName)) {
            if (!$this->plugin->getServer()->getWorldManager()->isWorldGenerated($newName)) {
                if ($this->plugin->getServer()->getWorldManager()->getDefaultWorld() !== $oldName) {
                    $from = $this->plugin->getServer()->getDataPath() . "worlds/{$oldName}";
                    $to = $this->plugin->getServer()->getDataPath() . "worlds/{$newName}";

                    rename($from, $to);

                    $player->sendMessage("§8[§cWorldManager§8] §7Du hast die Welt §c{$oldName} §7zu §a{$newName} §7umbennant!");
                } else {
                    $player->sendMessage("8[§cWorldManager§8] §7Die Welt {$oldName} ist die Default World, eine Änderung ist nicht möglich");
                }
            } else {
                $player->sendMessage("§8[§cWorldManager§8] §7Die Welt {$newName} gibt es bereits!");
            }
        } else {
            $player->sendMessage("§8[§cWorldManager§8] §7Die Welt {$oldName} gibt es nicht!");
        }
    }

    public function delete(Player $player, string $world): void
    {
        if ($this->plugin->getServer()->getWorldManager()->isWorldGenerated($world) and file_exists($this->plugin->getServer()->getDataPath() . "wolrds/{$world}")) {
            $level = $this->plugin->getServer()->getWorldManager()->getWorldByName($world);
            if ($this->plugin->getServer()->getWorldManager()->getDefaultWorld() !== $level) {
                if ($level->isLoaded()) {
                    $players = $level->getPlayers();
                    if (count($players) > 0) {
                        foreach ($players as $player) {
                            $defworld = $this->plugin->getServer()->getWorldManager()->getDefaultWorld();
                            $player->teleport($defworld->getSpawnLocation());
                        }
                    }
                    $this->plugin->getServer()->getWorldManager()->unloadWorld($level);
                }
                $this->removeDir($world);
            } else {
                $player->sendMessage("8[§cWorldManager§8] §7Die Welt {$world} ist die Default World, eine Änderung ist nicht möglich");
            }
        } else {
            $player->sendMessage("§8[§cWorldManager§8] §7Die Welt {$world} gibt es nicht!");
        }
    }

    public function teleport(Player $player, string $world): void
    {
        if ($this->plugin->getServer()->getWorldManager()->isWorldGenerated($world)) {
            $level = $this->plugin->getServer()->getWorldManager()->getWorldByName($world);
            $player->teleport($level->getSafeSpawn());
        } else {
            $player->sendMessage("§8[§cWorldManager§8] §7Die Welt {$world} gibt es nicht!");
        }
    }

    function removeDir($world): void
    {
        $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($worldPath = $this->plugin->getServer()->getDataPath() . "worlds/{$world}", \FilesystemIterator::SKIP_DOTS), \RecursiveIteratorIterator::CHILD_FIRST);
        /**
         * @var \SplFileInfo $file
         */
        foreach ($files as $file) {
            if ($filePath = $file->getRealPath()) {
                if ($file->isFile()) {
                    unlink($filePath);
                } else {
                    rmdir($filePath);
                }
            }
        }
        rmdir($worldPath);
    }

    public function setDefaultWorld(Player $player, string $world): void
    {
        if ($this->plugin->getServer()->getWorldManager()->isWorldGenerated($world)) {
            $level = $this->plugin->getServer()->getWorldManager()->getWorldByName($world);
            $this->plugin->getServer()->getWorldManager()->setDefaultWorld($level);

            $player->sendMessage("§8[§cWorldManager§8] §7Die Welt {$world} ist jetzt die Default Welt!");
        } else {
            $player->sendMessage("§8[§cWorldManager§8] §7Die Welt {$world} gibt es nicht!");
        }
    }

    public function getAllWorlds(): string
    {
        $path = $this->plugin->getServer()->getDataPath() . "worlds";
        $worlds = glob("{$path}/*", GLOB_ONLYDIR);
        foreach ($worlds as $world) {
            return $world;
        }
        return "error";
    }

    public function loadAllWorlds(): void
    {
        $path = $this->plugin->getServer()->getDataPath() . "worlds";
        $worlds = glob("{$path}/*", GLOB_ONLYDIR);
        foreach ($worlds as $world) {
            $this->plugin->getServer()->getWorldManager()->loadWorld($world);
            $this->plugin->getLogger()->info("§7Welt: §a{$world} §7wurde geladen!");
        }
    }

    public function registerWorldGenerator(): void
    {
        $generatorClass = VoidGenerator::class;
        $generatorName = "void";
        GeneratorManager::getInstance()->addGenerator($generatorClass, $generatorName, fn() => null);
    }

    public function registerCommands(): void
    {

    }
}