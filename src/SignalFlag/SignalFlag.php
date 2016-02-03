<?php
namespace Mohi\SignalFlag;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\utils\TextFormat;
use pocketmine\plugin\PluginCommand;

class SignalFlag extends PluginBase implements Listener {
	const WAIT = 0;
	const ASK = 1;
	
	public $signalDB;
	private $touchTime, $status;
	public function onEnable() {
		
	}
	
	public function onCommand(CommandSender $sender, Command $command, $label, Array $args) {
		if(strtolower($command) = "signal") {
			
		}
	}
	//========Event Listener========
	public function onBlockInteract(PlayerInteractEvent $event) {
		$x = $event->getTouchVector()->getX();
		$y = $event->getTouchVector()->getY();
		$z = $event->getTouchVector()->getZ();
		if( ($x.$y.$z) == $this->signalDB[$event->getPlayer()->getName()]["xyz"]) {
		 	$this->signalDB[$event->getPlayer()->getName()]["break"]--;
		 $event->getPlayer()->sendPopup(TextFormat::GREEN.$signalDB[$event->getPlayer()->getName()]["break"]."번 남았습니다.", "[SignalFlag]");
		 $event->setCancelled();
		 return true;
		}
		if($this->status[$event->getPlayer()->getName()] == self::WAIT) {
			$this->message($event->getPlayer(),"x : ".$x." y : ".$y. " z : ".$z);
			$this->message($event->getPlayer(), "정말 그 블럭을 신호기로 만드시겠습니까?");
			$this->status[$event->getPlayer()->getName()] = self::ASK;
		}
	}
	
	public function onBlockBreak(PlayerBlockBreakEvent $event) {
		
	}
	
	public function alert(CommandSender $sender, $message, $prefix = "[SignalFlag]"){
		$sender->sendMessage(TextFormat::RED.$prefix." $message");
	}
	
	public function message(CommandSender $sender, $message, $prefix = "[SignalFlag]"){
		$sender->sendMessage(TextFormat::DARK_AQUA.$prefix." $message");
	}
	
	public function registerCommand($name, $fallback, $permission, $description = "", $usage = "") {
		$commandMap = $this->getServer ()->getCommandMap ();	
	 	$command = new PluginCommand ( $name, $this );
	 	$command->setDescription ( $description );	
	 	$command->setPermission ( $permission );
	 	$command->setUsage ( $usage );
	 	$commandMap->register ( $fallback, $command );
	}
	
	public function loadDB() {
	$this->signalDB = (new Config($this->getDataFolder()."signalDB.json", Config::JSON))->getAll();
	}
	
	public function save($db, $param, $async = false) {
		$dbsave = (new Config ($this->getDataFolder().$db, Config::JSON));
		$dbsave->setAll($param);
		$dbsave->save($async);
	}
}