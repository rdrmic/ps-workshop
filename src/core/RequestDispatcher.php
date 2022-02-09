<?php

    class RequestDispatcher {
        private static $regexPatterns = [
            'number' => '\d+',
            'string' => '\w'
        ];

        private $request;
        private $routeMap;

        public function __construct($request) {
            $this->request = $request;
            
            $url_mappings = file_get_contents(__DIR__ . '/../../config/url_mappings.json');
            $this->routeMap = json_decode($url_mappings, true);
        }
        

        private function getRegexRoute($route, $info) {
            if (isset($info['params'])) {
                foreach ($info['params'] as $name => $type) {
                    $route = str_replace(
                        ':' . $name, self::$regexPatterns[$type], $route
                    );
                }
            }
            return $route;
        }

        private function extractGetParams($route, $path) {
            $params = [];
            $pathParts = explode('/', $path);
            $routeParts = explode('/', $route);
            foreach ($routeParts as $key => $routePart) {
                if (strpos($routePart, ':') === 0) {
                    $name = substr($routePart, 1);
                    $params[$name] = $pathParts[$key + 1];
                }
            }
            return $params;
        }

        private function extractPostParams() {
            $params = [
                array(
                    'POST_params' => $this->request->getPostParams()    // ParamsFilteringMap
                )
            ];
            return $params;
        }

        private function executeController($route, $path, $info) {
            $ctrl_name = $info['controller'] . 'Controller';
            $ctrl_file = './src/domain/controllers/' . $ctrl_name . '.php';
            require_once($ctrl_file);
            $controller = new $ctrl_name();

            $method = $info['method'];

            $params = null;
            if ($this->request->isGet()) {
                $params = $this->extractGetParams($route, $path);
            } else if ($this->request->isPost()) {
                $params = $this->extractPostParams();
                
                // item deletions via POST request
                if ($params[0]['POST_params']->has('delete-id')) {
                    $method = 'deleteItem';
                    $params[0] = $params[0]['POST_params']->getInt('delete-id');
                }
            }

            return call_user_func_array([$controller, $method], $params);
        }

        public function dispatch() {
            $path = '/' . $this->request->getPath();
            if ($path == '/') {
                $path .= Config::getInstance()->get('start-route');
            }

            foreach ($this->routeMap as $route => $info) {
                $regexRoute = $this->getRegexRoute($route, $info);
                if (preg_match("@^/$regexRoute$@", $path)) {
                    return $this->executeController($route, $path, $info);
                }
            }
            
            // FIXME handle this situation and remove echo
            // FIXME error handling
            echo "<p style='color:yellow;background-color:red;padding:7px;font-family:courier;font-weight:bold;'>ERROR:<br />" .
                    "Path '$path' does not have a match in URL list!" .
                "</p><br />";
            
            // TODO ?
            //$errorController = new ErrorController($request);
            //return $errorController->notFound();
        }

    }

?>
