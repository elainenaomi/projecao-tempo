<?php

       include "class_ManipulaDataHora.php";
       include "class_ProjecaoTempo.php";
       include "feriados.php";
       
	 ########################################
     # PERÍODO ÚTIL
     ########################################
 

     $PeriodoUtil[1][0]->hora_ini = '08:00';
     $PeriodoUtil[1][0]->hora_fim = '12:00';
     $PeriodoUtil[1][1]->hora_ini = '13:00';
     $PeriodoUtil[1][1]->hora_fim = '17:00';
    
     $PeriodoUtil[2][0]->hora_ini = '08:00';
     $PeriodoUtil[2][0]->hora_fim = '12:00';
     $PeriodoUtil[2][1]->hora_ini = '13:00';
     $PeriodoUtil[2][1]->hora_fim = '17:00';
    
     $PeriodoUtil[3][0]->hora_ini = '08:00';
     $PeriodoUtil[3][0]->hora_fim = '12:00';
     $PeriodoUtil[3][1]->hora_ini = '13:00';
     $PeriodoUtil[3][1]->hora_fim = '17:00';
    
     $PeriodoUtil[4][0]->hora_ini = '08:00';
     $PeriodoUtil[4][0]->hora_fim = '12:00';
     $PeriodoUtil[4][1]->hora_ini = '13:00';
     $PeriodoUtil[4][1]->hora_fim = '17:00';
    
     $PeriodoUtil[5][0]->hora_ini = '08:00';
     $PeriodoUtil[5][0]->hora_fim = '12:00';
     $PeriodoUtil[5][1]->hora_ini = '13:00';
     $PeriodoUtil[5][1]->hora_fim = '17:00';
     
     ########################################
     # HORA ADICIONAL
     ########################################

     
     ###### PRIMEIRA SEMANA DE JUNHO - SEM HORA ADD
     
     ##### SEGUNDA SEMANA DE JUNHO - HORAS ADD E FERIADO
     
     ### DIA 08/06
        $DiaHoraAdicional["2009-06-08"][0]->hora_ini = '07:00';
        $DiaHoraAdicional["2009-06-08"][0]->hora_fim = '08:00';
     
     ### DIA 09/06
        $DiaHoraAdicional["2009-06-09"][0]->hora_ini = '12:00';
        $DiaHoraAdicional["2009-06-09"][0]->hora_fim = '13:00';
     
     ### DIA 10/06
        $DiaHoraAdicional["2009-06-10"][0]->hora_ini = '17:00';
        $DiaHoraAdicional["2009-06-10"][0]->hora_fim = '18:00';        
     
     ### DIA 11/06 - FERIADO
       
       $DiaHoraAdicional["2009-06-11"][0]->hora_ini = '08:00';
       $DiaHoraAdicional["2009-06-11"][0]->hora_fim = '12:00';
       $DiaHoraAdicional["2009-06-11"][1]->hora_ini = '13:00';
       $DiaHoraAdicional["2009-06-11"][1]->hora_fim = '17:00';
       
       
     ### DIA 12/06
       
       $DiaHoraAdicional["2009-06-12"][0]->hora_ini = '07:00';
       $DiaHoraAdicional["2009-06-12"][0]->hora_fim = '08:00';
       $DiaHoraAdicional["2009-06-12"][1]->hora_ini = '12:00';
       $DiaHoraAdicional["2009-06-12"][1]->hora_fim = '13:00';
       $DiaHoraAdicional["2009-06-12"][2]->hora_ini = '17:00';
       $DiaHoraAdicional["2009-06-12"][2]->hora_fim = '18:00';
       

       ###############################
       ### FERIADOS 
       ###############################
       
       $FeriadosFixos = array("01-01", "04-21", "05-01","07-09", "07-16", "09-07", "10-12", "11-02", "11-15", "12-24", "12-25", "12-31");
       $feriados = feriados($FeriadosFixos);
       
       
       
       $ProjecaoTempo = new ProjecaoTempo();
       
   //    print_r($ProjecaoTempo->MesclaIntervalo($PeriodoUtil[1], $DiaHoraAdicional["2009-06-08"]));        
        
        
   
        
        $data = "2009-06-01";
        $hora = "07:00";
        $id = 1;
        
      
        echo "INICIO - 01/06 - 7h <br><br>";

        $retorno = $ProjecaoTempo ->CalculaDataFinal($data,$hora, 60, $PeriodoUtil, $feriados, $DiaHoraAdicional);
        echo "<br>$id - ".$retorno[Data]; 
        $id++;
        
        
        $retorno = $ProjecaoTempo ->CalculaDataFinal($data,$hora, 120, $PeriodoUtil, $feriados, $DiaHoraAdicional);
        echo "<br>$id - ".$retorno[Data]; 
        $id++;
        
        $retorno = $ProjecaoTempo ->CalculaDataFinal($data,$hora, 240, $PeriodoUtil, $feriados, $DiaHoraAdicional);
        echo "<br>$id - ".$retorno[Data]; 
        $id++;
        
        $retorno = $ProjecaoTempo ->CalculaDataFinal($data,$hora, 250, $PeriodoUtil, $feriados, $DiaHoraAdicional);
        echo "<br>$id - ".$retorno[Data]; 
        $id++;
        
        $retorno = $ProjecaoTempo ->CalculaDataFinal($data,$hora, 300, $PeriodoUtil, $feriados, $DiaHoraAdicional);
        echo "<br>$id - ".$retorno[Data]; 
        $id++;     
        
        $retorno = $ProjecaoTempo ->CalculaDataFinal($data,$hora, 330, $PeriodoUtil, $feriados, $DiaHoraAdicional);
        echo "<br>$id - ".$retorno[Data]; 
        $id++;         
        
        $retorno = $ProjecaoTempo ->CalculaDataFinal($data,$hora, 480, $PeriodoUtil, $feriados, $DiaHoraAdicional);
        echo "<br>$id - ".$retorno[Data]; 
        $id++;  
        
        $retorno = $ProjecaoTempo ->CalculaDataFinal($data,$hora, 481, $PeriodoUtil, $feriados, $DiaHoraAdicional);
        echo "<br>$id - ".$retorno[Data]; 
        $id++;  
        
        $retorno = $ProjecaoTempo ->CalculaDataFinal($data,$hora, 960, $PeriodoUtil, $feriados, $DiaHoraAdicional);
        echo "<br>$id - ".$retorno[Data]; 
        $id++;  
         
        $retorno = $ProjecaoTempo ->CalculaDataFinal($data,$hora, 1921, $PeriodoUtil, $feriados, $DiaHoraAdicional);
        echo "<br>$id - ".$retorno[Data]; 
        $id++;  
        
        $retorno = $ProjecaoTempo ->CalculaDataFinal($data,$hora, 2400, $PeriodoUtil, $feriados, $DiaHoraAdicional);
        echo "<br>$id - ".$retorno[Data]; 
        $id++;  
        
        $retorno = $ProjecaoTempo ->CalculaDataFinal($data,$hora, 2880, $PeriodoUtil, $feriados, $DiaHoraAdicional);
        echo "<br>$id - ".$retorno[Data]; 
        $id++;  
       
        $retorno = $ProjecaoTempo ->CalculaDataFinal($data,$hora, 3360, $PeriodoUtil, $feriados, $DiaHoraAdicional);
        echo "<br>$id - ".$retorno[Data]; 
        $id++;  
        
     
        $retorno = $ProjecaoTempo ->CalculaDataFinal($data,$hora, 4500, $PeriodoUtil, $feriados, $DiaHoraAdicional);
        echo "<br>$id - ".$retorno[Data]; 
        $id++;  
        
        $retorno = $ProjecaoTempo ->CalculaDataFinal($data,$hora, 5160, $PeriodoUtil, $feriados, $DiaHoraAdicional);
        echo "<br>$id - ".$retorno[Data]; 
        $id++;  
     
     
       
     
       
?>
