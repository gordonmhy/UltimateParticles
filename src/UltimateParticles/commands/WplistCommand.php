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
namespace UltimateParticles\commands;

use UltimateParticles\base\BaseCommand;
use UltimateParticles\Particles;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\Player;

class WplistCommand extends BaseCommand{

	public function onCommand(CommandSender $issuer, Command $cmd, $label, array $args){
		switch($cmd->getName()){
			case "wplist":
				if($issuer->hasPermission("walkingparticles.command") || $issuer->hasPermission("walkingparticles.command.wplist")){
					$particles = new Particles($this->getPlugin());
					$issuer->sendMessage($this->getPlugin()->colorMessage("&aList of particles: &6" . implode(", ", $particles->getAll())));
					return true;
				} else{
					$issuer->sendMessage($this->getPlugin()->colorMessage("&cYou don't have permission for this!"));
					return true;
				}
			break;
		}
	}

}
?>