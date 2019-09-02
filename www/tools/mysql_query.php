<?php
class mysql{
	
	public $link;
	public $query;
	
	function __construct(){
		
		$host='127.0.0.1';
		$user='iestpsa_intranet';
		$password='13stps4_1ntr4n3t';
		$db='iestpsa_intranet';

		$this->link=mysqli_connect("$host", "$user", "$password", "$db");


		if (!$this->link) {
			echo "Error: Unable to connect to MySQL." . PHP_EOL;
			echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
			echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
			exit;
		}


	}
	
	function __destruct() {
    	mysqli_free_result($this->query);
		$this->link->close();
    }
	
/*----------------------------------------------------------------------------------------------------*/
	
	
	function get_est_puerto_actuador(){
		
			$dig_actuador=$_GET['d']; //md5
			$ipa_actuador=$_SERVER['REMOTE_ADDR'];
		
			$this->query=mysqli_query($this->link,"SELECT dig_actuador  FROM tb_actuador WHERE ipa_actuador='$ipa_actuador';");
		
			while($row = $this->query->fetch_assoc()){
				
					if(md5($row['dig_actuador'])==$dig_actuador){
						
						echo('d='.sha1($row['dig_actuador']));

						$this->query=mysqli_query($this->link,"SELECT pa.est_puerto_actuador FROM tb_actuador a INNER JOIN tb_puerto_actuador pa ON pa.id_actuador=a.id_actuador WHERE a.ipa_actuador='$ipa_actuador' AND pa.id_estado=1;");
						
						$i=1;
						
						while($row = $this->query->fetch_assoc()){
							echo('p'.$i.'='.$row['est_puerto_actuador']);
							$i++;
						}
						echo 'p';
				}
			}
		

	}
	
	
	
	function update_est_puerto_actuador(){
		
		$dig_sensor_get=$_GET['d']; //md5
		$ipa_sensor=$_SERVER['REMOTE_ADDR'];
		$p1=$_GET['p1'];
		$p2=$_GET['p2'];
		$p3=$_GET['p3'];
		$p4=$_GET['p4'];

		
		$this->query=mysqli_query($this->link,"SELECT dig_sensor FROM tb_sensor where ipa_sensor='$ipa_sensor';");
		
		
		while($row = $this->query->fetch_assoc()){
			$dig_sensor=$row['dig_sensor'];
			break;
			
		}
				
			if(md5($dig_sensor)==$dig_sensor_get){

				echo 'ok';
				

				$this->query=mysqli_query($this->link,"SELECT COUNT(*) AS count_sensor FROM  tb_puerto_sensor ps INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor'");
				
				while($row = $this->query->fetch_assoc()){
					$count_sensor=$row['count_sensor'];
					break;
				}
				
				if($count_sensor>=1){ /*P1 START*/
					
					$this->query=mysqli_query($this->link,"SELECT ps.id_puerto_sensor FROM  tb_puerto_sensor ps INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' ORDER BY ps.id_puerto_sensor ASC LIMIT 0,1;");
				
					while($row = $this->query->fetch_assoc()){
						$id_puerto_sensor=$row['id_puerto_sensor'];
						break;
					}

					
					$this->query=mysqli_query($this->link,"SELECT pa.est_puerto_actuador,ps.id_tipo_sensor,pa.id_tipo_actuador,ps.est_puerto_sensor FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor INNER JOIN tb_puerto_actuador pa ON mpaps.id_puerto_actuador=pa.id_puerto_actuador INNER JOIN tb_tipo_actuador ta ON  ta.id_tipo_actuador=pa.id_tipo_actuador WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor ORDER BY pa.id_puerto_actuador ASC LIMIT 0,1;");
						
						while($row = $this->query->fetch_assoc()){
							$est_puerto_actuador=$row['est_puerto_actuador'];
							$id_tipo_sensor=$row['id_tipo_sensor'];
							$id_tipo_actuador=$row['id_tipo_actuador'];
							$est_puerto_sensor=$row['est_puerto_sensor'];
							break;
						}

					
					
					if($_GET['p1']==255){ 
													
						if($id_tipo_actuador==1){


							if($est_puerto_actuador==0){

								$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=255 WHERE id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC);");


							}else{
									$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=0 WHERE id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC);");
							}

						}

						if($id_tipo_actuador==2){


							if($est_puerto_actuador==0){

								$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=255 WHERE id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC);");


							}else{
									$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=0 WHERE id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC);");
							}

						}


