<?php

namespace Emagine\CRM\BLL;

use Exception;
use stdClass;
 
class ZendeskBLL {

    const ZDAPIKEY = "NdHJ4CYR7VMbUTv0Aptey5CoXSdAMvdqY4goqAMX";
    const ZDUSER = "rodrigo@imobsync.com.br";
    const ZDURL = "https://emaginebr.zendesk.com/api/v2";
    const ZENDESK_TIPO_ERRO = "problem";
    const ZENDESK_TIPO_PERGUNTA = "question";

    /**
     * @param string $url
     * @param string $json
     * @param string $action
     * @throws Exception
     * @return stdClass
     */
    public function curlWrap($url, $json, $action)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10 );
        curl_setopt($ch, CURLOPT_URL, ZendeskBLL::ZDURL . $url);
        curl_setopt($ch, CURLOPT_USERPWD, ZendeskBLL::ZDUSER . "/token:" . ZendeskBLL::ZDAPIKEY);
        switch($action){
            case "POST":
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
                break;
            case "GET":
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
                break;
            case "PUT":
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
                break;
            case "DELETE":
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
                break;
            default:
                break;
        }
        
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
        curl_setopt($ch, CURLOPT_USERAGENT, "MozillaXYZ/1.0");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        if( !($output = curl_exec($ch))) {
            throw new Exception(curl_error($ch));
        }
        //$output = curl_exec($ch);
        //var_dump(ZendeskBLL::ZDURL . $url, ZendeskBLL::ZDUSER . "/token:" . ZendeskBLL::ZDAPIKEY, $output);
        curl_close($ch);
        $decoded = json_decode($output);
        return $decoded;
    }

    /**
     * @param string $assunto
     * @param string $mensagem
     * @param string $nome
     * @param string $email
     * @param string $tipo
     * @return string
     * @throws Exception
     */
    public function ticket($assunto, $mensagem, $nome, $email, $tipo = ZendeskBLL::ZENDESK_TIPO_ERRO) {
        $payload = array(
            'ticket' => array(
                "requester" => array(
                    "name" => $nome, 
                    "email" => $email
                ),
                'subject' => $assunto, 
                'comment' => array(
                    'body' => $mensagem
                ),
                'type' => $tipo
            )
        );

        $json = json_encode($payload);

        $data = $this->curlWrap("/tickets.json", $json, "POST");
        if (is_null($data) && isset($data->error)) {
            throw new Exception($data->error);
        }
        return $data->ticket;
    }

    /**
     * @throws Exception
     * @deprecated Use o ticket
     * @param string $assunto
     * @param string $mensagem
     * @param string $nome
     * @param string $email
     * @param string $tipo
     * @return string
     */
    public function zendesk_ticket($assunto, $mensagem, $nome, $email, $tipo = ZendeskBLL::ZENDESK_TIPO_ERRO) {
        return $this->ticket($assunto, $mensagem, $nome, $email, $tipo);
    }

    /**
     * @param int $pg
     * @return stdClass
     * @throws Exception
     */
    public function listarUsuario($pg = 1) {
        $data = $this->curlWrap("/users.json?page=" . $pg, null, "GET");
        //var_dump($pg, $data);
        if (is_null($data) && isset($data->error)) {
            throw new Exception($data->error);
        }
        return $data;
    }
}