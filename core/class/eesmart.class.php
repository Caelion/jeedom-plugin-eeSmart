<?php

/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */

/* * ***************************Includes********************************* */
require_once __DIR__  . '/../../../../core/php/core.inc.php';

class eesmart extends eqLogic {
    /*     * *************************Attributs****************************** */
		// *****************
		// * Configuration *
		// *****************
        //public $error = null;
        //public $typeContrat = null;
        //authentification :
        //public $_login = trim(config::byKey('identifiant', 'eesmart'));
        //public $_password = trim(config::byKey('motdepasse', 'eesmart'));
        //private $_login;
        //private $_password;
        //private $_isAuth = false;
        private $_APILoginUrl = 'https://consospyapi.sicame.io/api';
        private $_APIHost = 'sicame.io';
        //private $_numModule;
        //private $_idModule;
        //private $_APIKey;
        //private $_APIExpirationDate;


    /*     * ***********************Methode static*************************** */

    /* Fonction exécutée automatiquement toutes les minutes par Jeedom */
      public static function cron5() {
		  foreach (self::byType('eesmart') as $eesmart) {//parcours tous les équipements du plugin vdm
			  if ($eesmart->getIsEnable() == 1) {//vérifie que l'équipement est actif
				  $cmd = $eesmart->getCmd(null, 'refresh');//retourne la commande "refresh si elle existe
				  if (!is_object($cmd)) {//Si la commande n'existe pas
				  	continue; //continue la boucle
				  }
				  $cmd->execCmd(); // la commande existe on la lance
			  }
		  }
      }

    /*
     * Fonction exécutée automatiquement toutes les heures par Jeedom
      public static function cronHourly() {

      }
     */

    /*
     * Fonction exécutée automatiquement tous les jours par Jeedom
      public static function cronDaily() {

      }
     */



    /*     * *********************Méthodes d'instance************************* */

    public function preInsert() {
        //log::add('eesmart', 'debug', 'Exécution de la fonction preInsert');
    }

    public function postInsert() {
        //log::add('eesmart', 'debug', 'Exécution de la fonction postInsert');
    }

    public function preSave() {
        //log::add('eesmart', 'debug', 'Exécution de la fonction preSave');
    }

    public function postSave() {
		//log::add('eesmart', 'debug', 'Exécution de la fonction postSave');
		$refresh = $this->getCmd(null, 'refresh');
		if (!is_object($refresh)) {
			$refresh = new eesmartCmd();
			$refresh->setName(__('Rafraichir', __FILE__));
		}
		$refresh->setEqLogic_id($this->getId());
		$refresh->setLogicalId('refresh');
		$refresh->setType('action');
		$refresh->setSubType('other');
		$refresh->save();

		$info = $this->getCmd(null, 'typecontrat');
		if (!is_object($info)) {
			$info = new eesmartCmd();
			$info->setName(__('Type de contrat', __FILE__));
		}
		$info->setLogicalId('typecontrat');
		$info->setEqLogic_id($this->getId());
		$info->setType('info');
		$info->setSubType('string');
		$info->save();

		$info = $this->getCmd(null, 'indexHC');
		if (!is_object($info)) {
			$info = new eesmartCmd();
			$info->setName(__('Index Heures Creuses', __FILE__));
		}
		$info->setUnite('kWh');
		$info->setIsHistorized(true);
		$info->setLogicalId('indexHC');
		$info->setEqLogic_id($this->getId());
		$info->setType('info');
		$info->setSubType('numeric');
		$info->save();

      	$info = $this->getCmd(null, 'indexHP');
		if (!is_object($info)) {
			$info = new eesmartCmd();
			$info->setName(__('Index Heures Pleines', __FILE__));
		}
		$info->setUnite('kWh');
		$info->setIsHistorized(true);
		$info->setLogicalId('indexHP');
		$info->setEqLogic_id($this->getId());
		$info->setType('info');
		$info->setSubType('numeric');
		$info->save();
    }

    public function preUpdate() {
        //log::add('eesmart', 'debug', 'Exécution de la fonction preUpdate');
    }

    public function postUpdate() {
        //log::add('eesmart', 'debug', 'Exécution de la fonction postUpdate');
    }

