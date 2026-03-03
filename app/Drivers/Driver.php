<?php

	namespace Webtimal\Vite\Drivers;

	abstract class Driver
	{
		abstract public function renderAssets(array $entrypoints): void;

		protected function renderStyleTag(string $url): void
		{
			echo "<link rel=\"stylesheet\" href=\"$url\">";
		}

		protected function renderScriptTag(string $url, bool $preload = false): void
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