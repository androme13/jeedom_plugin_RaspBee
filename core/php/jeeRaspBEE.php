<?php
require_once dirname(__FILE__) . "/../../../../core/php/core.inc.php";

if (!jeedom::apiAccess(init('apikey'), 'RaspBEE')) {
	echo __('Vous n\'etes pas autorisé à effectuer cette action', __FILE__);	
	//echo "clé: ".jeedom::getApiKey('RaspBEE');
	//echo " clé 2".jeedom::apiAccess(init('apikey'), 'RaspBEE');
	die();
}
if (isset($_GET['test'])) {
	echo 'OK';
	die();
}

$results = json_decode(file_get_contents("php://input"));

if (!is_object($results)) {
	die();
}

if ($results->type == "sensors"){
	if (is_object($results->info))
	{
	echo "{'id':".$results->id.",'battery':".$results->info->battery."}";	
	}
	//else
	if (is_object($results->action)){
		$boucle=0;
		foreach (eqLogic::byType('RaspBEE') as $equipement) {
			$boucle=$boucle+1;
			echo "id:".$equipement->getId();
			echo "name:".$equipement->getName();
			foreach ($equipement->getCmd('info') as $cmd){
				echo "cmdid:".$cmd->getId();
				echo "cmdname:".$cmd->getName();
				$cmd->setValue($results->action->buttonevent);
				
				$cmd->event($results->action->buttonevent);
				$cmd->save();
				echo "cmdvalue:".$cmd->getValue();
			}
			$equipement->save();
			echo "boucle ".$boucle;
			//$JeeOrangeTv->ActionInfo($JeeOrangeTv->getConfiguration('box_ip'));
		}
		echo "{'id':".$results->id.",'button':".$results->action->buttonevent."}";
		
	}
			
	
}
else
echo json_encode($results->params);


?>