    public function preRemove() {
        //log::add('eesmart', 'debug', 'Exécution de la fonction preRemove');
    }

    public function postRemove() {
        //log::add('eesmart', 'debug', 'Exécution de la fonction postRemove');
    }

    public function eesmart_API() {//non utilisé : en direct dans la page de la configuration du plugin
        $_login = trim(config::byKey('identifiant', 'eesmart'));
        $_password = trim(config::byKey('motdepasse', 'eesmart'));
	/* Connexion */
		$infoCurl = null; // Pour récupérer les info curl
		$headers = array();
			$headers[] = 'Accept: application/json';
			$headers[] = 'Content-Type: application/json';
		$action = 'POST';
		$postfields = '{"login":"'.$_login.'","password":"'.$_password.'"}';

		$curl = curl_init(); // Première étape, initialiser une nouvelle session cURL.
		curl_setopt($curl, CURLOPT_URL, $this->_APILoginUrl.'/D2L/Security/GetAPIKey'); // Il va par exemple falloir lui fournir l'url de la page à récupérer.
		curl_setopt ($curl, CURLOPT_HTTPHEADER, $headers);
		if ($action == 'GET') {
			curl_setopt($curl, CURLOPT_HTTPGET, true); // Pour envoyer une requête POST, il va alors tout d'abord dire à la fonction de faire un HTTP POST
		} elseif ($action == 'POST') {
			curl_setopt($curl, CURLOPT_POST, true); // Pour envoyer une requête POST, il va alors tout d'abord dire à la fonction de faire un HTTP POST
			curl_setopt($curl, CURLOPT_POSTFIELDS, $postfields);
		}
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); // Cette option permet d'indiquer que nous voulons recevoir le résultat du transfert au lieu de l'afficher.

