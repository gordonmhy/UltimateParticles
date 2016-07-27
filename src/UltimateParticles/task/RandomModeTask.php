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

use UltimateParticles\UltimateParticles;
use UltimateParticles\base\BaseTask;

class RandomModeTask extends BaseTask{

	public function onRun($tick){
		foreach((array) $this->getPlugin()->random_mode as $rp){
			$p = $this->getPlugin()->getServer()->getPlayer($rp);
			if($p !== null){
				$this->getPlugin()->changeParticle($p);
			}
		}
	}

}
?>