<?php
/*
 * This file is a part of UltimateParticles.
 * Copyright (C) 2017 hoyinm14mc
 *
 * UltimateParticles is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * UltimateParticles is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with UltimateParticles. If not, see <http://www.gnu.org/licenses/>.
 */

namespace hoyinm14mc\ultimateparticles;

use pocketmine\item\Item;
use pocketmine\level\particle\AngryVillagerParticle;
use pocketmine\level\particle\BubbleParticle;
use pocketmine\level\particle\CriticalParticle;
use pocketmine\level\particle\DustParticle;
use pocketmine\level\particle\EnchantmentTableParticle;
use pocketmine\level\particle\EnchantParticle;
use pocketmine\level\particle\EntityFlameParticle;
use pocketmine\level\particle\ExplodeParticle;
use pocketmine\level\particle\FlameParticle;
use pocketmine\level\particle\HappyVillagerParticle;
use pocketmine\level\particle\HeartParticle;
use pocketmine\level\particle\HugeExplodeParticle;
use pocketmine\level\particle\InkParticle;
use pocketmine\level\particle\InstantEnchantParticle;
use pocketmine\level\particle\ItemBreakParticle;
use pocketmine\level\particle\LavaParticle;
use pocketmine\level\particle\LavaDripParticle;
use pocketmine\level\particle\MobSpawnParticle;
use pocketmine\level\particle\PortalParticle;
use pocketmine\level\particle\RainSplashParticle;
use pocketmine\level\particle\RedstoneParticle;
use pocketmine\level\particle\SmokeParticle;
use pocketmine\level\particle\SplashParticle;
use pocketmine\level\particle\SporeParticle;
use pocketmine\level\particle\TerrainParticle;
use pocketmine\level\particle\WaterDripParticle;
use pocketmine\level\particle\WaterParticle;
use pocketmine\level\Position;
use pocketmine\math\Vector3;
use pocketmine\Player;

class Particles
{

    private $plugin;
    //Spiral mode is now forced to be animated
    private $spiralOrder = 0;

    public function __construct(UltimateParticles $plugin)
    {
        $this->plugin = $plugin;
    }

    public function showParticleEffects(Player $player, Position $from = null): bool
    {
        $t = $this->plugin->data->getAll();
        if ($player === null) {
            return false;
        }
        if ($this->plugin->playerProfileExists($player->getName()) !== true) {
            return false;
        }
        //Display the particles
        foreach ($t[$player->getName()]["particles"] as $particle => $value) {
            if ($t[$player->getName()]["particles"][$particle]["type"] == "particle") {
                for ($i = 0; $i < (int)$t[$player->getName()]["particles"][$particle]["amplifier"]; $i++) {
                    if ($t[$player->getName()]["particles"][$particle]["shape"] == "tail") {
                        $player->getLevel()->addParticle(
                            $this->getParticle(new Vector3($from->x, $from->y + 1, $from->z), $particle)
                        );
                    } elseif ($t[$player->getName()]["particles"][$particle]["shape"] == "spiral") {
                        $array = [];
                        for ($yaw = 3, $i = $player->y; $i <= ($player->y + 2.5); $yaw = $yaw + 2 * M_PI / 20, $i = $i + 1 / 100) {
                            $array[] = new Vector3((sin($yaw) * -1) + $player->x, $i, sin(90 - $yaw) + $player->z);
                            $player->getLevel()->addParticle(
                                $this->getParticle($array[$this->spiralOrder], $particle)
                            );
                        }
                    }
                }
            }
        }
        return true;
    }

    public function getParticle(Vector3 $vector3, string $particle, $data = null)
    {
        switch (strtolower($particle)) {
            case "explode":
                return new ExplodeParticle($vector3);
            case "hugeexplode":
                return new HugeExplodeParticle($vector3);
            case "bubble":
                return new BubbleParticle($vector3);
            case "splash":
                return new SplashParticle($vector3);
            case "water":
                return new WaterParticle($vector3);
            case "critical":
                return new CriticalParticle($vector3);
            case "enchant":
                return new EnchantParticle($vector3);
            case "instantenchant":
                return new InstantEnchantParticle($vector3);
            case "smoke":
                return new SmokeParticle($vector3);
            case "waterdrip":
                return new WaterDripParticle($vector3);
            case "lavadrip":
                return new LavaDripParticle($vector3);
            case "spore":
                return new SporeParticle($vector3);
            case "portal":
                return new PortalParticle($vector3);
            case "entityflame":
                return new EntityFlameParticle($vector3);
            case "flame":
                return new FlameParticle($vector3);
            case "lava":
                return new LavaParticle($vector3);
            case "redstone":
                return new RedstoneParticle($vector3, ($data === null ? 1 : $data));
            case "snowball":
                return new ItemBreakParticle($vector3, Item::get(Item::SNOWBALL));
            case "slimeball":
                return new ItemBreakParticle($vector3, Item::get(Item::SLIMEBALL));
            case "heart":
                return new HeartParticle($vector3);
            case "ink":
                return new InkParticle($vector3);
            case "enchantmenttable":
                return new EnchantmentTableParticle($vector3);
            case "happyvillager":
                return new HappyVillagerParticle($vector3);
            case "angryvillager":
                return new AngryVillagerParticle($vector3);
            case "rainsplash":
                return new RainSplashParticle($vector3);
            case "mobspawn":
                return new MobSpawnParticle($vector3);
            case "colorful":
                return new DustParticle ($vector3, rand(0, 255), rand(0, 255), rand(0, 255));
        }
        if (substr($particle, 0, 5) == "item_") {
            return new ItemBreakParticle($vector3, Item::get((int)substr($particle, 6)));
        }
        if (substr($particle, 0, 6) == "block_") {
            return new TerrainParticle($vector3, Item::get((int)substr($particle, 7)));
        }
        //dust_rgb1,rgb2,rgb3
        if (substr($particle, 0, 5) == "dust_") {
            $rgb = explode(substr($particle, 6), ",");
            if ($rgb[0] > -1 && $rgb[0] < 256 && $rgb[1] > -1 && $rgb[1] < 256 && $rgb[2] > -1 && $rgb[2] < 256) {
                return new DustParticle($vector3, $rgb[0], $rgb[1], $rgb[2]);
            }
        }
        return new ItemBreakParticle($vector3, Item::get(Item::AIR));
    }

}