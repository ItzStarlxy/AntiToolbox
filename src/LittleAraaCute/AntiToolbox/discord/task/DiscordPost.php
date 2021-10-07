<?php

namespace YaN\AntiToolBox\discord\task;

use pocketmine\scheduler\AsyncTask;

class DiscordPost extends AsyncTask
{

	private $url;
	private $content;
	private $player;

	public function __construct(string $url, string $content)
	{
		$this->url = $url;
		$this->content = $content;
	}

	public function onRun(): void
	{
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $this->url);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $this->content);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		$response = curl_exec($curl);
		$curlerror = curl_error($curl);

		$responsejson = json_decode($response, true);

		if ($curlerror != '') {
			$error = $curlerror;
		} else if (curl_getinfo($curl, CURLINFO_HTTP_CODE) != 204) {
			$response = '';
		}

		$this->setResult($response);
	}
}
