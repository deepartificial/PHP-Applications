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
  * Spell Checker (or spell check) <https://en.wikipedia.org/wiki/Spell_checker>
  *
  * @author  iSoftware <iSoftware.NGO@gmail.com>
  * @license http://www.gnu.org/licenses/agpl-3.0
  * @link    https://github.com/i-Software/Enigne
  */


class SpellChecker extends Application
{
    protected function Application()
    {
        $Application = array(
        'group' => 'NLP',
        'parameters' => array(array(
         "require" => true,
         "parameter" => "q",
         "name" => "text",
         "pattern" => "/(.*)/",
         "input" => "",
         "type" => "client"
       )),
       "method" => "GET",
       "host" => "search1.aljazeera.net",
       "path" => "/spell.htm",
       "callback" => "SpellChecker"
      );
        foreach ($Application as $Setter => $value) {
            $this->Setter($Setter, $value);
        }
    }
    protected function extrackSuggestions($DATA)
    {
        $XML = str_get_html($DATA);
        $suggestions = $XML->find('suggestion');
        $suggestionsList = array();
        if (count($suggestions) > 0) {
            foreach ($suggestions as $suggestion) {
                $suggestionsList[] = array("hits" => $suggestion->getAttribute('hits'),"suggestion"=>trim($suggestion->xmltext()));
            }
            arsort($suggestionsList);
        }
        return $suggestionsList;
    }
    protected function onSucceed()
    {
        $this->Setter('output', $this->extrackSuggestions($this->getBody()), 'suggestionsList');
    }
    public function SpellChecker()
    {
        $this->request();
        return $this->Getter('output')['suggestionsList'];
    }
}
