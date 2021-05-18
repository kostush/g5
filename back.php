<?php

class Log {
    public static function add($data=null,$title=''){

            $log = "\n------------------------\n";
            $log .= date("Y.m.d G:i:s")."\n";
            $log .= (strlen($title) > 0 ? $title : 'DEBUG')."\n";
            $log .= print_r($data, 1);
            $log .= "\n------------------------\n";

            file_put_contents("log_".date('Y.m.d').".log", $log, FILE_APPEND);

            return true;
    }
}

class Currency{
    private $api_key ='625af6558938f45c37ca063abbb4e4e3';

    public $api_url='http://api.exchangeratesapi.io/v1/';
    public $baseCurrency;
    public $symbol='USD,AUD,CAD,PLN,MXN,GBP';
    public $currentDate ;
    public $filename = 'rate.txt';


    public function __construct($baseCurrency){
        $this->baseCurrency = $baseCurrency;
        $this->currentDate = date("Y-m-d");
        $this->filename ='rate_'.$this->currentDate.'.txt';
    }


    public function getApiCurrencyRate()
    {
        $currancy_arrays=[];
        $methods[]=$method = date("Y-m-d",strtotime("-1 days"));
        $methods[] = 'latest';
        Log::add($methods) ;
        foreach($methods as $method){
            try{
                $apiResult = $this->getApiData($method);
                if(array_key_exists('error',$apiResult)){
                    return $apiResult ;
                }else{
                    $currancy_arrays[] =  $apiResult;
                }
            }catch(Exception $e){
                return ['status'=>"error",'message'=>$e->getMessage()];
            }
        }
        return $currancy_arrays;
    }

    public function getApiData($method):array
    {
        $result=[];
        $headers[] = "Content-Type:application/json";
        $params=array(
            'access_key'=>$this->api_key,
            'base'      =>$this->baseCurrency,
            'symbol'    =>$this->symbol
        );
        $timeout=50;
        $url = $this->api_url . $method."?".http_build_query($params);
        $curlOptions = array(
            CURLOPT_HTTPHEADER=>$headers,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => $timeout,
            CURLOPT_TIMEOUT => $timeout,
            );

        $curl = curl_init($url);
        curl_setopt_array($curl, $curlOptions);
        $result = curl_exec($curl);
        $result = json_decode($result, true);

        return $result;
    }

    public function getFileCurrencyRate():array
    {
        $arrayFromFile=[];
        if(file_exists($this->filename)){
            $data= file_get_contents($this->filename);
            if($data) {
                $arrayFromFile = json_decode($data,true);
            }
        }
        return $arrayFromFile;
    }

    public function setFileCurrencyRate($arrayFromApi){
        $arrayToFile =[];
        $arrayToFile = $this->getFileCurrencyRate();
        Log::add($arrayFromApi,__LINE__);

        foreach($arrayFromApi as $key => $currancy_array){
            $arrayToFile[$currancy_array['base']][$currancy_array['date']]['rates'] = $currancy_array['rates'];
        };
        $filePutResult = file_put_contents($this->filename, json_encode($arrayToFile));

        return $arrayToFile;

    }
}


class Process{
    public $arrayToFront;
    public function start(){
        if($_POST){
            if((array_key_exists('baseCurrency',$_POST)) && (!empty($_POST))){
                $Currency= new Currency($_POST['baseCurrency']);
                $arrayFromFile = $Currency->getFileCurrencyRate();
                if(!array_key_exists($Currency->baseCurrency, $arrayFromFile)){
                    $arrayFromApi = $Currency->getApiCurrencyRate();
                    if (array_key_exists('error',$arrayFromApi)){
                        $arrayToFront['status']='error';
                        $arrayToFront['message']=$arrayFromApi['error']['code'];
                    }else{
                        $arrayToFile = $Currency->setFileCurrencyRate($arrayFromApi);
                        $arrayToFront['status']='success';
                        $arrayToFront['message']=$arrayToFile[$Currency->baseCurrency];


                    }
                }else{
                    $arrayToFront['status']='success';
                    $arrayToFront['message']=$arrayFromFile[$Currency->baseCurrency];

                }
                Log::add($arrayToFront,__LINE__);
                $this->jsonResponse($arrayToFront['status'],$arrayToFront['message']);
            }else{
                $this->jsonResponse('error',array('message'=>'в Запросе нет базовой валюты'));
            }
        }else {
            $this->jsonResponse('error',array('message'=>'Метод не Пост'));
        }
    }

    public function verify(){
        return true;
    }

    public function jsonResponse ($status,$data=null){
        ob_start();
        ob_end_clean();
        Header('Cache-Control: no-cache');
        Header('Pragma: no-cache');
        echo json_encode(array('status'=>$status,'data'=>$data));
    }
}

$ob = new process;
$ob->start();


