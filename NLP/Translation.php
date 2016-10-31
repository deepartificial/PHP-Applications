<?php
/*
 * intelligent-Software
 * Take artificial intelligence with you everywhere.
 *
 * Copyright (C) iSoftware
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

 namespace iSoftware\Application\NLP;

use iSoftware\Engine\Application as Application;
use iSoftware\Engine\Exception as Exception;

/**
  * Translation
  *
  * @sponsor Google Translate <https://en.wikipedia.org/wiki/Google_Translate>
  * @author  iSoftware <iSoftware.NGO@gmail.com>
  * @license http://www.gnu.org/licenses/agpl-3.0
  * @link    https://github.com/i-Software/Enigne
  */


class Translation extends Application
{
    protected function Application()
    {
        $Application = array(
        'group' => 'NLP',
        'parameters' => array(
          array(
         "require" => false,
         "parameter" => "sl",
         "name" => "sourceLang",
         "pattern" => "/(.*)/",
         "input" => "auto",
         "type" => "request"
       ),array(
        "require" => true,
        "parameter" => "tl",
        "name" => "tl",
        "pattern" => "/(.*)/",
        "input" => "",
        "type" => "client"
      ),array(
       "require" => false,
       "parameter" => "dt",
       "name" => "dt",
       "pattern" => "/(.*)/",
       "input" => "t",
       "type" => "request"
     ),array(
      "require" => true,
      "parameter" => "q",
      "name" => "q",
      "pattern" => "/(.*)/",
      "input" => "",
      "type" => "client"
    ),array(
     "require" => false,
     "parameter" => "client",
     "name" => "client",
     "pattern" => "/(.*)/",
     "input" => "gtx",
     "type" => "request"
   ),array(
    "require" => false,
    "parameter" => "ie",
    "name" => "ie",
    "pattern" => "/(.*)/",
    "input" => "UTF-8",
    "type" => "request"
  ),array(
   "require" => false,
   "parameter" => "oe",
   "name" => "ie",
   "pattern" => "/(.*)/",
   "input" => "UTF-8",
   "type" => "request"
 )),
       "method" => "GET",
       "host" => "translate.googleapis.com",
       "path" => "/translate_a/single",
       "callback" => "Translation"
      );
        foreach ($Application as $Setter => $value) {
            $this->Setter($Setter, $value);
        }
    }
    protected function extrackTranslation($DATA)
    {
        $translation = array();
        preg_match('/"(.*)",/i', $DATA, $result);
        if ($result) {
            $c = explode(",", $result[1]);
            if (count($c) > 1) {
                $translation['sourceText'] = str_replace('"', "", $c[1]);
                $translation['translatedText'] = str_replace('"', '', $c[0]);
            }
        }
        return $translation;
    }
    protected function onSucceed()
    {
        $this->Setter('output', $this->extrackTranslation($this->getBody()), 'translation');
    }
    public function Translation()
    {
        $this->request();
        return $this->Getter('output')['translation'];
    }

}