		$return = curl_exec($curl); // Il suffit ensuite d'exécuter la requête
		$infoCurl = curl_getinfo($curl); // Récupération des infos curl
		curl_close($curl);
//return $infoCurl['http_code'] != 200;
	/* Parse du résultat*/
		if ($infoCurl['http_code'] != 200) {
			return "Couple identifiant / mot de passe incorrect";
		} else {
			$params = json_decode($return,true);
			return $params['apiKey'];
        }
     }

    public function eesmart_liste_modules_D2L() {//non utilisé : en direct dans la page de la configuration du module
		$_api = trim(config::byKey('APIKey', 'eesmart'));
	/* Connexion */
		$infoCurl = null; // Pour récupérer les info curl
		$headers = array();
			$headers[] = 'Accept: application/json';
			$headers[] = 'Content-Type: application/json';
      		$headers[] = 'APIKey: '. $_api;
		$action = 'GET';
		$postfields = null; // Pour éviter plantage

		$curl = curl_init(); // Première étape, initialiser une nouvelle session cURL.
		curl_setopt($curl, CURLOPT_URL, $this->_APILoginUrl.'/D2L/D2Ls'); // Il va par exemple falloir lui fournir l'url de la page à récupérer.
		curl_setopt ($curl, CURLOPT_HTTPHEADER, $headers);
		if ($action == 'GET') {
			curl_setopt($curl, CURLOPT_HTTPGET, true); // Pour envoyer une requête POST, il va alors tout d'abord dire à la fonction de faire un HTTP POST
		} elseif ($action == 'POST') {
			curl_setopt($curl, CURLOPT_POST, true); // Pour envoyer une requête POST, il va alors tout d'abord dire à la fonction de faire un HTTP POST
			curl_setopt($curl, CURLOPT_POSTFIELDS, $postfields);
		}
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); // Cette option permet d'indiquer que nous voulons recevoir le résultat du transfert au lieu de l'afficher.
		$return = curl_exec($curl); // Il suffit ensuite d'exécuter la requête
		$infoCurl = curl_getinfo($curl); // Récupération des infos curl
		curl_close($curl);
	/* Parse du résultat*/
		if ($infoCurl['http_code'] != 200) {
			return "Couple identifiant / mot de passe incorrect";
		} else {
			$params = json_decode($return,true);
			$nbid = count($params);
			return $params[1]['idModule'];
        }
     }

    public function eesmart_type_contrat() {
		$_api = trim(config::byKey('APIKey', 'eesmart'));
		if ($this->getConfiguration('idmodule') == '') {
			throw new Exception(__('L\'identifiant du module ne peut être vide', __FILE__));
		} else {
            $_idmodule = $this->getConfiguration('idmodule');
        /* Connexion */
            $infoCurl = null; // Pour récupérer les info curl
            $headers = array();
                $headers[] = 'Accept: application/json';
                $headers[] = 'Content-Type: application/json';
                $headers[] = 'APIKey: '. $_api;
            $action = 'GET';
            $postfields = null; // Pour éviter plantage

            $curl = curl_init(); // Première étape, initialiser une nouvelle session cURL.
            curl_setopt($curl, CURLOPT_URL, $this->_APILoginUrl.'/D2L/D2Ls/'.$_idmodule.'/TypeContrat'); // Il va par exemple falloir lui fournir l'url de la page à récupérer.
            curl_setopt ($curl, CURLOPT_HTTPHEADER, $headers);
            if ($action == 'GET') {
                curl_setopt($curl, CURLOPT_HTTPGET, true); // Pour envoyer une requête POST, il va alors tout d'abord dire à la fonction de faire un HTTP POST
            } elseif ($action == 'POST') {
                curl_setopt($curl, CURLOPT_POST, true); // Pour envoyer une requête POST, il va alors tout d'abord dire à la fonction de faire un HTTP POST
                curl_setopt($curl, CURLOPT_POSTFIELDS, $postfields);
            }
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); // Cette option permet d'indiquer que nous voulons recevoir le résultat du transfert au lieu de l'afficher.
            $return = curl_exec($curl); // Il suffit ensuite d'exécuter la requête
            $infoCurl = curl_getinfo($curl); // Récupération des infos curl
            curl_close($curl);
            if ($infoCurl['http_code'] != 200) { // Traitement des erreurs
                return "Couple identifiant / mot de passe incorrect";
            } else {
                return $return;
            }
		}
    }

    public function eesmart_last_index_HC() {
		$_api = trim(config::byKey('APIKey', 'eesmart'));
		if ($this->getConfiguration('idmodule') == '') {
			throw new Exception(__('L\'identifiant du module ne peut être vide', __FILE__));
		} else {
            $_idmodule = $this->getConfiguration('idmodule');
        /* Connexion */
            $infoCurl = null; // Pour récupérer les info curl
            $headers = array();
                $headers[] = 'Accept: application/json';
                $headers[] = 'Content-Type: application/json';
                $headers[] = 'APIKey: '. $_api;
            $action = 'GET';
            $postfields = null; // Pour éviter plantage

            $curl = curl_init(); // Première étape, initialiser une nouvelle session cURL.
            curl_setopt($curl, CURLOPT_URL, $this->_APILoginUrl.'/D2L/D2Ls/'.$_idmodule.'/LastIndexes'); // Il va par exemple falloir lui fournir l'url de la page à récupérer.
            curl_setopt ($curl, CURLOPT_HTTPHEADER, $headers);
            if ($action == 'GET') {
                curl_setopt($curl, CURLOPT_HTTPGET, true); // Pour envoyer une requête POST, il va alors tout d'abord dire à la fonction de faire un HTTP POST
            } elseif ($action == 'POST') {
                curl_setopt($curl, CURLOPT_POST, true); // Pour envoyer une requête POST, il va alors tout d'abord dire à la fonction de faire un HTTP POST
                curl_setopt($curl, CURLOPT_POSTFIELDS, $postfields);
            }
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); // Cette option permet d'indiquer que nous voulons recevoir le résultat du transfert au lieu de l'afficher.
            $return = curl_exec($curl); // Il suffit ensuite d'exécuter la requête
            $infoCurl = curl_getinfo($curl); // Récupération des infos curl
            curl_close($curl);
            if ($infoCurl['http_code'] != 200) { // Traitement des erreurs
                return "Couple identifiant / mot de passe incorrect";
            } else {
				$params = json_decode($return,true);
				return $params['baseHchcEjphnBbrhcjb'];
            }
		}
     }

    public function eesmart_last_index_HP() {
		$_api = trim(config::byKey('APIKey', 'eesmart'));
		if ($this->getConfiguration('idmodule') == '') {
			throw new Exception(__('L\'identifiant du module ne peut être vide', __FILE__));
		} else {
            $_idmodule = $this->getConfiguration('idmodule');
        /* Connexion */
            $infoCurl = null; // Pour récupérer les info curl
            $headers = array();
                $headers[] = 'Accept: application/json';
                $headers[] = 'Content-Type: application/json';
                $headers[] = 'APIKey: '. $_api;
            $action = 'GET';
            $postfields = null; // Pour éviter plantage

            $curl = curl_init(); // Première étape, initialiser une nouvelle session cURL.
            curl_setopt($curl, CURLOPT_URL, $this->_APILoginUrl.'/D2L/D2Ls/'.$_idmodule.'/LastIndexes'); // Il va par exemple falloir lui fournir l'url de la page à récupérer.
            curl_setopt ($curl, CURLOPT_HTTPHEADER, $headers);
            if ($action == 'GET') {
                curl_setopt($curl, CURLOPT_HTTPGET, true); // Pour envoyer une requête POST, il va alors tout d'abord dire à la fonction de faire un HTTP POST
            } elseif ($action == 'POST') {
                curl_setopt($curl, CURLOPT_POST, true); // Pour envoyer une requête POST, il va alors tout d'abord dire à la fonction de faire un HTTP POST
                curl_setopt($curl, CURLOPT_POSTFIELDS, $postfields);
            }
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); // Cette option permet d'indiquer que nous voulons recevoir le résultat du transfert au lieu de l'afficher.
            $return = curl_exec($curl); // Il suffit ensuite d'exécuter la requête
            $infoCurl = curl_getinfo($curl); // Récupération des infos curl
            curl_close($curl);
            if ($infoCurl['http_code'] != 200) { // Traitement des erreurs
                return "Couple identifiant / mot de passe incorrect";
            } else {
				$params = json_decode($return,true);
				return $params['hchpEjphpmBbrhpjb'];
            }
		}
     }

    /*
     * Non obligatoire mais permet de modifier l'affichage du widget si vous en avez besoin
      public function toHtml($_version = 'dashboard') {

      }
     */

    /*
     * Non obligatoire mais ca permet de déclencher une action après modification de variable de configuration
    public static function postConfig_<Variable>() {
    }
     */

    /*
     * Non obligatoire mais ca permet de déclencher une action avant modification de variable de configuration
    public static function preConfig_<Variable>() {
    }
     */

    /*     * **********************Getteur Setteur*************************** */
}

