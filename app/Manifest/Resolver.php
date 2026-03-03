<?php

	namespace Webtimal\Vite\Manifest;

	use RuntimeException;

	readonly class Resolver
	{
		public function __construct(private array $manifest) {}

		public function resolve(string $key, array &$resolved = []): array
		{
			if(!isset($this->manifest[$key]))
			{
				throw new RuntimeException("Invalid entrypoint: $key");
			}

			if(isset($resolved[$key]))
			{
				return [];
			}

			$resolved[$key] = true;

			$root = new Chunk($this->manifest[$key]);

			$chunks = [];

			foreach($root->imports as $import)
			{
				$children = $this->resolve($import, $resolved);

				$chunks = array_merge($chunks, $children);
			}

			return [...$chunks, $root->file => $root];
		}
	}