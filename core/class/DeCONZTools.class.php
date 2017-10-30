<?php
/* This file is part of Plugin RaspBEE for jeedom.
 *
 * Plugin RaspBEE for jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Plugin RaspBEE for jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Plugin RaspBEE for jeedom. If not, see <http://www.gnu.org/licenses/>.
 */

 require_once dirname(__FILE__) . "/../../../../core/php/core.inc.php";
 
 class DeCONZTools{	
	private $platform;
	private $host;
	private $sshPwd;
	private $user;
	private $cnx=false;
	
 	public function __construct() {
		$this->user="pi";
		$this->installType="external";
		$this->sshPwd=config::byKey('raspbeePIPWD','RaspBEE');
		if ($this->installType!="localhost"){
			$fullUrl = explode(":",config::byKey('raspbeeIP','RaspBEE'));
			$this->platform = $fullUrl[0];
		}
		else
			$this->platform="localhost";	
    }
	private function cnxOpen(){
		$response->error=0;
		$response->state="nok";
		if ($this->sshPwd==''){
			$response->message="mot de passe ssh vide".$this->sshPwd;
		}
		else
		{
			$this->cnx = ssh2_connect($this->platform, 22);
			if ($this->cnx){
				if (!ssh2_auth_password($this->cnx, $this->user, $this->sshPwd))
				{
					$response->$message="identification pas ok";
				}
				else {
					$response->message="cnx ok";
					$response->state="ok";
				}					
			} else {
				$response->message="erreur cnx ".$this->platform;
			}			
		}
		return $response;
	}
	
	public function cnxClose(){
		ssh2_exec($this->cnx, 'exit');
		unset($this->cnx);
		$this->cnx=false;
	}
	
	
	public function cnxTest($command){
		$response->error=0;
		$response->state="nok";
		$response = self::sendCommand($command);
		error_log("cnxTest response: ".json_encode($response)."|",3,"/tmp/prob.txt");
		return $response;		
	}
	
	
	private function sendCommand($command){
		if (!$this->cnx)$response=self::cnxOpen();
		if ($this->cnx){
			$stream = ssh2_exec($this->cnx, $command);
			stream_set_blocking( $stream, true );
			$response->message=stream_get_contents(ssh2_fetch_stream($stream, SSH2_STREAM_STDIO));
			fclose($stream); 							
		}
		return $response;
	}
}
?>