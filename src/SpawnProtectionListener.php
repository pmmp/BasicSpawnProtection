<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 *
 *
*/

declare(strict_types=1);

namespace pmmp\BasicSpawnProtection;

use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\block\SignChangeEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\math\Vector2;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\world\World;

class SpawnProtectionListener implements Listener{

	/** @var int */
	private $radiusSquared;

	public function __construct(int $radius){
		$this->radiusSquared = $radius ** 2;
	}

	private function checkSpawnProtection(World $world, Player $player, Vector3 $vector) : bool{
		if(!$player->hasPermission("basicspawnprotect.bypass")){
			$t = new Vector2($vector->x, $vector->z);

			$spawnLocation = $world->getSpawnLocation();
			$s = new Vector2($spawnLocation->x, $spawnLocation->z);
			if($t->distanceSquared($s) <= $this->radiusSquared){
				return true;
			}
		}

		return false;
	}

	/**
	 * @priority LOWEST
	 *
	 * @param PlayerInteractEvent $event
	 */
	public function onInteract(PlayerInteractEvent $event) : void{
		if($this->checkSpawnProtection($event->getPlayer()->getWorld(), $event->getPlayer(), $event->getBlock()->getPosition())){
			//This prevents opening doors. Perhaps not desired...
			$event->cancel();
		}
	}

	/**
	 * @priority LOWEST
	 *
	 * @param BlockPlaceEvent $event
	 */
	public function onBlockPlace(BlockPlaceEvent $event) : void{
		if($this->checkSpawnProtection($event->getPlayer()->getWorld(), $event->getPlayer(), $event->getBlockReplaced()->getPosition())){
			$event->cancel();
		}
	}

	/**
	 * @priority LOWEST
	 *
	 * @param BlockBreakEvent $event
	 */
	public function onBlockBreak(BlockBreakEvent $event) : void{
		if($this->checkSpawnProtection($event->getPlayer()->getWorld(), $event->getPlayer(), $event->getBlock()->getPosition())){
			$event->cancel();
		}
	}

	/**
	 * @priority LOWEST
	 *
	 * @param SignChangeEvent $event
	 */
	public function onSignChange(SignChangeEvent $event) : void{
		if($this->checkSpawnProtection($event->getPlayer()->getWorld(), $event->getPlayer(), $event->getBlock()->getPosition())){
			$event->cancel();
		}
	}
}
