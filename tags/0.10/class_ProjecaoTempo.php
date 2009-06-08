<?php
  /**
  * @desc Classe para projetar o tempo (data final e hora)
  * @author Elaine Watanabe , Rodrigo Morales
  * @version $Id$
  * @since $Date$
  */
  class ProjecaoTempo extends ManipulaDataHora{
       
        ### REFERENTE A MINUTOS ##
		public $SaldoMinuto = "";
        public $MinutoTotal = "";
        
        ### REFERENTE A DATAS ##
		public $DataValida = "";
		public $DataIni = "";
        public $ProximaData = "";
       // public $DataIntervalo = "";
        public $DataFinal = "";
        public $DiaSemana = "";
        
        ### REFERENTE A HORAS ##
		public $HoraIni = "";
		public $HoraFinal = "";
        public $HoraIniIntervalo = "";
        
        
        ### ARRAY - INTERVALOS DE TEMPO ##
        public $PeriodoValido = "";
		public $DiaHoraAdicional = "";
        public $Feriado = "";
        public $IntervaloDia = "";
        
        
        ### ID DOS ARRAYS ##
        public $IdIntervalo = "";
		
       
       
		Function __construct(){


		}
       
       
		/**
		* @desc Determina a data final baseada na data inicial e períodos válidos (úteis)
		* @param date $DataIni Data em formato YYYY-mm-dd ou dd/mm/AAAA
		* @param time $HoraIni Hora em formato HH:mm
		* @param int $MinutoTotal Minutos total a adicionar na data
		* @param array $PeriodoValido Periodos fixos contabilizados por dia da semana no formato $Array[D] onde D(int) é o dia da semana sendo 0 = domingo 
		* @param array $Feriado Feriados no formato dd-mm
		* @param array $DiaHoraAdicional Data/Horas válidas que não constam em $PeriodoValido
	    */
		Function CalculaDataFinal($DataIni, $HoraIni, $MinutoTotal, $PeriodoValido, $Feriado,$DiaHoraAdicional){

               ### ARRAY - INTERVALOS DE TEMPO ##
               $this->DiaHoraAdicional = $DiaHoraAdicional; 
               $this->PeriodoValido = $PeriodoValido;
               $this->Feriado = $Feriado;
               
               ### REFERENTE A DATAS ##
               $this->DataIni = $this->ValidaFormatoData($DataIni); ### RETORNA DATA Y-m-d ##
               $this->ProximaData = $this->DataIni;
               
               ### REFERENTE A MINUTOS ##
               $this->MinutoTotal = $MinutoTotal;
               $this->SaldoMinuto = $this->MinutoTotal; 
       
               ### REFERENTE A HORAS ##
               $this->HoraIni = $HoraIni;
               $this->HoraIniIntervalo = $this->HoraIni;
               
               ### REFERENTE AOS IDS DOS ARRAYS ##
               $this->IdIntervalo = 0;
               
 
 
               ### VERIFICA O SALDO DE MINUTOS POR DIA ##              
			   while($this->SaldoMinuto > 0){  
                    
                   ### VERIFICA SE INFORMAÇÕES SOBRE O DIA E HORA SÃO VÁLIDAS ##
                   $this->VerificaDataHora();

                    ### ATUALIZA O SALDO DE MINUTOS SUBTRAINDO OS PERIODOS DENTRO DE UM DIA ##
					$this->SaldoIntervaloDia();
					 
					 
                    ### DETERMINA A PROXIMA DATA (VÁLIDA OU NÃO) ##
                    $this->DeterminaProximoDia();
                
                    

				}
                
                $retorno[Data] = date('Y-m-d',strtotime($this->DataFinal))." ".$this->HoraFinal.":00";

                return $retorno;
                
               
        }
        
        /**
        * @desc Determina o próximo dia mesmo não sendo válido
        */
        Function DeterminaProximoDia(){
                
              ### GERA A PROXIMA DATA INDEPENDENTE DE SER VÁLIDA OU NÃO ##
              $this->ProximaData = date('Y-m-d',strtotime('+1day',strtotime($this->DataValida))); 
              
        }
        
       

       
       /**
       * @desc Verifica se a data inicial pertence a um periodo válido
       * @param date $DataIni
       * @param array $Feriado
       * @param array $DiaHoraAdicional
       */
       Function VerificaDataValida(){
            
            ### ZERA O CONTADOR ##
            $DiasCorridos = 0;
            
            ### DEIXA A VARIAVEL TESTE COMO TRUE POR DEFAULT ##
            $Invalido = TRUE;
            
            
            ### ENQUANTO EXISTIR DATA INVÁLIDA CONTINUA O LOOP ##  
            while ($Invalido == TRUE ){
           
            ### DIVIDE A DATA EM DIA E MES APENAS PARA COMPARAR COM O ARRAY DE FERIADOS ##
			 $DiaMes = date('m-d',strtotime('+'.$DiasCorridos.'day',strtotime($this->ProximaData))); ### UTILIZA PROXIMADATA PARA NÃO PERDER O VALOR DA DATAINI ##
			 
             ### GERA UMA MATRIZ A PARTIR DA PROXIMADATA SEPARANDO DIA/MES/ANO ##
             $DataArray = explode('-', $this->ProximaData);           
             
             ### GERA A DATA QUE SERÁ VERIFICADA , CONTANDO A PARTIR DA PROXIMADATA ##
			 $DataTeste = date('Y-m-d',strtotime('+'.$DiasCorridos.'day',strtotime($this->ProximaData))); 
			 
            
             ### VERIFICA SE A DATA É VÁLIDA ##
             if(($DiaSemana = gmdate('w', strtotime('+'.$DiasCorridos.' day', gmmktime(0, 0, 0, $DataArray[1], $DataArray[2], $DataArray[0]))) ) != '0' && $DiaSemana != '6' && !in_array($DiaMes,$this->Feriado)) { 
                    
                    ### SETA A DATA TESTE COMO  VALIDA ##
                    //$this->DataValida = date('Y-m-d',strtotime('+'.$DiasCorridos.'day',strtotime($this->ProximaData)));
                    $this->DataValida = $DataTeste;
             
                    ### SETA O DIA DA SEMANA PARA A DATA VÁLIDA ## 
                    $this->DiaSemana = $this->VerificaDiaSemana($this->DataValida); ### INTERVALOS SÃO SEPARADOS POR DIA DA SEMANA ##      
                    
                    ### SETA PARA UM ARRAY TODOS OS INTERVALOS ORDENADOS POR HORA INI (MESCLA PERIODO UTIL COM HORA ADICIONAL ) ##
                    $this->IntervaloDia[$this->DiaSemana] = $this->MesclaIntervalo($this->PeriodoValido[$this->DiaSemana], $this->DiaHoraAdicional[$this->DataValida]);
                    
                    ### ENCERRA TESTE ##
                    break;
             
             
             }else{
                       
                       ### VERIFICA SE PARA ESSA DATA, MESMO SENDO INVÁLIDA, EXISTE HORA EXTRA ##
                        if($this->VerificaHoraAdicional($DataTeste)){
                            
                            ### SETA A DATA TESTE COMO  VALIDA ##
                            //$this->DataValida = date('Y-m-d',strtotime('+'.$DiasCorridos.'day',strtotime($this->ProximaData)));
                            $this->DataValida = $DataTeste;
                            
                            
                            ### SETA O DIA DA SEMANA PARA A DATA VÁLIDA ## 
                            $this->DiaSemana = $this->VerificaDiaSemana($this->DataValida); ### INTERVALOS SÃO SEPARADOS POR DIA DA SEMANA ##      
                         
                            ### SETA PARA UM ARRAY TODOS OS INTERVALOS ORDENADOS POR HORA INI (MESCLA PERIODO UTIL COM HORA ADICIONAL ) ##
                            $this->IntervaloDia[$this->DiaSemana] = $this->MesclaIntervalo("", $this->DiaHoraAdicional[$this->DataValida]);               
                            
                            ### ENCERRA TESTE ##
                            break;
                            
                        }else{
                            
                           ### INCREMENTA O CONTADOR PORQUE A DATATESTE É INVÁLIDA 
                           $DiasCorridos++;
                           
                        }                            
                           
              }
            }

         
        }
        
       
       
       /**
       * @desc Verifica se a data é Hora Adicional (Extra)
       * @param date $Data
       */
       Function VerificaHoraAdicional($Data){
               
               ### UTILIZADA NA VERIFICAÇÃO DE DATA VÁLIDA ##
               if(count($this->DiaHoraAdicional[$Data]) > 0)
                    ### RETORNA TRUE CASO ENCONTRE ALGUMA HORA EXTRA ##
                    return true;
               else 
                    return false;
       }
       

     
     /**
     * @desc Valida informações sobre datas e horas
     */
     Function VerificaDataHora(){
     
          ### SETA UMA DATA VÁLIDA A PARTIR DA INICIAL ##
          $this->VerificaDataValida();
          
          ### SETA UMA HORA VÁLIDA REFERENTE A DATA INICIAL E A VALIDA ##
          $this->VerificaHoraValida();     
     
     }
     
     /**
     * @desc Verifica se a hora inicial é válida para o dia 
     */
     Function VerificaHoraValida(){
           
           ### VERIFICA SE A DATA VALIDA É DIFERENTE DA INICIAL ##
          if($this->DataValida != $this->DataIni){
               
               ### HORAINIINTERVALO É 00:00 PORQUE O DATAINI É INVÁLIDA ( ASSIM, É A PRIMEIRA HORA DA DATA VÁLIDA) ##
               $this->HoraIniIntervalo = "00:00";
               
          }
          
     }

     /**
     * @desc Mescla dois arrays - Período Util e Hora Adicional
     * @param array $Periodo
     * @param array $Adicional
     */
     Function MesclaIntervalo($Periodo, $Adicional){

     ### INDICE DA MATRIZ RETORNO ##
     $a = 0;

     ### VERIFICA SE PERIODO EXISTE ##
     if($Periodo){
           
           ### VERIFICA SE PERIODO POSSUE ELEMENTOS ##
          if(count($Periodo)>0){
               
               ### ADICIONA A MATRIZ PERIODO NA MATRIZ RETORNO ##
               foreach($Periodo as $value){
                    
                    ### SETA A HORA INICIAL NA MATRIZ RETORNO##
                    $retorno[$a]->hora_ini = $value->hora_ini;
                    
                    ### SETA A HORA FINAL NA MATRIZ RETORNO##
                    $retorno[$a]->hora_fim = $value->hora_fim;
                    
                    ### INCREMENTA O ÍNDICE ##
                    $a++;
               
               }
          }
     }

     ### VERIFICA SE ADICIONAL EXISTE ##
     if($Adicional){
         
          ### VERIFICA SE ADICIONAL POSSUE ELEMENTOS  ##
          if(count($Adicional)>0){
          
                ### ADICIONA A MATRIZ ADICIONAL NA MATRIZ RETORNO ##
               foreach($Adicional as $value){
               
                    ### SETA A HORA INICIAL NA MATRIZ RETORNO##
                    $retorno[$a]->hora_ini = $value->hora_ini;
                    
                    ### SETA A HORA FINAL NA MATRIZ RETORNO##
                    $retorno[$a]->hora_fim = $value->hora_fim;
                    
                    ### INCREMENTA O ÍNDICE ##
                    $a++;
               
               }
          }
     }

     ### VERIFICA SE A MATRIZ RETORNO EXISTE ##
     if($retorno){

          //### ORDENA A MATRIZ (CONTEÚDO) EM ORDEM CRESCENTE ##
          //asort($retorno);
        
          ### ORDENA A MATRIZ (ÍNDICE E CONTEÚDO) EM ORDEM CRESCENTE ##
          sort($retorno);
     }



     return $retorno;

     }
            
       /**
       * @desc Calcula o saldo de minutos a cada dia através dos intervalos
       */
       Function SaldoIntervaloDia(){
  			

            ### VERIFICA SE EXISTE O PERÍODO ÚTIL (EXCETO HORA EXTRA) PARA ESSE DIA ##
            if($this->IntervaloDia[$this->DiaSemana] != ""){
             
                ### PROCURA O INTERVALO INICIAL (RETORNO O ID DO PROXIMO PERIODO DO DIA )##    
                $this->LocalizaPrimeiroIntervalo();                 
                
                ### VERIFICA SE EXISTE MAIS INTERVALOS
                if($this->SaldoMinuto > 0){
                
                    ### DECREMENTA O SALDO DE MINUTOS ATRAVES DOS INTERVALOS CHEIOS DOS PROXIMOS PERIODOS DO DIA ##
                    $this->CalculaDemaisIntervalos();
                    
                }
                
                
			}
        
       }
       
       
       /**
       * @desc Localiza o primeiro intervalo válido para uma determinada hora e data
       */
       Function LocalizaPrimeiroIntervalo(){
       
                ### PROCURA O PERÍODO INICIAL ##
                foreach($this->IntervaloDia[$this->DiaSemana] as $id => $value) {
                  
                    
                    if($this->VerificaIntervalo($this->HoraIniIntervalo, $value->hora_ini,$value->hora_fim) == 0){
                    ### VERIFICA SE ESTA DENTRO DO INTERVALO (RETORNO == 0 ) ##
                    
                         
                         ### RETORNA A DIFERENCA EM MINUTOS ##
                        $diferenca = $this->DiferencaTempo($value->hora_fim, $this->HoraIniIntervalo,"i");
                
                        ### SE SALDO MINUTO ZERA NESSE INTERVALO ##
                        if($this->SaldoMinuto <= $diferenca){
                        
                              ### GERA A HORA FINAL E DATA FINAL ##
                              $this->GeraHoraFinalPrimeiroInter();
     
                              break;
                             
                        }else{

                            ### SALDO MINUTO CONTINUA MAIOR QUE ZERO##
                            $this->DecrescimoSaldo($diferenca);
                        
                        
                             ### SETA O ID DO PRÓXIMO INTERVALO ##
                             $this->IdIntervalo = $id+1; 
                             
                             ### SETA A HORA INICIAL DO PROXIMO INTERVALO ##
                             $this->HoraIniIntervalo = "00:00";
                                
                       
                             break;
                        
                        }
                        
  
                    }elseif($this->VerificaIntervalo($this->HoraIniIntervalo, $value->hora_ini,$value->hora_fim) < 0 ) {
                    ### DATA INICIAL ESTÁ ANTES DO PRIMEIRO INTERVALO DO DIA (RETORNO == -1 ) ##
                    
                         ### PRÓXIMO INTERVALO É CHEIO ( SALDO = HORAFIM - HORAINI)
                         $this->IdIntervalo = $id; 


                         ### SETA A HORA INICIAL DO PROXIMO INTERVALO ##
                         $this->HoraIniIntervalo = "00:00";
                         
                         break;
                         

                    }
                     
                }
                
       
       }
       
       /**
       * @desc Calcula o saldo dos demais períodos para o mesmo dia
       */
       Function CalculaDemaisIntervalos(){
        
        
          ### DECREMENTA DO SALDO MINUTOS OS INTERVALOS CHEIOS RESTANTES NO DIA##
          for($a=$this->IdIntervalo; count($this->IntervaloDia[$this->DiaSemana]) > $a; $a++) {

             ### CALCULA QUANTOS MINUTOS POSSUE O INTERVALO ## 
             $saldointervalo = $this->DiferencaTempo($this->IntervaloDia[$this->DiaSemana][$a]->hora_fim,$this->IntervaloDia[$this->DiaSemana][$a]->hora_ini,'i');
             
             ### REALIZA O DECRESCIMO DO SALDO DE MINUTOS
             $this->DecrescimoSaldo($saldointervalo); 
             
                    if($this->SaldoMinuto <= 0) {
                        

                        ### SE SALDO MINUTO MENOR OU IGUAL A ZERO, ACHO O INTERVALO EM QUE TERMINA O PROCESSO
                        $this->GeraHoraFinal($this->IntervaloDia[$this->DiaSemana][$a]->hora_fim);
                                   
                        ### NÃO EXISTEM MAIS INTERVALOS A SEREM DECREMENTADOS
                        break; 
                    }
                    
               $this->IdIntervalo++;        
                     
          }
          
          
       }
        

	  

       

	   /**
	   *@desc Decrementa o saldo de minutos
	   *@param int $Decrescimo
	   */
	   Function DecrescimoSaldo($Decrescimo){

            ### SUBTRAI DO SALDO MINUTOS A QUANTIA DE MINUTOS DE UM INTERVALO ##
			$this->SaldoMinuto -= $Decrescimo;

	   }
       

    
       /**
       * @desc Gera a Hora Final quando o saldo zera dentro do primeiro intervalo
       */
       Function GeraHoraFinalPrimeiroInter(){
       
               ### SETA A HORA FINAL ##
               $this->HoraFinal = $this->SomaHora($this->HoraIniIntervalo, $this->SaldoMinuto); 
               
               ### ZERA SALDO DE MINUTOS PARA PARAR O LOOP
               $this->SaldoMinuto = 0;
               
               ### GERA A DATA FINAL ##                     
               $this->GeraDataFinal();
       
       }
    
       /**
       *@desc Gera a hora final do processo
       * @param time $UltimaHora
       * @param int $SaldoIntervalo
       */
       Function GeraHoraFinal($UltimaHoraFim){
       
            ### VERIFICA SE O SALDO FICOU NEGATIVO NO INTERVALO ##     
            if($this->SaldoMinuto < 0) {
               
                ### CONVERTE PARA POSITIVO O SALDO DE MINUTOS PARA GERAR CORRETAMENTE A HORA FINAL ##
                $SaldoMinutoPositivo = ($this->SaldoMinuto) * (-1);
                
                ### GERA A DATA FINAL (DATA VALIDA) ##
                $this->GeraDataFinal();         
             
                ### GERA A HORA FINAL A PARTIR DA ULTIMA HORA##
                $this->HoraFinal = $this->SubtraiHora($UltimaHoraFim, $SaldoMinutoPositivo);
               
                ### RETORNA TRUE PARA INDICAR QUE ENCONTROU A DATA E HORA FINAL ##
                return true;
                       
            } elseif($this->SaldoMinuto == 0) {

                ### GERA A DATA FINAL (DATA VALIDA) ##
                $this->GeraDataFinal();
                
                ### SETA A ULTIMA HORA COMO A FINAL PORQUE O SALDO MINUTO ZEROU NESSE MOMENTO ##
                $this->HoraFinal = $UltimaHoraFim;
                
                ### RETORNA TRUE PARA INDICAR QUE ENCONTROU A DATA E HORA FINAL ##  
                return true;
                        
            
            } 
            
            
            return false;
       }
       
       /**
       * @desc Gera a Data Final baseado no saldo de minutos zerado
       */
       Function GeraDataFinal(){
                    
                    ### DATA FINAL SE SALDO MINUTO ZEROU ##
                    if($this->SaldoMinuto <= 0) {
                            
                          ### SETA A DATA FINAL EM UM ATRIBUTO ##
                          $this->DataFinal = $this->DataValida;  
                          
                    }
       
       }
       
    
  }
    

  
  
  
?>