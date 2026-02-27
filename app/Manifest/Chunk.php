<?php

	namespace Webtimal\Vite\Manifest;

	readonly class Chunk
	{
		public string $file;

		public array $imports;

		public array $css;

		public bool $isEntry;

		public function __construct(array $data)
		{
			$this->file = $data['file'];

			$this->imports = $data['imports'] ?? [];

			$this->css = $data['css'] ?? [];

			$this->isEntry = $data['isEntry'] ?? false;
		}
	}