						if($id_tipo_actuador==3){
							
	
							if($est_puerto_sensor!=$p1){

								$this->query=mysqli_query($this->link,"UPDATE tb_puerto_sensor SET est_puerto_sensor=$p1 WHERE id_puerto_sensor=$id_puerto_sensor;");
								
								$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=255 WHERE pa.est_puerto_actuador != 9999 AND id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC);");
								sleep(1);
								$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=0 WHERE pa.est_puerto_actuador != 9999 AND id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC);");
								
								
							}							
						}


						if($id_tipo_actuador==4){
							
	
							if($est_puerto_sensor!=$p1){

								$this->query=mysqli_query($this->link,"UPDATE tb_puerto_sensor SET est_puerto_sensor=$p1 WHERE id_puerto_sensor=$id_puerto_sensor;");
								
								$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=255 WHERE id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC);");
								sleep(1);
								$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=0 WHERE id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC);");
								
								sleep(9);

								$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=255 WHERE id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC);");
								sleep(1);
								$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=0 WHERE id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC);");

								sleep(9);

								$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=255 WHERE id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC);");
								sleep(1);
								$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=0 WHERE id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC);");

							}							
						}


						if($id_tipo_actuador==5){
							
	
							if($est_puerto_sensor!=$p1){

								$this->query=mysqli_query($this->link,"UPDATE tb_puerto_sensor SET est_puerto_sensor=$p1 WHERE id_puerto_sensor=$id_puerto_sensor;");
								
								$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=255 WHERE id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC);");
								sleep(4);
								$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=0 WHERE id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC);");
								
								
							}							
						}

						
					}else if($_GET['p1']==0){ 
													
						if($id_tipo_actuador==1){

																
														
						}

						if($id_tipo_actuador==2){

																
														
						}


						if($id_tipo_actuador==3){
							
							if($est_puerto_sensor!=$p1){

								$this->query=mysqli_query($this->link,"UPDATE tb_puerto_sensor SET est_puerto_sensor=$p1 WHERE id_puerto_sensor=$id_puerto_sensor;");
								
								$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=255 WHERE pa.est_puerto_actuador != 9999 AND id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC);");
								sleep(1);
								$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=0 WHERE pa.est_puerto_actuador != 9999 AND id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC);");
								
							}							
						}


						if($id_tipo_actuador==4){
							
							if($est_puerto_sensor!=$p1){

								$this->query=mysqli_query($this->link,"UPDATE tb_puerto_sensor SET est_puerto_sensor=$p1 WHERE id_puerto_sensor=$id_puerto_sensor;");
								
								$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=255 WHERE id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC);");
								sleep(1);
								$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=0 WHERE id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC);");
								
								sleep(9);

								$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=255 WHERE id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC);");
								sleep(1);
								$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=0 WHERE id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC);");

								sleep(9);

								$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=255 WHERE id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC);");
								sleep(1);
								$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=0 WHERE id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC);");

							}							
						}


						if($id_tipo_actuador==5){
							
	
							if($est_puerto_sensor!=$p1){

								$this->query=mysqli_query($this->link,"UPDATE tb_puerto_sensor SET est_puerto_sensor=$p1 WHERE id_puerto_sensor=$id_puerto_sensor;");
								
								$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=255 WHERE id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC);");
								sleep(4);
								$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=0 WHERE id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC);");
								
								
							}							
						}

						
					}

					
					
					
						if($count_sensor>=2){ /*P2 START*/

							$this->query=mysqli_query($this->link,"SELECT ps.id_puerto_sensor FROM  tb_puerto_sensor ps INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' ORDER BY ps.id_puerto_sensor ASC LIMIT 1,1;");

							while($row = $this->query->fetch_assoc()){
								$id_puerto_sensor=$row['id_puerto_sensor'];
								break;
							}

							$this->query=mysqli_query($this->link,"SELECT pa.est_puerto_actuador,ps.id_tipo_sensor,pa.id_tipo_actuador,ps.est_puerto_sensor FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor INNER JOIN tb_puerto_actuador pa ON mpaps.id_puerto_actuador=pa.id_puerto_actuador INNER JOIN tb_tipo_actuador ta ON  ta.id_tipo_actuador=pa.id_tipo_actuador WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor ORDER BY pa.id_puerto_actuador ASC LIMIT 0,1;");
						
							while($row = $this->query->fetch_assoc()){
								$est_puerto_actuador=$row['est_puerto_actuador'];
								$id_tipo_sensor=$row['id_tipo_sensor'];
								$id_tipo_actuador=$row['id_tipo_actuador'];
								$est_puerto_sensor=$row['est_puerto_sensor'];
								break;
							}



							if($_GET['p2']==255){ 

								if($id_tipo_actuador==1){


									if($est_puerto_actuador==0){

										$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=255 WHERE id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC);");


									}else{
										$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=0 WHERE id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC);");
									}

								}

								if($id_tipo_actuador==2){


									if($est_puerto_actuador==0){

										$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=255 WHERE id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC);");


									}else{
										$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=0 WHERE id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC);");
									}

								}

								if($id_tipo_actuador==3){
							
	
									if($est_puerto_sensor!=$p2){

										$this->query=mysqli_query($this->link,"UPDATE tb_puerto_sensor SET est_puerto_sensor=$p2 WHERE id_puerto_sensor=$id_puerto_sensor;");
								
										$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=255 WHERE pa.est_puerto_actuador != 9999 AND id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC);");
										sleep(1);
										$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=0 WHERE pa.est_puerto_actuador != 9999 AND id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC);");

								
									}							
								}


								if($id_tipo_actuador==4){
							
	
									if($est_puerto_sensor!=$p2){

										$this->query=mysqli_query($this->link,"UPDATE tb_puerto_sensor SET est_puerto_sensor=$p2 WHERE id_puerto_sensor=$id_puerto_sensor;");
								
										$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=255 WHERE id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC);");
										sleep(1);
										$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=0 WHERE id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC);");

										sleep(9);

										$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=255 WHERE id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC);");
										sleep(1);
										$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=0 WHERE id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC);");

										sleep(9);

										$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=255 WHERE id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC);");
										sleep(1);
										$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=0 WHERE id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC);");

								
									}							
								}


								if($id_tipo_actuador==5){
							
	
									if($est_puerto_sensor!=$p2){

										$this->query=mysqli_query($this->link,"UPDATE tb_puerto_sensor SET est_puerto_sensor=$p2 WHERE id_puerto_sensor=$id_puerto_sensor;");
								
										$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=255 WHERE id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC);");
										sleep(4);
										$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=0 WHERE id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC);");

								
									}							
								}



						        }else if($_GET['p2']==0){ 
													
								if($id_tipo_actuador==1){
									
														
								}


								if($id_tipo_actuador==2){
									
														
								}


								if($id_tipo_actuador==3){
							
	
									if($est_puerto_sensor!=$p2){

										$this->query=mysqli_query($this->link,"UPDATE tb_puerto_sensor SET est_puerto_sensor=$p2 WHERE id_puerto_sensor=$id_puerto_sensor;");
								
										$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=255 WHERE pa.est_puerto_actuador != 9999 AND id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC);");
										sleep(1);
										$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=0 WHERE pa.est_puerto_actuador != 9999 AND id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC);");

								
									}							
								}



								if($id_tipo_actuador==4){
							
									if($est_puerto_sensor!=$p2){

										$this->query=mysqli_query($this->link,"UPDATE tb_puerto_sensor SET est_puerto_sensor=$p2 WHERE id_puerto_sensor=$id_puerto_sensor;");
								
										$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=255 WHERE id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC);");
										sleep(1);
										$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=0 WHERE id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC);");

										sleep(9);

										$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=255 WHERE id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC);");
										sleep(1);
										$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=0 WHERE id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC);");

										sleep(9);

										$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=255 WHERE id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC);");
										sleep(1);
										$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=0 WHERE id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC);");

									}							
								}


								if($id_tipo_actuador==5){
							
	
									if($est_puerto_sensor!=$p2){

										$this->query=mysqli_query($this->link,"UPDATE tb_puerto_sensor SET est_puerto_sensor=$p2 WHERE id_puerto_sensor=$id_puerto_sensor;");
								
										$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=255 WHERE id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC);");
										sleep(4);
										$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=0 WHERE id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC);");

								
									}							
								}

						
							}




							if($count_sensor>=3){ /*P3 START*/

								$this->query=mysqli_query($this->link,"SELECT ps.id_puerto_sensor FROM  tb_puerto_sensor ps INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' ORDER BY ps.id_puerto_sensor ASC LIMIT 2,1;");

								while($row = $this->query->fetch_assoc()){
									$id_puerto_sensor=$row['id_puerto_sensor'];
									break;
								}

								$this->query=mysqli_query($this->link,"SELECT pa.est_puerto_actuador,ps.id_tipo_sensor,pa.id_tipo_actuador,ps.est_puerto_sensor FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor INNER JOIN tb_puerto_actuador pa ON mpaps.id_puerto_actuador=pa.id_puerto_actuador INNER JOIN tb_tipo_actuador ta ON  ta.id_tipo_actuador=pa.id_tipo_actuador WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor ORDER BY pa.id_puerto_actuador ASC LIMIT 0,1;");
						
								while($row = $this->query->fetch_assoc()){
									$est_puerto_actuador=$row['est_puerto_actuador'];
									$id_tipo_sensor=$row['id_tipo_sensor'];
									$id_tipo_actuador=$row['id_tipo_actuador'];
									$est_puerto_sensor=$row['est_puerto_sensor'];
									break;
								}



								if($_GET['p3']==255){ 

									if($id_tipo_actuador==1){


										if($est_puerto_actuador==0){

											$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=255 WHERE id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC);");


										}else{
											$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=0 WHERE id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC);");
										}

									}

									if($id_tipo_actuador==2){


										if($est_puerto_actuador==0){

											$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=255 WHERE id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC);");

										}else{
											$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=0 WHERE id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC);");
										}

									}


									if($id_tipo_actuador==3){
							
	
										if($est_puerto_sensor!=$p3){

											$this->query=mysqli_query($this->link,"UPDATE tb_puerto_sensor SET est_puerto_sensor=$p3 WHERE id_puerto_sensor=$id_puerto_sensor;");
								
											$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=255 WHERE pa.est_puerto_actuador != 9999 AND id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC);");
											sleep(1);
											$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=0 WHERE pa.est_puerto_actuador != 9999 AND id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC);");

								
										}							
									}


									if($id_tipo_actuador==4){
							
	
										if($est_puerto_sensor!=$p3){

											$this->query=mysqli_query($this->link,"UPDATE tb_puerto_sensor SET est_puerto_sensor=$p3 WHERE id_puerto_sensor=$id_puerto_sensor;");
								
											$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=255 WHERE id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC);");
											sleep(1);
											$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=0 WHERE id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC);");

											sleep(9);

											$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=255 WHERE id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC);");
											sleep(1);
											$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=0 WHERE id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC);");

											sleep(9);

											$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=255 WHERE id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC);");
											sleep(1);
											$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=0 WHERE id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC);");

								
										}							
									}


									if($id_tipo_actuador==5){
							
	
										if($est_puerto_sensor!=$p3){

											$this->query=mysqli_query($this->link,"UPDATE tb_puerto_sensor SET est_puerto_sensor=$p3 WHERE id_puerto_sensor=$id_puerto_sensor;");
								
											$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=255 WHERE id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC);");
											sleep(4);
											$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=0 WHERE id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC);");

								
										}							
									}



							 	 }else if($_GET['p3']==0){ 
													
									if($id_tipo_actuador==1){
									
														
									}


									if($id_tipo_actuador==2){
									
														
									}


									if($id_tipo_actuador==3){
							
	
										if($est_puerto_sensor!=$p3){

											$this->query=mysqli_query($this->link,"UPDATE tb_puerto_sensor SET est_puerto_sensor=$p3 WHERE id_puerto_sensor=$id_puerto_sensor;");
								
											$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=255 WHERE pa.est_puerto_actuador != 9999 AND id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC);");
											sleep(1);
											$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=0 pa.est_puerto_actuador != 9999 AND WHERE id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC);");

								
										}							
									}



									if($id_tipo_actuador==4){
							
										if($est_puerto_sensor!=$p3){

											$this->query=mysqli_query($this->link,"UPDATE tb_puerto_sensor SET est_puerto_sensor=$p3 WHERE id_puerto_sensor=$id_puerto_sensor;");
								
											$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=255 WHERE id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC);");
											sleep(1);
											$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=0 WHERE id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC);");

											sleep(9);

											$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=255 WHERE id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC);");
											sleep(1);
											$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=0 WHERE id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC);");

											sleep(9);

											$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=255 WHERE id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC);");
											sleep(1);
											$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=0 WHERE id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC);");

										}							
									}


									if($id_tipo_actuador==5){
							
	
										if($est_puerto_sensor!=$p3){

											$this->query=mysqli_query($this->link,"UPDATE tb_puerto_sensor SET est_puerto_sensor=$p3 WHERE id_puerto_sensor=$id_puerto_sensor;");
								
											$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=255 WHERE id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC);");
											sleep(4);
											$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=0 WHERE id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC);");
							
										}							
									}

								}
								
								
								if($count_sensor>=4){ /*P4 START*/

									$this->query=mysqli_query($this->link,"SELECT ps.id_puerto_sensor FROM  tb_puerto_sensor ps INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' ORDER BY ps.id_puerto_sensor ASC LIMIT 3,1;");

									while($row = $this->query->fetch_assoc()){
										$id_puerto_sensor=$row['id_puerto_sensor'];
										break;
									}

									$this->query=mysqli_query($this->link,"SELECT pa.est_puerto_actuador,ps.id_tipo_sensor,pa.id_tipo_actuador,ps.est_puerto_sensor FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor INNER JOIN tb_puerto_actuador pa ON mpaps.id_puerto_actuador=pa.id_puerto_actuador INNER JOIN tb_tipo_actuador ta ON  ta.id_tipo_actuador=pa.id_tipo_actuador WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor ORDER BY pa.id_puerto_actuador ASC LIMIT 0,1;");
						
									while($row = $this->query->fetch_assoc()){
										$est_puerto_actuador=$row['est_puerto_actuador'];
										$id_tipo_sensor=$row['id_tipo_sensor'];
										$id_tipo_actuador=$row['id_tipo_actuador'];
										$est_puerto_sensor=$row['est_puerto_sensor'];
										break;
									}


									if($_GET['p4']==255){ 

										if($id_tipo_actuador==1){

											if($est_puerto_actuador==0){

												$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=255 WHERE id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC);");

											}else{
												$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=0 WHERE id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC);");
											}

										}

										if($id_tipo_actuador==2){


											if($est_puerto_actuador==0){

												$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=255 WHERE id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC);");


											}else{
												$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=0 WHERE id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC);");
											}

										}

										if($id_tipo_actuador==3){
							
	
											if($est_puerto_sensor!=$p4){

												$this->query=mysqli_query($this->link,"UPDATE tb_puerto_sensor SET est_puerto_sensor=$p4 WHERE id_puerto_sensor=$id_puerto_sensor;");
								
												$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=255 WHERE pa.est_puerto_actuador != 9999 AND id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC);");
												sleep(1);
												$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=0 WHERE pa.est_puerto_actuador != 9999 AND id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC);");

								
											}							
										}


									if($id_tipo_actuador==4){
							
	
										if($est_puerto_sensor!=$p4){

											$this->query=mysqli_query($this->link,"UPDATE tb_puerto_sensor SET est_puerto_sensor=$p4 WHERE id_puerto_sensor=$id_puerto_sensor;");
								
											$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=255 WHERE id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC);");
											sleep(1);
											$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=0 WHERE id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC);");

											sleep(9);

											$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=255 WHERE id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC);");
											sleep(1);
											$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=0 WHERE id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC);");

											sleep(9);

											$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=255 WHERE id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC);");
											sleep(1);
											$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=0 WHERE id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC);");

								
										}							
									}


									if($id_tipo_actuador==5){
							
	
										if($est_puerto_sensor!=$p4){

											$this->query=mysqli_query($this->link,"UPDATE tb_puerto_sensor SET est_puerto_sensor=$p4 WHERE id_puerto_sensor=$id_puerto_sensor;");
								
											$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=255 WHERE id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC);");
											sleep(4);
											$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=0 WHERE id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC);");

								
										}							
									}



								  }else if($_GET['p4']==0){ 
													
									if($id_tipo_actuador==1){
									
														
									}


									if($id_tipo_actuador==2){
									
														
									}


									if($id_tipo_actuador==3){
							
	
										if($est_puerto_sensor!=$p4){

											$this->query=mysqli_query($this->link,"UPDATE tb_puerto_sensor SET est_puerto_sensor=$p4 WHERE id_puerto_sensor=$id_puerto_sensor;");
								
											$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=255 WHERE pa.est_puerto_actuador != 9999 AND id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC);");
											sleep(1);
											$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=0 WHERE pa.est_puerto_actuador != 9999 AND id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC);");

								
										}							
									}



									if($id_tipo_actuador==4){
							
										if($est_puerto_sensor!=$p4){

											$this->query=mysqli_query($this->link,"UPDATE tb_puerto_sensor SET est_puerto_sensor=$p4 WHERE id_puerto_sensor=$id_puerto_sensor;");
								
											$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=255 WHERE id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC);");
											sleep(1);
											$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=0 WHERE id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC);");

											sleep(9);

											$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=255 WHERE id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC);");
											sleep(1);
											$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=0 WHERE id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC);");

											sleep(9);

											$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=255 WHERE id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC);");
											sleep(1);
											$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=0 WHERE id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC);");

										}							
									}


									if($id_tipo_actuador==5){
							
	
										if($est_puerto_sensor!=$p4){

											$this->query=mysqli_query($this->link,"UPDATE tb_puerto_sensor SET est_puerto_sensor=$p4 WHERE id_puerto_sensor=$id_puerto_sensor;");
								
											$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=255 WHERE id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC);");
											sleep(4);
											$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=0 WHERE id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC);");
							
										}							
									}

								}

									
									
									
									



						  	  }/*P4 END*/





						  }/*P3 END*/




					}/*P2 END*/
					
					
					
					
				}/*P1 END*/
				
						
			}
						
						
						
					/*
						
						
						$p1=$_GET['p1='];
						$p2=$_GET['p2='];
						$p3=$_GET['p3='];
						$p4=$_GET['p4='];
						
						
						
						if($_GET['p1='])
						
						

						$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=1 WHERE id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador_puerto_sensor FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_estado=1 AND s.id_estado=1);");
						
						$i=1;
						
						while($row = $this->query->fetch_assoc()){
							echo('p'.$i.'='.$row['est_puerto_actuador']);
							$i++;
						}
						*/
					
				
	}
		
		
	
	
}
?>
