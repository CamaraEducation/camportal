<?php
    class Utilities{
        static function quote(){
            $quotes = file_get_contents('assets/data/quotes.json');
            $quotes = json_decode($quotes, true);

            return $quotes[array_rand($quotes)];
        }

        static function search(){
            header("Content-Type: application/json");
            $search = mysqli_real_escape_string(conn(), $_POST['search']);            

            $vid = db()->fetchAll('SELECT * FROM `video` WHERE `title` LIKE ? OR `description` LIKE ? OR `tags` LIKE ? LIMIT 5', "%$search%", "%$search%", "%$search%");
            $doc = db()->fetchAll('SELECT * FROM `document` WHERE `name` LIKE ? OR `description` LIKE ? LIMIT 5',  "%$search%", "%$search%");

            echo json_encode([
                'vid' => $vid,
                'doc' => $doc
            ], JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
        }

        static function fix_json($string){
            // $string = str_replace('\"', '"', $string);
            // $string = str_replace("\'", "'", $string);
            // $string = str_replace('\\\\', '\\', $string);
            // $string = str_replace('\/', '/', $string);
            $string = str_replace('\n', '', $string);
            $string = str_replace('\r', '', $string);
            $string = str_replace('\t', '', $string);
            $string = str_replace('\b', '', $string);
            $string = str_replace('\f', '', $string);
            $string = str_replace('\u', '', $string);

            return $string;
        }
    }