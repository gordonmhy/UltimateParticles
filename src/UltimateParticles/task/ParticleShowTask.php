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

use pocketmine\math\Vector3;
use pocketmine\Player;
use UltimateParticles\UltimateParticles;
use UltimateParticles\base\BaseTask;

class ParticleShowTask extends BaseTask{

	public function onRun($tick){
		$t = $this->getPlugin()->getData()->getAll();
		foreach((array) $this->getPlugin()->getServer()->getOnlinePlayers() as $p){
			if($p->hasPermission("walkingparticles") && isset($t[$p->getName()]) && $this->getPlugin()->isCleared($p) !== true && $t[$p->getName()]["enabled"] !== false){
                if ($this->plugin->getAuth() !== null) {
                    switch ($this->getPlugin()->getAuth()->getName()) {
                        case "SimpleAuth":
                            if ($this->getPlugin()->getAuth()->isPlayerAuthenticated($p) !== true && $this->plugin->getConfig()->get("auth-check") !== false) {
                                return;
                            }
                            break;
                        case "EasyAuth":
                            if ($this->getPlugin()->getAuth()->isAuth($p) !== true && $this->plugin->getConfig()->get("auth-check") !== false) {
                                return;
                            }
                            break;
                    }
                }
				if($this->getPlugin()->VanishNoPacket !== null){
					if($this->getPlugin()->VanishNoPacket->isVanished($p) !== false && $this->plugin->getConfig()->get("hideparticles-vanished") !== false){
						return;
					}
				}
				foreach((array) $t[$p->getName()]["particle"] as $particle){
					if($this->getPlugin()->getParticles()->getTheParticle($particle, new Vector3($p->x, $p->y, $p->z)) == ""){
						return;
					}
					$y = $p->y;
					$y2 = $y + 0.5;
					$y3 = $y2 + 1.4;
					$p->getLevel()->addParticle($this->getPlugin()->getParticles()->getTheParticle($particle, new Vector3($p->x, mt_rand($y, rand($y2, $y3)), $p->z)));
				}
			}
		}
	}

}
?>