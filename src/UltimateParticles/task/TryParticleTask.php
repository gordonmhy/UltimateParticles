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
namespace UltimateParticles\task;

use pocketmine\scheduler\PluginTask;
use pocketmine\math\Vector3;
use pocketmine\Player;
use UltimateParticles\UltimateParticles;
use UltimateParticles\task\TryLockTask;

class TryParticleTask extends PluginTask{

	public $plugin;

	public $player;

	public function __construct(UltimateParticles $plugin, Player $player){
		$this->plugin = $plugin;
		$this->player = $player;
		parent::__construct($plugin);
	}

	public function onRun($tick){
		if($this->player !== null){
			$this->plugin->byeTemp($this->player);
			$this->player->sendMessage($this->plugin->colorMessage("&b[UltimateParticles] &aTry section has ended!\n&eRestored your original particles"));
			$this->plugin->getServer()->getScheduler()->cancelTask($this->getTaskId());
		}
	}

}
?>