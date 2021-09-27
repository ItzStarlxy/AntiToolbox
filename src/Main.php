<?php
declare(strict_types = 1);

namespace LittleAraaCute;

use pocketmine\{Server, Player};
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\network\mcpe\protocol\LoginPacket;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\utils\Config;
use LittleAraaCute\DiscordManager;

class Main extends PluginBase implements Listener {
	
	public Config $config;
	
	public function onEnable(){
		$this->getLogger()->info("Plugin Enable");
		$this->getLogger()->info("Plugin by LittleAraaCute");
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		@mkdir($this->getDataFolder());
        $this->saveResource("config.yml");
        $this->getConfig = new Config($this->getDataFolder() . "config.yml", Config::YAML);
	$this->webhook = $this->getConfig()->get("webhook");
	DiscordManager::postWebhook($this->webhook);
	}
	
	public function onRecieve (DataPacketReceiveEvent $event)
    {
        $player = $event->getPlayer();
        $packet = $event->getPacket();

        if ($packet instanceof LoginPacket)
        {
            $deviceOS = (int)$packet->clientData["DeviceOS"];
            $deviceModel = (string)$packet->clientData["DeviceModel"];

            if ($deviceOS !== 1) //AndroidOS
            {
                return;
            }

            $name = explode(" ", $deviceModel);
            if (!isset($name[0]))
            {
                return;
            }
            $check = $name[0];
            $check = strtoupper($check);
            if ($check !== $name[0])
            {
                $player->kick($this->getConfig->get("kick-message"));
            }
        }
    }
}
