<?php

	namespace Webtimal\Vite;

	use Webtimal\Vite\Drivers\Driver;
	use Webtimal\Vite\Drivers\HotDriver;
	use Webtimal\Vite\Drivers\ManifestDriver;

	class Vite
	{
		private Driver $driver;

		public function __construct(string $manifest, string $base = '/dist', ?string $hotfile = null)
		{
			if($hotfile && file_exists($hotfile))
			{
				$this->driver = new HotDriver($hotfile);
			}
			else
			{
				$this->driver = new ManifestDriver($manifest, $base);
			}
		}

		public function __invoke(array $entrypoints): void
		{
			$this->driver->renderAssets($entrypoints);
		}
	}