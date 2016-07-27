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
namespace UltimateParticles\listeners;

use pocketmine\tile\Sign;
use pocketmine\tile\Tile;
use pocketmine\level\sound\BatSound;
use pocketmine\level\sound\ClickSound;
use pocketmine\event\block\SignChangeEvent;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\player\PlayerInteractEvent;
use UltimateParticles\base\BaseListener;
use UltimateParticles\UltimateParticles;
use UltimateParticles\Particles;

class SignListener extends BaseListener{

	public function onBlockBreak(BlockBreakEvent $event){
		if($event->getBlock()->getID() == 323 || $event->getBlock()->getID() == 63 || $event->getBlock()->getID() == 68){
			$sign = $event->getPlayer()->getLevel()->getTile($event->getBlock());
			if(! $sign instanceof Sign){
				return;
			}
			$sign = $sign->getText();
			if($sign[0] == '§f[§aWParticles§f]' || $sign[0] == '§f[§aWalkp§f]'){
				if($event->getPlayer()->hasPermission("walkingparticles.sign.destroy")){
					$event->getPlayer()->sendMessage($this->getPlugin()->colorMessage("&bUltimateParticles &esign has been destroyed!"));
					return true;
				} else{
					$event->getPlayer()->sendMessage($this->getPlugin()->colorMessage("&cYou don't have permission for this!"));
					$event->setCancelled(true);
				}
			}
		}
	}

	public function onSignChange(SignChangeEvent $event){
		if($event->getBlock()->getID() == 323 || $event->getBlock()->getID() == 63 || $event->getBlock()->getID() == 68){
			$sign = $event->getPlayer()->getLevel()->getTile($event->getBlock());
			if(! $sign instanceof Sign){
				return;
			}
			$sign = $event->getLines();
			if($sign[0] == '[WParticles]' || $sign[0] == '[Walkp]'){
				if($event->getPlayer()->hasPermission("walkingparticles.sign.create")){
					if(! empty($sign[1]) && ! empty($sign[2])){
						$allowed = [
								"set",
								"add",
								"amplifier",
								"remove",
								"display",
								"pack"
						];
						if(in_array($sign[1], $allowed)){
							$event->setLine(0, ($sign[0] == "§f[§aWParticles§f]" ? "§f[§aWParticles§f]" : "§f[§aWalkp§f]"));
							$event->setLine(1, "§e" . $sign[1]);
							switch($sign[1]):
								case 'pack':
									if($sign[2] == "get" || $sign[2] == "use" || $sign[2] == "list"){
										if(($sign[2] == "get" || $sign[2] == "use") && ! isset($sign[3])){
											$event->setLine(0, null);
											$event->setLine(1, null);
											$event->setLine(2, null);
											$event->setLine(3, null);
											$event->getPlayer()->sendMessage($this->getPlugin()->colorMessage("&cSign broken, line 4 should contain arguments!"));
											return false;
										} else{
											$event->setLine(3, "§6" . $sign[3]);
										}
									} else{
										$event->setLine(0, null);
										$event->setLine(1, null);
										$event->setLine(2, null);
										$event->setLine(3, null);
										$event->getPlayer()->sendMessage($this->getPlugin()->colorMessage("&cSign broken, line 3 must be 'get', 'use' or 'list'"));
										return false;
									}
								break;
								case 'amplifier':
									if(is_numeric($sign[2]) !== true){
										$event->setLine(0, null);
										$event->setLine(1, null);
										$event->setLine(2, null);
										$event->setLine(3, null);
										$event->getPlayer()->sendMessage($this->getPlugin()->colorMessage("&cSign broken, line 3 must be numeric as your creating a sign to change the amplifier!"));
										return false;
									}
								break;
								case 'display':
									if($sign[2] == "line" || $sign[2] == "group"){
										// correct lol
									} else{
										$event->setLine(0, null);
										$event->setLine(1, null);
										$event->setLine(2, null);
										$event->setLine(3, null);
										$event->getPlayer()->sendMessage($this->getPlugin()->colorMessage("&cSign broken, line 3 must be line/group as your creating a sign to change the display!"));
										return false;
									}
								break;
							endswitch
							;
							$event->getPlayer()->sendMessage($this->getPlugin()->colorMessage("&bUltimateParticles &asign created!"));
							$event->setLine(2, "§d" . $sign[2]);
							return true;
						} else{
							$event->setLine(0, null);
							$event->setLine(1, null);
							$event->setLine(2, null);
							$event->setLine(3, null);
							$event->getPlayer()->sendMessage($this->getPlugin()->colorMessage("&cSign broken, line 2 must be &eadd&c, &eremove&c, &edisplay&c, &epack&c, &eget&c, &elist &cor &eamplifier&c!"));
							return false;
						}
					} else if(! empty($sign[1]) && empty($sign[2])){
						$allowed2 = [
								"clear",
								"get",
								"list",
								"randomshow",
								"random",
								"itemshow",
								"item",
								"on",
								"off"
						];
						if(in_array($sign[1], $allowed2)){
							$event->getPlayer()->sendMessage("§bUltimateParticles §asign created!");
							$event->setLine(0, ($sign[0] == "§f[§aWParticles§f]" ? "§f[§aWParticles§f]" : "§f[§aWalkp§f]"));
							$event->setLine(1, "§e" . $sign[1]);
							$event->setLine(2, null);
							$event->setLine(3, null);
							return true;
						} else{
							$event->setLine(0, null);
							$event->setLine(1, null);
							$event->setLine(2, null);
							$event->setLine(3, null);
							$event->getPlayer()->sendMessage($this->getPlugin()->colorMessage("&cSign broken, please fill in correct information!"));
							return false;
						}
					} else{
						$event->setLine(0, null);
						$event->setLine(1, null);
						$event->setLine(2, null);
						$event->getPlayer()->sendMessage($this->getPlugin()->colorMessage("&cSign broken, please fill in correct information!"));
						return false;
					}
				} else{
					$event->setLine(0, null);
					$event->setLine(1, null);
					$event->setLine(2, null);
					$event->getPlayer()->sendMessage($this->getPlugin()->colorMessage("&cSign broken, you have no permission for this!"));
					return false;
				}
			}
		}
	}

