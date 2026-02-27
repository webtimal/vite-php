<?php

	namespace Webtimal\Vite;

	use Webtimal\Vite\Manifest\Manifest;

	readonly class Vite
	{
		private Manifest $manifest;

		private string $base;

		public function __construct(string $manifest, string $base = '/dist')
		{
			$this->manifest = new Manifest($manifest);

			$this->base = rtrim($base, '/');
		}

		public function __invoke(array $entrypoints): void
		{
			$assets = $this->manifest->get(...$entrypoints);

			$this->printAssets($assets);
		}

		private function printAssets(array $assets): void
		{
			foreach($assets['styles'] as $file)
			{
				$this->printStyle($file);
			}

			foreach($assets['scripts']['preloads'] as $chunk)
			{
				$this->printScript($chunk->file, preload: true);
			}

			foreach($assets['scripts']['entries'] as $chunk)
			{
				$this->printScript($chunk->file);
			}
		}

		private function printStyle(string $file): void
		{
			$url = "$this->base/$file";

			echo "<link rel=\"stylesheet\" href=\"$url\">";
		}

		private function printScript(string $file, bool $preload = false): void
		{
			$url = "$this->base/$file";

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