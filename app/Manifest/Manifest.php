<?php

	namespace Webtimal\Vite\Manifest;

	use RuntimeException;

	readonly class Manifest
	{
		private Resolver $resolver;

		public function __construct(private string $file)
		{
			$manifest = $this->parse();

			$this->resolver = new Resolver($manifest);
		}

		private function parse(): array
		{
			if(!file_exists($this->file))
			{
				throw new RuntimeException("Manifest file not found: $this->file");
			}

			$json = file_get_contents($this->file);

			$manifest = json_decode($json, true);

			if(!$manifest)
			{
				throw new RuntimeException("Invalid JSON in manifest file: $this->file");
			}

			return $manifest;
		}

		public function get(string ...$keys): array
		{
			$chunks = [];

			foreach($keys as $key)
			{
				$chunks = array_merge($chunks, $this->resolver->resolve($key));
			}

			return [
				'styles'  => $this->collectStyles($chunks),
				'scripts' => $this->collectScripts($chunks),
			];
		}

		private function collectStyles(array $chunks): array
		{
			$styles = [];

			foreach($chunks as $chunk)
			{
				foreach($chunk->css as $file)
				{
					$styles[$file] = $file;
				}

				if(str_ends_with($chunk->file, '.css'))
				{
					$styles[$chunk->file] = $chunk->file;
				}
			}

			return array_values($styles);
		}

		private function collectScripts(array $chunks): array
		{
			$preloads = [];
			$entries  = [];

			foreach($chunks as $chunk)
			{
				if(str_ends_with($chunk->file, '.js'))
				{
					$chunk->isEntry
						? $entries[] = $chunk
						: $preloads[] = $chunk;
				}
			}

			return [
				'preloads' => $preloads,
				'entries'  => $entries,
			];
		}
	}