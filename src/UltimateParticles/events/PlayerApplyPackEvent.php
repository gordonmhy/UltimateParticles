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

use pocketmine\Player;
use pocketmine\event\Cancellable;
use UltimateParticles\UltimateParticles;
use UltimateParticles\base\BaseEvent;

class PlayerApplyPackEvent extends BaseEvent implements Cancellable{

	const METHOD_ADMIN = 0;

	const METHOD_PURCHASE = 1;

	const ECONOMY_ECONOMYS = 2;

	const ECONOMY_POCKETMONEY = 3;

	const ECONOMY_MASSIVEECONOMY = 4;

	const ECONOMY_GOLDSTD = 5;

	public static $handlerList = null;

	private $player;

	private $pack;

	private $method;

	private $eco;

	public function __construct(UltimateParticles $plugin, Player $player, string $pack, $method, $eco){
		$this->player = $player;
		$this->pack = $pack;
		$this->method = $method;
		$this->eco = $eco;
		parent::__construct($plugin);
	}

	public function getPlayer(): Player{
		return $this->player;
	}

	public function getPack(): string{
		return $this->pack;
	}

	public function getMethodID(){
		return $this->method;
	}

	public function getEcoID(){
		return $this->eco;
	}

}
?>