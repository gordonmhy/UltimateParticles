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

use UltimateParticles\UltimateParticles;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class SignHelp{

	private $plugin;

	public function __construct(UltimateParticles $plugin){
		$this->plugin = $plugin;
	}

	private function getHelp($line2){
		switch(strtolower($line2)):
			case "add":
				return [
						"[WParticles]",
						"add",
						"<particle>"
				];
			case "remove":
				return [
						"[WParticles]",
						"remove",
						"<particle>"
				];
			case "set":
				return [
						"[WParticles]",
						"set",
						"<particle>"
				];
			case "amplifier":
				return [
						"[WParticles]",
						"amplifier",
						"<amplifier>"
				];
			case "display":
				return [
						"[WParticles]",
						"display",
						"line/group"
				];
			case "pack":
				return [
						"[WParticles]",
						"pack",
						"use/get/list",
						"<pack>"
				];
			case "randomshow":
				return [
						"[WParticles]",
						"randomshow"
				];
			case "itemshow":
				return [
						"[WParticles]",
						"itemshow"
				];
			case "get":
				return [
						"[WParticles]",
						"get"
				];
			case "list":
				return [
						"[WParticles]",
						"list"
				];
			case "clear":
				return [
						"[WParticles]",
						"clear"
				];
			case "on":
				return [
						"[WParticles]",
						"on"
				];
			case "off":
				return [
						"[WParticles]",
						"off"
				];
		endswitch
		;
		return false;
	}

	public function sendHelp(CommandSender $issuer, $line2){
		switch(strtolower($line2)):
			case "add":
				$array = $this->getHelp("add");
				$issuer->sendMessage("Line 1: " . $array[0]);
				$issuer->sendMessage("Line 2: " . $array[1]);
				$issuer->sendMessage("Line 3: " . $array[2]);
			break;
			case "remove":
				$array = $this->getHelp("remove");
				$issuer->sendMessage("Line 1: " . $array[0]);
				$issuer->sendMessage("Line 2: " . $array[1]);
				$issuer->sendMessage("Line 3: " . $array[2]);
			break;
			case "set":
				$array = $this->getHelp("set");
				$issuer->sendMessage("Line 1: " . $array[0]);
				$issuer->sendMessage("Line 2: " . $array[1]);
				$issuer->sendMessage("Line 3: " . $array[2]);
			break;
			case "amplifier":
				$array = $this->getHelp("amplifier");
				$issuer->sendMessage("Line 1: " . $array[0]);
				$issuer->sendMessage("Line 2: " . $array[1]);
				$issuer->sendMessage("Line 3: " . $array[2]);
			break;
			case "display":
				$array = $this->getHelp("display");
				$issuer->sendMessage("Line 1: " . $array[0]);
				$issuer->sendMessage("Line 2: " . $array[1]);
				$issuer->sendMessage("Line 3: " . $array[2]);
			break;
			case "pack":
				$array = $this->getHelp("pack");
				$issuer->sendMessage("Line 1: " . $array[0]);
				$issuer->sendMessage("Line 2: " . $array[1]);
				$issuer->sendMessage("Line 3: " . $array[2]);
				$issuer->sendMessage("Line 4: " . $array[3]);
			break;
			case "randomshow":
				$array = $this->getHelp("randomshow");
				$issuer->sendMessage("Line 1: " . $array[0]);
				$issuer->sendMessage("Line 2: " . $array[1]);
			break;
			case "itemshow":
				$array = $this->getHelp("itemshow");
				$issuer->sendMessage("Line 1: " . $array[0]);
				$issuer->sendMessage("Line 2: " . $array[1]);
			break;
			case "get":
				$array = $this->getHelp("get");
				$issuer->sendMessage("Line 1: " . $array[0]);
				$issuer->sendMessage("Line 2: " . $array[1]);
			break;
			case "list":
				$array = $this->getHelp("list");
				$issuer->sendMessage("Line 1: " . $array[0]);
				$issuer->sendMessage("Line 2: " . $array[1]);
			break;
			case "clear":
				$array = $this->getHelp("clear");
				$issuer->sendMessage("Line 1: " . $array[0]);
				$issuer->sendMessage("Line 2: " . $array[1]);
			break;
			case "on":
				$array = $this->getHelp("on");
				$issuer->sendMessage("Line 1: " . $array[0]);
				$issuer->sendMessage("Line 2: " . $array[1]);
			break;
			case "off":
				$array = $this->getHelp("off");
				$issuer->sendMessage("Line 1: " . $array[0]);
				$issuer->sendMessage("Line 2: " . $array[1]);
			break;
		endswitch
		;
	}

}
?>