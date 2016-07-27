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
namespace UltimateParticles\events;

use UltimateParticles\UltimateParticles;
use UltimateParticles\base\BaseEvent;
use pocketmine\Player;
use pocketmine\event\Cancellable;

class PlayerSwitchRandommodeEvent extends BaseEvent implements Cancellable{

	public static $handlerList = null;

	public $plugin;

	public $player;

	public $value;

	public function __construct(UltimateParticles $plugin, Player $player, bool $value){
		$this->plugin = $plugin;
		$this->player = $player;
		$this->value = $value;
		parent::__construct($plugin);
	}

	public function getPlugin(){
		return $this->plugin;
	}

	public function getPlayer(): Player{
		return $this->player;
	}

	public function getValue(): bool{
		return $this->value;
	}

}