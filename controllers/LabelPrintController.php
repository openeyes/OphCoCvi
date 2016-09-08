<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace OEModule\OphCoCvi\controllers;
use OEModule\OphCoCvi\components\LabelManager;

class LabelPrintController extends \BaseController
{
    public function accessRules()
    {
        return array(
            array('allow',
                'actions'       => array('test', 'getPDF'),
                'roles'         => array('admin')
            ),
        );
    }
    
    public function actionGetPDF(){
        $labelClass = new LabelManager( 
                'labels.odt' ,
                realpath(__DIR__ . '/..').'/views/odtTemplate',
                realpath(dirname(__FILE__).'/..').'/files',
                'labels_'.mt_rand().'.odt'
        );
        
        $testAddresses = array(
            '2 Sir Simon Milton Square<br/>
            LONDON<br/>
            SW1E 5DJ',
            '37 Well Grove<br/>
            Totteridge<br/>
            LONDON<br/>
            <br/>
            N20 9BN',
            'Coppa Club<br/>
            3 Three Quays Walk<br/>
            LONDON<br/>
            <br/>
            EC3R 6AH ',
            '2 Sir Simon Milton Square<br/>
            LONDON<br/>
            SW1E 5DJ',
            '2 Sir Simon Milton Square<br/>
            LONDON<br/>
            SW1E 5DJ',
            '2 Sir Simon Milton Square<br/>
            LONDON<br/>
            SW1E 5DJ','2 Sir Simon Milton Square<br/>
            LONDON<br/>
            SW1E 5DJ','2 Sir Simon Milton Square<br/>
            LONDON<br/>
            SW1E 5DJ','2 Sir Simon Milton Square<br/>
            LONDON<br/>
            SW1E 5DJ','2 Sir Simon Milton Square<br/>
            LONDON<br/>
            SW1E 5DJ','2 Sir Simon Milton Square<br/>
            LONDON<br/>
            SW1E 5DJ','2 Sir Simon Milton Square<br/>
            LONDON<br/>
            SW1E 5DJ','2 Sir Simon Milton Square<br/>
            LONDON<br/>
            SW1E 5DJ','2 Sir Simon Milton Square<br/>
            LONDON<br/>
            SW1E 5DJ','2 Sir Simon Milton Square<br/>
            LONDON<br/>
            SW1E 5DJ','2 Sir Simon Milton Square<br/>
            LONDON<br/>
            SW1E 5DJ','2 Sir Simon Milton Square<br/>
            LONDON<br/>
            SW1E 5DJ','2 Sir Simon Milton Square<br/>
            LONDON<br/>
            SW1E 5DJ','2 Sir Simon Milton Square<br/>
            LONDON<br/>
            SW1E 5DJ',
            '2 Sir Simon Milton Square<br/>
            LONDON<br/>
            SW1E 5DJ',
            'Coppa Club<br/>
            3 Three Quays Walk<br/>
            LONDON<br/>
            <br/>
            EC3R 6AH ',
            'Coppa Club<br/>
            3 Three Quays Walk<br/>
            LONDON<br/>
            <br/>
            EC3R 6AH ',
            'Coppa Club<br/>
            3 Three Quays Walk<br/>
            LONDON<br/>
            <br/>
            EC3R 6AH ',
            'Coppa Club<br/>
            3 Three Quays Walk<br/>
            LONDON<br/>
            <br/>
            EC3R 6AH ',
            'Coppa Club<br/>
            3 Three Quays Walk<br/>
            LONDON<br/>
            <br/>
            EC3R 6AH '
        );
        
        $labelClass->fillLabelsInTable( $testAddresses , 0);
        //$labelClass->removeElement();
        //exit;
        $labelClass->saveContentXML();
        $labelClass->generatePDF();
        $labelClass->getPDF();
    }
}
