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
	const WAR = 2;
	
	public $signalDB;
	private $touchTime, $status;
	public function onEnable() {
		@mkdir($this->getDataFolder());
		$this->loadDB();
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
	}
	public function onDisable() {
		
	}
	public function onCommand(CommandSender $sender, Command $command, $label, Array $args) {
		if(strtolower($command) == "신호기") {
			switch(strtolower($args[0])) {
				case "추가" :
				 $this->newFlag($sender);
				 break;
			}
		}
		if(strtolower($command) == "왕국") {
			switch(strtolower($args[0])) {
				case "설정" :
				
			}
		}
		return true;
	}
	//========Event Listener========
	public function onBlockInteract(PlayerInteractEvent $event) {
		$x = $event->getTouchVector()->getX();
		$y = $event->getTouchVector()->getY();
		$z = $event->getTouchVector()->getZ();
		
		if($this->isAsk($event->getPlayer())) {
			$this->message($event->getPlayer(), "해당 블럭이 신호기로 설정되었습니다.")
			$this->message($event->getPlayer(), "[/왕국 이름설정] 으로 왕국의 이름을 설정해주세요.")
		}
		
		if( ($x.$y.$z) == $this->signalDB[$event->getPlayer()->getName()]["xyz"]) {
		 	$this->signalDB[$event->getPlayer()->getName()]["break"]--;
		 $event->getPlayer()->sendPopup(TextFormat::GREEN.$signalDB[$event->getPlayer()->getName()]["break"]."번 남았습니다.", "[SignalFlag]");
		 $event->setCancelled();
		 return true;
		}
		if($this->status[$event->getPlayer()->getName()] == self::WAIT) {
			$this->message($event->getPlayer(),"x : ".$x." y : ".$y. " z : ".$z);
			$this->message($event->getPlayer(), "정말 그 블럭을 신호기로 만드시겠습니까?");
			$this->message($event->getPlayer(), "블럭을 한 번 더 터치해 주세요.");
			$this->setAsk($event->getPlayer());
		}
	}
	
	public function onBlockBreak(PlayerBlockBreakEvent $event) {
		
	}
	
	public function onChat(PlayerChatEvent $event) {
		
	}
	public function newSignal(Player $player, Vector3 $vector) {
		$this->setWait($player);
	}
	public function setFlag(Player $player, Vector3 $vector) {
		$this->signalDB[$player->getName()]["flag"] = "$vector->x"."$vector->y"."$vector(z)";
		$this->save()
	}
	public function setWait(Player $player) {
		$this->status[$player->getName()] = self::WAIT;
	}
	public function setAsk(Player $player) {
	 $this->status[$player->getName()] = self::ASK;
	}
	public function isAsk(Player $player) {
		return isset($this->status[$player->getName()]) ? $this->status[$player->getName()] === self::ASK : false;
	}
	
	public function isWait(Player $player) {
		return isset($this->status[$player->getName()]) ? $this->status[$player->getName()] === self::WAIT : false;
	}
	
	public function isWar(Player $player) {
		return isset($this->status[$player->getName()]) ? $this->status[$player->getName()] === self::WAR : false;
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