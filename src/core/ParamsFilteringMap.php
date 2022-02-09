<?php

    class ParamsFilteringMap {
        private $map;

        public function __construct($baseMap) {
            $this->map = $baseMap;
        }

        public function __toString() {
            $html = '';
            if (!empty($this->map)) {
                $html .= '<hr />';
                $html .= '<p>REQUEST PARAMS:</p>';
                $html .= '<ul>';
                foreach ($this->map as $key => $value) {
                    $html .= "<li>$key: $value</li>";
                }
                $html .= '</ul>';
                $html .= '<hr />';
            }
            return $html;
        }


        public function has($name) {
            return isset($this->map[$name]);
        }

        public function get($name) {
            return $this->has($name) ? $this->map[$name] : null;
        }

        public function set($name, $value) {
            $this->map[$name] = $value;
        }


        public function getString($name, $filter = true) {
            //$inputed = $this->get($name);
            //__show("inputed:");
            //__show($inputed);
            
            //$converted = mb_convert_encoding($inputed, 'UTF-8', 'ISO-8859-2');
            //__show("converted:");
            //__show($converted);
            
            // $converted = (string) $converted;
            
            $inputed = $this->get($name);
            $converted = (string) $inputed;
            
            $converted = $filter ? addslashes($inputed) : $inputed;
            return $converted;
        }

        public function getInt($name) {
            $inputed = $this->get($name);
            
            $converted = (int) $inputed;
            return $converted;
        }

        public function getFloat($name) {
            $inputed = $this->get($name);
            
            if ($inputed == null) {
                $converted = $inputed;
            } else {
                $converted = str_replace([','], ['.'], $inputed);
            }
            return (float) $converted;
        }
        
        public function getFloatFromCurrency($name) {
            $inputed = $this->get($name);
            
            if ($inputed == null) {
                $converted = $inputed;
            } else {
                $converted = str_replace(['.', ','], ['', '.'], $inputed);
            }
            return (float) $converted;
        }

    }

?>
