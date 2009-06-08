<?php
  /**
  * @desc Classe para projetar o tempo (data final e hora)
  * @author Elaine Watanabe , Rodrigo Morales
  * @version $Id$
  * @since $Date$
  */
  class ManipulaDataHora{
       
     Function __construct(){
          
     }
     
     /**
     * @desc Valida valor para data
     * @param date $Data (formtato Y-m-d)
     */
     Function ValidaValorData($Data){
        
        ### DIVIDE UMA DATA EM UM ARRAY ##
        $DataArray = explode('-', $Data);
        
        ### VERIFICA SE A DATA É VÁLIDA ##
        $DataVerifica = checkdate($DataArray[1], $DataArray[0], $DataArray[2]);
        
        return $DataVerifica;     
     }
     
     
     /**
     * @desc Valida Formato da data
     * @param data $Date 
     */
     Function ValidaFormatoData($Data){
     
        ### Caso seja informado uma data do MySQL do tipo DATETIME - aaaa-mm-dd 00:00:00 ##
        ### Transforma para DATE - aaaa-mm-dd ##
        $Data = substr($Data,0,10); 

        ### Se a data estiver no formato brasileiro: dd/mm/aaaa ##
        if ( preg_match("@/@",$Data) == 1 ) {
            
          ### Converte-a para o padrão americano: aaaa-mm-dd ##
          $Data = implode("-", array_reverse(explode("/",$Data)));

        }
               
        
        return $Data;
               
     }
     
     /**
     * @desc Verifica o dia da semana ao qual a data pertence
     * @param date $Data (Y-m-d)     
     */
     Function VerificaDiaSemana($Data){
     
        ### VERIFICA DATA ##
        if($Data){
     
          ### DIVIDE EM SUBSTRINGS ##
          $ano =  substr("$Data", 0, 4);
          $mes =  substr("$Data", 5, -3);
          $dia =  substr("$Data", 8, 9);
          
          ### VERIFICA O DIA DA SEMANA ##
          $diasemana = date("w", mktime(0,0,0,$mes,$dia,$ano) );
         
         ## OUTRA FORMA ##
         // $diasemana = strftime('%w',strtotime($Data));
		
          return $diasemana;  
		  
		}else{
		    
            ### RETORNA UM VALOR INVÁLIDO PARA DIA DA SEMANA ##
			return -1;
		}
     }
     
     /**
     * @desc Verifica o dia da semana por extenso
     * @param date $DiaSemana (Y-m-d)
     */
     Function VerificaDiaSemanaExtenso($DiaSemana){
          
          ### VERIFICA O DIA DA SEMANA (INTEIRO) ##
          $DiaSemana = $this->VerificaDiaSemana($DiaSemana);
     
          
          switch($DiaSemana) {
			 case"-1": 	$DiaSemana = "Inválido"; 	break;
             case"0": 	$DiaSemana = "Domingo";       break;
             case"1": 	$DiaSemana = "Segunda-Feira"; break;
             case"2": 	$DiaSemana = "Terça-Feira";   break;
             case"3": 	$DiaSemana = "Quarta-Feira";  break;
             case"4": 	$DiaSemana = "Quinta-Feira";  break;
             case"5": 	$DiaSemana = "Sexta-Feira";   break;
             case"6": 	$DiaSemana = "Sábado";        break;
         }
        
          return $DiaSemana;
     }
     
     /**
     * @desc Verifica se uma hora pertence a um determinado intervalo
     * @param time $HoraTeste
     * @param time $HoraIni
     * @param time $HoraFim
     */
     Function VerificaIntervalo($HoraTeste, $HoraIni, $HoraFim){
    
          
          ### CONVERTE VALORES PARA TIMESTAMP ##
          $TmHoraTeste = strtotime($HoraTeste);
          $TmHoraIni = strtotime($HoraIni);
          $TmHoraFim = strtotime($HoraFim);
         
         ### VERIFICA SE HORATESTE PERTENCE AO INTERVALO ##
          if (($TmHoraIni <= $TmHoraTeste) && ($TmHoraTeste <$TmHoraFim) ){
                  return 0; // OK
          }else{
             
               if ($TmHoraTeste < $TmHoraIni)  {
                    return -1; // Um intervalo antes
              }else{
                  
                  if ($TmHoraFim <= $TmHoraTeste)  {
                    return 1; // Um intervalo depois
                  }
                 
              }
          }
         
         
       }

     
       /**
       * @desc Função para somar datas em dias
       * @param date $Data (formato Y-m-d)
       * @param int $Dias
       */
       Function SomaData($Data,$Dias) {
       
            $Data = str_replace("-","",$Data);
            $Ano = substr ( $Data, 0, 4 );
            $Mes = substr ( $Data, 4, 2 );
            $Dia = substr ( $Data, 6, 2 );
            $NovaData = mktime ( 0, 0, 0, $Mes, $Dia + $Dias, $Ano );
            
            return strftime("%Y-%m-%d", $NovaData);
       }
       
       /**
       * @desc Função para subtrair datas em dias
       * @param date $Data (no formato Y-m-d)
       * @param int $Dias 
       */
       Function SubtraiData($Data,$Dias) {
          
          $Data = str_replace("-","",$Data);
          $Ano = substr ( $Data, 0, 4 );
          $Mes = substr ( $Data, 4, 2 );
          $Dia = substr ( $Data, 6, 2 );
          $NovaData = mktime ( 0, 0, 0, $Mes, $Dia - $Dias, $Ano );
          
          return strftime("%Y-%m-%d", $NovaData);
       }
       
       /**
       * @desc Verifica a diferença entre intervalos de tempo
       * @param time $Time1
       * @param time $Time2
       * @param char $TipoIntervalo
       */
       Function DiferencaTempo($Time1, $Time2, $TipoIntervalo){
          
          $Q = 1;
          
          ### TIPO DE VALOR ( MES/DIA/HORA/MINUTO) ##
          switch($TipoIntervalo){
               
               case 'm': $Q *= 30; // mes
               case 'd': $Q *= 24; // dia
               case 'h': $Q *= 60; // hora
               case 'i': $Q *= 60; // minuto
              
               
          }
          
          ### CALCULA A DIFERENCA ##
          $Diferenca = intval((strtotime($Time2) - strtotime($Time1)) / $Q);
          
          if ($Diferenca < 0) $Diferenca *=-1;
          
          return $Diferenca;
       
       }
       
       /**
       * @desc Soma minutos a uma hora
       * @param time  $Hora
       * @param time (minutes) $MinutosSomar
       */
       Function SomaHora($Hora, $MinutosSomar){
          
          $retorno = date("H:i",strtotime("$Hora + $MinutosSomar minutes"));
         
          return $retorno;
       }
       
       /**
       * @desc Subtrai minutos de uma hora
       * @param time  $Hora
       * @param time (minutes) $MinutosSubtrair
       */
       Function SubtraiHora ($Hora, $MinutosSubtrair){
  
          ### SUBTRAI MINUTOS DE UMA HORA ##
          $retorno = date("H:i",strtotime("$Hora - $MinutosSubtrair minutes"));

          return $retorno;

       }
       
            
     /**
     * @desc Compara tempo
     * @param time $Time1
     * @param time $Time2
     */
     Function ComparaTempo($Time1, $Time2){
        
          $Time1 = strtotime($Time1); 
          $Time2 = strtotime($Time2); 
          
        
          if ($Time1 == $Time2) { 
                  return 0; // Igual
          }else{
           
              if ($Time1 < $Time2)  {
                    return -1; // Um intervalo antes
              }else{
                   
                  if ($Time1 > $Time2)  {
                    return 1; // Um intervalo depois
                  }
                  
              }
          }
          
          
     }


  }
?>
