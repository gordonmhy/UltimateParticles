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
use pocketmine\Player;
use pocketmine\level\Position;
use pocketmine\entity\Entity;

interface UltimateParticlesAPI
{

    /**
     * Every time get instance of UltimateParticles first before you access the API part
     * @return UltimateParticles
     */
    public static function getInstance(): UltimateParticles;

    /**
     * @api
     * Try a player's trail by another player. Has time limit.
     *
     * @param Player $player
     * @param Player $player2
     *
     * @return boolean
     */
    public function tryPlayerParticle(Player $player, Player $player2): bool;

    /**
     * @api
     * Use a player's trail by another player. Does not have time limit.
     *
     * @param Player $player
     * @param Player $player2
     *
     * @return boolean
     */
    public function usePlayerParticles(Player $player, Player $player2): bool;

    /**
     * @api
     * Set a player's trail to a particle type
     *
     * @param Player $player
     * @param string $particle
     *
     * @return boolean
     */
    public function setPlayerParticle(Player $player, string $particle): bool;

    /**
     * @api
     * Set a player's trail to an array. (The array has to be particle types)
     *
     * @param Player $player
     * @param array $particles
     *
     * @return bool
     */
    public function setPlayerParticles(Player $player, array $particles): bool;

    /**
     * @api
     * Add a particle type into a player's trail
     *
     * @param Player $player
     * @param string $particle
     *
     * @return boolean
     */
    public function addPlayerParticle(Player $player, string $particle): bool;

    /**
     * @api
     * Add particle types (array) into a player's trail
     *
     * @param Player $player
     * @param array $particles
     *
     * @return bool
     */
    public function addPlayerParticles(Player $player, array $particles): bool;

    /**
     * @api
     * Remove a particle type from a player's trail
     *
     * @param Player $player
     * @param string $particle
     *
     * @return boolean
     */
    public function removePlayerParticle(Player $player, string $particle): bool;

    /**
     * @api
     * Clear the player's trail
     *
     * @param Player $player
     *
     * @return boolean
     */
    public function clearPlayerParticle(Player $player): bool;

    /**
     * @api
     * Get all particle types in a player's trail (in array form)
     *
     * @param Player $player
     *
     * @return string
     */
    public function getAllPlayerParticles(Player $player): string;

    /**
     * @api
     * Check if a player's trail is cleared
     *
     * @param Player $player
     *
     * @return boolean
     */
    public function isCleared(Player $player): bool;

    /**
     * @api
     * Enable particle trail effects for a player.
     *
     * @param Player $player
     * @return boolean
     */
    public function enableEffects(Player $player): bool;

    /**
     * @api
     * Disable particle trail effects for a player.
     *
     * @param Player $player
     * @return boolean
     */
    public function disableEffects(Player $player): bool;

    /**
     * @api
     * Check if a player's trail effects is enabled
     *
     * @param Player $player
     * @return boolean
     */
    public function isEffectsEnabled(Player $player): bool;

    /**
     * @api
     * Set a player's trail amplifier
     *
     * @param Player $player
     * @param int $amplifier
     *
     * @return boolean
     */
    public function setPlayerAmplifier(Player $player, int $amplifier): bool;

    /**
     * @api
     * Get the player's trail amplifier
     *
     * @param Player $player
     *
     * @return integer
     */
    public function getPlayerAmplifier(Player $player);

    /**
     * @api
     * Set a player's trail display format.
     *
     * @param Player $player
     * @param string $display
     *
     * @return boolean
     */
    public function setPlayerDisplay(Player $player, string $display): bool;

    /**
     * @api
     * Get a player's trail display format
     *
     * @param Player $player
     *
     * @return string
     */
    public function getPlayerDisplay(Player $player): string;

    /**
     * @api
     * Gets an array which stores all Vector3 that forms a spiral
     *
     * @param Position $location
     *
     * @return array
     */
    public function getSpiral(Position $location): array;

    /**
     * @api
     * For animated spiral. Get the global order of the spiral particle
     *
     * @return int
     */
    public function getCurrentSpiralOrder(): int;

    /**
     * @api
     * Checks if the server spiral is animated.
     * 
     * @return bool
     */
    public function isSpiralAnimated(): bool;
    
    /*
     * Packs
     * API Part
     */

    /**
     * @api
     * Activate a pack for a player
     *
     * @param Player $player
     * @param string $pack_name
     *
     * @return boolean
     */
    public function activatePack(Player $player, string $pack_name): bool;

    /**
     * @api
     * Create a particle pack
     *
     * @param string $pack_name
     */
    public function createPack(string $pack_name);

    /**
     * @api
     * Add a patcicle type to a particle pack
     *
     * @param string $pack_name
     * @param string $particle
     */
    public function addParticleToPack(string $pack_name, string $particle);

    /**
     * @api
     *
     * @param string $pack_name
     *
     * @return string
     */
    public function getPack(string $pack_name): string;

    /**
     * @api
     * Delete a pack
     *
     * @param string $pack_name
     */
    public function deletePack(string $pack_name);

    /**
     * @api
     * Check if a pack exists
     *
     * @param string $pack_name
     *
     * @return boolean
     */
    public function packExists(string $pack_name): bool;

    /**
     * @api
     * Get the particle types in a particle pack
     *
     * @param string $pack_name
     * @return bool
     */
    public function getPackParticles(string $pack_name): bool;

    /**
     * @api
     * List all available particle packs
     *
     * @return string
     */
    public function listPacks(): string;

    /**
     * @api
     * Toggle a player's random trail mode on/off
     *
     * @param Player $player
     * @param bool $value
     *
     * @return boolean
     */
    public function switchRandomMode(Player $player, bool $value = true): bool;

    /**
     * @api
     * Check if a player's random trail mode is enabled.
     *
     * @param Player $player
     *
     * @return boolean
     */
    public function isRandomMode(Player $player): bool;

    /**
     * @api
     * Toggle a player's item trail mode on/off
     *
     * @param Player $player
     * @param bool $value
     * @return boolean
     */
    public function switchItemMode(Player $player, bool $value = true): bool;

    /**
     * @api
     * Check if a player's item trail mode is enabled.
     *
     * @param Player $player
     * @return boolean
     */
    public function isItemMode(Player $player): bool;

    /**
     * @api
     * Spray blood at the entity's position
     *
     * @param Entity $entity
     * @param float $amplifier
     * @return boolean
     */
    public function sprayBlood(Entity $entity, float $amplifier): bool;

    /**
     * @api
     * Checks if a player's blood effect is disabled.
     *
     * @param Player $player
     * @return bool
     */
    public function isPlayerBloodDisabled(Player $player): bool;

    /**
     * @api
     * Sets a particle ejector at player's position, which ejects particles (For parties :P)
     *
     * @param string $name Ejector's name to be an identification of it
     * @param Position $pos Ejector position
     * @param array $particles All kinds of particles to be shown (in array form)
     * @param float $amplifier Frequency of particles' appearance
     * @param string $type "normal" / "spiral"
     *
     * @return boolean
     */
    public function setEjector(string $name, Position $pos, array $particles, float $amplifier = 1, string $type = "normal"): bool;

    /**
     * @api
     * Checks if an ejector with the name of parameter 1 exists
     *
     * @param string $name
     *
     * @return boolean
     */
    public function isEjectorExists(string $name): bool;

    /**
     * @api
     * Removes the ejector with the name of parameter 1
     *
     * @param string $name
     *
     * @return boolean
     */
    public function removeEjector(string $name): bool;

    /**
     * @api
     * Gets a list of ejectors set by admins, string.
     *
     * @return string
     */
    public function getAllEjectors(): string;
}

?>