<?php
class mysql{
	
	public $link;
	public $query;
	
	function __construct(){
		
		$host='127.0.0.1';
		$user='iot-server';
		$password='P@ssw0rd';
		$db='iot';

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

						$this->query=mysqli_query($this->link,"SELECT pa.est_puerto_actuador FROM tb_actuador a INNER JOIN tb_puerto_actuador pa ON pa.id_actuador=a.id_actuador WHERE a.ipa_actuador='$ipa_actuador';");
						
						$i=1;
						
						while($row = $this->query->fetch_assoc()){
							echo('p'.$i.'='.$row['est_puerto_actuador']);
							$i++;
						}
					
				}
			}
		

	}
	
	
	
	function update_est_puerto_actuador(){
		
		$dig_sensor_get=$_GET['d']; //md5
		$ipa_sensor=$_SERVER['REMOTE_ADDR'];
		
		$this->query=mysqli_query($this->link,"SELECT dig_sensor FROM tb_sensor where ipa_sensor='$ipa_sensor';");
		
		
		while($row = $this->query->fetch_assoc()){
			$dig_sensor=$row['dig_sensor'];
			break;
			
		}
				
			if(md5($dig_sensor)==$dig_sensor_get){
				
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
					
					
					if($_GET['p1']==255){ 
						
						$this->query=mysqli_query($this->link,"SELECT pa.est_puerto_actuador,ps.id_tipo_sensor FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor INNER JOIN tb_puerto_actuador pa ON mpaps.id_puerto_actuador=pa.id_puerto_actuador WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor ORDER BY pa.id_puerto_actuador ASC LIMIT 0,1;");
						
						while($row = $this->query->fetch_assoc()){
							$est_puerto_actuador=$row['est_puerto_actuador'];
							$id_tipo_sensor=$row['id_tipo_sensor'];
							break;
						}
						
						
							
						if($id_tipo_sensor==1){
							
								
							if($est_puerto_actuador==0){
								
								$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=255 WHERE id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC);");
								
								
							}else{
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


							if($_GET['p2']==255){ 

								$this->query=mysqli_query($this->link,"SELECT pa.est_puerto_actuador,ps.id_tipo_sensor FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor INNER JOIN tb_puerto_actuador pa ON mpaps.id_puerto_actuador=pa.id_puerto_actuador WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor ORDER BY pa.id_puerto_actuador ASC LIMIT 0,1;");

								while($row = $this->query->fetch_assoc()){
									$est_puerto_actuador=$row['est_puerto_actuador'];
									$id_tipo_sensor=$row['id_tipo_sensor'];
									break;
								}



								if($id_tipo_sensor==1){


									if($est_puerto_actuador==0){

										$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=255 WHERE id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC);");


									}else{
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


								if($_GET['p3']==255){ 

									$this->query=mysqli_query($this->link,"SELECT pa.est_puerto_actuador,ps.id_tipo_sensor FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor INNER JOIN tb_puerto_actuador pa ON mpaps.id_puerto_actuador=pa.id_puerto_actuador WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor ORDER BY pa.id_puerto_actuador ASC LIMIT 0,1;");

									while($row = $this->query->fetch_assoc()){
										$est_puerto_actuador=$row['est_puerto_actuador'];
										$id_tipo_sensor=$row['id_tipo_sensor'];
										break;
									}



									if($id_tipo_sensor==1){


										if($est_puerto_actuador==0){

											$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=255 WHERE id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC);");


										}else{
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


									if($_GET['p4']==255){ 

										$this->query=mysqli_query($this->link,"SELECT pa.est_puerto_actuador,ps.id_tipo_sensor FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor INNER JOIN tb_puerto_actuador pa ON mpaps.id_puerto_actuador=pa.id_puerto_actuador WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor ORDER BY pa.id_puerto_actuador ASC LIMIT 0,1;");

										while($row = $this->query->fetch_assoc()){
											$est_puerto_actuador=$row['est_puerto_actuador'];
											$id_tipo_sensor=$row['id_tipo_sensor'];
											break;
										}



										if($id_tipo_sensor==1){


											if($est_puerto_actuador==0){

												$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=255 WHERE id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC);");


											}else{
												$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=0 WHERE id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC);");
											}

										}

								  }
									
									
									
									if($count_sensor>=5){ /*P5 START*/

										$this->query=mysqli_query($this->link,"SELECT ps.id_puerto_sensor FROM  tb_puerto_sensor ps INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' ORDER BY ps.id_puerto_sensor ASC LIMIT 4,1;");

										while($row = $this->query->fetch_assoc()){
											$id_puerto_sensor=$row['id_puerto_sensor'];
											break;
										}


										if($_GET['p5']==255){ 

											$this->query=mysqli_query($this->link,"SELECT pa.est_puerto_actuador,ps.id_tipo_sensor FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor INNER JOIN tb_puerto_actuador pa ON mpaps.id_puerto_actuador=pa.id_puerto_actuador WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor ORDER BY pa.id_puerto_actuador ASC LIMIT 0,1;");

											while($row = $this->query->fetch_assoc()){
												$est_puerto_actuador=$row['est_puerto_actuador'];
												$id_tipo_sensor=$row['id_tipo_sensor'];
												break;
											}



											if($id_tipo_sensor==1){


												if($est_puerto_actuador==0){

													$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=255 WHERE id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC);");


												}else{
													$this->query=mysqli_query($this->link,"UPDATE tb_puerto_actuador pa SET pa.est_puerto_actuador=0 WHERE id_puerto_actuador IN (SELECT mpaps.id_puerto_actuador FROM tb_puerto_actuador_puerto_sensor mpaps INNER JOIN tb_puerto_sensor ps ON mpaps.id_puerto_sensor=ps.id_puerto_sensor INNER JOIN tb_sensor s ON ps.id_sensor=s.id_sensor WHERE s.ipa_sensor='$ipa_sensor' AND ps.id_puerto_sensor=$id_puerto_sensor AND ps.id_estado=1 AND s.id_estado=1 ORDER BY mpaps.id_puerto_actuador ASC);");
												}

											}

									  }




								  }/*P4 END*/




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