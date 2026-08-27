<?php

namespace FuelSdk;

/**
 * The class can get, convert, render, send messages.
 */
class ET_Message_Guide extends ET_CUDSupportRest
{
    /**
    * The constructor will assign endpoint, urlProps, urlPropsRequired fields of parent ET_BaseObjectRest
    */
    public function __construct()
    {
        $this->path = '/guide/v1/messages/{id}';
        $this->urlProps = ['id'];
        $this->urlPropsRequired = [];
    }

    // method for calling a Fuel API using GET
    /**
    * @return ET_GetRest     Object of type ET_GetRest which contains http status code, response, etc from the GET REST service
    */
    public function get()
    {
        $origPath = $this->path;
        $origProps = $this->urlProps;
        if (count($this->props) == 0) {
            $this->path = '/guide/v1/messages/f:@all';
        } elseif (array_key_exists('key', $this->props)) {
            $this->path = '/guide/v1/messages/key:{key}';
            $this->urlProps = ['key'];
        }
        $response = parent::get();
        $this->path = $origPath;
        $this->urlProps = $origProps;

        return $response;
    }

    // method for calling a Fuel API using POST
    /**
    * @return ET_PostRest     Object of type ET_PostRest which contains http status code, response, etc from the POST REST service
    */
    public function convert()
    {
        $completeURL = $this->authStub->baseUrl . '/guide/v1/messages/convert';

        $response = new ET_PostRest($this->authStub, $completeURL, $this->props, $this->authStub->getAuthToken());
        return $response;
    }

    // method for calling a Fuel API using POST
    /**
    * @return ET_Post     Object of type ET_Post which contains http status code, response, etc from the POST SOAP (not REST) service
    */
    public function sendProcess()
    {
        $renderMG = new ET_Message_Guide();
        $renderMG->authStub = $this->authStub;
        $renderMG->props = ['id' => $this->props['messageID']];
        $renderResult = $renderMG->render();
        if (!$renderResult->status) {
            return $renderResult;
        }

        $html = $renderResult->results->emailhtmlbody;
        $send = [];
        $send['Email'] = ['Subject' => $this->props['subject'], 'HTMLBody' => $html];
        $send['List'] = ['ID' => $this->props['listID']];
        $response = new ET_Post($this->authStub, 'Send', $send);
        return $response;
    }

    // method for calling a Fuel API using GET or POST
    /**
    * @return ET_GetRest|ET_PosttRest     Object of type ET_GetRest or ET_PostRest if props field is an array and holds id as a key
    */
    public function render()
    {
        $completeURL = null;
        $response = null;

        if (is_array($this->props) && array_key_exists('id', $this->props)) {
            $completeURL = $this->authStub->baseUrl . "/guide/v1/messages/render/{$this->props['id']}";
            $response = new ET_GetRest($this->authStub, $completeURL, $this->authStub->getAuthToken());
        } else {
            $completeURL = $this->authStub->baseUrl . '/guide/v1/messages/render';
            $response = new ET_PostRest($this->authStub, $completeURL, $this->props, $this->authStub->getAuthToken());
        }
        return $response;
    }
}
