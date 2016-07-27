<?php

namespace UltimateParticles\listeners;

use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityTeleportEvent;
use pocketmine\event\entity\EntityMotionEvent;
use UltimateParticles\base\BaseListener;
use pocketmine\Player;
use pocketmine\entity\Egg;
use pocketmine\entity\Arrow;
use pocketmine\entity\Snowball;

class EntityListener extends BaseListener
{

    public function onEntityMotion(EntityMotionEvent $event)
    {
        $cfg = $this->plugin->getConfig();
        if($cfg->get("enable-projectile-tails") !== false){
            if ($event->getEntity() instanceof Egg){
                $event->getEntity()->getLevel()->addParticle($this->plugin->getParticles()->getTheParticle($cfg->get("proj-egg"), $event->getEntity()));
            }
            if ($event->getEntity() instanceof Arrow){
                $event->getEntity()->getLevel()->addParticle($this->plugin->getParticles()->getTheParticle($cfg->get("proj-arrow"), $event->getEntity()));
            }
            if ($event->getEntity() instanceof Snowball){
                $event->getEntity()->getLevel()->addParticle($this->plugin->getParticles()->getTheParticle($cfg->get("proj-snowball"), $event->getEntity()));
            }
        }
    }

    public function onEntityTeleport(EntityTeleportEvent $event)
    {
        if ($event->getEntity() instanceof Player && $this->getPlugin()->getConfig()->get("tp-effects") !== false) {
            foreach ($this->getPlugin()->getSpiral($event->getFrom()) as $pos) {
                $event->getFrom()->getLevel()->addParticle($this->getPlugin()->getParticles()->getTheParticle("portal", $pos));
            }
            foreach ($this->getPlugin()->getSpiral($event->getTo()) as $pos) {
                $event->getTo()->getLevel()->addParticle($this->getPlugin()->getParticles()->getTheParticle("portal", $pos));
            }
        }
    }

    /**
     * @priority MONITOR
     */
    public function onEntityDamage(EntityDamageEvent $event)
    {
        $b = $this->getPlugin()->data4->getAll();
        if ($this->getPlugin()->getConfig()->get("enable-blood") !== false) {
            if ($event->isCancelled() !== true && in_array($event->getEntity()->getName(), $b["disabled-players"]) !== true && $event->getEntity() instanceof Player) {
                $this->getPlugin()->sprayBlood($event->getEntity(), $event->getDamage());
            }
        }
    }

}

?>