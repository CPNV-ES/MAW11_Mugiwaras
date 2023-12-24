<?php

namespace Mugiwaras\Framework\Core;

class Request
{

    public readonly String $uri;
    private mixed $body;
    public readonly String $method;
    public readonly mixed $queryStrings;

    public function __construct()
    {
        [$this->uri, $this->queryStrings] = $this->extractQueryStringFromURI($_SERVER['REQUEST_URI']);
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
    
    /**
     * Extract the query string from the uri and returns an array containing the trimmed uri and the query string array.
     *
     * @param  string $uri
     * @return array|null array containing the trimmed uri and the query string array or null if no query string is found
     */
    private function extractQueryStringFromURI($uri){
        $queryString = explode('?', $uri);
        if(count($queryString) > 1){
            parse_str($queryString[1], $queryStrings);
            return [$queryString[0], $queryStrings];
        }
        return [$uri, []];
    }
}
