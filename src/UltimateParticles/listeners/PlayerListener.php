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

use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\event\player\PlayerTeleportEvent;
use pocketmine\event\player\PlayerItemHeldEvent;
use pocketmine\math\Vector3;
use pocketmine\item\ItemBlock;
use UltimateParticles\base\BaseListener;

class PlayerListener extends BaseListener
{

    public function onMove(PlayerMoveEvent $event)
    {
        if (in_array($event->getPlayer()->getLevel()->getName(), $this->getPlugin()->getConfig()->get("disabled-worlds")) !== false) {
            if ($event->getPlayer()->hasPermission("walkingparticles.disabledworlds.bypass") !== true) {
                return;
            }
        }
        if ($this->plugin->getAuth() !== null) {
            switch ($this->getPlugin()->getAuth()->getName()) {
                case "SimpleAuth":
                    if ($this->getPlugin()->getAuth()->isPlayerAuthenticated($event->getPlayer()) !== true && $this->plugin->getConfig()->get("auth-check") !== false) {
                        return;
                    }
                    break;
                case "EasyAuth":
                    if ($this->getPlugin()->getAuth()->isAuth($event->getPlayer()) !== true && $this->plugin->getConfig()->get("auth-check") !== false) {
                        return;
                    }
                    break;
            }
        }
        if ($event->getPlayer()->hasPermission("walkingparticles")) {
            if ($event->getFrom()->x == $event->getPlayer()->x && $event->getFrom()->z == $event->getPlayer()->z) {
            } else {
                $t = $this->getPlugin()->getData()->getAll();
                $this->getPlugin()->moving[$event->getPlayer()->getName()] = $event->getPlayer()->getName();
                if (isset($t[$event->getPlayer()->getName()]) && $this->getConfig()->get("enable") !== false && $this->getPlugin()->isCleared($event->getPlayer()) !== true && $t[$event->getPlayer()->getName()]["enabled"] !== false) {
                    if ($this->plugin->VanishNoPacket !== null) {
                        if ($this->plugin->VanishNoPacket->isVanished($event->getPlayer()) !== false && $this->plugin->getConfig()->get("hideparticles-vanished") !== false) {
                            return;
                        }
                    }
                    $x = $event->getFrom()->x;
                    $y = $event->getFrom()->y;
                    $z = $event->getFrom()->z;
                    $x1 = $x - 1;
                    $x2 = $x + 1;
                    $y1 = $y + 0.6;
                    $y2 = $y + 1;
                    $y3 = $y + 1.4;
                    $z1 = $z - 1;
                    $z2 = $z + 1;
                    for ($i = 0; $i < $this->getPlugin()->getPlayerAmplifier($event->getPlayer()); $i++) {
                        foreach ((array)$t[$event->getPlayer()->getName()]["particle"] as $p) {
                            if ($this->getPlugin()->getParticles()->getTheParticle($p, new Vector3($x, $y, $z)) == "unknown_particle") {
                                return;
                            } else if ($this->getPlugin()->getPlayerDisplay($event->getPlayer()) == "line") {
                                $event->getPlayer()->getLevel()->addParticle($this->getPlugin()->getParticles()->getTheParticle($p, new Vector3($x, $y2, $z)));
                            } else if ($this->getPlugin()->getPlayerDisplay($event->getPlayer()) == "spiral") {
                                // Do nothing here.
                            } else {
                                $event->getPlayer()->getLevel()->addParticle($this->getPlugin()->getParticles()->getTheParticle($p, new Vector3(rand($x1, $x2), rand(rand($y1, $y), rand($y2, $y3)), rand($z1, $z2))));
                            }
                        }
                    }
                }
            }
        }
    }

    public function onLogin(PlayerLoginEvent $event)
    {
        $t = $this->getPlugin()->getData()->getAll();
        if (!isset($t[$event->getPlayer()->getName()])) {
            $t[$event->getPlayer()->getName()]["amplifier"] = $this->getPlugin()->getConfig()->get("default-amplifier");
            $t[$event->getPlayer()->getName()]["display"] = $this->getPlugin()->getConfig()->get("default-display");
            $t[$event->getPlayer()->getName()]["enabled"] = true;
            $this->getPlugin()->getData()->setAll($t);
            $this->getPlugin()->getData()->save();
            if ($this->getConfig()->get("default-particle") !== null) {
                $t[$event->getPlayer()->getName()]["particle"][] = $this->getPlugin()->getConfig()->get("default-particle");
                $this->getPlugin()->getData()->setAll($t);
                $this->getPlugin()->getData()->save();
            }
        }
        if (!isset($t[$event->getPlayer()->getName()]["enabled"])) {
            $t[$event->getPlayer()->getName()]["enabled"] = true;
            $this->getPlugin()->data->setAll($t);
            $this->getPlugin()->data->save();
        }
    }

    public function onQuit(PlayerQuitEvent $event)
    {
        if ($this->getPlugin()->isRandomMode($event->getPlayer()))
            $this->getPlugin()->switchRandomMode($event->getPlayer(), false);
        if ($this->getPlugin()->isItemMode($event->getPlayer()))
            $this->getPlugin()->switchItemMode($event->getPlayer(), false);
    }

    public function onItemHeld(PlayerItemHeldEvent $event)
    {
        if ($this->getPlugin()->isItemMode($event->getPlayer())) {
            $id = (string)$event->getItem()->getId();
            if ($event->getItem() instanceof ItemBlock) {
                if ($id == "0") {
                    $this->getPlugin()->setPlayerParticle($event->getPlayer(), "unknown");
                    return;
                } else {
                    $this->getPlugin()->setPlayerParticle($event->getPlayer(), "block_" . $id);
                }
            } else {
                $this->getPlugin()->setPlayerParticle($event->getPlayer(), "item_" . $id);
            }
        }
    }

}

?>