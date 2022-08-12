<?php

declare(strict_types=1);

namespace WorldManager\generator\void;

use pocketmine\block\BlockTypeIds;
use pocketmine\world\ChunkManager;
use pocketmine\world\generator\Generator;

class VoidGenerator extends Generator
{
    public function generateChunk(ChunkManager $world, int $chunkX, int $chunkZ): void
    {
        $chunk = $world->getChunk($chunkX, $chunkZ);
        if ($chunkX == 16 and $chunkZ == 16) $chunk->setFullBlock(0, 64, 0, BlockTypeIds::STONE);
    }

    public function populateChunk(ChunkManager $world, int $chunkX, int $chunkZ): void
    {
    }
}