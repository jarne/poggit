<?php

/*
 * Poggit
 *
 * Copyright (C) 2016 Poggit
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace poggit\utils;

use poggit\module\error\InternalErrorPage;
use poggit\Poggit;

class LangUtils {
    public static function startsWith(string $string, string $prefix): bool {
        return strlen($string) >= strlen($prefix) and substr($string, 0, strlen($prefix)) === $prefix;
    }

    public static function endsWith(string $string, string $suffix): bool {
        return strlen($string) >= strlen($suffix) and substr($string, -strlen($suffix)) === $suffix;
    }

    public static function copyToObject($source, $object) {
        foreach($source as $k => $v) {
            $object->{$k} = $v;
        }
    }

    public static function handleError(\Throwable $ex) {
        http_response_code(500);
        $refid = mt_rand();
        Poggit::getLog()->e("Error#$refid " . $ex->getMessage() . "\n" . (new \Exception)->getTraceAsString());

        if(OutputManager::$plainTextOutput) {
            header("Content-Type: text/plain");
            if(Poggit::isDebug()) {
                OutputManager::$current->outputTree();
            } else {
                OutputManager::terminateAll();
            }
            echo "Error#$refid\n";
        } else {
            OutputManager::terminateAll();
            (new InternalErrorPage((string) $refid))->output();
        }
        die;
    }
}