class eesmartCmd extends cmd {
    /*     * *************************Attributs****************************** */


    /*     * ***********************Methode static*************************** */


    /*     * *********************Methode d'instance************************* */

    /*
     * Non obligatoire permet de demander de ne pas supprimer les commandes même si elles ne sont pas dans la nouvelle configuration de l'équipement envoyé en JS
      public function dontRemoveCmd() {
      return true;
      }
     */

    public function execute($_options = array()) {
		$eqlogic = $this->getEqLogic(); // récupère l'éqlogic de la commande $this
		switch ($this->getLogicalId()) { // vérifie le logicalid de la commande
			case 'refresh': // LogicalId de la commande rafraîchir que l’on a créé dans la méthode Postsave de la classe eesmart.
			$info = $eqlogic->eesmart_type_contrat(); // On lance la fonction eesmartauth() pour récupérer l'api et on la stocke dans la variable $info
			$eqlogic->checkAndUpdateCmd('typecontrat', $info); // on met à jour la commande avec le LogicalId "story" de l'eqlogic 
            $info = "";
			$info = $eqlogic->eesmart_last_index_HC(); // On lance la fonction eesmartauth() pour récupérer l'api et on la stocke dans la variable $info
			$eqlogic->checkAndUpdateCmd('indexHC', $info); // on met à jour la commande avec le LogicalId "story" de l'eqlogic 
            $info = "";
			$info = $eqlogic->eesmart_last_index_HP(); // On lance la fonction eesmartauth() pour récupérer l'api et on la stocke dans la variable $info
			$eqlogic->checkAndUpdateCmd('indexHP', $info); // on met à jour la commande avec le LogicalId "story" de l'eqlogic 
			break;
		}
    }

    /*     * **********************Getteur Setteur*************************** */
}