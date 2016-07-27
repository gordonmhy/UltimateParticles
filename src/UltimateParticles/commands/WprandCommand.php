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
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\Player;

class WprandCommand extends BaseCommand{

	public function onCommand(CommandSender $issuer, Command $cmd, $label, array $args){
		switch($cmd->getName()):
			case "wprand":
				if(! $issuer instanceof Player){
					$issuer->sendMessage("Command only works in-game!");
					return true;
				}
				if(! $issuer->hasPermission("walkingparticles.command.wprand")){
					$issuer->sendMessage($this->getPlugin()->colorMessage("&cYou don't have permission for this!"));
					return true;
				}
				if($this->getPlugin()->switchRandomMode($issuer, ($this->getPlugin()->isRandomMode($issuer) ? false : true)) !== true){
					return true;
				}
				$issuer->sendMessage($this->getPlugin()->colorMessage("&aYou turned random mode " . ($this->getPlugin()->isRandomMode($issuer) ? "on" : "off")));
				return true;
			break;
		endswitch
		;
	}

}
?>