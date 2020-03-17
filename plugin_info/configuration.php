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
			<div class="col-lg-4">
				<input class="configKey form-control" data-l1key="identifiant" />
			</div>
		</div>
		<div class="form-group">
			<label class="col-lg-4 control-label">{{Mot de passe}}</label>
			<div class="col-lg-4">
				<input class="configKey form-control" type="password" data-l1key="motdepasse" />
			</div>
			<div class="col-lg-2">
				<a class="btn btn-sm btn-success" id="bt_connect_eeSmart"><i class="fa fa-plug" aria-hidden="true"></i> {{Connexion}}</a>
			</div>
		</div>
		<div class="form-group">
			<label class="col-lg-4 control-label">{{Clé API}}</label>
			<div class="col-lg-4">
				<input class="configKey form-control" data-l1key="APIKey" disabled/>
			</div>
		</div>
		<div class="form-group">
			<label class="col-lg-4 control-label">{{Date de fin de validité de la clé API}}</label>
			<div class="col-lg-4">
				<input class="configKey form-control" data-l1key="ValidTo" disabled/>
			</div>
		</div>
	</fieldset>
</form>

<script type="text/javascript">
$('#bt_connect_eeSmart').off('click').on('click',function(){
	$.ajax({
		type: "POST",
		url: "plugins/eesmart/core/ajax/eesmart.ajax.php",
		data: {
			action: "connexion",
		},
		dataType: 'json',
		error: function (request, status, error) {
			handleAjaxError(request, status, error);
		},
		success: function (data) {
			if (data.state != 'ok') {
				$('#div_alert').showAlert({message: data.result, level: 'danger'});
				return;
			}
			$('#div_alert').showAlert({message: '{{Connexion réussie - Merci de raffraichir la page.}}', level: 'success'});
		}
	});
});
</script>