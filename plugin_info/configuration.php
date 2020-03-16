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

require_once dirname(__FILE__) . '/../../../core/php/core.inc.php';
include_file('core', 'authentification', 'php');
if (!isConnect()) {
    include_file('desktop', '404', 'php');
    die();
}
?>
<form class="form-horizontal">
    <fieldset>
        <div class="form-group">
            <label class="col-lg-4 control-label">{{Identifiant}}</label>
            <div class="col-lg-6">
                <input class="configKey form-control" data-l1key="identifiant" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-4 control-label">{{Mot de passe}}</label>
            <div class="col-lg-6">
                <input class="configKey form-control" type="password" data-l1key="motdepasse" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-4 control-label">{{Clé API}}</label>
            <div class="col-lg-6">
                <input class="configKey form-control" data-l1key="APIKey" disabled/>
<?php
$_login = trim(config::byKey('identifiant', 'eesmart'));
$_password = trim(config::byKey('motdepasse', 'eesmart'));
if ($_login != '' && $_password != ''){
	/* Paramètres de connexion */
	$_APILoginUrl = 'https://consospyapi.sicame.io/api';
	$_APIHost = 'sicame.io';
	$infoCurl = null; // Pour récupérer les info curl
	$postfields = '{"login":"'.$_login.'","password":"'.$_password.'"}';
	$headers = array();
		$headers[] = 'Accept: application/json';
		$headers[] = 'Content-Type: application/json';
	/* Connexion */
	$curl = curl_init(); //Première étape, initialiser une nouvelle session cURL.
	$action = 'POST';
	curl_setopt($curl, CURLOPT_URL, $_APILoginUrl.'/D2L/Security/GetAPIKey'); //Il va par exemple falloir lui fournir l'url de la page à récupérer.
	if ($action == 'GET') {
    	curl_setopt($curl, CURLOPT_HTTPGET, true); //Pour envoyer une requête POST, il va alors tout d'abord dire à la fonction de faire un HTTP POST
	} elseif ($action == 'POST') {
    	curl_setopt($curl, CURLOPT_POST, true); //Pour envoyer une requête POST, il va alors tout d'abord dire à la fonction de faire un HTTP POST
    	curl_setopt($curl, CURLOPT_POSTFIELDS, $postfields);
	}
	curl_setopt ($curl, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); //Cette option permet d'indiquer que nous voulons recevoir le résultat du transfert au lieu de l'afficher.
	$return = curl_exec($curl); //Il suffit ensuite d'exécuter la requête
	$infoCurl = curl_getinfo($curl); //recupération des infos curl
	curl_close($curl);
	/* Analyse du résultat */
	$params = json_decode($return,true);
	if ($params['apiKey'] != "") {
		config::save('APIKey', $params['apiKey'], 'eesmart');
		config::save('ValidTo', $params['validTo'], 'eesmart');
	} else {
    	config::save('APIKey', 'identifiant / mot de passe incorrect(s)', 'eesmart');
	config::save('ValidTo', 'identifiant / mot de passe incorrect(s)', 'eesmart');
	}
}
?>
Rafraichir la page pour vérifier la correcte saisie des identifiants.
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-4 control-label">{{Date de fin de validité de la clé API}}</label>
            <div class="col-lg-6">
		<input class="configKey form-control" data-l1key="ValidTo" disabled/>
            </div>
        </div>
  </fieldset>
</form>

