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


use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use UltimateParticles\base\BaseCommand;
use pocketmine\Player;

class BloodCommand extends BaseCommand
{

    public function onCommand(CommandSender $issuer, Command $cmd, $label, array $args){
        $b = $this->getPlugin()->data4->getAll();
        switch($cmd->getName()){
            case "blood":
                if($issuer->hasPermission("walkingparticles.command") !== true && $issuer->hasPermission("walkingparticles.command.blood") !== true){
                    $issuer->sendMessage($this->getPlugin()->colorMessage("&cYou don't have permission for this!"));
                    return true;
                }
                if($issuer instanceof Player !== true) {
                    $issuer->sendMessage($this->getPlugin()->colorMessage("Command only works in-game!"));
                    return true;
                }
                if(isset($args[0]) !== true){
                    return false;
                }
                switch(strtolower($args[0])){
                    case "on":
                        if(in_array($issuer->getName(), $b["disabled-players"]) !== true){
                            $issuer->sendMessage($this->getPlugin()->colorMessage("&cYour blood effect was not disabled!"));
                            return true;
                        }
                        $pa = array_search($issuer->getName(), $b ["disabled-players"]);
                        unset($b["disabled-players"][$pa]);
                        $this->plugin->data4->setAll($b);
                        $this->plugin->data4->save();
                        $issuer->sendMessage($this->getPlugin()->colorMessage("&eActivated your blood effects!"));
                        return true;
                    break;
                    case "off":
                        if(in_array($issuer->getName(), $b["disabled-players"]) !== false){
                            $issuer->sendMessage($this->getPlugin()->colorMessage("&cYour blood effect was not enabled!"));
                            return true;
                        }
                        $b["disabled-players"][] = $issuer->getName();
                        $this->plugin->data4->setAll($b);
                        $this->plugin->data4->save();
                        $issuer->sendMessage($this->getPlugin()->colorMessage("&eDeactivated your blood effects!"));
                        return true;
                    break;
                }
            break;
        }
    }

}