<?php

	namespace Webtimal\Vite\Drivers;

	class HotDriver extends Driver
	{
		private string $server;

		public function __construct(protected string $hotfile)
		{
			$this->server = $this->readServerUrl();
		}

		private function readServerUrl(): string
		{
			$server = file_get_contents($this->hotfile);

			return trim($server);
		}

		public function renderAssets(array $entrypoints): void
		{
			$this->renderScriptTag("$this->server/@vite/client");

			foreach($entrypoints as $file)
			{
				if(pathinfo($file, PATHINFO_EXTENSION) === 'css')
				{
					$this->renderStyleTag("$this->server/$file");
				}
			}

			foreach($entrypoints as $file)
			{
				if(pathinfo($file, PATHINFO_EXTENSION) !== 'css')
				{
					$this->renderScriptTag("$this->server/$file");
				}
			}
		}
	}