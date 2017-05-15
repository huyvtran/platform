<?php
/**
 *  Fluent-Logger-PHP
 *
 *  Copyright (C) 2011 - 2012 Fluent-Logger-PHP Contributors
 *
 *     Licensed under the Apache License, Version 2.0 (the "License");
 *     you may not use this file except in compliance with the License.
 *     You may obtain a copy of the License at
 *
 *         http://www.apache.org/licenses/LICENSE-2.0
 *
 *     Unless required by applicable law or agreed to in writing, software
 *     distributed under the License is distributed on an "AS IS" BASIS,
 *     WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *     See the License for the specific language governing permissions and
 *     limitations under the License.
 */
namespace Fluent\Logger;

class JsonPacker implements PackerInterface
{
    public function __construct()
    {
    }

    /**
     * pack entity as a json string.
     *
     * @param Entity $entity
     * @return string
     */
    public function pack(Entity $entity)
    {
        $encodeThis = array($entity->getTag(), $entity->getTime(), $entity->getData());
        $pack = @json_encode($encodeThis);
        if (empty($pack)) {
            $pack = json_encode($this->__utf8_encode_all($encodeThis));
            if (empty($pack)) {
                $pack = $this->__json_encode($encodeThis);
                if (empty($pack)) {
                    CakeLog::info("cant not pack this JSON, UTF8 bugs");
                }
            }
        }
        return $pack;
    }

    public function __utf8_encode_all($dat) // -- It returns $dat encoded to UTF8 
    { 
        if (is_string($dat))
            return utf8_encode($dat); 
        if (!is_array($dat))
            return $dat; 

        $ret = array(); 
        foreach($dat as $i => $d)
            $ret[$i] = utf8_encode_all($d); 
        return $ret; 
    } 

    function __json_encode($data)
    {            
        if( is_array($data) || is_object($data) ) { 
            $islist = is_array($data) && ( empty($data) || array_keys($data) === range(0,count($data)-1) ); 
            
            if( $islist ) { 
                $json = '[' . implode(',', array_map('__json_encode', $data) ) . ']'; 
            } else { 
                $items = Array(); 
                foreach( $data as $key => $value ) { 
                    $items[] = __json_encode("$key") . ':' . __json_encode($value); 
                } 
                $json = '{' . implode(',', $items) . '}'; 
            } 
        } elseif( is_string($data) ) { 
            # Escape non-printable or Non-ASCII characters. 
            # I also put the \\ character first, as suggested in comments on the 'addclashes' page. 
            $string = '"' . addcslashes($data, "\\\"\n\r\t/" . chr(8) . chr(12)) . '"'; 
            $json    = ''; 
            $len    = strlen($string); 
            # Convert UTF-8 to Hexadecimal Codepoints. 
            for( $i = 0; $i < $len; $i++ ) { 
                
                $char = $string[$i]; 
                $c1 = ord($char); 
                
                # Single byte; 
                if( $c1 <128 ) { 
                    $json .= ($c1 > 31) ? $char : sprintf("\\u%04x", $c1); 
                    continue; 
                } 
                
                # Double byte 
                $c2 = ord($string[++$i]); 
                if ( ($c1 & 32) === 0 ) { 
                    $json .= sprintf("\\u%04x", ($c1 - 192) * 64 + $c2 - 128); 
                    continue; 
                } 
                
                # Triple 
                $c3 = ord($string[++$i]); 
                if( ($c1 & 16) === 0 ) { 
                    $json .= sprintf("\\u%04x", (($c1 - 224) <<12) + (($c2 - 128) << 6) + ($c3 - 128)); 
                    continue; 
                } 
                    
                # Quadruple 
                $c4 = ord($string[++$i]); 
                if( ($c1 & 8 ) === 0 ) { 
                    $u = (($c1 & 15) << 2) + (($c2>>4) & 3) - 1; 
                
                    $w1 = (54<<10) + ($u<<6) + (($c2 & 15) << 2) + (($c3>>4) & 3); 
                    $w2 = (55<<10) + (($c3 & 15)<<6) + ($c4-128); 
                    $json .= sprintf("\\u%04x\\u%04x", $w1, $w2); 
                } 
            } 
        } else { 
            # int, floats, bools, null 
            $json = strtolower(var_export( $data, true )); 
        } 
        return $json; 
    }
}