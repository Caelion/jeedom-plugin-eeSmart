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
        private $_APILoginUrl = 'https://consospyapi.sicame.io/api';
        private $_APIHost = 'sicame.io';

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

    /* Fonction exécutée automatiquement toutes les heures par Jeedom
    public static function cronHourly() {

      }
     */

    /* Fonction exécutée automatiquement tous les jours par Jeedom
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

		//Création des commandes
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

		$info = $this->getCmd(null, 'horlogeindex');
		if (!is_object($info)) {
			$info = new eesmartCmd();
			$info->setName(__('Index - Dernier relevé', __FILE__));
		}
		$info->setLogicalId('horlogeindex');
		$info->setEqLogic_id($this->getId());
		$info->setType('info');
		$info->setSubType('string');
		$info->save();

		$info = $this->getCmd(null, 'horlogeintensite');
		if (!is_object($info)) {
			$info = new eesmartCmd();
			$info->setName(__('Intensité - Dernier relevé', __FILE__));
		}
		$info->setLogicalId('horlogeintensite');
		$info->setEqLogic_id($this->getId());
		$info->setType('info');
		$info->setSubType('string');
		$info->save();

		$info = $this->getCmd(null, 'indexTotal');
		if (!is_object($info)) {
			$info = new eesmartCmd();
			$info->setName(__('Index Total', __FILE__));
		}
		$info->setUnite('kWh');
		$info->setIsHistorized(true);
		$info->setLogicalId('indexTotal');
		$info->setEqLogic_id($this->getId());
		$info->setType('info');
		$info->setSubType('numeric');
		$info->setTemplate('dashboard','badge');
      	$info->setDisplay('showStatsOndashboard','0');
      	$info->setDisplay('showStatsOnplan','0');
      	$info->setDisplay('showStatsOnview','0');
      	$info->setDisplay('showStatsOnmobile','0');
		$info->save();

		$info = $this->getCmd(null, 'indexBase');
		if (!is_object($info)) {
			$info = new eesmartCmd();
			$info->setName(__('Index Base', __FILE__));
		}
		$info->setUnite('kWh');
		$info->setIsHistorized(true);
		$info->setLogicalId('indexBase');
		$info->setEqLogic_id($this->getId());
		$info->setType('info');
		$info->setSubType('numeric');
		$info->setTemplate('dashboard','badge');
      	$info->setDisplay('showStatsOndashboard','0');
      	$info->setDisplay('showStatsOnplan','0');
      	$info->setDisplay('showStatsOnview','0');
      	$info->setDisplay('showStatsOnmobile','0');
		$info->save();

		$info = $this->getCmd(null, 'indexHC');
		if (!is_object($info)) {
			$info = new eesmartCmd();
			$info->setName(__('Index HCHP - Heures Creuses', __FILE__));
		}
		$info->setUnite('kWh');
		$info->setIsHistorized(true);
		$info->setLogicalId('indexHC');
		$info->setEqLogic_id($this->getId());
		$info->setType('info');
		$info->setSubType('numeric');
		$info->setTemplate('dashboard','badge');
      	$info->setDisplay('showStatsOndashboard','0');
      	$info->setDisplay('showStatsOnplan','0');
      	$info->setDisplay('showStatsOnview','0');
      	$info->setDisplay('showStatsOnmobile','0');
		$info->save();

      	$info = $this->getCmd(null, 'indexHP');
		if (!is_object($info)) {
			$info = new eesmartCmd();
			$info->setName(__('Index HCHP - Heures Pleines', __FILE__));
		}
		$info->setUnite('kWh');
		$info->setIsHistorized(true);
		$info->setLogicalId('indexHP');
		$info->setEqLogic_id($this->getId());
		$info->setType('info');
		$info->setSubType('numeric');
		$info->setTemplate('dashboard','badge');
      	$info->setDisplay('showStatsOndashboard','0');
      	$info->setDisplay('showStatsOnplan','0');
      	$info->setDisplay('showStatsOnview','0');
      	$info->setDisplay('showStatsOnmobile','0');
		$info->save();

		$info = $this->getCmd(null, 'indexHN');
		if (!is_object($info)) {
			$info = new eesmartCmd();
			$info->setName(__('Index EJP - Heures Normales', __FILE__));
		}
		$info->setUnite('kWh');
		$info->setIsHistorized(true);
		$info->setLogicalId('indexHN');
		$info->setEqLogic_id($this->getId());
		$info->setType('info');
		$info->setSubType('numeric');
		$info->setTemplate('dashboard','badge');
      	$info->setDisplay('showStatsOndashboard','0');
      	$info->setDisplay('showStatsOnplan','0');
      	$info->setDisplay('showStatsOnview','0');
      	$info->setDisplay('showStatsOnmobile','0');
		$info->save();

		$info = $this->getCmd(null, 'indexHPl');
		if (!is_object($info)) {
			$info = new eesmartCmd();
			$info->setName(__('Index EJP - Heures Pleines', __FILE__));
		}
		$info->setUnite('kWh');
		$info->setIsHistorized(true);
		$info->setLogicalId('indexHPl');
		$info->setEqLogic_id($this->getId());
		$info->setType('info');
		$info->setSubType('numeric');
		$info->setTemplate('dashboard','badge');
      	$info->setDisplay('showStatsOndashboard','0');
      	$info->setDisplay('showStatsOnplan','0');
      	$info->setDisplay('showStatsOnview','0');
      	$info->setDisplay('showStatsOnmobile','0');
		$info->save();

		$info = $this->getCmd(null, 'indexHCJB');
		if (!is_object($info)) {
			$info = new eesmartCmd();
			$info->setName(__('Index Tempo - Heures Creuses - Jours Bleus', __FILE__));
		}
		$info->setUnite('kWh');
		$info->setIsHistorized(true);
		$info->setLogicalId('indexHCJB');
		$info->setEqLogic_id($this->getId());
		$info->setType('info');
		$info->setSubType('numeric');
		$info->setTemplate('dashboard','badge');
      	$info->setDisplay('showStatsOndashboard','0');
      	$info->setDisplay('showStatsOnplan','0');
      	$info->setDisplay('showStatsOnview','0');
      	$info->setDisplay('showStatsOnmobile','0');
		$info->save();

		$info = $this->getCmd(null, 'indexHPJB');
		if (!is_object($info)) {
			$info = new eesmartCmd();
			$info->setName(__('Index Tempo - Heures Pleines - Jours Bleus', __FILE__));
		}
		$info->setUnite('kWh');
		$info->setIsHistorized(true);
		$info->setLogicalId('indexHPJB');
		$info->setEqLogic_id($this->getId());
		$info->setType('info');
		$info->setSubType('numeric');
		$info->setTemplate('dashboard','badge');
      	$info->setDisplay('showStatsOndashboard','0');
      	$info->setDisplay('showStatsOnplan','0');
      	$info->setDisplay('showStatsOnview','0');
      	$info->setDisplay('showStatsOnmobile','0');
		$info->save();

		$info = $this->getCmd(null, 'indexHCJW');
		if (!is_object($info)) {
			$info = new eesmartCmd();
			$info->setName(__('Index Tempo - Heures Creuses - Jours Blancs', __FILE__));
		}
		$info->setUnite('kWh');
		$info->setIsHistorized(true);
		$info->setLogicalId('indexHCJW');
		$info->setEqLogic_id($this->getId());
		$info->setType('info');
		$info->setSubType('numeric');
		$info->setTemplate('dashboard','badge');
      	$info->setDisplay('showStatsOndashboard','0');
      	$info->setDisplay('showStatsOnplan','0');
      	$info->setDisplay('showStatsOnview','0');
      	$info->setDisplay('showStatsOnmobile','0');
		$info->save();

		$info = $this->getCmd(null, 'indexHPJW');
		if (!is_object($info)) {
			$info = new eesmartCmd();
			$info->setName(__('Index Tempo - Heures Pleines - Jours Blancs', __FILE__));
		}
		$info->setUnite('kWh');
		$info->setIsHistorized(true);
		$info->setLogicalId('indexHPJW');
		$info->setEqLogic_id($this->getId());
		$info->setType('info');
		$info->setSubType('numeric');
		$info->setTemplate('dashboard','badge');
      	$info->setDisplay('showStatsOndashboard','0');
      	$info->setDisplay('showStatsOnplan','0');
      	$info->setDisplay('showStatsOnview','0');
      	$info->setDisplay('showStatsOnmobile','0');
		$info->save();

		$info = $this->getCmd(null, 'indexHCJR');
		if (!is_object($info)) {
			$info = new eesmartCmd();
			$info->setName(__('Index Tempo - Heures Creuses - Jours Rouges', __FILE__));
		}
		$info->setUnite('kWh');
		$info->setIsHistorized(true);
		$info->setLogicalId('indexHCJR');
		$info->setEqLogic_id($this->getId());
		$info->setType('info');
		$info->setSubType('numeric');
		$info->setTemplate('dashboard','badge');
      	$info->setDisplay('showStatsOndashboard','0');
      	$info->setDisplay('showStatsOnplan','0');
      	$info->setDisplay('showStatsOnview','0');
      	$info->setDisplay('showStatsOnmobile','0');
		$info->save();

		$info = $this->getCmd(null, 'indexHPJR');
		if (!is_object($info)) {
			$info = new eesmartCmd();
			$info->setName(__('Index Tempo - Heures Pleines - Jours Rouges', __FILE__));
		}
		$info->setUnite('kWh');
		$info->setIsHistorized(true);
		$info->setLogicalId('indexHPJR');
		$info->setEqLogic_id($this->getId());
		$info->setType('info');
		$info->setSubType('numeric');
		$info->setTemplate('dashboard','badge');
      	$info->setDisplay('showStatsOndashboard','0');
      	$info->setDisplay('showStatsOnplan','0');
      	$info->setDisplay('showStatsOnview','0');
      	$info->setDisplay('showStatsOnmobile','0');
		$info->save();

		$info = $this->getCmd(null, 'intensite');
		if (!is_object($info)) {
			$info = new eesmartCmd();
			$info->setName(__('Intensité totale', __FILE__));
		}
		$info->setUnite('A');
		$info->setIsHistorized(true);
		$info->setLogicalId('intensite');
		$info->setEqLogic_id($this->getId());
		$info->setType('info');
		$info->setSubType('numeric');
		$info->setTemplate('dashboard','badge');
      	$info->setDisplay('showStatsOndashboard','0');
      	$info->setDisplay('showStatsOnplan','0');
      	$info->setDisplay('showStatsOnview','0');
      	$info->setDisplay('showStatsOnmobile','0');
		$info->save();

		$info = $this->getCmd(null, 'intensite1');
		if (!is_object($info)) {
			$info = new eesmartCmd();
			$info->setName(__('Intensité - Phase 1', __FILE__));
		}
		$info->setUnite('A');
		$info->setIsHistorized(true);
		$info->setLogicalId('intensite1');
		$info->setEqLogic_id($this->getId());
		$info->setType('info');
		$info->setSubType('numeric');
		$info->setTemplate('dashboard','badge');
      	$info->setDisplay('showStatsOndashboard','0');
      	$info->setDisplay('showStatsOnplan','0');
      	$info->setDisplay('showStatsOnview','0');
      	$info->setDisplay('showStatsOnmobile','0');
		$info->save();

      	$info = $this->getCmd(null, 'intensite2');
		if (!is_object($info)) {
			$info = new eesmartCmd();
			$info->setName(__('Intensité - Phase 2', __FILE__));
		}
		$info->setUnite('A');
		$info->setIsHistorized(true);
		$info->setLogicalId('intensite2');
		$info->setEqLogic_id($this->getId());
		$info->setType('info');
		$info->setSubType('numeric');
		$info->setTemplate('dashboard','badge');
      	$info->setDisplay('showStatsOndashboard','0');
      	$info->setDisplay('showStatsOnplan','0');
      	$info->setDisplay('showStatsOnview','0');
      	$info->setDisplay('showStatsOnmobile','0');
		$info->save();

		$info = $this->getCmd(null, 'intensite3');
		if (!is_object($info)) {
			$info = new eesmartCmd();
			$info->setName(__('Intensité - Phase 3', __FILE__));
		}
		$info->setUnite('A');
		$info->setIsHistorized(true);
		$info->setLogicalId('intensite3');
		$info->setEqLogic_id($this->getId());
		$info->setType('info');
		$info->setSubType('numeric');
		$info->setTemplate('dashboard','badge');
      	$info->setDisplay('showStatsOndashboard','0');
      	$info->setDisplay('showStatsOnplan','0');
      	$info->setDisplay('showStatsOnview','0');
      	$info->setDisplay('showStatsOnmobile','0');
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

    public function eesmart_type_contrat() {
		$_api = trim(config::byKey('APIKey', 'eesmart'));
		if ($this->getConfiguration('idmodule') == '') {
			throw new Exception(__('L\'identifiant du module ne peut être vide', __FILE__));
		} else {
            /* Paramètres de connexion */
			$_idmodule = $this->getConfiguration('idmodule');
            $infoCurl = null; // Pour récupérer les info curl
            $headers = array();
                $headers[] = 'Accept: application/json';
                $headers[] = 'Content-Type: application/json';
                $headers[] = 'APIKey: '. $_api;
            $action = 'GET';
            $postfields = null; // Pour éviter plantage
			/* Connexion */
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
			/* Analyse du résultat */
            if ($infoCurl['http_code'] != 200) { // Traitement des erreurs
                return "Couple identifiant / mot de passe incorrect";
            } else {
            	$this->setConfiguration("typecontrat",$return);// Enregistrement de la valeur dans la configuration
            	$this->save();
            	if ($return == '"BASE"') {$return = "Contrat de base";};
            	if ($return == '"HEURE_CREUSE_HEURE_PLEINE"') {$return = "Contrat HCHP";};
            	if ($return == '"EJP"') {$return = "Contrat EJP";};
            	if ($return == '"TEMPO"') {$return = "Contrat Tempo";};
				$this->setConfiguration("typecontrat_libelle",$return);
            	$this->save();

		// Affichage par défaut en fonction du type de contrat
      	$typecontrat = $this->getConfiguration('typecontrat_libelle');
		if ($typecontrat != '' && $typecontrat != 'none') {
			if ($typecontrat != 'Contrat de base') {
				$info = $this->getCmd(null, 'indexBase');
				$info->setIsVisible(false);
              	$info->save();
            }
			if ($typecontrat != 'Contrat HCHP') {
				$info = $this->getCmd(null, 'indexHC');
				$info->setIsVisible(false);
              	$info->save();
				$info = $this->getCmd(null, 'indexHP');
				$info->setIsVisible(false);
              	$info->save();
            }
			if ($typecontrat != 'Contrat EJP') {
				$info = $this->getCmd(null, 'indexHN');
				$info->setIsVisible(false);
              	$info->save();
				$info = $this->getCmd(null, 'indexHPl');
				$info->setIsVisible(false);
              	$info->save();
            }
			if ($typecontrat != 'Contrat Tempo') {
				$info = $this->getCmd(null, 'indexHCJB');
				$info->setIsVisible(false);
              	$info->save();
				$info = $this->getCmd(null, 'indexHPJB');
				$info->setIsVisible(false);
              	$info->save();
				$info = $this->getCmd(null, 'indexHCJW');
				$info->setIsVisible(false);
              	$info->save();
				$info = $this->getCmd(null, 'indexHPJW');
				$info->setIsVisible(false);
              	$info->save();
				$info = $this->getCmd(null, 'indexHCJR');
				$info->setIsVisible(false);
              	$info->save();
				$info = $this->getCmd(null, 'indexHPJR');
				$info->setIsVisible(false);
              	$info->save();
            }
		}

				return $return;
            }
		}
     }

    public function eesmart_last_indexes() {
		$_api = trim(config::byKey('APIKey', 'eesmart'));
		if ($this->getConfiguration('idmodule') == '') {
			throw new Exception(__('L\'identifiant du module ne peut être vide', __FILE__));
		} else {
            /* Paramètres de connexion */
			$_idmodule = $this->getConfiguration('idmodule');
            $infoCurl = null; // Pour récupérer les info curl
            $headers = array();
                $headers[] = 'Accept: application/json';
                $headers[] = 'Content-Type: application/json';
                $headers[] = 'APIKey: '. $_api;
            $action = 'GET';
            $postfields = null; // Pour éviter plantage
			/* Connexion */
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
			/* Analyse du résultat */
            if ($infoCurl['http_code'] != 200) { // Traitement des erreurs
                return "Couple identifiant / mot de passe incorrect";
            } else {
				$params = json_decode($return,true);
				return $params;
            }
		}
     }

    public function eesmart_last_currents() {
		$_api = trim(config::byKey('APIKey', 'eesmart'));
		if ($this->getConfiguration('idmodule') == '') {
			throw new Exception(__('L\'identifiant du module ne peut être vide', __FILE__));
		} else {
            /* Paramètres de connexion */
			$_idmodule = $this->getConfiguration('idmodule');
            $infoCurl = null; // Pour récupérer les info curl
            $headers = array();
                $headers[] = 'Accept: application/json';
                $headers[] = 'Content-Type: application/json';
                $headers[] = 'APIKey: '. $_api;
            $action = 'GET';
            $postfields = null; // Pour éviter plantage
			/* Connexion */
            $curl = curl_init(); // Première étape, initialiser une nouvelle session cURL.
            curl_setopt($curl, CURLOPT_URL, $this->_APILoginUrl.'/D2L/D2Ls/'.$_idmodule.'/LastCurrents'); // Il va par exemple falloir lui fournir l'url de la page à récupérer.
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
			/* Analyse du résultat */
            if ($infoCurl['http_code'] != 200) { // Traitement des erreurs
                return "Couple identifiant / mot de passe incorrect";
            } else {
				$params = json_decode($return,true);
				return $params;
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

    /* Non obligatoire permet de demander de ne pas supprimer les commandes même si elles ne sont pas dans la nouvelle configuration de l'équipement envoyé en JS */
      public function dontRemoveCmd() {
		return true;
      }

    public function execute($_options = array()) {
		$eqlogic = $this->getEqLogic(); // récupère l'éqlogic de la commande $this
		switch ($this->getLogicalId()) { // vérifie le logicalid de la commande
			case 'refresh': // LogicalId de la commande rafraîchir que l’on a créé dans la méthode Postsave de la classe eesmart.
            $info = $eqlogic->eesmart_type_contrat();
			$eqlogic->checkAndUpdateCmd('typecontrat', $info); // on met à jour la commande typecontrat avec la valeur de la clé de configuration typecontrat
            $info = "";
			$info = $eqlogic->eesmart_last_indexes(); // On lance la fonction eesmart_last_indexes() pour récupérer l'api et on la stocke dans la variable $info
			$eqlogic->checkAndUpdateCmd('indexTotal', ($info['baseHchcEjphnBbrhcjb']+$info['hchpEjphpmBbrhpjb']+$info['bbrhcjw']+$info['bbrhpjw']+$info['bbrhcjr']+$info['bbrhpjr'])/1000); // on met à jour la commande avec le LogicalId "indexTotal" de l'eqlogic
            $eqlogic->checkAndUpdateCmd('indexBase', $info['baseHchcEjphnBbrhcjb']/1000); // on met à jour la commande avec le LogicalId "indexBase" de l'eqlogic
            $eqlogic->checkAndUpdateCmd('indexHC', $info['baseHchcEjphnBbrhcjb']/1000); // on met à jour la commande avec le LogicalId "indexHC" de l'eqlogic
            $eqlogic->checkAndUpdateCmd('indexHP', $info['hchpEjphpmBbrhpjb']/1000);
            $eqlogic->checkAndUpdateCmd('indexHN', $info['baseHchcEjphnBbrhcjb']/1000);
            $eqlogic->checkAndUpdateCmd('indexHPl', $info['hchpEjphpmBbrhpjb']/1000);
            $eqlogic->checkAndUpdateCmd('indexHCJB', $info['baseHchcEjphnBbrhcjb']/1000);
            $eqlogic->checkAndUpdateCmd('indexHPJB', $info['hchpEjphpmBbrhpjb']/1000);
            $eqlogic->checkAndUpdateCmd('indexHCJW', $info['bbrhcjw']/1000);
            $eqlogic->checkAndUpdateCmd('indexHPJW', $info['bbrhpjw']/1000);
            $eqlogic->checkAndUpdateCmd('indexHCJR', $info['bbrhcjr']/1000);
            $eqlogic->checkAndUpdateCmd('indexHPJR', $info['bbrhpjr']/1000);
            $eqlogic->checkAndUpdateCmd('horlogeindex', $info['horloge']);
			$info = $eqlogic->eesmart_last_currents(); // On lance la fonction eesmart_last_currents() pour récupérer l'api et on la stocke dans la variable $info
            $eqlogic->checkAndUpdateCmd('intensite', $info['iinst1']+$info['iinst2']+$info['iinst3']);
            $eqlogic->checkAndUpdateCmd('intensite1', $info['iinst1']);
            $eqlogic->checkAndUpdateCmd('intensite2', $info['iinst2']);
            $eqlogic->checkAndUpdateCmd('intensite3', $info['iinst3']);
            $eqlogic->checkAndUpdateCmd('horlogeintensite', $info['horloge']);
			break;
		}
    }

    /*     * **********************Getteur Setteur*************************** */
}