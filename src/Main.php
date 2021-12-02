<?php

declare(strict_types=1);

namespace pmmp\BasicSpawnProtection;

use pocketmine\plugin\PluginBase;
use pocketmine\plugin\PluginException;
use function count;
use function is_int;

class Main extends PluginBase{

	public function onEnable() : void{
		$config = $this->getConfig()->getAll();
		if(count($config) !== 1 || !isset($config['radius'])){
			throw new PluginException("Invalid configuration file: Must only contain 'radius'");
		}
		if(!is_int($config['radius']) || $config['radius'] <= 0){
			throw new PluginException("Invalid configuration file: Radius must be a number bigger than 0");
		}

		$this->getServer()->getPluginManager()->registerEvents(
			new SpawnProtectionListener($config['radius']),
			$this
		);
	}
}
