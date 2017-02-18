<?php
/*
 * This file is a part of UltimateParticles.
 * Copyright (C) 2017 hoyinm14mc
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

namespace hoyinm14mc\ultimateparticles\commands;

use hoyinm14mc\ultimateparticles\bases\BaseCommand;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class UltimateparticlesCommand extends BaseCommand
{

    public function onCommand(CommandSender $issuer, Command $cmd, $label, array $args)
    {
        switch ($cmd->getName()) {
            case "ultimateparticles":
                if (isset($args[0]) !== true) {
                    return false;
                }
                if ($issuer instanceof Player !== true) {
                    $issuer->sendMessage($this->plugin->textKeyToResult("operation.inGameOnly"));
                    return true;
                }
                switch (strtolower($args[0])) {
                    case "addparticle":
                    case "addp":
                        if ($issuer->hasPermission("ultimateparticles.command.ultimateparticles.addparticle") !== true) {
                            $issuer->sendMessage($this->plugin->textKeyToResult("operation.noPermission"));
                            return true;
                        }
                        if (count($args) < 3) {
                            $issuer->sendMessage("Usage: /ultip addp (particle) (amplifier) (shape) (type)");
                            return true;
                        }
                        if (is_numeric($args[2]) !== true) {
                            $issuer->sendMessage(str_replace("%arg%", "amplifier", $this->plugin->textKeyToResult("operation.parameterInvalid")));
                            $issuer->sendMessage("Usage: /ultip addp (particle) (amplifier) (shape) (type)");
                            return true;
                        }
                        if (isset($args[3]) !== true ||
                            (isset($args[3]) !== false && $args[3] != "spiral" && $args[3] != "tail")
                        ) {
                            $args[3] = $this->getConfig()->get("default-shape");
                        }
                        if (isset($args[4]) !== true ||
                            (isset($args[4]) !== false && $args[3] != "particle")
                        ) {
                            $args[4] = $this->getConfig()->get("default-type");
                        }
                        if ($this->plugin->addParticle($issuer->getName(), $args[1], $args[2], $args[3], $args[4]) !== false) {
                            $issuer->sendMessage(
                                str_replace("%particle%", $args[1],
                                    str_replace("%amplifier%", $args[2],
                                        str_replace("%shape%", $args[3],
                                            str_replace("%type%", $args[4],
                                                $this->plugin->textKeyToResult("addparticle.success"))))));
                            return true;
                        } else {
                            $issuer->sendMessage($this->plugin->textKeyToResult("operation.failure"));
                            return true;
                        }
                        break;
                    case "removeparticle":
                    case "removep":
                        if ($issuer->hasPermission("ultimateparticles.command.ultimateparticles.removeparticle") !== true) {
                            $issuer->sendMessage($this->plugin->textKeyToResult("operation.noPermission"));
                            return true;
                        }
                        if (isset($args[1]) !== true) {
                            $issuer->sendMessage("Usage: /ultip removep (particle)");
                            return true;
                        }
                        $t = $this->plugin->data->getAll();
                        $exist = false;
                        foreach (array_keys($t[$issuer->getName()]["particles"]) as $particle) {
                            if ($particle == strtolower($args[1])) {
                                $exist = true;
                            }
                        }
                        if ($exist !== true) {
                            $issuer->sendMessage($this->plugin->textKeyToResult("removeparticle.particleNotContained"));
                            return true;
                        }
                        if ($this->plugin->removeParticle($issuer->getName(), $args[1]) !== false) {
                            $issuer->sendMessage(str_replace("%particle%", strtolower($args[1]), $this->plugin->textKeyToResult("removeparticle.success")));
                            return true;
                        } else {
                            $issuer->sendMessage($this->plugin->textKeyToResult("operation.failure"));
                            return true;
                        }
                        break;
                    case "get":
                    case "getparticles":
                        if ($issuer->hasPermission("ultimateparticles.command.ultimateparticles.getparticles") !== true) {
                            $issuer->sendMessage($this->plugin->textKeyToResult("operation.noPermission"));
                            return true;
                        }
                        $issuer->sendMessage(str_replace("%list%", $this->plugin->playerParticlesToString($issuer->getName()), $this->plugin->textKeyToResult("getparticles.success")));
                        return true;
                        break;
                    default:
                        return false;
                }
                break;
        }
    }

}

?>