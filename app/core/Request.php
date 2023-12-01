<?php

namespace Mugiwaras\Framework\Core;

class Request
{

    public readonly String $uri;
    public readonly String $method;
    public readonly mixed $body;

    public function __construct()
    {
        $this->uri = $_SERVER['REQUEST_URI'];
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->body = $this->mapRequestContent(file_get_contents('php://input'));
    }

    /**
     * Map the request content to an array.
     *
     * @param  array|string $body request content
     * @return array
     */
    private function mapRequestContent($body)
    {
        $content = [];

        if (is_array($body)) {
            $content = $body;
        } else {
            parse_str($body, $content);
        }

        return $content;
    }
}
