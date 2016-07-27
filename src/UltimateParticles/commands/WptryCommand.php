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
use UltimateParticles\economy\Economyapi;
use UltimateParticles\economy\Goldstd;
use UltimateParticles\economy\Massiveeconomy;
use UltimateParticles\economy\Pocketmoney;

class WptryCommand extends BaseCommand{

	public function onCommand(CommandSender $issuer, Command $cmd, $label, array $args){
		switch($cmd->getName()){
			case "wptry":
				if($issuer->hasPermission("walkingparticles.command.wptry")){
					if(isset($args[0])){
						$target = $this->getPlugin()->getServer()->getPlayer($args[0]);
						if($target !== null){
							if($this->getPlugin()->getEco() !== null){
								if($this->getPlugin()->isCleared($target) !== false){
									$issuer->sendMessage($this->getPlugin()->colorMessage("&cTarget player isn't using any particles!"));
									return true;
								}
								switch($this->getPlugin()->getEco()->getName()):
									case "EconomyAPI":
										$economyapi = new Economyapi($this->getPlugin());
										if($economyapi->tryPlayer($issuer, $target) !== false){
											$issuer->sendMessage($this->getPlugin()->colorMessage("&aYou have &e10 &aseconds to test &b" . $target->getName() . "&a's particles!\n&aParticles which " . $target->getName() . " using: &6" . $this->getPlugin()->getAllPlayerParticles($target)));
											return true;
										} else{
											return true;
										}
									break;
									case "PocketMoney":
										$pocketmoney = new Pocketmoney($this->getPlugin());
										if($pocketmoney->tryPlayer($issuer, $target) !== false){
											$issuer->sendMessage($this->getPlugin()->colorMessage("&aYou have &e10 &aseconds to test &b" . $target->getName() . "&a's particles!\n&aParticles which " . $target->getName() . " using: &6" . $this->getPlugin()->getAllPlayerParticles($target)));
											return true;
										} else{
											return true;
										}
									break;
									case "MassiveEconomy":
										$me = new Massiveeconomy($this->getPlugin());
										if($me->tryPlayer($issuer, $target) !== false){
											$issuer->sendMessage($this->getPlugin()->colorMessage("&aYou have &e10 &aseconds to test &b" . $target->getName() . "&a's particles!\n&aParticles which " . $target->getName() . " using: &6" . $this->getPlugin()->getAllPlayerParticles($target)));
											return true;
										} else{
											return true;
										}
									break;
									case "GoldStd":
										$goldstd = new Goldstd($this->getPlugin());
										if($goldstd->tryPlayer($issuer, $target) !== false){
											$issuer->sendMessage($this->getPlugin()->colorMessage("&aYou have &e10 &aseconds to test &b" . $target->getName() . "&a's particles!\n&aParticles which " . $target->getName() . " using: &6" . $this->getPlugin()->getAllPlayerParticles($target)));
											return true;
										} else{
											return true;
										}
									break;
								endswitch
								;
							} else{
								$this->getPlugin()->tryPlayerParticle($issuer, $target);
								$issuer->sendMessage($this->getPlugin()->colorMessage("&aYou have &e10 &aseconds to test &b" . $target->getName() . "&a's particles!\n&aParticles which " . $target->getName() . " using: &6" . $this->getPlugin()->getAllPlayerParticles($target)));
								return true;
							}
						} else{
							$issuer->sendMessage($this->getPlugin()->colorMessage("&cInvalid target!"));
							return true;
						}
					} else{
						return false;
					}
				} else{
					$issuer->sendMessage($this->getPlugin()->colorMessage("&cYou don't have permission for this!"));
					return true;
				}
			break;
		}
	}

}
?>