	public function onInteract(PlayerInteractEvent $event){
		if($event->getBlock()->getID() == 323 || $event->getBlock()->getID() == 63 || $event->getBlock()->getID() == 68){
			$sign = $event->getPlayer()->getLevel()->getTile($event->getBlock());
			if(! $sign instanceof Sign){
				return;
			}
			$sign = $sign->getText();
			if($sign[0] == '§f[§aWParticles§f]' || $sign[0] == '§f[§aWalkp§f]'){
				if(empty($sign[1]) !== true && empty($sign[2]) !== true){
					if($event->getPlayer()->hasPermission("walkingparticles.sign.toggle")){
						switch(strtolower($sign[1])):
							case "§eadd":
								$particle = substr($sign[2], 3);
								if($this->getPlugin()->addPlayerParticle($event->getPlayer(), $particle) !== true){
									return;
								}
								$event->getPlayer()->sendMessage($this->getPlugin()->colorMessage("&aYou added your &bUltimateParticles&a's " . $particle . " particle!"));
								$event->getPlayer()->getLevel()->addSound(new BatSound($event->getPlayer()), $this->getPlugin()->getServer()->getOnlinePlayers());
								return true;
							break;
							case "§eremove":
								$particle = substr($sign[2], 3);
								if($this->getPlugin()->removePlayerParticle($event->getPlayer(), $particle) !== true){
									return;
								}
								$event->getPlayer()->sendMessage($this->getPlugin()->colorMessage("&aYou removed your &bUltimateParticles&a's " . $particle . " particle!"));
								$event->getPlayer()->getLevel()->addSound(new BatSound($event->getPlayer()), $this->getPlugin()->getServer()->getOnlinePlayers());
								return true;
							break;
							case "§eset":
								$particle = substr($sign[2], 3);
								if($this->getPlugin()->setPlayerParticle($event->getPlayer(), $particle) !== true){
									return;
								}
								$event->getPlayer()->sendMessage($this->getPlugin()->colorMessage("&aYou set your &bUltimateParticles&a's particle to &e" . $particle . "!"));
								$event->getPlayer()->getLevel()->addSound(new BatSound($event->getPlayer()), $this->getPlugin()->getServer()->getOnlinePlayers());
								return true;
							break;
							case "§eamplifier":
								$amplifier = substr($sign[2], 3);
								if($this->getPlugin()->setPlayerAmplifier($event->getPlayer(), $amplifier) !== true){
									return;
								}
								$event->getPlayer()->sendMessage($this->getPlugin()->colorMessage("&aYou changed your &bUltimateParticles&a's amplifier!"));
								$event->getPlayer()->getLevel()->addSound(new BatSound($event->getPlayer()), $this->getPlugin()->getServer()->getOnlinePlayers());
								return true;
							break;
							case "§edisplay":
								$display = substr($sign[2], 3);
								if($this->getPlugin()->setPlayerDisplay($event->getPlayer(), $display) !== true){
									return true;
								}
								$event->getPlayer()->sendMessage($this->getPlugin()->colorMessage("&aYou changed the display of your particles!"));
								$event->getPlayer()->getLevel()->addSound(new BatSound($event->getPlayer()), $this->getPlugin()->getServer()->getOnlinePlayers());
								return true;
							break;
							case "§epack":
								$option = $sign[2];
								switch($option):
									case "§duse":
										$option2 = substr($sign[3], 3);
										if($this->getPlugin()->packExists($option2)){
											$this->getPlugin()->activatePack($event->getPlayer(), $option2);
											$event->getPlayer()->sendMessage($this->getPlugin()->colorMessage("&aYou applied &b" . $option2 . " &apack!"));
											$event->getPlayer()->getLevel()->addSound(new BatSound($event->getPlayer()), $this->getPlugin()->getServer()->getOnlinePlayers());
										} else{
											$issuer->sendMessage($this->getPlugin()->colorMessage("&cPack doesn't exist!"));
											$event->getPlayer()->getLevel()->addSound(new ClickSound($event->getPlayer()), $this->getPlugin()->getServer()->getOnlinePlayers());
										}
									break;
									case "§dget":
										$option2 = substr($sign[3], 3);
										if($this->getPlugin()->packExists($option2)){
											$event->getPlayer()->sendMessage($this->getPlugin()->colorMessage("&aList of particles in &b" . $option2 . " &apack: &6" . $this->getPlugin()->getPackParticles($option2)));
										} else{
											$issuer->sendMessage($this->getPlugin()->colorMessage("&cPack doesn't exist!"));
										}
										$event->getPlayer()->getLevel()->addSound(new ClickSound($event->getPlayer()), $this->getPlugin()->getServer()->getOnlinePlayers());
									break;
									case "§dlist":
										$event->getPlayer()->sendMessage($this->getPlugin()->colorMessage("&aList of particle packs: &6" . $this->getPlugin()->listPacks()));
										$event->getPlayer()->getLevel()->addSound(new ClickSound($event->getPlayer()), $this->getPlugin()->getServer()->getOnlinePlayers());
									break;
								endswitch
								;
							break;
						endswitch
						;
					} else{
						$event->getPlayer()->sendMessage($this->getPlugin()->colorMessage("&cYou don't have permission for this!"));
						return false;
					}
				} else if(! empty($sign[1]) && empty($sign[2])){
					if($event->getPlayer()->hasPermission("walkingparticles.sign.toggle")){
						switch(strtolower($sign[1])):
							case "§erandomshow":
							case "§erandom":
								if($this->getPlugin()->switchRandomMode($event->getPlayer(), ($this->getPlugin()->isRandomMode($event->getPlayer()) !== true ? true : false)) !== true){
									return;
								}
								$event->getPlayer()->sendMessage($this->getPlugin()->colorMessage("&aYour random mode has been turned " . ($this->getPlugin()->isRandomMode($event->getPlayer()) !== true ? "off" : "on") . "!"));
								$event->getPlayer()->getLevel()->addSound(new BatSound($event->getPlayer()), $this->getPlugin()->getServer()->getOnlinePlayers());
							break;
							case "§eitemshow":
							case "§eitem":
								if($this->getPlugin()->switchItemMode($event->getPlayer(), ($this->getPlugin()->isItemMode($event->getPlayer()) !== false ? false : true)) !== true){
									return;
								}
								$event->getPlayer()->sendMessage($this->getPlugin()->colorMessage("&aYour item mode has been turned " . ($this->getPlugin()->isItemMode($event->getPlayer()) !== true ? "off" : "on") . "!"));
								$event->getPlayer()->getLevel()->addSound(new BatSound($event->getPlayer()), $this->getPlugin()->getServer()->getOnlinePlayers());
							break;
							case "§eget":
								$event->getPlayer()->sendMessage($this->getPlugin()->colorMessage("&aYour &bUltimateParticles&a: &f" . $this->getPlugin()->getAllPlayerParticles($event->getPlayer())));
								$event->getPlayer()->getLevel()->addSound(new ClickSound($event->getPlayer()), $this->getPlugin()->getServer()->getOnlinePlayers());
								return true;
							break;
							case "§eclear":
								if($this->getPlugin()->clearPlayerParticle($event->getPlayer()) !== true){
									return;
								}
								$event->getPlayer()->sendMessage($this->getPlugin()->colorMessage("&aYour &bUltimateParticles &ahas been cleared!"));
								$event->getPlayer()->getLevel()->addSound(new BatSound($event->getPlayer()), $this->getPlugin()->getServer()->getOnlinePlayers());
								return true;
							break;
							case "§elist":
								$particles = new Particles($this->getPlugin());
								$event->getPlayer()->sendMessage($this->getPlugin()->colorMessage("&aList of available particles: &6" . implode(", ", $particles->getAll())));
								$event->getPlayer()->getLevel()->addSound(new ClickSound($event->getPlayer()), $this->getPlugin()->getServer()->getOnlinePlayers());
								return true;
							break;
							case "§eon":
								if($this->getPlugin()->enableEffects($event->getPlayer()) !== true){
									return true;
								}
								$event->getPlayer()->sendMessage($this->getPlugin()->colorMessage("&aYour UltimateParticles has been turned on!"));
								$event->getPlayer()->getLevel()->addSound(new ClickSound($event->getPlayer()), $this->getPlugin()->getServer()->getOnlinePlayers());
								return true;
							break;
							case "§eoff":
								if($this->getPlugin()->disableEffects($event->getPlayer()) !== true){
									return true;
								}
								$event->getPlayer()->sendMessage($this->getPlugin()->colorMessage("&aYour UltimateParticles has been turned off!"));
								$event->getPlayer()->getLevel()->addSound(new ClickSound($event->getPlayer()), $this->getPlugin()->getServer()->getOnlinePlayers());
								return true;
							break;
						endswitch
						;
					} else{
						$event->getPlayer()->sendMessage($this->getPlugin()->colorMessage("&cYou have no permission for this!"));
						return true;
					}
				} else{
					$event->getPlayer()->sendMessage($this->getPlugin()->colorMessage("&cSorry, you're clicking an incorrect &bUltimateParticles &csign!"));
					return false;
				}
			}
		}
	}

}
?>