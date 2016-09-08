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
use \DOMDocument;
use \ZipArchive;
use \DomXpath;
use \RecursiveIteratorIterator;
use \RecursiveDirectoryIterator;

/**
 * A class for odt document modify and change 70 x 37mm labels on an A4 sheet and generate pdf
 */
class LabelManager{
    
    /**
     * @var string
     */
    private $xml;
    
    /**
     * @var string
     */
    private $contentFilename = 'content.xml';
    
    /**
     * @var string
     */
    private $newOdtFilename = '';
    
    /**
     * @var string
     */
    var $templateDir;
    
    /**
     * @var string
     */
    var $openedSablonFilename;
    
    /**
     * @var string
     */
    var $odtFilename;
    
    /**
     * @var integer
     */
    var $firstEmptyLabel = 0;
    
    /**
     * @param $filename 
     * @param $templateDir
     * @param $outputDir
     * @param $outputName
    */
    public function __construct( $filename , $templateDir, $outputDir, $outputName )
    {
        $this->uniqueId = time();
        $this->templateDir = $templateDir;
        $this->openedSablonFilename = $filename;
        $this->odtFilename = $this->templateDir.'/'.$filename;
        $this->generatedOdt = $outputName;
        
        $this->outDir = realpath(dirname(__FILE__).'/..').'/files/pdf';
        $this->zippedDir = $outputDir.'/zipped/'.$this->uniqueId.'/';
        $this->unzippedDir = $outputDir.'/unzipped/'.$this->uniqueId.'/';
        
        $this->newOdtFilename = $this->zippedDir.$this->generatedOdt;
        $this->outFile = str_replace('odt','pdf', $this->generatedOdt);
        
        $this->unZip();
        $this->openXml();
    }
    
    /*
     * Fill labels in document table and set starting point
     * @param $addressesArray 
     * @param $firstEmptyLabel
     */
    
    public function fillLabelsInTable( $addressesArray , $firstEmptyLabel )
    {
        $this->firstEmptyLabel = $firstEmptyLabel;
        $this->setLabelsInTable( $addressesArray );
    }
    
    /*
     * Find and create labels in the document table
     * @param $addressesArray 
     */
    private function setLabelsInTable( $addressesArray )
    {
        $table = $this->xpath->query('//table:table')->item(0);
       
        $labelCounter = 1;
        $rowCount = 0;
       
        foreach($table->childNodes as $nodeCount => $tableNode) {
            
            if( $tableNode->nodeName == 'table:table-row'){
                
                $rowCount++;
                $cols = $tableNode->childNodes;
                
                $i = 1;
                
                foreach ($cols as $oneCol){
                    
                    if($i % 3 == 0){
                        $colCount = 3;
                    } else if($i % 3 == 1){
                        $colCount = 1;
                    } else {
                        $colCount = 2;
                    }
                   
                    if($labelCounter >= $this->firstEmptyLabel){
                        
                        $setArrayKey = $labelCounter - $this->firstEmptyLabel;
                        
                        if (array_key_exists($setArrayKey, $addressesArray)) {
                            foreach ($oneCol->childNodes as $child){
                                if($child->hasAttribute('text:style-name')){
                                    $existingStyleName = $child->getAttribute('text:style-name'); // get style
                                }
                            }
                            
                            $oneCol->nodeValue = "";
                            $this->createSingleOrMultilineTextNode( $oneCol , $addressesArray[$setArrayKey], $existingStyleName);
                        }  
                    }
                   
                    $i++; 
                    $labelCounter++;
                }
            } 
        }
    }
    
    /*
     * Create node with value and break line
     * @param $node 
     * @param $string 
     * @param $existingStyleName 
     */
    private function createSingleOrMultilineTextNode( $node, $string, $existingStyleName=null ){
        $stringArr = '';
        $stringArr = explode('<br/>',$string);

        if(count($stringArr)==1){
        	$stringArr = explode('<br/>',$string);
				}
        
        if(count($stringArr)==1){
        	$stringArr = explode("\\n",$string);
				}
        
        if(count($stringArr)>1){ // Is multiline
            foreach ($stringArr as $inc => $oneLine){
                if($inc > 0){
                    $break = $this->xml->createElement('text:line-break');
                    $node->appendChild($break);
                }
                $newTextNode = $this->xml->createElement('text:p');
                $newTextNode -> nodeValue = $oneLine;
                $newTextNode -> setAttribute('text:style-name',$existingStyleName);
                $node->appendChild($newTextNode);                
            }
        } else { // is single line
            $newTextNode = $this->xml->createElement('text:p');
            $newTextNode -> nodeValue = $string;
            $newTextNode -> setAttribute('text:style-name',$existingStyleName);
            $node->appendChild($newTextNode);
        }
    }
    
    /*
     * Open xml file 
     */
    private function openXML()
    {
        $this->xml = new DOMDocument();
        $this->xml->load($this->openedSablonFilename);
        $this->xml->formatOutput = true;
        $this->xml->preserveWhiteSpace = false;  
        $this->xpath = new DomXpath($this->xml);
    }
    
