<?php

	namespace Webtimal\Vite\Drivers;

	use Webtimal\Vite\Manifest\Manifest;

	class ManifestDriver extends Driver
	{
		private Manifest $manifest;

		public function __construct(string $manifest, protected string $base)
		{
			$this->manifest = new Manifest($manifest);
		}

		public function renderAssets(array $entrypoints): void
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
	}