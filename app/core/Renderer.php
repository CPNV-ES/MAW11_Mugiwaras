<?php

namespace Mugiwaras\Framework\Core;

use Exception;

class Renderer
{

    private string $viewDirectoryPath;

    private string $viewFileExtension = "php";

    private string $layoutPath;

    private string $currentSectionName = "";

    private array $sections = [];


    /**
     * @param  string $viewDirectoryPath The path to the directory where the views are stored
     * @return void
     */
    public function __construct(string $viewDirectoryPath)
    {
        if (!is_dir($viewDirectoryPath)) {
            throw new Exception("The given view directory path is not a valid directory: $viewDirectoryPath");
        }

        $this->viewDirectoryPath = $viewDirectoryPath;
    }

    /**
     * Create a section that stores the given content under the given name
     *
     * @param  string $name
     * @param  mixed $content
     * @return void
     */
    public function section(string $name, mixed $content): void
    {

        if (array_key_exists($name, $this->sections)) {
            return;
        }

        $this->sections[$name] = $content;
    }

    /**
     * Start a new section with the given name
     *
     * @param  string $name
     * @return void
     */
    public function startSection(string $name): void
    {
        $this->currentSectionName = $name;

        ob_start();
    }

    /**
     * End the current section
     *
     * @return void
     */
    public function endSection(): void
    {
        $this->section($this->currentSectionName, ob_get_clean());
        unset($this->currentSectionName);
    }
    
    /**
     * Render the content of the section with the given name
     *
     * @param  mixed $name
     * @return void
     */
    public function renderSection(string $name)
    {
        return $this->sections[$name];
    }

    /**
     * Render the given view with the given parameters. If a layout is set, the view will be rendered inside the layout
     *
     * @param  string $viewName
     * @param  array $params
     * @return void
     */
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

        $this->sections["content"] = $content;

        return $this->render($this->layoutPath);
    }

    /**
     * Set the layout that should be used to render the view
     *
     * @param  string $layout
     * @return void
     */
    public function layout(string $layout): void
    {
        $this->layoutPath = $layout;
    }
    
    /**
     * PHP magic method to get the content of the section with the given name
     *
     * @param  mixed $name
     * @return void
     */
    public function __get($name)
    {
        return $this->Sections[$name];
    }
}
