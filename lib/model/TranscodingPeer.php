<?php

/**
 * Subclass for performing query and update operations on the 'transcoding' table.
 *
 *
 * @author Ruben Gonzalez Gonzalez
 * @author rubenrua@uvigo.es
 * @copyright Copyright (c) Universidad de Vigo
 * @version 1
 *
 * @package lib.model
 */ 
class TranscodingPeer extends BaseTranscodingPeer
{
  /**
   *
   * Diferentes valores para estado
   *
   */
  const STATUS_ERROR = -1;
  const STATUS_PAUSADO = 0;
  const STATUS_ESPERANDO = 1;
  const STATUS_EJECUTANDOSE = 2;
  const STATUS_FINALIZADO = 3;
  const STATUS_ERROR_TR = -51;
  const STATUS_PAUSADO_TR = -50;
  const STATUS_ESPERANDO_TR = -49;
  const STATUS_EJECUTANDOSE_TR = -48;
  const STATUS_FINALIZADO_TR = -47;
  

  /**
   *
   *
   *
   */
  public static function getTranscodingsFromMm($id, $all = true)
  {
    $criteria = new Criteria();
    $criteria->add(TranscodingPeer::MM_ID, $id);
    if (!$all) $criteria->add(TranscodingPeer::STATUS_ID, TranscodingPeer::STATUS_FINALIZADO, Criteria::NOT_EQUAL);

    return TranscodingPeer::doSelect($criteria);
  }

  /**
   *
   *
   *
   */
  public static function getTranscodingsFromMmAndPerfil($mm_id, $perfil_id)
  {
    $criteria = new Criteria();
    $criteria->add(TranscodingPeer::MM_ID, $mm_id);
    $criteria->add(TranscodingPeer::PERFIL_ID, $perfil_id);

    /*Solo ejecutandose o parados*/
    $criteria->add(TranscodingPeer::STATUS_ID, array(0, 1, 2), Criteria::IN);

    return TranscodingPeer::doSelectOne($criteria);
  }



  /**
   *
   *
   *
   */
  public static function getNext()
  {
    $c = new Criteria();
    $c->add(TranscodingPeer::STATUS_ID, TranscodingPeer::STATUS_ESPERANDO);
    $c->addDescendingOrderByColumn(TranscodingPeer::PRIORITY);
    
    return TranscodingPeer::doSelectOne($c);
  }


  /**
   *
   *
   *
   */
  public static function execNext()
  {
    //MIRO SI EXISTEN PROCESOS BLOQ
    //TranscodingPeer::checkBloq();

    //MIRO SI HAY CPU LIBRE Y PROCESO EN COLA
    $cpu_free = CpuPeer::getFree();
    $next = TranscodingPeer::getNext();
    if (($cpu_free)&&($next)&&($cpu_free->isActive())){
      //CAMBIO ESTADO Y ASIGNP CPU  (HACER UNA TRANSACION)
      $next->setCpuId($cpu_free->getId());
      $next->setTimestart('now');
      $next->setStatusId(TranscodingPeer::STATUS_EJECUTANDOSE);
      $next->save();
      
      //EJECUTO
      $command = 'php ' . sfConfig::get('sf_bin_dir') . '/transcoder/finalizado.php ' . $next->getId();
      //shell_exec("nohup $command 1> /tmp/1-" . $next->getId() . ".txt 2> /tmp/2-" . $next->getId() . ".txt  & echo $!");
      shell_exec("nohup $command 1> /dev/null 2> /dev/null & echo $!");

      return $next;
    }else{      
      return null;
    }
  }
  
  
  /**
   *
   *
   *
   */
  public static function checkBloq()
  {
    $criteria = new Criteria();
    $criteria->add(TranscodingPeer::STATUS_ID, TranscodingPeer::STATUS_EJECUTANDOSE);
    $jobs = TranscodingPeer::doSelect($criteria);

    foreach($jobs as $job) {
      if((time() - $job->getTimestart(null)) > 86400) {  //Mas de 1 dia = 86400;
	//Mail.
	$mail = new sfMail();
	$mail->initialize();
	$mail->setMailer('sendmail');
	$mail->setCharset('utf-8');
	
	$mail->setSender(sfConfig::get('app_info_mail'), 'Email automatico');
	$mail->setFrom(sfConfig::get('app_info_mail'), 'Email automatico');
	
	$mail->addAddress($trans->getEmail());
	
	$mail->setSubject('Tarea Bloqueada [' . $job->getId(). ']');
	$mail->setBody("Id:" . $job->getId() . "\n Perfil:" . $job->getPerfil()->getName() . "\n");
	$mail->send();

	//Change State.
	$job->setStatusId(TranscodingPeer::STATUS_ERROR); 
	$job->save();
	TranscodingPeer::execNext();
      }
    }
  }



  /**
   *
   *
   */
  public static function search_error($tipo_perfil, $var, $duration_before = 0, $duration_after = 0){
    //Comprueba si hay error en el fichero de salida
    //echo '<br /> var: '.$var.' tipo_perfil '.$tipo_perfil.' id '.$id;

    $duration_conf = 3;
    $complete = true;
    
    if ($duration_after < $duration_before - $duration_conf ){
      $complete = false;
    }

    
    if ($tipo_perfil == 'cscript'){
      if ((strpos($var, '100%')== false)&&(strpos($var, 'Encoding Completed') == false)){
	$complete = false;
      }
    }

    if ($tipo_perfil == 'vdub'){
      if (strpos($var, "Ending operation.") == false){
    	$complete = false;
      }
    }

   /*
    if (($tipo_perfil == 'copy')){
      if ((strpos($var, "1 archivos copiados.") == false)&&(strpos($var, "1 file(s) copied.") == false)){
	$complete = false;
      }
    }    
    
*/
    $trans = null;

    if($trans != null){
      $relIni = 0;
      $relEnd = 0;
      if (file_exists($trans->getPathIni())){
        $movie = new ffmpeg_movie($trans->getPathIni(), false);
        if(!$movie){
          $complete = false;
        }else{
          $relIni = $movie->getFrameWidth();
        }
      }

      if (file_exists($trans->getPathEnd())){
        $movie = new ffmpeg_movie($trans->getPathEnd(), false);
        if (!$movie){
          $complete = false;
        }else{
          $relEnd = $movie->getFrameWidth();
        }
      }

      if(($relEnd == 0)&&($relIni != 0)){//Si solo saca audo
        $complete = false;
      }
    }
    

    return($complete);
  }
  
}


