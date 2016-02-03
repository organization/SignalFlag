<?php
namespace Mohi\SignalFlag;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\ 

class SignalFlag extends PluginBase implements Listener {
	public $signalDB;
	
	public function onEnable() {
		
	}
	
	public function onBlockPlace(BlockPlaceEvent $event) {
		if($event->getBlock() == Item::SPONGE) {
			
	}
	public function onBlockInteract(PlayerInteractEvent $event) {
		 if($event->getBlock() == Item::SPONGE && $event->getTouchVector == $this->signalDB["Castle"]["xyz"]) {
		 	$this->signalDB["Castle"]["distroyed"]--;
		}
	}
	public function onBlockBreak(PlayerBlockBreakEvent $event) {
		
	}
	public function loadDB() {
	$this->config = (new Config($this->getDataFolder()."signalDB.json", Config::JSON))->getAll();
	}
	
	public function save($db, $param, $async = false) {
		$dbsave = (new Config ($this->getDataFolder().$db, Config::JSON));
		$dbsave->setAll($param);
		$dbsave->save($async);
	}
}