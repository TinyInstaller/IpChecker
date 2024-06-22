<?php

namespace App\Http\Controllers;

class PopupController
{
    function index()
    {
        return view('popup');
    }
    function getCss(){
        $resource=$this->getResource();
        if($resource['css']){
            //Response file with correct mime type
            return response()->file(public_path($resource['css']),['Content-Type'=>'text/css']);
        }
    }
    function getJs(){
        $resource=$this->getResource();
        if($resource['js']){
            return response()->file(public_path($resource['js']),['Content-Type'=>'application/javascript']);
        }
    }
    protected function getResource(){
        static $res;
        if($res){
            return $res;
        }
        $buildDirectory='build';
        $manifest=json_decode(file_get_contents(public_path($buildDirectory.'/manifest.json')),true);
        $resource=$manifest['resources/js/popup.js'];
        $res=[];
        if($resource['file']){
            $res['js']=$resource['file'];
        }
        if($resource['css']){
            $res['css']=$resource['css'][0];
        }
        foreach ($res as $type=>$file){
            $res[$type]=($buildDirectory.'/'.$file);
        }

        return $res;
    }
}
