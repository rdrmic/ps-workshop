<?php

    class Template {
        private $file;

        public function __construct($file) {
            $this->file = $file;
        }

        public function render($params = null) {
            if (!file_exists($this->file)) {
                // TODO error-handling
                // TODO could file_exists() check be performed earlier?
                return "<p style='color:yellow;background-color:red;padding:7px;font-family:courier;font-weight:bold;'>ERROR:<br />Error loading template file ($this->file).</p><br />";
            }

            $template = file_get_contents($this->file);
            //echo '----------> ' . $this->file . '<br />';

            $placeholders = [];
            $found = preg_match_all("/\[\@.+?\]/", $template, $placeholders);
            //echo "found: $found<br />";

            // no placeholders in template
            if ($found == 0) {
                return $template;
            }
            
            //echo "PLACEHOLDERS:<br /";
            //print_r($placeholders);
            /*foreach ($placeholders[0] as $ph) {
                echo "&nbsp;&nbsp;&nbsp; $ph<br />";
            }
            echo "<br />";*/

            // replace placeholders with given parameters
            if ($params != null) {
                foreach ($params as $key => $value) {
                    $params_key = "[@$key]";
                    $template = str_replace($params_key, $value, $template);
                    $index = array_search($params_key, $placeholders[0]);
                    if ($index !== false) {
                        unset($placeholders[0][$index]);
                    }
                }
            }
            // delete non-replaced placeholders
            foreach ($placeholders[0] as $ph) {
                $template = str_replace("$ph", '', $template);
            }
            return $template;
        }

    }

?>
