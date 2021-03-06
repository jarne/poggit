<?php

/*
 * Poggit
 *
 * Copyright (C) 2016-2017 Poggit
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

namespace poggit\ci\lint;

use poggit\utils\lang\Lang;

abstract class V2BuildStatus implements \JsonSerializable {
    /** @var string|null */
    public $name;
    /** @var int */
    public $level;

    public abstract function echoHtml();

    public function jsonSerialize() {
        if(!isset($this->level)) return $this;
        $clone = clone $this;
        unset($clone->level);
        return $clone;
    }

    public static function unserializeNew(\stdClass $data, string $class, int $level): V2BuildStatus {
        $class = __NAMESPACE__ . "\\" . $class;
        /** @var V2BuildStatus $object */
        $object = new $class;
        $object->level = $level;
        Lang::copyToObject($data, $object);
        return $object;
    }
}
