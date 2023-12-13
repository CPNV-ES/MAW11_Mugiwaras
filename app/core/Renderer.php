<?php

namespace Mugiwaras\Framework\Core;

use Exception;

class Renderer
{

    private string $viewDirectoryPath;

    private string $viewFileExtension = "php";

    private string $layoutPath;

    private string $currentSectionName = "";

    private array $Sections = [];


    public function __construct(string $viewDirectoryPath)
    {
        if (!is_dir($viewDirectoryPath)) {
            throw new Exception("The given view directory path is not a valid directory: $viewDirectoryPath");
        }

        $this->viewDirectoryPath = $viewDirectoryPath;
    }

    public function Section(string $name, string $content): void
    {

        if (array_key_exists($name, $this->Sections)) {
            return;
        }

        $this->Sections[$name] = $content;
    }

    public function startSection(string $name): void
    {
        $this->currentSectionName = $name;

        ob_start();
    }

    public function endSection(): void
    {
        $this->Section($this->currentSectionName, ob_get_clean());
        unset($this->currentSectionName);
    }

    public function renderSection(string $name)
    {
        return $this->Sections[$name];
    }

    public function render(string $viewName, array $params = [])
    {
        $viewPath = $this->viewDirectoryPath . DIRECTORY_SEPARATOR . $viewName . "." . $this->viewFileExtension;

        if (!file_exists($viewPath)) {
            throw new Exception("The given view file does not exist: $viewPath");
        }

        unset($this->layoutPath);

        ob_start();

        extract($params);
        
        require $viewPath;
        
        $content = ob_get_clean();
        
        if (empty($this->layoutPath)) {
            echo $content;
            return;
        }
        
        $this->Sections["content"] = $content;

        return $this->render($this->layoutPath);
    }

    public function layout(string $layout): void
    {
        $this->layoutPath = $layout;
    }
}
