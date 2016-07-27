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

class SpiralTask extends BaseTask
{

    public function onRun($tick)
    {
        $t = $this->getPlugin()->getData()->getAll();
        //PARTICLE EJECTOR PART (The ticks between each ejection is as same as the display of spiral effects
        $ej = $this->plugin->data5->getAll();
        foreach (array_keys($ej) as $name){
            $x = $ej[$name]["pos"]["x"];
            $y = $ej[$name]["pos"]["y"];
            $z = $ej[$name]["pos"]["z"];
            $level = $this->getPlugin()->getServer()->getLevelByName($ej[$name]["pos"]["world"]);
            for($i=0;$i<$ej[$name]["amplifier"];$i++){
                foreach($ej[$name]["particle"] as $parti){
                    $level->addParticle($this->getPlugin()->getParticles()->getTheParticle($parti, new Vector3($x, $y, $z)));
                }
            }
        }
        //SPIRAL PART
        $this->getPlugin()->spiral_order = $this->getPlugin()->spiral_order + 1;
        if($this->getPlugin()->spiral_order > 49){
            $this->getPlugin()->spiral_order = 0;
        }
        foreach ((array)$this->getPlugin()->getServer()->getOnlinePlayers() as $p) {
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
            if ($this->getPlugin()->getPlayerDisplay($p) == "spiral" && $this->getPlugin()->isEffectsEnabled($p) !== false) {
                if($this->plugin->getConfig()->get("disable-animated-spirals") !== true) {
                    foreach ($t[$p->getName()]["particle"] as $particle) {
                        $arr = $this->getPlugin()->getSpiral($p);
                        $p->getLevel()->addParticle($this->getPlugin()->getParticles()->getTheParticle($particle, $arr[$this->getPlugin()->spiral_order]));
                    }
                }else{
                    foreach($this->plugin->getSpiral($p) as $v3){
                        foreach($t[$p->getName()]["particle"] as $particle){
                            $p->getLevel()->addParticle($this->getPlugin()->getParticles()->getTheParticle($particle, $v3));
                        }
                    }
                }
            }
        }
    }

}

?>