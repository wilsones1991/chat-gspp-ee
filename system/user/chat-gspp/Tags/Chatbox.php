<?php

namespace EricWilson\ChatGSPP\Tags;

use ExpressionEngine\Service\Addon\Controllers\Tag\AbstractRoute;

class Chatbox extends AbstractRoute
{
    
    private $themesPath = URL_THIRD_THEMES . 'chat_gspp/';
    
    // Example tag: {exp:chat_gspp:chatbox}
    public function process()
    {
        
        // // Load manifest.json file
        $manifest = $this->readManifest();

        // Store root entry key
        $rootKey = $manifest['src/main.tsx'];

        // Get css links.
        $cssLinks = '';
        foreach ($rootKey['css'] as $cssFile) {
            $cssLinks .= $this->buildCssTag($cssFile);
        }

        // Recursively follow all chunks in the entry point's imports list and include a <link rel="stylesheet"> tag for each CSS file of each imported chunk.
        $cssLinks .= $this->getCssLinks($rootKey, $manifest);

        // Retrieve A tag for the file key of the entry point chunk (<script type="module"> for JavaScript, or <link rel="stylesheet"> for CSS)
        $entryPoint = $rootKey['file'];
        $jsScript = $this->buildJsTag($entryPoint);

        $reactRoot = '<div id="chat-gspp"></div>';

        return $cssLinks . $jsScript . $reactRoot;
    }

    private function readManifest()
    {
        
        $manifestPath = PATH_THIRD_THEMES . '/chat_gspp/.vite/manifest.json';
        
        try {
            // Check if the manifest.json file exists
            if (!file_exists($manifestPath)) {
                throw new \Exception("Manifest file not found: $manifestPath");
            }
        
            // Load the manifest.json file
            $manifestContent = file_get_contents($manifestPath);
        
            // Check if the file_get_contents call was successful
            if ($manifestContent === false) {
                throw new \Exception("Failed to read manifest file: $manifestPath");
            }
        
            // Decode the JSON content
            $manifest = json_decode($manifestContent, true);
        
            // Check if the JSON decoding was successful
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception("Failed to decode JSON from manifest file: " . json_last_error_msg());
            }
        
            // Use the $manifest object as needed
        } catch (\Exception $e) {
            // Handle the error (e.g., log it, display an error message, etc.)
            echo $e->getMessage();
            // Optionally, you can set $manifest to null or an empty object to handle the error gracefully
            $manifest = null;
        }

        return $manifest;
    }

    private function getCssLinks($rootKey, $manifest) {
        $cssLinks = '';

        // Helper function to recursively process chunks
        $processChunk = function($key) use ($manifest, &$cssLinks, &$processChunk) {
            if (!isset($manifest->$key)) {
                return;
            }

            $chunk = $manifest->$key;

            // Add CSS files for the current chunk
            if (isset($chunk->css)) {
                foreach ($chunk->css as $cssFile) {
                    $cssLinks .= $this->buildCssTag($cssFile);
                }
            }

            // Recursively process imports
            if (isset($chunk->imports)) {
                foreach ($chunk->imports as $importKey) {
                    $processChunk($importKey);
                }
            }
        };

        // Start processing from the root key
        $processChunk($rootKey);

        return $cssLinks;
    }

    private function buildCssTag($cssFile) {
        return '<link rel="stylesheet" href="' . $this->themesPath . $cssFile . '">' . PHP_EOL;
    }

    private function buildJsTag($jsFile) {
        return '<script type="module" src="' . $this->themesPath . $jsFile . '"></script>' . PHP_EOL;
    }
}
