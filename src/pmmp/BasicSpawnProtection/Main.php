<?php

declare(strict_types=1);

namespace pmmp\BasicSpawnProtection;

use Particle\Validator\Validator;
use pocketmine\plugin\PluginBase;
use function implode;
use const PHP_INT_MAX;

class Main extends PluginBase{

	public function onEnable() : void{
		$v = new Validator();
		$v->required('radius')->integer()->between(0, PHP_INT_MAX);

		$result = $v->validate($this->getConfig()->getAll());
		if($result->isNotValid()){
			$messages = [];
			foreach($result->getFailures() as $f){
				$messages[] = $f->format();
			}
			$this->getLogger()->alert('Invalid config file: ' . implode(' | ', $messages));
			$this->getServer()->getPluginManager()->disablePlugin($this);
			return;
		}

		$this->getServer()->getPluginManager()->registerEvents(
			new SpawnProtectionListener((int) $this->getConfig()->get('radius')),
			$this
		);
	}
}
