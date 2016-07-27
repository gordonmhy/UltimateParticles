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
use UltimateParticles\SignHelp;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\Player;

class AdminCommand extends BaseCommand
{

    public function onCommand(CommandSender $issuer, Command $cmd, $label, array $args)
    {
        switch ($cmd->getName()):
            case "walkp":
                if (isset($args[0])) {
                    switch ($args[0]):
                        case "help":
                        case "h":
                            if ($issuer->hasPermission("walkingparticles.command") !== true && $issuer->hasPermission("walkingparticles.command.admin") !== true && $issuer->hasPermission("walkingparticles.command.admin.help") !== true) {
                                $issuer->sendMessage($this->getPlugin()->colorMessage("&cYou don't have permission for this!"));
                                return true;
                            }
                            if (isset($args[1])) {
                                switch ($args[1]):
                                    case 1:
                                        $this->getPlugin()->getServer()->dispatchCommand($issuer, "walkp help");
                                        return true;
                                        break;
                                    case 2:
                                        $issuer->sendMessage($this->getPlugin()->colorMessage("&aShowing help page &6(2/2)"));
                                        $issuer->sendMessage($this->getPlugin()->colorMessage("&l&b- &r&f/walkp pack <use|create|delete|addp|rmp|get|list> <args..>"));
                                        $issuer->sendMessage($this->getPlugin()->colorMessage("&l&b- &r&f/walkp try <player>"));
                                        $issuer->sendMessage($this->getPlugin()->colorMessage("&l&b- &r&f/walkp use <player>"));
                                        $issuer->sendMessage($this->getPlugin()->colorMessage("&l&b- &r&f/walkp set <particle> <player>"));
                                        $issuer->sendMessage($this->getPlugin()->colorMessage("&l&b- &r&f/walkp itemshow <player>"));
                                        $issuer->sendMessage($this->getPlugin()->colorMessage("&l&b- &r&f/walkp on|off <player>"));
                                        $issuer->sendMessage($this->getPlugin()->colorMessage("&l&b- &r&f/walkp signhelp <args..>"));
                                        $issuer->sendMessage($this->getPlugin()->colorMessage("&l&b- &r&f/walkp version"));
                                        $issuer->sendMessage($this->getPlugin()->colorMessage("&l&b- &r&f/walkp reload"));
                                        $issuer->sendMessage($this->getPlugin()->colorMessage("&l&b- &r&f/walkp colors"));
                                        return true;
                                        break;
                                endswitch;
                            } else {
                                $issuer->sendMessage($this->getPlugin()->colorMessage("&aShowing help page &6(1/2)  &e-  &7Show the next page by typing '/walkp help 2'"));
                                $issuer->sendMessage($this->getPlugin()->colorMessage("&l&b- &r&f/walkp add <particle> <player>"));
                                $issuer->sendMessage($this->getPlugin()->colorMessage("&l&b- &r&f/walkp remove <particle> <player>"));
                                $issuer->sendMessage($this->getPlugin()->colorMessage("&l&b- &r&f/walkp amplifier <amplifier> <player>"));
                                $issuer->sendMessage($this->getPlugin()->colorMessage("&l&b- &r&f/walkp display line|group|spiral <player>"));
                                $issuer->sendMessage($this->getPlugin()->colorMessage("&l&b- &r&f/walkp randomshow <player>"));
                                $issuer->sendMessage($this->getPlugin()->colorMessage("&l&b- &r&f/walkp clear <player>"));
                                $issuer->sendMessage($this->getPlugin()->colorMessage("&l&b- &r&f/walkp get <player>"));
                                $issuer->sendMessage($this->getPlugin()->colorMessage("&l&b- &r&f/walkp list"));
                                return true;
                            }
                            break;
                        case "reload":
                            if ($issuer->hasPermission("walkingparticles.command") !== true && $issuer->hasPermission("walkingparticles.command.admin") !== true && $issuer->hasPermission("walkingparticles.command.admin.reload") !== true) {
                                $issuer->sendMessage($this->getPlugin()->colorMessage("&cYou don't have permission for this!"));
                                return true;
                            }
                            $this->getPlugin()->reloadConfig();
                            $this->getPlugin()->data->reload();
                            $this->getPlugin()->data2->reload();
                            $this->getPlugin()->data3->reload();
                            $issuer->sendMessage($this->getPlugin()->colorMessage("&6Config and data have been reloaded!"));
                            return true;
                            break;
                        case "add":
                        case "addparticle":
                            if ($issuer->hasPermission("walkingparticles.command") !== true && $issuer->hasPermission("walkingparticles.command.admin") !== true && $issuer->hasPermission("walkingparticles.command.admin.add") !== true) {
                                $issuer->sendMessage($this->getPlugin()->colorMessage("&cYou don't have permission for this!"));
                                return true;
                            }
                            if (isset($args[1])) {
                                $particle = $args[1];
                                if (isset($args[2])) {
                                    $target = $this->getPlugin()->getServer()->getPlayer($args[2]);
                                    if ($target !== null) {
                                        if ($this->getPlugin()->addPlayerParticle($target, $particle) !== true) {
                                            return true;
                                        }
                                        $issuer->sendMessage($this->getPlugin()->colorMessage("&aYou added " . $particle . " to &b" . $target->getName() . "&a's UltimateParticles!"));
                                        return true;
                                    } else {
                                        $issuer->sendMessage($this->getPlugin()->colorMessage("&cInvalid target!"));
                                    }
                                } else {
                                    if ($issuer instanceof Player) {
                                        if ($this->getPlugin()->addPlayerParticle($issuer, $particle) !== true) {
                                            return true;
                                        }
                                        $issuer->sendMessage($this->getPlugin()->colorMessage("&aYou added &b" . $particle . " &aparticle to your UltimateParticles!"));
                                        return true;
                                    } else {
                                        $issuer->sendMessage("Usage: /walkp add <particle> <player>");
                                        return true;
                                    }
                                }
                            } else {
                                $issuer->sendMessage("Usage: /walkp add <particle> <player>");
                                return true;
                            }
                            break;
                        case "removeparticle":
                        case "rmparticle":
                        case "remove":
                        case "rm":
                            if ($issuer->hasPermission("walkingparticles.command") !== true && $issuer->hasPermission("walkingparticles.command.admin") !== true && $issuer->hasPermission("walkingparticles.command.admin.remove") !== true) {
                                $issuer->sendMessage($this->getPlugin()->colorMessage("&cYou don't have permission for this!"));
                                return true;
                            }
                            if (isset($args[1])) {
                                $particle = $args[1];
                                if (isset($args[2])) {
                                    $target = $this->getPlugin()->getServer()->getPlayer($args[2]);
                                    if ($target !== null) {
                                        if ($this->getPlugin()->removePlayerParticle($target, $particle) !== true) {
                                            return true;
                                        }
                                        $issuer->sendMessage($this->getPlugin()->colorMessage("&aYou removed &b" . $target->getName() . "&a's Walking Particle!"));
                                        return true;
                                    } else {
                                        $issuer->sendMessage($this->getPlugin()->colorMessage("&cInvalid target!"));
                                        return true;
                                    }
                                } else {
                                    if ($issuer instanceof Player) {
                                        if ($this->getPlugin()->removePlayerParticle($issuer, $particle) !== true) {
                                            return true;
                                        }
                                        $issuer->sendMessage($this->getPlugin()->colorMessage("&aYour particle '&b" . $particle . "&a' has been removed!"));
                                        return true;
                                    } else {
                                        $issuer->sendMessage("Usage: /walkp remove <particle> <player>");
                                        return true;
                                    }
                                }
                            } else {
                                $issuer->sendMessage("Usage: /walkp remove <particle> <player>");
                                return true;
                            }
                            break;
                        case "setamplifier":
                        case "amplifier":
                            if ($issuer->hasPermission("walkingparticles.command") !== true && $issuer->hasPermission("walkingparticles.command.admin") !== true && $issuer->hasPermission("walkingparticles.command.admin.amplifier") !== true) {
                                $issuer->sendMessage($this->getPlugin()->colorMessage("&cYou don't have permission for this!"));
                                return true;
                            }
                            if (isset($args[2]) && isset($args[1])) {
                                if (is_numeric($args[1])) {
                                    $target = $this->getPlugin()->getServer()->getPlayer($args[2]);
                                    if ($target !== null) {
                                        $amplifier = $args[1];
                                        if ($this->getPlugin()->setPlayerAmplifier($target, $amplifier) !== true) {
                                            return true;
                                        }
                                        $issuer->sendMessage($this->getPlugin()->colorMessage("&aYou changed &b" . $target->getName() . "&a's amplifier!"));
                                        return true;
                                    } else {
                                        $issuer->sendMessage($this->getPlugin()->colorMessage("&cInvalid target!"));
                                        return true;
                                    }
                                } else {
                                    $issuer->sendMessage($this->getPlugin()->colorMessage("&cInvalid amplifier!"));
                                    return true;
                                }
                            } else if (isset($args[1]) && !isset($args[2])) {
                                if (is_numeric($args[1]) !== false) {
                                    if ($issuer instanceof Player) {
                                        $amplifier = $args[1];
                                        if ($this->getPlugin()->setPlayerAmplifier($issuer, $amplifier) !== true) {
                                            return true;
                                        }
                                        $issuer->sendMessage($this->getPlugin()->colorMessage("&aYou changed your amplifier of &bUltimateParticles&a!"));
                                        return true;
                                    } else {
                                        $issuer->sendMessage("Usage: /walkp amplifier <amplifier> <player>");
                                        return true;
                                    }
                                } else {
                                    $issuer->sendMessage($this->getPlugin()->colorMessage("&cInvalid amplifier!"));
                                    return true;
                                }
                            } else {
                                $issuer->sendMessage($this->getPlugin()->colorMessage("&fUsage: /walkp amplifier <amplifier> <player>"));
                                return true;
                            }
                            break;
                        case "display":
                            if ($issuer->hasPermission("walkingparticles.command") !== true && $issuer->hasPermission("walkingparticles.command.admin") !== true && $issuer->hasPermission("walkingparticles.command.admin.display") !== true) {
                                $issuer->sendMessage($this->getPlugin()->colorMessage("&cYou don't have permission for this!"));
                                return true;
                            }
                            if (isset($args[1]) && isset($args[2])) {
                                $target = $this->getPlugin()->getServer()->getPlayer($args[2]);
                                if ($target !== null) {
                                    switch ($args[1]):
                                        case "line":
                                            if ($this->getPlugin()->setPlayerDisplay($target, "line") !== true) {
                                                return true;
                                            }
                                            $issuer->sendMessage($this->getPlugin()->colorMessage("&aYou set &e" . $target->getName() . "&a's display to &bline&a!"));
                                            return true;
                                        case "group":
                                            if ($this->getPlugin()->setPlayerDisplay($target, "group") !== true) {
                                                return true;
                                            }
                                            $issuer->sendMessage($this->getPlugin()->colorMessage("&aYou set &e" . $target->getName() . "&a's display to &bgroup&a!"));
                                            return true;
                                        case "spiral":
                                            if ($this->getPlugin()->setPlayerDisplay($target, "spiral") !== true) {
                                                return true;
                                            }
                                            $issuer->sendMessage($this->getPlugin()->colorMessage("&aYou set &e" . $target->getName() . "'s display to &bspiral&a!"));
                                            return true;
                                        default:
                                            $issuer->sendMessage("Usage: /walkp display line|group|spiral <target>");
                                            return true;
                                    endswitch;
                                } else {
                                    $issuer->sendMessage($this->getPlugin()->colorMessage("&cInvalid target!"));
                                    return true;
                                }
                            } else if (isset($args[1]) && !isset($args[2])) {
                                if ($issuer instanceof Player) {
                                    switch ($args[1]):
                                        case "line":
                                            if ($this->getPlugin()->setPlayerDisplay($issuer, "line") !== true) {
                                                return true;
                                            }
                                            $issuer->sendMessage($this->getPlugin()->colorMessage("&aYou set your display to &bline&a!"));
                                            return true;
                                        case "group":
                                            if ($this->getPlugin()->setPlayerDisplay($issuer, "group") !== true) {
                                                return true;
                                            }
                                            $issuer->sendMessage($this->getPlugin()->colorMessage("&aYou set your display to &bgroup&a!"));
                                            return true;
                                        case "spiral":
                                            if ($this->getPlugin()->setPlayerDisplay($issuer, "spiral") !== true) {
                                                return true;
                                            }
                                            $issuer->sendMessage($this->getPlugin()->colorMessage("&aYou set your display to &bspiral&a!"));
                                            return true;
                                        default:
                                            $issuer->sendMessage("Usage: /walkp display line|group|spiral");
                                            return true;
                                    endswitch;
                                }
                            } else {
                                $issuer->sendMessage("Usage: /walkp display line|group|spiral <target>");
                                return true;
                            }
                            break;
                        case "clear":
                        case "rmall":
                        case "removeall":
                            if ($issuer->hasPermission("walkingparticles.command") !== true && $issuer->hasPermission("walkingparticles.command.admin") !== true && $issuer->hasPermission("walkingparticles.command.admin.clear") !== true) {
                                $issuer->sendMessage($this->getPlugin()->colorMessage("&cYou don't have permission for this!"));
                                return true;
                            }
                            if (isset($args[1])) {
                                $target = $this->getPlugin()->getServer()->getPlayer($args[1]);
                                if ($target !== null) {
                                    if ($this->getPlugin()->isCleared($target) !== true) {
                                        if ($this->getPlugin()->clearPlayerParticle($target) !== true) {
                                            return true;
                                        }
                                        $issuer->sendMessage($this->getPlugin()->colorMessage("&aYou cleared &b" . $target->getName() . "&a's UltimateParticles!"));
                                        $target->sendMessage($this->getPlugin()->colorMessage("&aYour &bUltimateParticles &ahas been cleared!"));
                                        return true;
                                    } else {
                                        $issuer->sendMessage($this->getPlugin()->colorMessage("&cThere is no particle in use!"));
                                        return true;
                                    }
                                } else {
                                    $issuer->sendMessage($this->getPlugin()->colorMessage("&cInvalid target!"));
                                    return true;
                                }
                            } else {
                                if ($issuer instanceof Player) {
                                    if ($this->getPlugin()->isCleared($issuer) !== true) {
                                        if ($this->getPlugin()->clearPlayerParticle($issuer) !== true) {
                                            return true;
                                        }
                                        $issuer->sendMessage($this->getPlugin()->colorMessage("&aYour &bUltimateParticles &ahas been cleared!"));
                                        return true;
                                    } else {
                                        $issuer->sendMessage($this->getPlugin()->colorMessage("&cThere are no particles in use!"));
                                        return true;
                                    }
                                } else {
                                    $issuer->sendMessage("Usage: /walkp clear <player>");
                                    return true;
                                }
                            }
                            break;
                        case "pack":
                            if (isset($args[1])) {
                                switch ($args[1]):
                                    case "use":
                                    case "apply":
                                        if ($issuer->hasPermission("walkingparticles.command") !== true && $issuer->hasPermission("walkingparticles.command.admin") !== true && $issuer->hasPermission("walkingparticles.command.admin.pack") !== true && $issuer->hasPermission("walkingparticles.command.admin.pack.use") !== true) {
                                            $issuer->sendMessage($this->getPlugin()->colorMessage("&cYou don't have permission for this!"));
                                            return true;
                                        }
                                        if (isset($args[2])) {
                                            if ($this->getPlugin()->packExists($args[2])) {
                                                if ($issuer instanceof Player) {
                                                    if ($this->getPlugin()->activatePack($issuer, $args[2]) !== true) {
                                                        return true;
                                                    }
                                                    $issuer->sendMessage($this->getPlugin()->colorMessage("&aYou are now using walkp pack &b" . $args[2]));
                                                    return true;
                                                } else {
                                                    $issuer->sendMessage("Command only works in-game!");
                                                    return true;
                                                }
                                            } else {
                                                $issuer->sendMessage($this->getPlugin()->colorMessage("&cPack doesn't exist!"));
                                                return true;
                                            }
                                        } else {
                                            $issuer->sendMessage("Usage: /walkp pack use <pack_name>");
                                            return true;
                                        }
                                        break;
                                    case "create":
                                    case "cre":
                                        if ($issuer->hasPermission("walkingparticles.command") !== true && $issuer->hasPermission("walkingparticles.command.admin") !== true && $issuer->hasPermission("walkingparticles.command.admin.pack") !== true && $issuer->hasPermission("walkingparticles.command.admin.pack.create") !== true) {
                                            $issuer->sendMessage($this->getPlugin()->colorMessage("&cYou don't have permission for this!"));
                                            return true;
                                        }
                                        if (isset($args[2])) {
                                            $pack_name = $args[2];
                                            if ($this->getPlugin()->packExists($pack_name) !== true) {
                                                $this->getPlugin()->createPack($pack_name);
                                                $issuer->sendMessage($this->getPlugin()->colorMessage("&aWalkp pack created successfully with the name &b" . $pack_name));
                                                return true;
                                            } else {
                                                $issuer->sendMessage($this->getPlugin()->colorMessage("&cPack already exists!"));
                                                return true;
                                            }
                                        } else {
                                            $issuer->sendMessage("Usage: /walkp pack create <pack_name>");
                                            return true;
                                        }
                                        break;
                                    case "delete":
                                    case "del":
                                        if ($issuer->hasPermission("walkingparticles.command") !== true && $issuer->hasPermission("walkingparticles.command.admin") !== true && $issuer->hasPermission("walkingparticles.command.admin.pack") !== true && $issuer->hasPermission("walkingparticles.command.admin.pack.delete") !== true) {
                                            $issuer->sendMessage($this->getPlugin()->colorMessage("&cYou don't have permission for this!"));
                                            return true;
                                        }
                                        if (isset($args[2])) {
                                            $pack_name = $args[2];
                                            if ($this->getPlugin()->packExists($pack_name) !== false) {
                                                $this->getPlugin()->deletePack($pack_name);
                                                $issuer->sendMessage($this->getPlugin()->colorMessage("&aWalkp pack deleted successfully!"));
                                                return true;
                                            } else {
                                                $issuer->sendMessage($this->getPlugin()->colorMessage("&cPack doesn't exists!"));
                                                return true;
                                            }
                                        } else {
                                            $issuer->sendMessage("Usage: /walkp pack delete <pack_name>");
                                            return true;
                                        }
                                        break;
                                    case "addp":
                                        if ($issuer->hasPermission("walkingparticles.command") !== true && $issuer->hasPermission("walkingparticles.command.admin") !== true && $issuer->hasPermission("walkingparticles.command.admin.pack") !== true && $issuer->hasPermission("walkingparticles.command.admin.pack.addp") !== true) {
                                            $issuer->sendMessage($this->getPlugin()->colorMessage("&cYou don't have permission for this!"));
                                            return true;
                                        }
                                        if (isset($args[2]) && isset($args[3])) {
                                            if ($this->getPlugin()->packExists($args[2]) !== false) {
                                                $this->getPlugin()->addParticleToPack($args[2], $args[3]);
                                                $issuer->sendMessage($this->getPlugin()->colorMessage("&aYou added &e" . $args[3] . " &aparticle to the pack &b" . $args[2] . "&a!"));
                                                return true;
                                            } else {
                                                $issuer->sendMessage($this->getPlugin()->colorMessage("&cPack doesn't exist!"));
                                                return true;
                                            }
                                        } else {
                                            $issuer->sendMessage("Usage: /walkp pack addp <pack_name> <particle>");
                                            return true;
                                        }
                                        break;
                                    case "get":
                                        if ($issuer->hasPermission("walkingparticles.command") !== true && $issuer->hasPermission("walkingparticles.command.admin") !== true && $issuer->hasPermission("walkingparticles.command.admin.pack") !== true && $issuer->hasPermission("walkingparticles.command.admin.pack.get") !== true) {
                                            $issuer->sendMessage($this->getPlugin()->colorMessage("&cYou don't have permission for this!"));
                                            return true;
                                        }
                                        if (isset($args[2])) {
                                            $pack_name = $args[2];
                                            if ($this->getPlugin()->packExists($pack_name) !== false) {
                                                $issuer->sendMessage($this->getPlugin()->colorMessage("&aPack &b" . $pack_name . " &acontains: &6" . $this->getPlugin()->getPackParticles($pack_name)));
                                                return true;
                                            } else {
                                                $issuer->sendMessage($this->getPlugin()->colorMessage("&cPack doesn't exist!"));
                                                return true;
                                            }
                                        } else {
                                            $issuer->sendMessage("Usage: /walkp pack get <pack_name>");
                                            return true;
                                        }
                                        break;
                                    case "list":
                                        if ($issuer->hasPermission("walkingparticles.command") !== true && $issuer->hasPermission("walkingparticles.command.admin") !== true && $issuer->hasPermission("walkingparticles.command.admin.pack") !== true && $issuer->hasPermission("walkingparticles.command.admin.pack.list") !== true) {
                                            $issuer->sendMessage($this->getPlugin()->colorMessage("&cYou don't have permission for this!"));
                                            return true;
                                        }
                                        $issuer->sendMessage($this->getPlugin()->colorMessage("&aList of particle packs: &6" . $this->getPlugin()->listPacks()));
                                        return true;
                                        break;
                                    default:
                                        $issuer->sendMessage("Usage: /walkp pack <use|create|delete|addp|get|list> <args..>");
                                        return true;
                                endswitch;
                            } else {
                                $issuer->sendMessage("Usage: /walkp pack <use|create|delete|addp|get|list> <args..>");
                                return true;
                            }
                            break;
                        case "try":
                            if ($issuer->hasPermission("walkingparticles.command") !== true && $issuer->hasPermission("walkingparticles.command.admin") !== true && $issuer->hasPermission("walkingparticles.command.admin.try") !== true) {
                                $issuer->sendMessage($this->getPlugin()->colorMessage("&cYou don't have permission for this!"));
                                return true;
                            }
                            if (isset($args[1])) {
                                $target = $this->getPlugin()->getServer()->getPlayer($args[1]);
                                if ($target !== null) {
                                    if ($issuer instanceof Player) {
                                        if ($this->getPlugin()->isCleared($target) !== false) {
                                            $issuer->sendMessage($this->getPlugin()->colorMessage("&cTarget player isn't using any particles!"));
                                            return true;
                                        }
                                        if ($this->getPlugin()->tryPlayerParticle($issuer, $target) !== true) {
                                            return true;
                                        }
                                        $issuer->sendMessage($this->getPlugin()->colorMessage("&aYou have &e10 &aseconds to test &b" . $target->getName() . "&a's particles!\n&aParticles which " . $target->getName() . " using: &6" . $this->getPlugin()->getAllPlayerParticles($target)));
                                        return true;
                                    } else {
                                        $issuer->sendMessage($this->getPlugin()->colorMessage("Command only works in-game!"));
                                        return true;
                                    }
                                } else {
                                    $issuer->sendMessage($this->plugin->colorMessage("&cInvalid target!"));
                                    return true;
                                }
                            } else {
                                $issuer->sendMessage("Usage: /walkp try <player>");
                                return true;
                            }
                            break;
                        case "randomshow":
                        case "random":
                        case "randommode":
                        case "rand":
                            if ($issuer->hasPermission("walkingparticles.command") !== true && $issuer->hasPermission("walkingparticles.command.admin") !== true && $issuer->hasPermission("walkingparticles.command.admin.random") !== true) {
                                $issuer->sendMessage($this->getPlugin()->colorMessage("&cYou don't have permission for this!"));
                                return true;
                            }
                            if (isset($args[1])) {
                                $target = $this->getPlugin()->getServer()->getPlayer($args[1]);
                                if ($target !== null) {
                                    if ($this->getPlugin()->switchRandomMode($target, ($this->getPlugin()->isRandomMode($target) !== true ? true : false)) !== true) {
                                        return true;
                                    }
                                    $issuer->sendMessage($this->getPlugin()->colorMessage("&aYou turned " . ($this->getPlugin()->isRandomMode($target) !== true ? "off" : "on") . " &b" . $target->getName() . "&a's random mode!"));
                                    $target->sendMessage($this->getPlugin()->colorMessage("&aYour random mode has been turned " . ($this->getPlugin()->isRandomMode($target) !== true ? "off" : "on") . "!"));
                                    return true;
                                } else {
                                    $issuer->sendMessage($this->getPlugin()->colorMessage("&cInvalid target!"));
                                    return true;
                                }
                            } else {
                                if ($issuer instanceof Player) {
                                    if ($this->getPlugin()->switchRandomMode($issuer, ($this->getPlugin()->isRandomMode($issuer) !== true ? true : false)) !== true) {
                                        return true;
                                    }
                                    $issuer->sendMessage($this->getPlugin()->colorMessage("&aYour random mode has been turned " . ($this->getPlugin()->isRandomMode($issuer) !== true ? "off" : "on") . "!"));
                                    return true;
                                } else {
                                    $issuer->sendMessage("Usage: /walkp rand <player>");
                                    return true;
                                }
                            }
                            break;
                        case "use":
                            if ($issuer->hasPermission("walkingparticles.command") !== true && $issuer->hasPermission("walkingparticles.command.admin") !== true && $issuer->hasPermission("walkingparticles.command.admin.use") !== true) {
                                $issuer->sendMessage($this->getPlugin()->colorMessage("&cYou don't have permission for this!"));
                                return true;
                            }
                            if (isset($args[1])) {
                                $target = $this->getPlugin()->getServer()->getPlayer($args[1]);
                                if ($target !== null) {
                                    if ($issuer instanceof Player) {
                                        if ($this->getPlugin()->isCleared($target) !== false) {
                                            $issuer->sendMessage($this->getPlugin()->colorMessage("&c" . $target->getName() . " is not using any particles!"));
                                            return true;
                                        }
                                        if ($this->getPlugin()->usePlayerParticles($issuer, $target) !== true) {
                                            return true;
                                        }
                                        $issuer->sendMessage($this->getPlugin()->colorMessage("&aYour particles are now same as &b" . $target->getName() . "&a's!"));
                                        return true;
                                    } else {
                                        $issuer->sendMessage("Command only works in-game!");
                                        return true;
                                    }
                                } else {
                                    $issuer->sendMessage($this->getPlugin()->colorMessage("&cInvalid target!"));
                                    return true;
                                }
                            } else {
                                $issuer->sendMessage("Usage: /walkp use <player>");
                                return true;
                            }
                            break;
                        case "set":
                            if ($issuer->hasPermission("walkingparticles.command") !== true && $issuer->hasPermission("walkingparticles.command.admin") !== true && $issuer->hasPermission("walkingparticles.command.admin.set") !== true) {
                                $issuer->sendMessage($this->getPlugin()->colorMessage("&cYou don't have permission for this!"));
                                return true;
                            }
                            if (isset($args[1]) && isset($args[2])) {
                                $target = $this->getPlugin()->getServer()->getPlayer($args[2]);
                                if ($target !== null) {
                                    if ($this->getPlugin()->setPlayerParticle($target, $args[1]) !== true) {
                                        return true;
                                    }
                                    $issuer->sendMessage($this->getPlugin()->colorMessage("&aYou set &b" . $args[1] . "&a as &e" . $target->getName() . "&a walkingparticle!"));
                                    $target->sendMessage($this->getPlugin()->colorMessage("&aYour WalkingParticle has been set to &b" . $args[1] . "&a!"));
                                    return true;
                                } else {
                                    $issuer->sendMessage($this->getPlugin()->colorMessage("&cInvalid target!"));
                                    return true;
                                }
                            } else if (isset($args[1]) && !isset($args[2])) {
                                if ($issuer instanceof Player) {
                                    if ($this->getPlugin()->setPlayerParticle($issuer, $args[1]) !== true) {
                                        return true;
                                    }
                                    $issuer->sendMessage($this->getPlugin()->colorMessage("&aYou set &b" . $args[1] . "&a as your walkingparticle!"));
                                    return true;
                                } else {
                                    $issuer->sendMessage($this->getPlugin()->colorMessage("Usage: /walkp set <particle> <player>"));
                                    return true;
                                }
                            } else {
                                $issuer->sendMessage("Usage: /walkp set <particle> <player>");
                                return true;
                            }
                            break;
                        case "get":
                            if ($issuer->hasPermission("walkingparticles.command") !== true && $issuer->hasPermission("walkingparticles.command.admin") !== true && $issuer->hasPermission("walkingparticles.command.admin.get") !== true) {
                                $issuer->sendMessage($this->getPlugin()->colorMessage("&cYou don't have permission for this!"));
                                return true;
                            }
                            if (isset($args[1])) {
                                $target = $this->getPlugin()->getServer()->getPlayer($args[1]);
                                if ($target !== null) {
                                    $issuer->sendMessage($this->getPlugin()->colorMessage("&e" . $target->getName() . "&a's &bUltimateParticles§a: &f" . $this->getPlugin()->getAllPlayerParticles($target)));
                                    return true;
                                } else {
                                    $issuer->sendMessage($this->getPlugin()->colorMessage("&cInvalid target!"));
                                    return true;
                                }
                            } else {
                                if ($issuer instanceof Player) {
                                    $issuer->sendMessage($this->getPlugin()->colorMessage("&aYour &bUltimateParticles&a: &f" . $this->getPlugin()->getAllPlayerParticles($issuer)));
                                    return true;
                                } else {
                                    $issuer->sendMessage("Usage: /walkp get <player>");
                                    return true;
                                }
                            }
                            break;
                        case "itemmode":
                        case "itemshow":
                        case "item":
                            if ($issuer->hasPermission("walkingparticles.command") !== true && $issuer->hasPermission("walkingparticles.command.admin") !== true && $issuer->hasPermission("walkingparticles.command.admin.item") !== true) {
                                $issuer->sendMessage($this->getPlugin()->colorMessage("&cYou don't have permission for this!"));
                                return true;
                            }
                            if (isset($args[1])) {
                                $target = $this->getPlugin()->getServer()->getPlayer($args[1]);
                                if ($target !== null) {
                                    if ($this->getPlugin()->switchItemMode($target, $this->getPlugin()->isItemMode($target) ? false : true) !== true) {
                                        return true;
                                    }
                                    $issuer->sendMessage($this->getPlugin()->colorMessage("&aYou turned " . ($this->getPlugin()->isItemMode($target) ? "on " : "off ") . $target->getName() . "'s item mode!"));
                                    $target->sendMessage($this->getPlugin()->colorMessage("&aYour item mode has been turned " . ($this->getPlugin()->isItemMode($target) ? "on" : "off") . "!"));
                                    return true;
                                } else {
                                    $issuer->sendMessage($this->getPlugin()->colorMessage("&cInvalid target!"));
                                    return true;
                                }
                            } else {
                                if (!$issuer instanceof Player) {
                                    $issuer->sendMessage("Usage: /walkp item <player>");
                                    return true;
                                }
                                if ($this->getPlugin()->switchItemMode($issuer, ($this->getPlugin()->isItemMode($issuer) ? false : true)) !== true) {
                                    return true;
                                }
                                $issuer->sendMessage($this->getPlugin()->colorMessage("&aYou turned " . ($this->getPlugin()->isItemMode($issuer) ? "on" : "off") . " your item mode!"));
                                return true;
                            }
                            break;
                        case "list":
                            if ($issuer->hasPermission("walkingparticles.command") !== true && $issuer->hasPermission("walkingparticles.command.admin") !== true && $issuer->hasPermission("walkingparticles.command.admin.list") !== true) {
                                $issuer->sendMessage($this->getPlugin()->colorMessage("&cYou don't have permission for this!"));
                                return true;
                            }
                            $particles = new Particles($this->getPlugin());
                            $issuer->sendMessage($this->getPlugin()->colorMessage("&aList of available particles: &6" . implode(", ", $particles->getAll())));
                            return true;
                            break;
                        case "signhelp":
                        case "sign":
                            if ($issuer->hasPermission("walkingparticles.command") !== true && $issuer->hasPermission("walkingparticles.command.admin") !== true && $issuer->hasPermission("walkingparticles.command.admin.signhelp") !== true) {
                                $issuer->sendMessage($this->getPlugin()->colorMessage("&cYou don't have permission for this!"));
                                return true;
                            }
                            if (!isset($args[1])) {
                                $issuer->sendMessage($this->getPlugin()->colorMessage("&a- &f/walkp signhelp add"));
                                $issuer->sendMessage($this->getPlugin()->colorMessage("&a- &f/walkp signhelp remove"));
                                $issuer->sendMessage($this->getPlugin()->colorMessage("&a- &f/walkp signhelp set"));
                                $issuer->sendMessage($this->getPlugin()->colorMessage("&a- &f/walkp signhelp amplifier"));
                                $issuer->sendMessage($this->getPlugin()->colorMessage("&a- &f/walkp signhelp display"));
                                $issuer->sendMessage($this->getPlugin()->colorMessage("&a- &f/walkp signhelp pack"));
                                $issuer->sendMessage($this->getPlugin()->colorMessage("&a- &f/walkp signhelp randomshow"));
                                $issuer->sendMessage($this->getPlugin()->colorMessage("&a- &f/walkp signhelp itemshow"));
                                $issuer->sendMessage($this->getPlugin()->colorMessage("&a- &f/walkp signhelp get"));
                                $issuer->sendMessage($this->getPlugin()->colorMessage("&a- &f/walkp signhelp list"));
                                $issuer->sendMessage($this->getPlugin()->colorMessage("&a- &f/walkp signhelp clear"));
                                return true;
                            } else {
                                $issuer->sendMessage($this->getPlugin()->colorMessage("-----"));
                                $sh = new SignHelp($this->getPlugin());
                                $sh->sendHelp($issuer, $args[1]);
                                $issuer->sendMessage($this->getPlugin()->colorMessage("-----"));
                                return true;
                            }
                            break;
                        case "about":
                        case "version":
                            if ($issuer->hasPermission("walkingparticles.command") !== true && $issuer->hasPermission("walkingparticles.command.admin") !== true && $issuer->hasPermission("walkingparticles.command.admin.version") !== true) {
                                $issuer->sendMessage($this->getPlugin()->colorMessage("&cYou don't have permission for this!"));
                                return true;
                            }
                            $plugin = $this->getPlugin();
                            $issuer->sendMessage("You are using UltimateParticles version " . $plugin::VERSION . " for PocketMine 1.6 (API 1.13.0/2.0.0) developed by hoyinm14mc!");
                            return true;
                            break;
                        case "on":
                            if ($issuer->hasPermission("walkingparticles.command") !== true && $issuer->hasPermission("walkingparticles.command.admin") !== true && $issuer->hasPermission("walkingparticles.command.admin.on") !== true) {
                                $issuer->sendMessage($this->getPlugin()->colorMessage("&cYou don't have permission for this!"));
                                return true;
                            }
                            $t = $this->getPlugin()->data->getAll();
                            if (isset($args[1])) {
                                $target = $this->getPlugin()->getServer()->getPlayer($args[1]);
                                if ($target === null) {
                                    $issuer->sendMessage($this->getPlugin()->colorMessage("&cInvalid target!"));
                                    return true;
                                }
                                if ($this->getPlugin()->enableEffects($target) !== true) {
                                    return true;
                                }
                                $target->sendMessage($this->getPlugin()->colorMessage("&aYour UltimateParticles has been turned on!"));
                                $issuer->sendMessage($this->getPlugin()->colorMessage("&aYou turned &b" . $target->getName() . "&a's UltimateParticles on!"));
                                return true;
                            } else {
                                if ($issuer instanceof Player !== true) {
                                    $issuer->sendMessage("Usage: /walkp on|off <player>");
                                    return true;
                                }
                                if ($this->getPlugin()->enableEffects($issuer) !== true) {
                                    return true;
                                }
                                $issuer->sendMessage($this->getPlugin()->colorMessage("&aYour UltimateParticles has been turned on!"));
                                return true;
                            }
                            break;
                        case "off":
                            if ($issuer->hasPermission("walkingparticles.command") !== true && $issuer->hasPermission("walkingparticles.command.admin") !== true && $issuer->hasPermission("walkingparticles.command.admin.off") !== true) {
                                $issuer->sendMessage($this->getPlugin()->colorMessage("&cYou don't have permission for this!"));
                                return true;
                            }
                            $t = $this->getPlugin()->data->getAll();
                            if (isset($args[1])) {
                                $target = $this->getPlugin()->getServer()->getPlayer($args[1]);
                                if ($target === null) {
                                    $issuer->sendMessage($this->getPlugin()->colorMessage("&cInvalid target!"));
                                    return true;
                                }
                                if ($this->getPlugin()->disableEffects($target) !== true) {
                                    return true;
                                }
                                $target->sendMessage($this->getPlugin()->colorMessage("&aYour UltimateParticles has been turned off!"));
                                $issuer->sendMessage($this->getPlugin()->colorMessage("&aYou turned &b" . $target->getName() . "&a's UltimateParticles off!"));
                                return true;
                            } else {
                                if ($issuer instanceof Player !== true) {
                                    $issuer->sendMessage("Usage: /walkp on|off <player>");
                                    return true;
                                }
                                if ($this->getPlugin()->disableEffects($issuer) !== true) {
                                    return true;
                                }
                                $issuer->sendMessage($this->getPlugin()->colorMessage("&aYour UltimateParticles has been turned off!"));
                                return true;
                            }
                            break;
                        case "colors":
                        case "colours":
                            if ($issuer->hasPermission("walkingparticles.command") !== true && $issuer->hasPermission("walkingparticles.command.admin") !== true && $issuer->hasPermission("walkingparticles.command.admin.colors") !== true) {
                                $issuer->sendMessage($this->getPlugin()->colorMessage("&cYou don't have permission for this!"));
                                return true;
                            }
                            $particles = new Particles($this->getPlugin());
                            $issuer->sendMessage($this->getPlugin()->colorMessage("&aColor list: &6" . implode(", ", $particles->getAllColors())));
                            return true;
                            break;
                    endswitch;
                } else {
                    return false;
                }
                break;
        endswitch;
    }

}

?>