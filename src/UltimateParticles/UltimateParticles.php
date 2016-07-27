<?php

/*
 * This file is the main class of UltimateParticles.
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

use UltimateParticles\commands\BloodCommand;
use UltimateParticles\events\PlayerAddWPEvent;
use UltimateParticles\events\PlayerClearWPEvent;
use UltimateParticles\events\PlayerRemoveWPEvent;
use UltimateParticles\events\PlayerSetAmplifierEvent;
use UltimateParticles\events\PlayerSetDisplayEvent;
use UltimateParticles\events\PlayerSetWPEvent;
use UltimateParticles\events\PlayerSwitchRandommodeEvent;
use UltimateParticles\events\PlayerTryPlayerParticleEvent;
use UltimateParticles\events\PlayerApplyPackEvent;
use UltimateParticles\events\PlayerUsePlayerParticlesEvent;
use UltimateParticles\events\PlayerSwitchItemmodeEvent;
use UltimateParticles\listeners\PlayerListener;
use UltimateParticles\listeners\SignListener;
use UltimateParticles\listeners\EntityListener;
use UltimateParticles\task\ParticleShowTask;
use UltimateParticles\task\RandomModeTask;
use UltimateParticles\task\TryParticleTask;
use UltimateParticles\task\SpiralTask;
use UltimateParticles\commands\WponCommand;
use UltimateParticles\commands\WpoffCommand;
use UltimateParticles\commands\WprandCommand;
use UltimateParticles\commands\WpitemCommand;
use UltimateParticles\commands\WppackCommand;
use UltimateParticles\commands\WplistCommand;
use UltimateParticles\commands\WpgetCommand;
use UltimateParticles\commands\WptryCommand;
use UltimateParticles\commands\AdminCommand;
use UltimateParticles\commands\UltimateParticlesCommand;
use UltimateParticles\events\PlayerEffectsEnableEvent;
use UltimateParticles\events\PlayerEffectsDisableEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\Player;
use pocketmine\utils\Config;
use pocketmine\level\Position;
use pocketmine\math\Vector3;
use pocketmine\item\ItemBlock;
use pocketmine\entity\Entity;

class UltimateParticles extends PluginBase implements UltimateParticlesAPI
{

    /**
     *
     * @var static $this |null
     */
    private static $instance = null;
    /**
     *
     * @var multitype:string
     */
    public $random_mode = [];
    /**
     *
     * @var multitype:string
     */
    public $item_mode = [];
    /**
     * 0-50
     * @var int
     */
    public $spiral_order = 0;
    /**
     *
     * @var object|null
     */
    private $eco = null;
    /**
     *
     * @var object|null
     */
    private $auth = null;

    /**
     *
     * @var string
     */
    private $provider = "YAML";

    /**
     *
     * @return UltimateParticles
     */
    public static function getInstance(): UltimateParticles
    {
        return self::$instance;
    }

    public function onEnable()
    {
        $this->getLogger()->info("Loading resources..");
        if (!is_dir($this->getDataFolder())) {
            mkdir($this->getDataFolder());
        }
        $this->saveDefaultConfig();
        if ($this->getConfig()->exists("v") !== true || $this->getConfig()->get("v") != $this->getDescription()->getVersion()) {
            $this->getLogger()->info("Update found!  Updating configuration...");
            $this->getLogger()->info("All settings are being reset. The old config is saved in the plugin data folder.");
            if (file_exists($this->getDataFolder() . "config.yml.old")) {
                unlink($this->getDataFolder() . "config.yml.old");
            }
            rename($this->getDataFolder() . "config.yml", $this->getDataFolder() . "config.yml.old");
            $this->saveDefaultConfig();
        }
        $this->reloadConfig();
        $this->data = new Config ($this->getDataFolder() . "players.yml", Config::YAML, array());
        $this->saveResource("particlepacks.yml");
        $this->data2 = new Config ($this->getDataFolder() . "particlepacks.yml", Config::YAML, array());
        $this->data3 = new Config ($this->getDataFolder() . "temp1.yml", Config::YAML, array());
        $this->saveResource("bloodstats.yml");
        $this->data4 = new Config($this->getDataFolder() . "bloodstats.yml", Config::YAML, array());
        $this->data5 = new Config($this->getDataFolder() . "ejector.yml", Config::YAML, array());
        $bloodfx = $this->getServer()->getPluginManager()->getPlugin("BloodFX");
        if ($bloodfx !== null) {
            $this->getLogger()->notice($this->colorMessage("Plugin &bBloodFX &fhas been disabled, as UltimateParticles has this plugin's features implemented."));
            $this->getServer()->getPluginManager()->disablePlugin($bloodfx);
        }
        $this->getLogger()->info("Loading authentication plugins..");
        $aplu = [
            "SimpleAuth",
            "EasyAuth"
        ];
        foreach ($aplu as $auth) {
            $pl = $this->getServer()->getPluginManager()->getPlugin($auth);
            if ($pl !== null) {
                $this->auth = $pl;
                $this->getLogger()->info("Loaded with " . $auth . "!");
            }
        }
        $this->getLogger()->info("Loading economy plugins..");
        $econ_plugins = [
            "GoldStd",
            "MassiveEconomy",
            "PocketMoney",
            "EconomyAPI"
        ];
        foreach ($econ_plugins as $e_plugin) {
            $pl = $this->getServer()->getPluginManager()->getPlugin($e_plugin);
            if ($pl !== null) {
                $this->eco = $pl;
                $this->getLogger()->info("Loaded with " . $e_plugin . "!");
            }
        }
        if ($this->eco === null) {
            $this->getLogger()->info("No economy plugin found!");
        }
        $this->getLogger()->info("Loading plugin..");
        $this->VanishNoPacket = $this->getServer()->getPluginManager()->getPlugin("VanishNP");
        if ($this->VanishNoPacket !== null) {
            $this->getLogger()->info("Loaded with VanishNoPacket!");
        }
        self::$instance = $this;
        $this->particles = new Particles ($this);
        $this->getServer()->getScheduler()->scheduleRepeatingTask(new ParticleShowTask ($this), 13);
        $this->getServer()->getScheduler()->scheduleRepeatingTask(new RandomModeTask ($this), 10);
        $this->getServer()->getScheduler()->scheduleRepeatingTask(new SpiralTask ($this), 3);
        $this->getServer()->getPluginManager()->registerEvents(new PlayerListener ($this), $this);
        $this->getServer()->getPluginManager()->registerEvents(new SignListener ($this), $this);
        $this->getServer()->getPluginManager()->registerEvents(new EntityListener ($this), $this);
        $this->getCommand("wprand")->setExecutor(new WprandCommand ($this));
        $this->getCommand("wpitem")->setExecutor(new WpitemCommand ($this));
        $this->getCommand("wppack")->setExecutor(new WppackCommand ($this));
        $this->getCommand("wplist")->setExecutor(new WplistCommand ($this));
        $this->getCommand("wpget")->setExecutor(new WpgetCommand ($this));
        $this->getCommand("wptry")->setExecutor(new WptryCommand ($this));
        $this->getCommand("wpon")->setExecutor(new WponCommand ($this));
        $this->getCommand("wpoff")->setExecutor(new WpoffCommand ($this));
        $this->getCommand("walkingparticles")->setExecutor(new AdminCommand ($this));
        $this->getCommand("ultimateparticles")->setExecutor(new UltimateParticlesCommand($this));
        $this->getCommand("blood")->setExecutor(new BloodCommand($this));
        $this->getLogger()->info($this->colorMessage("&aLoaded Successfully!"));
    }

    public function colorMessage(string $message): string
    {
        return str_replace("&", "§", $message);
    }

    /*
     * Economy plugin that UltimateParticles detected
     */

    public function getEco()
    {
        return $this->eco;
    }

    /*
     * Auth plugin that UltimateParticles detected
     */
    public function getAuth()
    {
        return $this->auth;
    }

    // For external use
    /*
     * @deprecated
     */
    public function getData(string $file = "data")
    {
        switch (strtolower($file)) :
            case "data" :
                return $this->data;
            case "data2" :
            case "particlepacks" :
                return $this->data2;
            case "data3" :
            case "temp" :
                return $this->data3;
        endswitch;
        return false;
    }

    /**
     *
     * @return Particles
     */
    public function getParticles(): Particles
    {
        $particles = new Particles ($this);
        return $particles;
    }

    /**
     * @api
     *
     * @param Player $player
     * @param Player $player2
     *
     * @return boolean
     */
    public function tryPlayerParticle(Player $player, Player $player2): bool
    {
        $this->getServer()->getPluginManager()->callEvent($event = new PlayerTryPlayerParticleEvent ($this, $player, $player2));
        if ($event->isCancelled()) {
            return false;
        }
        $t = $this->data->getAll();
        $this->putTemp($player);
        $this->clearPlayerParticle($player);
        foreach ($t [$player2->getName()] ["particle"] as $pc) {
            $this->addPlayerParticle($player, $pc);
        }
        $this->getServer()->getScheduler()->scheduleDelayedTask(new TryParticleTask ($this, $player), 20 * 10);
        return true;
    }

    /**
     *
     * @param Player $player
     *
     * @return boolean
     */
    public function putTemp(Player $player): bool
    {
        if ($this->isCleared($player) !== true) {
            $t = $this->data->getAll();
            $temp = $this->data3->getAll();
            foreach ($t [$player->getName()] ["particle"] as $pc) {
                $temp [$player->getName()] [] = $pc;
            }
            $this->data3->setAll($temp, $pc);
            $this->data3->save();
            return true;
        }
        return false;
    }

    /**
     * @api
     *
     * @param Player $player
     *
     * @return boolean
     */
    public function isCleared(Player $player): bool
    {
        $t = $this->data->getAll();
        $array = $t [$player->getName()] ["particle"];
        return count($array) < 1;
    }

    /**
     * @api
     *
     * @param Player $player
     *
     * @return boolean
     */
    public function clearPlayerParticle(Player $player): bool
    {
        $t = $this->data->getAll();
        $this->getServer()->getPluginManager()->callEvent($event = new PlayerClearWPEvent ($this, $player, $t [$player->getName()] ["particle"]));
        if ($event->isCancelled()) {
            return false;
        }
        foreach ($t [$player->getName()] ["particle"] as $p) {
            $pa = array_search($p, $t [$player->getName()] ["particle"]);
            unset ($t [$player->getName()] ["particle"] [$pa]);
        }
        $this->data->setAll($t);
        $this->data->save();
        return true;
    }

    /**
     * @api
     *
     * @param Player $player
     * @param string $particle
     *
     * @return boolean
     */
    public function addPlayerParticle(Player $player, string $particle): bool
    {
        $this->getServer()->getPluginManager()->callEvent($event = new PlayerAddWPEvent ($this, $player, $particle));
        if ($event->isCancelled()) {
            return false;
        }
        $t = $this->data->getAll();
        $t [$player->getName()] ["particle"] [] = $particle;
        $this->data->setAll($t);
        $this->data->save();
        return true;
    }

    /**
     * @api
     *
     * @param Player $player
     * @param Player $player2
     *
     * @return boolean
     */
    public function usePlayerParticles(Player $player, Player $player2): bool
    {
        $t = $this->data->getAll();
        $this->getServer()->getPluginManager()->callEvent($event = new PlayerUsePlayerParticlesEvent ($this, $player, $player2));
        if ($event->isCancelled()) {
            return false;
        }
        $this->clearPlayerParticle($player);
        foreach ($t [$player2->getName()] ["particle"] as $pc) {
            $this->addPlayerParticle($player, $pc);
        }
        return true;
    }

    /**
     * @api
     *
     * @param Player $player
     * @param array $particles
     *
     * @return bool
     */
    public function setPlayerParticles(Player $player, array $particles): bool
    {
        if (is_array($particles) !== true) {
            return false;
        }
        $this->clearPlayerParticle($player);
        foreach ($particles as $particle) {
            $this->addPlayerParticle($player, $particle);
        }
        return true;
    }

    /**
     * @api
     *
     * @param Player $player
     * @param array $particles
     *
     * @return bool
     */
    public function addPlayerParticles(Player $player, array $particles): bool
    {
        $t = $this->data->getAll();
        if (is_array($particles) !== true) {
            return false;
        }
        foreach ($particles as $particle) {
            $t [$player->getName()] ["particle"] [] = $particle;
            $this->data->setAll($t);
            $this->data->save();
        }
        return true;
    }

    /**
     * @api
     *
     * @param Player $player
     * @param string $particle
     *
     * @return boolean
     */
    public function removePlayerParticle(Player $player, string $particle): bool
    {
        $this->getServer()->getPluginManager()->callEvent($event = new PlayerRemoveWPEvent ($this, $player, $particle));
        if ($event->isCancelled()) {
            return false;
        }
        $t = $this->data->getAll();
        $p = array_search($particle, $t [$player->getName()] ["particle"]);
        unset ($t [$player->getName()] ["particle"] [$p]);
        $this->data->setAll($t);
        $this->data->save();
        return true;
    }

    /**
     * @api
     *
     * @param Player $player
     *
     * @return string
     */
    public function getAllPlayerParticles(Player $player): string
    {
        $t = $this->data->getAll();
        $particles = $t [$player->getName()] ["particle"];
        $p = "";
        foreach ($particles as $ps) {
            $p .= $ps . ", ";
        }
        return substr($p, 0, -2);
    }

    /**
     * @api
     *
     * @param Player $player
     * @return boolean
     */
    public function enableEffects(Player $player): bool
    {
        $this->getServer()->getPluginManager()->callEvent($event = new PlayerEffectsEnableEvent ($this, $player));
        if ($event->isCancelled()) {
            return false;
        }
        $t = $this->data->getAll();
        $t [$player->getName()] ["enabled"] = true;
        $this->data->setAll($t);
        $this->data->save();
        return true;
    }

    /**
     * @api
     *
     * @param Player $player
     * @return boolean
     */
    public function disableEffects(Player $player): bool
    {
        $this->getServer()->getPluginManager()->callEvent($event = new PlayerEffectsDisableEvent ($this, $player));
        if ($event->isCancelled()) {
            return false;
        }
        $t = $this->data->getAll();
        $t [$player->getName()] ["enabled"] = false;
        $this->data->setAll($t);
        $this->data->save();
        return true;
    }

    /**
     * @api
     *
     * @param Player $player
     * @return boolean
     */
    public function isEffectsEnabled(Player $player): bool
    {
        $t = $this->data->getAll();
        return $t [$player->getName()] ["enabled"];
    }

    /**
     * @api
     *
     * @param Player $player
     * @param int $amplifier
     *
     * @return boolean
     */
    public function setPlayerAmplifier(Player $player, int $amplifier): bool
    {
        $this->getServer()->getPluginManager()->callEvent($event = new PlayerSetAmplifierEvent ($this, $player, $amplifier));
        if ($event->isCancelled()) {
            return false;
        }
        $t = $this->data->getAll();
        $t [$player->getName()] ["amplifier"] = $amplifier;
        $this->data->setAll($t);
        $this->data->save();
        return true;
    }

    /**
     * @api
     *
     * @param Player $player
     *
     * @return integer
     */
    public function getPlayerAmplifier(Player $player)
    {
        $t = $this->data->getAll();
        return $t [$player->getName()] ["amplifier"];
    }

    /**
     * @api
     *
     * @param Player $player
     * @param string $display
     *
     * @return boolean
     */
    public function setPlayerDisplay(Player $player, string $display): bool
    {
        $this->getServer()->getPluginManager()->callEvent($event = new PlayerSetDisplayEvent ($this, $player, $display));
        if ($event->isCancelled()) {
            return false;
        }
        $t = $this->data->getAll();
        $t [$player->getName()] ["display"] = $display;
        $this->data->setAll($t);
        $this->data->save();
        return true;
    }

    /**
     * @api
     *
     * @param Player $player
     *
     * @return string
     */
    public function getPlayerDisplay(Player $player): string
    {
        $t = $this->data->getAll();
        return $t [$player->getName()] ["display"];
    }

    /**
     * @api
     *
     * @param Position $pos
     * @return array
     */
    public function getSpiral(Position $pos): array
    {
        $array = [];
        for ($yaw = 3, $i = $pos->y; $i <= ($pos->y + 2.5); $yaw = $yaw + (pi() * 2) / 20, $i = $i + 0.05) {
            $array [] = new Vector3 (-sin($yaw) + $pos->x, $i, cos($yaw) + $pos->z);
        }
        //echo count($array)."\n";
        return $array;
    }

    /**
     * @api
     *
     * @return int
     */
    public function getCurrentSpiralOrder(): int
    {
        return $this->spiral_order;
    }

    /**
     * @api
     *
     * @return bool
     */
    public function isSpiralAnimated(): bool
    {
        return (bool)$this->getConfig()->get("disable-animated-spirals") !== true;
    }

    /**
     * @api
     *
     * @param Player $player
     * @param string $pack_name
     *
     * @return boolean
     */
    public function activatePack(Player $player, string $pack_name): bool
    {
        $this->getServer()->getPluginManager()->callEvent($event = new PlayerApplyPackEvent ($this, $player, $pack_name, 0, null));
        if ($event->isCancelled()) {
            return false;
        }
        $p = $this->data2->getAll();
        $this->clearPlayerParticle($player);
        foreach ($p [$pack_name] as $pc) {
            $this->addPlayerParticle($player, $pc);
        }
        return true;
    }

    /**
     * @api
     *
     * @param string $pack_name
     */
    public function createPack(string $pack_name)
    {
        $p = $this->data2->getAll();
        $p [$pack_name] [] = "";
        $this->data2->setAll($p);
        $this->data2->save();
    }

    /**
     * @api
     *
     * @param string $pack_name
     * @param string $particle
     */
    public function addParticleToPack(string $pack_name, string $particle)
    {
        $p = $this->data2->getAll();
        $pa = array_search("", $p [$pack_name]);
        unset ($p [$pack_name] [$pa]);
        $p [$pack_name] [] = $particle;
        $this->data2->setAll($p);
        $this->data2->save();
    }

    /**
     * @api
     *
     * @param string $pack_name
     *
     * @return string
     */
    public function getPack(string $pack_name): string
    {
        $p = $this->data2->getAll();
        return $p [$pack_name];
    }

    /*
     * Packs
     */

    /**
     * @api
     *
     * @param string $pack_name
     */
    public function deletePack(string $pack_name)
    {
        $p = $this->data2->getAll();
        unset ($p [$pack_name]);
        $this->data2->setAll($p);
        $this->data2->save();
    }

    /**
     * @api
     *
     * @param string $pack_name
     *
     * @return boolean
     */
    public function packExists(string $pack_name): bool
    {
        $p = $this->data2->getAll();
        return isset ($p [$pack_name]);
    }

    /**
     * @api
     *
     * @param string $pack_name
     * @return boolean
     */
    public function getPackParticles(string $pack_name): bool
    {
        $p = $this->data2->getAll();
        $msg = "";
        foreach ($p [$pack_name] as $ps) {
            $msg .= $ps . ", ";
        }
        return substr($msg, 0, -2);
    }

    /**
     * @api
     *
     * @return string
     */
    public function listPacks(): string
    {
        $p = $this->data2->getAll();
        $array = array_keys($p);
        $msg = "";
        foreach ($array as $pack_names) {
            $msg .= $pack_names . ", ";
        }
        return substr($msg, 0, -2);;
    }

    /**
     * @api
     *
     * @param Player $player
     *
     * @return boolean
     */
    public function changeParticle(Player $player): bool
    {
        $this->clearPlayerParticle($player);
        $this->addPlayerParticle($player, $this->particles->getRandomParticle());
        $this->addPlayerParticle($player, $this->particles->getRandomParticle());
        return true;
    }

    /**
     * @api
     *
     * @param Player $player
     * @param bool $value
     *
     * @return boolean
     */
    public function switchRandomMode(Player $player, bool $value = true): bool
    {
        $this->getServer()->getPluginManager()->callEvent($event = new PlayerSwitchRandommodeEvent ($this, $player, $value));
        if ($event->isCancelled()) {
            return false;
        }
        switch ($value) :
            case true :
                $this->random_mode [$player->getName()] = $player->getName();
                $this->putTemp($player);
                break;
            case false :
                unset ($this->random_mode [$player->getName()]);
                $this->byeTemp($player);
                break;
        endswitch;
        return true;
    }

    /**
     *
     * @param Player $player
     *
     * @return boolean
     */
    public function byeTemp(Player $player): bool
    {
        $temp = $this->data3->getAll();
        if ($this->playerTempExists($player) !== false) {
            $this->clearPlayerParticle($player);
            foreach ($temp [$player->getName()] as $pc) {
                $this->addPlayerParticle($player, $pc);
            }
            unset ($temp [$player->getName()]);
            $this->data3->setAll($temp);
            $this->data3->save();
            return true;
        }
        return false;
    }

    /**
     * @api
     *
     * @param Player $player
     *
     * @return boolean
     */
    public function playerTempExists(Player $player): bool
    {
        $temp = $this->data3->getAll();
        return isset ($temp [$player->getName()]);
    }

    /*
     * RANDOM MODE
     */

    /**
     * @api
     *
     * @param Player $player
     *
     * @return boolean
     */
    public function isRandomMode(Player $player): bool
    {
        return in_array($player->getName(), $this->random_mode);
    }

    /**
     * @api
     *
     * @param Player $player
     *
     * @return boolean
     */
    public function switchItemMode(Player $player, bool $value = true): bool
    {
        $this->getServer()->getPluginManager()->callEvent($event = new PlayerSwitchItemmodeEvent ($this, $player, $value));
        if ($event->isCancelled()) {
            return false;
        }
        if ($value !== false) {
            $this->item_mode [$player->getName()] = $player->getName();
            $this->putTemp($player);
            if ($player->getInventory()->getItemInHand() instanceof ItemBlock) {
                if (( string )$player->getInventory()->getItemInHand()->getId() == "0") {
                    $this->clearPlayerParticle($player);
                    return true;
                } else {
                    $this->setPlayerParticle($player, "block_" . $player->getInventory()->getItemInHand()->getId());
                    return true;
                }
            } else {
                $this->setPlayerParticle($player, "item_" . $player->getInventory()->getItemInHand()->getId());
                return true;
            }
        } else if ($value !== true) {
            unset ($this->item_mode [$player->getName()]);
            $this->byeTemp($player);
            return true;
        }
        return false;
    }

    /**
     * @api
     *
     * @param Player $player
     * @param string $particle
     *
     * @return boolean
     */
    public function setPlayerParticle(Player $player, string $particle): bool
    {
        $this->getServer()->getPluginManager()->callEvent($event = new PlayerSetWPEvent ($this, $player, $particle));
        if ($event->isCancelled()) {
            return false;
        }
        $this->clearPlayerParticle($player);
        $this->addPlayerParticle($player, $particle);
        return true;
    }

    /*
     * Item Mode
     */

    /**
     * @api
     *
     * @param Player $player
     * @return boolean
     */
    public function isItemMode(Player $player): bool
    {
        return in_array($player->getName(), $this->item_mode);
    }

    /**
     * @api
     *
     * @param Entity $entity
     * @param float $amplifier
     * @return boolean
     */
    public function sprayBlood(Entity $entity, float $amplifier): bool
    {
        $amplifier = round($amplifier) / 2; //Amplifier, is actually the rate of blood spray. (The damage of the entity.)
        //Dividing the amplifier by 2 reduces lag and exceed amount of particles.
        if ($entity instanceof Player !== true) {
            return false;
        }
        $particles = new Particles($this);
        for ($i = 0; $i < $amplifier; $i++) {
            $entity->getLevel()->addParticle($particles->getTheParticle($this->getConfig()->get("blood-particle"), new Vector3($entity->x, $entity->y, $entity->z)));
        }
        return true;
    }

    /**
     * @api
     *
     * @param Player $player
     * @return bool
     */
    public function isPlayerBloodDisabled(Player $player): bool
    {
        $b = $this->data4->getAll();
        return (bool)in_array($player->getName(), $b["disabled-players"]);
    }

    /**
     * @api
     *
     * @param string $name Ejector's name to be an identification of it
     * @param Position $pos Ejector position
     * @param array $particles All kinds of particles to be shown (in array form)
     * @param float $amplifier Frequency of particles' appearance
     * @param string $type "normal" / "spiral"
     *
     * @return boolean
     */
    public function setEjector(string $name, Position $pos, array $particles, float $amplifier = 1, string $type = "normal"): bool
    {
        $ed = $this->data5->getAll();
        if (array_key_exists($name, $ed) !== false) {
            unset($ed[$name]);
        }
        $ed[$name]["pos"]["x"] = $pos->x;
        $ed[$name]["pos"]["y"] = $pos->y + 1;
        $ed[$name]["pos"]["z"] = $pos->z;
        $ed[$name]["pos"]["world"] = $pos->getLevel()->getName();
        foreach ($particles as $particle) {
            $ed[$name]["particle"][] = $particle;
        }
        $ed[$name]["amplifier"] = $amplifier;
        //TODO: $ed[$name]["type"] = $type;
        $this->data5->setAll($ed);
        $this->data5->save();
        return true;
    }

    /**
     * @api
     *
     * @param string $name
     *
     * @return boolean
     */
    public function isEjectorExists(string $name): bool
    {
        $ed = $this->data5->getAll();
        return (bool)array_key_exists($name, $ed);
    }

    /**
     * @api
     *
     * @param string $name
     *
     * @return boolean
     */
    public function removeEjector(string $name): bool
    {
        $ed = $this->data5->getAll();
        if (array_key_exists($name, $ed)) {
            unset($ed[$name]);
            $this->data5->setAll($ed);
            $this->data5->save();
            return true;
        }
        return false;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getAllEjectors(): string
    {
        $ed = $this->data5->getAll();
        return implode(", ", array_keys($ed));
    }
}

?>