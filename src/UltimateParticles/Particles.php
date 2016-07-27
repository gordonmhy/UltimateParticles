<?php

/*
 * This file is a part of UltimateParticles.
 * Copyright (C) 2016 hoyinm14mc
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
namespace UltimateParticles;

use pocketmine\math\Vector3;
use pocketmine\item\Item;
use pocketmine\block\Block;
use pocketmine\level\particle\BubbleParticle;
use pocketmine\level\particle\CriticalParticle;
use pocketmine\level\particle\DustParticle;
use pocketmine\level\particle\EnchantParticle;
use pocketmine\level\particle\InstantEnchantParticle;
use pocketmine\level\particle\ExplodeParticle;
use pocketmine\level\particle\LargeExplodeParticle;
use pocketmine\level\particle\HugeExplodeParticle;
use pocketmine\level\particle\EntityFlameParticle;
use pocketmine\level\particle\FlameParticle;
use pocketmine\level\particle\HeartParticle;
use pocketmine\level\particle\InkParticle;
use pocketmine\level\particle\ItemBreakParticle;
use pocketmine\level\particle\LavaDripParticle;
use pocketmine\level\particle\LavaParticle;
use pocketmine\level\particle\PortalParticle;
use pocketmine\level\particle\RedstoneParticle;
use pocketmine\level\particle\SmokeParticle;
use pocketmine\level\particle\SplashParticle;
use pocketmine\level\particle\SporeParticle;
use pocketmine\level\particle\TerrainParticle;
use pocketmine\level\particle\MobSpawnParticle;
use pocketmine\level\particle\WaterDripParticle;
use pocketmine\level\particle\WaterParticle;
use pocketmine\level\particle\EnchantmentTableParticle;
use pocketmine\level\particle\HappyVillagerParticle;
use pocketmine\level\particle\AngryVillagerParticle;
use pocketmine\level\particle\RainSplashParticle;
use pocketmine\level\particle\DestroyBlockParticle;
use UltimateParticles\UltimateParticles;

class Particles {
	public $plugin;
	public function __construct(UltimateParticles $plugin) {
		$this->plugin = $plugin;
	}
	public function getTheParticle(string $name, Vector3 $pos, $data = null) {
		switch ($name) :
			case "explode" :
				return new ExplodeParticle ( $pos );
			case "largeexplode" :
				return new LargeExplodeParticle ( $pos );
			case "hugeexplode" :
				return new HugeExplodeParticle ( $pos );
			case "bubble" :
				return new BubbleParticle ( $pos );
			case "splash" :
				return new SplashParticle ( $pos );
			case "water" :
				return new WaterParticle ( $pos );
			case "crit" :
			case "critical" :
				return new CriticalParticle ( $pos );
			case "spell" :
				return new EnchantParticle ( $pos );
			case "instantspell" :
				return new InstantEnchantParticle ( $pos );
			case "smoke" :
				return new SmokeParticle ( $pos, ($data === null ? 0 : $data) );
			case "dripwater" :
				return new WaterDripParticle ( $pos );
			case "driplava" :
				return new LavaDripParticle ( $pos );
			case "townaura" :
			case "spore" :
				return new SporeParticle ( $pos );
			case "portal" :
				return new PortalParticle ( $pos );
			case "entityflame" :
				return new EntityFlameParticle ( $pos );
			case "flame" :
				return new FlameParticle ( $pos );
			case "lava" :
				return new LavaParticle ( $pos );
			case "reddust" :
			case "redstone" :
				return new RedstoneParticle ( $pos, ($data === null ? 1 : $data) );
			case "snowballpoof" :
			case "snowball" :
				return new ItemBreakParticle ( $pos, Item::get ( Item::SNOWBALL ) );
			case "slime" :
				return new ItemBreakParticle ( $pos, Item::get ( Item::SLIMEBALL ) );
			case "heart" :
				return new HeartParticle ( $pos, ($data === null ? 0 : $data) );
			case "ink" :
				return new InkParticle ( $pos, ($data === null ? 0 : $data) );
			case "enchantmenttable" :
			case "enchantment" :
				return new EnchantmentTableParticle ( $pos );
			case "happyvillager" :
				return new HappyVillagerParticle ( $pos );
			case "angryvillager" :
				return new AngryVillagerParticle ( $pos );
			case "droplet" :
			case "rain" :
				return new RainSplashParticle ( $pos );
			case "mobspawn" :
				return new MobSpawnParticle ( $pos );
			case "colorful" :
			case "colourful" :
				return new DustParticle ( $pos, rand(0, 255), rand(0, 255), rand(0, 255));
		endswitch
		;
		if (substr ( $name, 0, 5 ) == "item_") {
			$arr = explode ( "_", $name );
			return new ItemBreakParticle ( $pos, new Item ( $arr [1] ) );
		}
		if (substr ( $name, 0, 6 ) == "block_") {
			$arr = explode ( "_", $name );
			return new TerrainParticle ( $pos, Block::get ( $arr [1] ) );
		}
		if (substr ( $name, 0, 9 ) == "desblock_") {
			$arr = explode ( "_", $name );
			return new DestroyBlockParticle ( $pos, Block::get ( $arr [1] ) );
		}
		if (substr ( $name, 0, 5 ) == "dust_") {
			$arr = explode ( "_", $name );
            if(strpos($arr[1], ",") !== false){
                $rgb = explode(",", $arr[1]);
                if(is_numeric($rgb[0]) && is_numeric($rgb[1]) && is_numeric($rgb[2])){
                    if($rgb[0] > -1 && $rgb[0] < 256 && $rgb[1] > -1 && $rgb[1] < 256 && $rgb[2] > -1 && $rgb[2] < 256){
                        return new DustParticle($pos, $rgb[0], $rgb[1], $rgb[2]);
                    }
                }
            }
			switch ($arr [1]) {
				case "red" :
				case "4" :
				case "c" :
					return new DustParticle ( $pos, 252, 8, 8 );
				case "orange" :
				case "6" :
					return new DustParticle ( $pos, 252, 195, 8 );
				case "yellow" :
				case "e" :
					return new DustParticle ( $pos, 252, 252, 8 );
				case "green" :
				case "a" :
				case "2" :
					return new DustParticle ( $pos, 8, 252, 8 );
				case "aqua" :
				case "b" :
					return new DustParticle ( $pos, 8, 252, 228 );
				case "blue" :
				case "1" :
					return new DustParticle ( $pos, 8, 8, 252 );
				case "purple" :
				case "d" :
				case "5" :
					return new DustParticle ( $pos, 252, 8, 252 );
				case "pink" :
					return new DustParticle ( $pos, 252, 8, 150 );
				case "white" :
				case "f" :
					return new DustParticle ( $pos, 255, 255, 255 );
				case "black" :
				case "0" :
					return new DustParticle ( $pos, 0, 0, 0 );
				case "grey" :
				case "gray" :
					return new DustParticle ( $pos, 138, 138, 138 );
				default :
					return new DustParticle ( $pos, 255, 255, 255 );
			}
		}
		return new TerrainParticle ( $pos, Block::get (0) );
	}
	public function getRandomParticle(): string {
		$random = array_rand($this->getAllColors());
		return "dust_" . $random;
	}
	public function getAll(): array {
		// For string output
		return array( 
				"bubble",
				"explode",
				"splash",
				"water",
				"critical",
				"spell",
				"smoke",
				"driplava",
				"dripwater",
				"spore",
				"portal",
				"flame",
				"entityflame",
				"lava",
				"reddust",
				"snowball",
				"heart",
				"ink",
				"hugeexplode",
				"largeexplode",
				"instantspell",
				"slime",
				"enchantment",
				"happyvillager",
				"angryvillager",
				"droplet",
				"mobspawn",
				"colorful",
				"item_{id}",
				"block_{id}",
				"desblock_{id}",
				"dust_{color}" 
		);
	}
	public function getAllColors(): array {
		return array( 
				"red",
				"orange",
				"yellow",
				"green",
				"aqua",
				"blue",
				"purple",
				"pink",
				"white",
				"black",
				"gray" 
		);
	}
}
?>