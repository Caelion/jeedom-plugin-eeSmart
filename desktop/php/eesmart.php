<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
$plugin = plugin::byId('eesmart');
sendVarToJS('eqType', $plugin->getId());
$eqLogics = eqLogic::byType($plugin->getId());
?>

<div class="row row-overflow">
	<div class="col-xs-12 eqLogicThumbnailDisplay">
		<legend><i class="fas fa-cog"></i>  {{Gestion}}</legend>
        <div class="eqLogicThumbnailContainer">
			<div class="cursor eqLogicAction logoPrimary" data-action="add" style="color:#ffc000;">
				<i class="fas fa-plus-circle"></i>
                <br>
				<span>{{Ajouter}}</span>
			</div>
			<div class="cursor eqLogicAction logoSecondary" data-action="gotoPluginConf">
				<i class="fas fa-wrench"></i>
				<br>
				<span>{{Configuration}}</span>
			</div>
		</div>
		<legend><i class="fas fa-table"></i> {{Mes modules eeSmart}}</legend>
		<input class="form-control" placeholder="{{Rechercher}}" id="in_searchEqlogic" />
		<div class="eqLogicThumbnailContainer">
<?php
foreach ($eqLogics as $eqLogic) {
	$opacity = ($eqLogic->getIsEnable()) ? '' : 'disableCard';
	echo '<div class="eqLogicDisplayCard cursor '.$opacity.'" data-eqLogic_id="' . $eqLogic->getId() . '">';
	echo '<img src="' . $plugin->getPathImgIcon() . '"/>';
	echo '<br>';
	echo '<span class="name">' . $eqLogic->getHumanName(true, true) . '</span>';
	echo '</div>';
}
?>
		</div>
	</div>
	<div class="col-xs-12 eqLogic" style="display: none;">
		<div class="input-group pull-right" style="display:inline-flex">
			<span class="input-group-btn">
				<a class="btn btn-default btn-sm eqLogicAction roundedLeft" data-action="configure"><i class="fa fa-cogs"></i> {{Configuration avancée}}</a><a class="btn btn-default btn-sm eqLogicAction" data-action="copy"><i class="fas fa-copy"></i> {{Dupliquer}}</a><a class="btn btn-sm btn-success eqLogicAction" data-action="save"><i class="fas fa-check-circle"></i> {{Sauvegarder}}</a><a class="btn btn-danger btn-sm eqLogicAction roundedRight" data-action="remove"><i class="fas fa-minus-circle"></i> {{Supprimer}}</a>
			</span>
		</div>
		<ul class="nav nav-tabs" role="tablist">
			<li role="presentation"><a href="#" class="eqLogicAction" aria-controls="home" role="tab" data-toggle="tab" data-action="returnToThumbnailDisplay"><i class="fa fa-arrow-circle-left"></i></a></li>
			<li role="presentation" class="active"><a href="#eqlogictab" aria-controls="home" role="tab" data-toggle="tab"><i class="fas fa-tachometer-alt"></i> {{Equipement}}</a></li>
			<li role="presentation"><a href="#commandtab" aria-controls="profile" role="tab" data-toggle="tab"><i class="fa fa-list-alt"></i> {{Commandes}}</a></li>
		</ul>
		<div class="tab-content" style="height:calc(100% - 50px);overflow:auto;overflow-x: hidden;">
			<div role="tabpanel" class="tab-pane active" id="eqlogictab">
				<br/>
                <form class="form-horizontal">
                    <fieldset>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">{{Nom de l équipement eeSmart}}</label>
                            <div class="col-sm-3">
                                <input type="text" class="eqLogicAttr form-control" data-l1key="id" style="display : none;" />
                                <input type="text" class="eqLogicAttr form-control" data-l1key="name" placeholder="{{Nom de l'équipement eeSmart}}"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label" >{{Objet parent}}</label>
                            <div class="col-sm-3">
                                <select id="sel_object" class="eqLogicAttr form-control" data-l1key="object_id">
                                    <option value="">{{Aucun}}</option>
<?php
foreach (jeeObject::all() as $object) {
	echo '<option value="' . $object->getId() . '">' . $object->getName() . '</option>';
}
?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">{{Catégorie}}</label>
                            <div class="col-sm-9">
<?php
foreach (jeedom::getConfiguration('eqLogic:category') as $key => $value) {
	echo '<label class="checkbox-inline">';
	echo '<input type="checkbox" class="eqLogicAttr" data-l1key="category" data-l2key="' . $key . '" />' . $value['name'];
	echo '</label>';
}
?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label"></label>
                            <div class="col-sm-9">
                                <label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isEnable" checked/>{{Activer}}</label>
                                <label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isVisible" checked/>{{Visible}}</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">{{Module à sélectionner}}</label>
                            <div class="col-sm-4">
<?php
$_api = trim(config::byKey('APIKey', 'eesmart'));
if ($_api != '' && $_api != 'identifiant / mot de passe incorrect(s)'){
	/* Paramètres de connexion */
	$_APILoginUrl = 'https://consospyapi.sicame.io/api';
	$infoCurl = null; // Pour récupérer les info curl
	$headers = array();
		$headers[] = 'Accept: application/json';
		$headers[] = 'Content-Type: application/json';
		$headers[] = 'APIKey: '. $_api;
	$action = 'GET';
	$postfields = null; // Pour éviter plantage
	/* Connexion */
	$curl = curl_init(); // Première étape, initialiser une nouvelle session cURL.
	curl_setopt($curl, CURLOPT_URL, $_APILoginUrl.'/D2L/D2Ls'); // Il va par exemple falloir lui fournir l'url de la page à récupérer.
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
	$params = json_decode($return,true);
	$nbid = count($params);
	echo '<select id="typeEq" class="form-control eqLogicAttr" data-l1key="configuration" data-l2key="idmodule">';
	for ($i = 0; $i < $nbid; $i++) {
		echo '<option value="'.$params[$i]['idModule'].'">{{'.$params[$i]['idModule'].' - '.$params[$i]['labelModule'].' - '.$params[$i]['counter'].'}}</option>';
	}
	echo '</select>';
} else {
	echo '<div class="form-control" disabled>Veuillez aller saisir / contrôler vos identifiants dans la page Configuration du Plugin</div>';
}
?>
                            </div>
                        </div>
					</fieldset>
				</form>
			</div>
			<div role="tabpanel" class="tab-pane" id="commandtab">
				<a class="btn btn-success btn-sm cmdAction pull-right" data-action="add" style="margin-top:5px;"><i class="fa fa-plus-circle"></i> {{Commandes}}</a><br/><br/>
				<table id="table_cmd" class="table table-bordered table-condensed">
					<thead>
						<tr>
							<th>{{Nom}}</th><th>{{Type}}</th><th style="width: 250px;">{{Paramètres}}</th><th>{{Action}}</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<?php include_file('desktop', 'eesmart', 'js', 'eesmart');?>
<?php include_file('core', 'plugin.template', 'js');?>
