<?php


namespace Discuz\Http;

use FastRoute\DataGenerator;
use FastRoute\RouteParser;

class RouteCollection
{
    /**
     * @var array
     */
    protected $reverse = [];
    /**
     * @var DataGenerator
     */
    protected $dataGenerator;
    /**
     * @var RouteParser
     */
    protected $routeParser;

    protected $currentGroupPrefix;

    public function __construct()
    {
        $this->dataGenerator = new DataGenerator\GroupCountBased;
        $this->routeParser = new RouteParser\Std;

        $this->currentGroupPrefix = '';
    }
    public function get($path, $name, $handler)
    {
        return $this->addRoute('GET', $path, $name, $handler);
    }
    public function post($path, $name, $handler)
    {
        return $this->addRoute('POST', $path, $name, $handler);
    }
    public function put($path, $name, $handler)
    {
        return $this->addRoute('PUT', $path, $name, $handler);
    }
    public function patch($path, $name, $handler)
    {
        return $this->addRoute('PATCH', $path, $name, $handler);
    }
    public function delete($path, $name, $handler)
    {
        return $this->addRoute('DELETE', $path, $name, $handler);
    }

    public function group($prefix, callable $callback)
    {
        $previousGroupPrefix = $this->currentGroupPrefix;
        $this->currentGroupPrefix = $previousGroupPrefix . $prefix;
        $callback($this);
        $this->currentGroupPrefix = $previousGroupPrefix;
    }


    public function addRoute($method, $path, $name, $handler)
    {
        $path = $this->currentGroupPrefix . $path;
        $routeDatas = $this->routeParser->parse($path);
        foreach ($routeDatas as $routeData) {
            $this->dataGenerator->addRoute($method, $routeData, $handler);
        }
        $this->reverse[$name] = $routeDatas;
        return $this;
    }
    public function getRouteData()
    {
        return $this->dataGenerator->getData();
    }
    protected function fixPathPart(&$part, $key, array $parameters)
    {
        if (is_array($part) && array_key_exists($part[0], $parameters)) {
            $part = $parameters[$part[0]];
        }
    }
    public function getPath($name, array $parameters = [])
    {
        if (isset($this->reverse[$name])) {
            $parts = $this->reverse[$name][0];
            array_walk($parts, [$this, 'fixPathPart'], $parameters);
            return '/'.ltrim(implode('', $parts), '/');
        }
        throw new \RuntimeException("Route $name not found");
    }
}
