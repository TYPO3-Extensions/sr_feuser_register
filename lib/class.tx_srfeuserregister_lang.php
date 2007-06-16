<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2007-2007 Stanislas Rolland <stanislas.rolland(arobas)fructifor.ca)>
*  All rights reserved
*
*  This script is part of the Typo3 project. The Typo3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*  A copy is found in the textfile GPL.txt and important notices to the license
*  from the author is found in LICENSE.txt distributed with these scripts.
*
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/
/**
 * Part of the sr_feuser_register (Frontend User Registration) extension.
 *
 * language functions
 *
 * $Id$
 *
 * @author Stanislas Rolland <stanislas.rolland(arobas)fructifor.ca>
 * @author Franz Holzinger <kontakt@fholzinger.com>
 *
 * @package TYPO3
 * @subpackage sr_feuser_register
 *
 *
 */



class tx_srfeuserregister_lang {
	var $pibase;
	var $conf = array();
	var $allowedSuffixes = array('formal', 'informal'); // list of allowed suffixes


	function init(&$pibase, &$conf)	{
		$this->pibase = &$pibase;
		$this->conf = &$conf;
	}


	function getLLFromString($string, $bForce=true) {
		global $LOCAL_LANG, $TSFE;
		
		$rc = '';
		$arr = explode(':',$string);
		if($arr[0] == 'LLL' && $arr[1] == 'EXT') {
			$temp = $this->pi_getLL($arr[3]);
			if ($temp || !$bForce) {
				return $temp;
			} else {
				return $TSFE->sL($string);
			}
		} else {
			$rc = $string;
		}
		
		return $rc;
	}	// getLLFromString


	/**
	* Get the item array for a select if configured via TypoScript
	* @param	string	name of the field
	* @ return	array	array of selectable items
	*/
	function getItemsLL($textSchema, $bAll = true, $valuesArray = array()) {
		$rc = array();
		if ($bAll)	{
			for ($i = 0; $i < 50; ++$i)	{
				$text = $this->pi_getLL($textSchema.$i);
				if ($text != '')	{
					$rc[] = array($text, $i);
				}
			}
		} else {
			foreach ($valuesArray as $k => $i)	{
				$text = $this->pi_getLL($textSchema.$i);
				if ($text != '')	{
					$rc[] = array($text, $i);
				}
			}
		}
		return $rc;
	}	// getItemsLL


	/**
		* Returns the localized label of the LOCAL_LANG key, $key
		* In $this->conf['salutation'], a suffix to the key may be set (which may be either 'formal' or 'informal').
		* If a corresponding key exists, the formal/informal localized string is used instead.
		* If the key doesn't exist, we just use the normal string.
		*
		* Example: key = 'greeting', suffix = 'informal'. If the key 'greeting_informal' exists, that string is used.
		* If it doesn't exist, we'll try to use the string with the key 'greeting'.
		*
		* Notice that for debugging purposes prefixes for the output values can be set with the internal vars ->LLtestPrefixAlt and ->LLtestPrefix
		*
		* @param    string        The key from the LOCAL_LANG array for which to return the value.
		* @param    string        Alternative string to return IF no value is found set for the key, neither for the local language nor the default.
		* @param    boolean        If true, the output label is passed through htmlspecialchars()
		* @return    string        The value from LOCAL_LANG.
		*/
	function pi_getLL($key, $alt = '', $hsc = FALSE) {
			// If the suffix is allowed and we have a localized string for the desired salutation, we'll take that.
		if (isset($this->conf['salutation']) && in_array($this->conf['salutation'], $this->allowedSuffixes, 1)) {
			$expandedKey = $key.'_'.$this->conf['salutation'];
			if (isset($this->LOCAL_LANG[$this->LLkey][$expandedKey])) {
				$key = $expandedKey;
			}
		}
		$rc =  $this->pibase->pi_getLL($key, $alt, $hsc);
		return $rc;
	}	// pi_getLL


	function pi_loadLL() {
			// flatten the structure of labels overrides
		if (is_array($this->conf['_LOCAL_LANG.'])) {
			$done = false;
			$i = 0;
			while(!$done && $i < 10) {
				$done = true;
				reset($this->conf['_LOCAL_LANG.']);
				while(list($k,$lA)=each($this->conf['_LOCAL_LANG.'])) {
					if (is_array($lA)) {
						foreach($lA as $llK => $llV)    {
							if (is_array($llV))    {
									foreach ($llV as $llK2 => $llV2) {
									$this->conf['_LOCAL_LANG.'][$k][$llK . $llK2] = $llV2;
								}
								unset($this->conf['_LOCAL_LANG.'][$k][$llK]);
								$done = false;
								++$i;
							}
						}
					}
				}
			}
		}
		$this->pibase->pi_loadLL();
	}	// pi_loadLL

}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sr_feuser_register/lib/class.tx_srfeuserregister_lang.php'])  {
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/sr_feuser_register/lib/class.tx_srfeuserregister_lang.php']);
}
?>
