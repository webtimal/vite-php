<?php

	namespace Webtimal\Vite;

	use Webtimal\Vite\Manifest\Manifest;

	class Vite
	{
		private Manifest $manifest;

		private string $base;

		private ?string $server = null;

		public function __construct(string $manifest, string $base = '/dist', ?string $hotfile = null)
		{
			$this->base = rtrim($base, '/');

			if($hotfile)
			{
				$this->server = $this->resolveServerUrl($hotfile);
			}
			else
			{
				$this->manifest = new Manifest($manifest);
			}
		}

		public function __invoke(array $entrypoints): void
		{
			if($this->server)
			{
				$this->renderHotAssets($entrypoints);
			}
			else
			{
				$this->renderManifestAssets($entrypoints);
			}
		}

		private function resolveServerUrl(string $hotfile): ?string
		{
			$server = file_get_contents($hotfile);

			return rtrim($server, '/');
		}

		private function renderHotAssets(array $entrypoints): void
		{
			$this->renderScriptTag("$this->server/@vite/client");

			foreach($entrypoints as $i => $file)
			{
				if(pathinfo($file, PATHINFO_EXTENSION) === 'css')
				{
					$this->renderStyleTag("$this->server/$file");
				}
			}

			foreach($entrypoints as $i => $file)
			{
				if(pathinfo($file, PATHINFO_EXTENSION) !== 'css')
				{
					$this->renderScriptTag("$this->server/$file");
				}
			}
		}

		private function renderManifestAssets(array $entrypoints): void
		{
			$assets = $this->manifest->get(...$entrypoints);

			foreach($assets['styles'] as $file)
			{
				$this->renderStyleTag("$this->base/$file");
			}

			foreach($assets['scripts']['preloads'] as $chunk)
			{
				$this->renderScriptTag("$this->base/$chunk->file", preload: true);
			}

			foreach($assets['scripts']['entries'] as $chunk)
			{
				$this->renderScriptTag("$this->base/$chunk->file");
			}
		}

		private function renderStyleTag(string $url): void
		{
			echo "<link rel=\"stylesheet\" href=\"$url\">";
		}

		private function renderScriptTag(string $url, bool $preload = false): void
		{
			if($preload)
			{
				echo "<link rel=\"modulepreload\" href=\"$url\">";
			}
			else
			{
				echo "<script type=\"module\" src=\"$url\"></script>";
			}
		}
	}