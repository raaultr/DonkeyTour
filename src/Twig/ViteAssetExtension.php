<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ViteAssetExtension extends AbstractExtension
{
    private ?array $manifest = null;
    private string $manifestPath;
    private string $buildPath;

    public function __construct(string $projectDir)
    {
        $this->manifestPath = $projectDir . '/public/build/.vite/manifest.json';
        $this->buildPath = '/build/';
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('vite_asset', [$this, 'getViteAsset']),
        ];
    }

    public function getViteAsset(string $entry): string
    {
        $manifest = $this->loadManifest();

        if (!isset($manifest[$entry])) {
            // Fallback: devuelve la ruta tal cual
            return $this->buildPath . $entry;
        }

        return $this->buildPath . $manifest[$entry]['file'];
    }

    private function loadManifest(): array
    {
        if ($this->manifest !== null) {
            return $this->manifest;
        }

        if (!file_exists($this->manifestPath)) {
            $this->manifest = [];
            return $this->manifest;
        }

        $this->manifest = json_decode(file_get_contents($this->manifestPath), true) ?? [];
        return $this->manifest;
    }
}
