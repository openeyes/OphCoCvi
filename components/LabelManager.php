<?php

/**
 * OpenEyes
 *
 * (C) OpenEyes Foundation, 2016
 * This file is part of OpenEyes.
 * OpenEyes is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
 * OpenEyes is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License along with OpenEyes in a file titled COPYING. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package OpenEyes
 * @link http://www.openeyes.org.uk
 * @author OpenEyes <info@openeyes.org.uk>
 * @copyright Copyright (c) 2016, OpenEyes Foundation
 * @license http://www.gnu.org/licenses/gpl-3.0.html The GNU General Public License V3.0
 */

namespace OEModule\OphCoCvi\components;
use \ODTTemplateManager;

/**
 * A class for odt document modify and change 70 x 37mm labels on an A4 sheet and generate pdf
 */
class LabelManager extends ODTTemplateManager{
    /**
     * @var string
     */
    var $cols = 3;
    /**
     * @param $filename 
     * @param $templateDir
     * @param $outputDir
     * @param $outputName
    */
    public function __construct( $filename , $templateDir, $outputDir, $outputName )
    {
        parent::__construct( $filename , $templateDir, $outputDir, $outputName );
    }
    
    /*
     * Fill labels in document table by table-name
     * @param $tableName
     * @param $addressesArray 
     * @param $firstEmptyCell 
     */
    
    public function fillLabelsInTable( $tableName , $addressesArray , $firstEmptyCell)
    {
        $dataArray = $this->generateArrayToTable( $addressesArray , $firstEmptyCell );
        $this->fillTableByName( $tableName , $dataArray );          
    }
    
    /*
     * Generate array to ODTTemplatemanager fillTableByName valid data array from a simple array
     * @param $addressesArray
     * @param $firstEmptyCell
     */
    private function generateArrayToTable( $addressesArray , $firstEmptyCell ){
        $result[] = array();
 
        $i = 0;
        foreach ($addressesArray as $key => $val){
            
            if($key % $this->cols == 0){
                $colCount = 2;
            } else if($key % $this->cols == 1){
                $colCount = 0;
            } else {
                $colCount = 1;
            }
            
            if($key < $firstEmptyCell){
                $result[$i][$colCount] = '';
            } else {
                $newKey = $key - $firstEmptyCell;
                $result[$i][$colCount] = $addressesArray[$newKey];
            }
            if(($key % $this->cols === 0) && ($key != 0)){
                $i++;
            }
        }
        return $result;
    }
}