    /*
     * Save xml file content after edit
     */
    public function saveContentXML( )
    {
        $this->xml->save( $this->unzippedDir.$this->contentFilename );
    }
    
     /*
     * Unzip odt file into the temporary directory
     */
    private function unZip( $createZipNameDir=true, $overwrite=true )
    {
        $zip = new ZipArchive;
        $destDir = $this->unzippedDir;
        $srcFile = $this->odtFilename;
        
        if( $zip = zip_open( $srcFile ) ) {
            if( $zip ) {
                $splitter = ($createZipNameDir === true) ? '.' : '/';
                if($destDir === false) $destDir = substr($srcFile, 0, strrpos($srcFile, $splitter)). '/';

                $this -> createDirectory($destDir);
                 
                while($zipEntry = zip_read($zip)){
                    
                    $posLastSlash = strrpos(zip_entry_name($zipEntry), '/');

                    if ($posLastSlash !== false) {
                        $this -> createDirectory($destDir.substr(zip_entry_name($zipEntry), 0, $posLastSlash+1));
                    }

                    if (zip_entry_open($zip,$zipEntry, 'r')) {
                        $fileName = $destDir.zip_entry_name($zipEntry);
                        if ($overwrite === true || ($overwrite === false && !is_file($fileName))) {
                            $fstream = zip_entry_read($zipEntry, zip_entry_filesize($zipEntry));
                            if(!is_dir($fileName)){
                                file_put_contents($fileName, $fstream );
                                //chmod($fileName, $this -> right );
                            }
                        }
                        zip_entry_close($zipEntry);
                    }       
                }
                zip_close($zip);
                $this->openedSablonFilename = $destDir.$this->contentFilename;
            }
        } else {
            $this->dropException( 'Failed unzip ODT. File: '.$this->templateDir.$this->odtFilename );
        }
    }
    
    /*
     * Zip xml files 
     * @return string
     */
    private function zipOdtFile()
    {
        $inputFolder  = $this -> unzippedDir;
        $destPath = $this -> zippedDir;
        mkdir($destPath, 0777, true);
        $zip   = new ZipArchive();
       
        
        $zip  -> open( $this->newOdtFilename, ZipArchive::CREATE | ZipArchive::OVERWRITE);
      
        $inputFolder = str_replace('\\', DIRECTORY_SEPARATOR, realpath($inputFolder));
       
        if (is_dir($inputFolder) === TRUE) {
            $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($inputFolder), RecursiveIteratorIterator::SELF_FIRST);

            foreach ($files as $file) {
                $file = str_replace('\\', DIRECTORY_SEPARATOR, $file);
                if (in_array(substr($file, strrpos($file, '/')+1), array('.', '..'))) {
                    continue;
                }

                $file = realpath($file);

                if (is_dir($file) === TRUE) {
                    $dirName = str_replace($inputFolder.DIRECTORY_SEPARATOR, '', $file.DIRECTORY_SEPARATOR);
                    $zip->addEmptyDir($dirName);
                }
                else if (is_file($file) === TRUE) {
                    $fileName = str_replace($inputFolder.DIRECTORY_SEPARATOR, '', $file);
                    $zip->addFromString($fileName, file_get_contents($file));
                }
            }
        } else if (is_file($inputFolder) === TRUE) {
            $zip->addFromString(basename($inputFolder), file_get_contents($inputFolder));
        }
        
        $zip->close();
        //$this->deleteDirectory( $inputFolder );
        return $destPath.$this->generatedOdt;
    }
    
    /*
     * Create directoy by path
     * @param $path
     */
    private function createDirectory( $path )
    {
        if (!is_dir($path)){
            $directoryPath = '';
            $directories = explode('/',$path);
            array_pop($directories);

            foreach($directories as $directory) {
                $directoryPath .= $directory. '/';
                if (!is_dir($directoryPath)) {
                    mkdir($directoryPath, 0777, true);
                    //chmod($directoryPath, $this -> right );
                }
            }
        }
    }
    
    /*
     * Delete temporary directoy by path
     * @param $path
    */
    private function deleteDirectory( $path )
    {
        if(is_dir($path)){
            $files = array_diff(scandir($path), array('.', '..'));
            foreach ($files as $file){
                $this->deleteDirectory(realpath($path) . '/' . $file);
            }
            return rmdir($path);
        } else if (is_file($path) === true){
            return unlink($path);
        }
    }
    
    
    /*
     * Generate pdf file from odt and delete temporary folder
     */
    public function generatePDF()
    {
        $path = $this->zipOdtFile();
        if($path !== FALSE){
            $shell = 'export HOME=/tmp && /usr/bin/libreoffice --headless --convert-to pdf --outdir '.$this->outDir.'  '.$path;
           
            exec($shell, $output, $return); 
            if($return == 0){
                $odtPath = substr($path, 0, strrpos( $path, '/'));
                //$this->deleteDirectory( $odtPath );
            }
        }
    }
    
    /*
     * Get generated pdf
     */
    public function getPDF()
    {
        header('Content-type: application/pdf');
        header('Content-Length: ' . filesize($this->outDir.'/'.$this->outFile));
        @readfile($this->outDir.'/'.$this->outFile);
    }
}
