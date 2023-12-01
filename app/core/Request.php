<?php

namespace Mugiwaras\Framework\Core;

class Request
{

    public readonly String $uri;
    private mixed $body;
    public readonly String $method;

    public function __construct()
    {
        $this->uri = $_SERVER['REQUEST_URI'];
        $this->body = $this->mapRequestContent(file_get_contents('php://input'));
        $this->method = $this->setRequestMethod();
    }

    public function body(){
        return $this->body;
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
    
    /**
     * Returns the request method.
     *
     * @return string
     */
    private function setRequestMethod()
    {
        $method = $this->getCustomRequestMethod($this->body);

        if ($method) {
            return $method;
        } else {
            return $_SERVER['REQUEST_METHOD'];
        }
    }
        
    /**
     * Returns the request method PUT, DELETE or any other based on the body attribute "_method" or returns null if no "_method" is found.
     *
     * @param  mixed $body
     * @return string|null
     */
    private function getCustomRequestMethod($body)
    {
        if (isset($body['_method'])) {
            $method = strtoupper($body['_method']);
            unset($this->body['_method']);
            return $method;
        }
        return null;
    }
}
