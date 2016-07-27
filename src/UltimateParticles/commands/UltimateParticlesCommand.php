<?php

namespace UltimateParticles\commands;

use pocketmine\level\Position;
use pocketmine\Player;
use UltimateParticles\base\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;

class UltimateParticlesCommand extends BaseCommand{

    public function onCommand(CommandSender $issuer, Command $cmd, $label, array $args)
    {
        switch($cmd->getName()){
            case "ultimateparticles":
                if(isset($args[0]) !== true){
                    $this->getPlugin()->getServer()->dispatchCommand($issuer, "ultimateparticles help");
                    return true;
                }
                switch($args[0]){
                    case "help":
                        if($issuer->hasPermission("ultimateparticles.command.help") !== true && $issuer->hasPermission("ultimateparticles.command") !== true){
                            $issuer->sendMessage($this->getPlugin()->colorMessage("&cYou don't have permission for this!"));
                            return true;
                        }
                        $issuer->sendMessage($this->plugin->colorMessage("&ahowing help page &6(1/1)"));
                        $issuer->sendMessage($this->plugin->colorMessage("&l&b-&f&r/ultip help"));
                        $issuer->sendMessage($this->plugin->colorMessage("&l&b-&f&r/ultip ejector"));
                        return true;
                        break;
                    case "ejector":
                        $ej = $this->plugin->data5->getAll();
                        if($issuer->hasPermission("ultimateparticles.command.ejector") !== true && $issuer->hasPermission("ultimateparticles.command") !== true){
                            $issuer->sendMessage($this->getPlugin()->colorMessage("&cYou don't have permission for this!"));
                            return true;
                        }
                        if(isset($args[1]) !== true){
                            $this->getPlugin()->getServer()->dispatchCommand($issuer, "ultip ejector help");
                            return true;
                        }
                        switch ($args[1]){
                            case "help":
                                $issuer->sendMessage($this->plugin->colorMessage("&aShowing help page (1/1)"));
                                $issuer->sendMessage($this->plugin->colorMessage("&b&l-&f&r/ultip ejector help"));
                                $issuer->sendMessage($this->plugin->colorMessage("&b&l-&f&r/ultip ejector add <particle(separate with commas)> <name> <amplfier>"));
                                $issuer->sendMessage($this->plugin->colorMessage("&b&l-&f&r/ultip ejector remove <name>"));
                                $issuer->sendMessage($this->plugin->colorMessage("&b&l-&f&r/ultip ejector list"));
                                return true;
                                break;
                            case "add":
                                if(isset($args[3]) !== true){
                                    $this->plugin->getServer()->dispatchCommand($issuer, "ultip ejector help");
                                    return true;
                                }
                                if($issuer instanceof Player !== true){
                                    $issuer->sendMessage("Command only works in-game!");
                                    return true;
                                }
                                if($this->plugin->isEjectorExists($args[3]) !== false){
                                    $issuer->sendMessage($this->plugin->colorMessage("&cEjector with same name already exists!"));
                                    return true;
                                }
                                $particles = explode(",", $args[2]);
                                if($this->getPlugin()->setEjector($args[3], new Position($issuer->x, $issuer->y, $issuer->z, $issuer->getLevel()), $particles, (isset($args[4]) !== false ? (is_numeric($args[4]) !== false ? $args[4] : 1) : 1)) !== false){
                                    $issuer->sendMessage($this->plugin->colorMessage("You set an ejector at your position successfully!"));
                                    $issuer->sendMessage($this->plugin->colorMessage("Name: ".$args[3]."\nParticles: ".$args[2]."\nAmplifier: ".(isset($args[4]) !== false ? (is_numeric($args[4]) !== false ? $args[4] : 1) : 1)));
                                    return true;
                                }else{
                                    $issuer->sendMessage("An error occurred while executing this command!");
                                    return true;
                                }
                                break;
                            case "remove":
                                if(isset($args[2]) !== true){
                                    $this->plugin->getServer()->dispatchCommand($issuer, "ultip ejector help");
                                    return true;
                                }
                                if($this->plugin->isEjectorExists($args[2]) !== true){
                                    $issuer->sendMessage($this->plugin->colorMessage("&cEjector with that name does not exist!"));
                                    return true;
                                }
                                if($this->plugin->removeEjector($args[2]) !== false){
                                    $issuer->sendMessage($this->plugin->colorMessage("&aYou removed ejector &e".$args[2]." &asuccessfully!"));
                                    return true;
                                }else{
                                    $issuer->sendMessage("An error occurred during execution of this command!");
                                    return true;
                                }
                                break;
                            case "list":
                                $issuer->sendMessage($this->plugin->colorMessage("&aList of particle ejectors: \n".$this->plugin->getAllEjectors()));
                                return true;
                                break;
                            default:
                                $this->getPlugin()->getServer()->dispatchCommand($issuer, "ultip ejector help");
                                return true;
                        }
                    break;
                    default:
                        $this->getPlugin()->getServer()->dispatchCommand($issuer, "ultimateparticles help");
                        return true;
                }
            break;
        }
    }

}
?>