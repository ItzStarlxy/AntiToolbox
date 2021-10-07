<?php

namespace LittleAraaCute\AntiToolBox\discord;

use LittleAraaCute\AntiToolBox\discord\task\DiscordPost;
use pocketmine\Server;

class DiscordManager{

	public static function postWebhook(string $url, string $content, string $username, array $embed = []): void{
		$data = [
			"username" => $username,
			"content" => $content
		];
		if(!empty($embed)){
			$data["embeds"] = $embed;
			unset($data["content"]);
		}else{
			$msg = $data["content"];
			$msg = str_replace("@everyone", "(@)everyone", $msg);
			$msg = str_replace("@here", "(@)here", $msg);
			$data["content"] = $msg;
		}
		$con = json_encode($data);
		$post = new DiscordPost("https://discordapp.com/api/webhooks/" . $url, $con);
		Server::getInstance()->getAsyncPool()->submitTask($post);
	}
}
