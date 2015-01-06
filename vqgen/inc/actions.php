<?php
// Get a file
if(isset($_GET['get'])){
	$filen = (substr($_GET['get'], -1)=='_'?rtrim($_GET['get'], '_'):$_GET['get']);
	
	header('Content-disposition: attachment; filename=' . $filen);
	header('Content-type: text/xml');
	header("Content-length: " . filesize(PATH . $_GET['get']));
    header("Cache-control: private");
	readfile(PATH . $_GET['get']);
	exit();
}

// Delete a file
if(isset($_GET['delete'])){
	if(file_exists(PATH . $_GET['delete'])){
		@unlink(PATH . $_GET['delete']);
		if(substr($_GET['delete'],-1)!='_'){
			foreach (glob(CACHE . '*.*') as $cachefile) {
				@unlink($cachefile);
			}
		}
	}
	header("Location:./");
	exit;
}

// Disable a file
if(isset($_GET['disable'])){
	if(file_exists(PATH . $_GET['disable'])){
		@rename(PATH . $_GET['disable'], PATH . $_GET['disable'].'_');
		foreach (glob(CACHE . '*.*') as $cachefile) {
			@unlink($cachefile);
		}
	}
	header("Location:./");
	exit;
}

// Disable all files
if(isset($_GET['disableall'])){
	foreach (glob(PATH . '*.xml') as $path) {
		if($path != PATH . 'vqmod_opencart.xml'){
			@rename($path, $path.'_');
		}
		foreach (glob(CACHE . '*.*') as $cachefile) {
			@unlink($cachefile);
		}
	}
	header("Location:./");
	exit;
}

// Enable a file
if(isset($_GET['enable'])){
	if(file_exists(PATH . $_GET['enable'])){
		@rename(PATH . $_GET['enable'], rtrim(PATH . $_GET['enable'], '_'));
		foreach (glob(CACHE . '*.*') as $cachefile) {
			@unlink($cachefile);
		}
	}
	header("Location:./");
	exit;
}

// Enable all files
if(isset($_GET['enableall'])){
	foreach (glob(PATH . '*.xml_') as $path) {
		@rename($path, rtrim($path, '_'));
		foreach (glob(CACHE . '*.*') as $cachefile) {
			@unlink($cachefile);
		}
	}
	header("Location:./");
	exit;
}

// Edit a file

// Generate a new file
if(isset($_POST['generatexml'])){
	$file = PATH . stripText($_POST['filename']) . '.xml_';

	$output = '<!-- Created using vQmod XML Generator by UKSB - http://www.opencart-extensions.co.uk //-->'."\n";
	$output .= '<modification>'."\n";
	$output .= "\t" . '<id><![CDATA[' . stripslashes($_POST['fileid']) . ']]></id>' . "\n";
	$output .= "\t" . '<version><![CDATA[' . stripslashes($_POST['version']) . ']]></version>' . "\n";
	$output .= "\t" . '<vqmver><![CDATA[' . stripslashes($_POST['vqmodver']) . ']]></vqmver>' . "\n";
	$output .= "\t" . '<author><![CDATA[' . stripslashes($_POST['author']) . ']]></author>';

// BOF - Zappo - vQGen Manual Generator - TWO LINES - Added Simple html manual
	$manual = "\t\t" . '<div id="file">' . stripslashes($_POST['fileid']) . ' <small>version ' . stripslashes($_POST['version']) . '</small>' . "\n";
	$manual .= "\t\t\t" . '<div id="author">By: ' . stripslashes($_POST['author']) . '</div>'."\n";
	$manual .= "\t\t" . '</div>'."\n";
	
	foreach ($_POST['file'] as $key => $value) {
		if(!isset($_POST['remove_'.$key])){
			$output .= "\n\t" . '<file name="' . stripslashes($value) . '">';

// BOF - Zappo - vQGen Manual Generator - TWO LINES - Added Simple html manual
			$manual .= "\t\t" . '<div class="infile" title="Click when Done with this File.">In file "' . stripslashes($value) . '":</div>'."\n";
			$manual .= "\t\t" . '<div class="vqfile">'."\n";
		
			foreach ($_POST['search'][$key] as $key2 => $val) {
				if(!isset($_POST['remove_'.$key.'_'.$key2])){
					$output .= "\n\t\t" . '<operation';
					if ($_POST['error'][$key][$key2] != 'abort') $output .= ' error="' . $_POST['error'][$key][$key2] . '"';
					$output .= '>';
					$output .= "\n\t\t\t" . '<search';
					$output .= ' position="' . $_POST['position'][$key][$key2] . '"';
// BOF - Zappo - Bugfix - index was only saving one integer, never multiple occurences...
					if ((int)$_POST['offset'][$key][$key2] > 0) $output .= ' offset="'.(int)$_POST['offset'][$key][$key2].'"';
					if ($_POST['index'][$key][$key2]) $output .= ' index="'.$_POST['index'][$key][$key2].'"';
					if ($_POST['regex'][$key][$key2] == 'true') $output .= ' regex="true"';
// EOF - Zappo - Bugfix - index was only saving one integer, never multiple occurences...
					$output .= '>';
					$output .= '<![CDATA[' . stripslashes($val) . ']]></search>';
					$output .= "\n\t\t\t" . '<add><![CDATA[' . stripslashes($_POST['add'][$key][$key2])  . ']]></add>';
					$output .= "\n\t\t" . '</operation>';

// BOF - Zappo - vQGen Manual Generator - Added Simple html manual
					$index = $_POST['index'][$key][$key2];
					$pos = $_POST['position'][$key][$key2];
					$posid = 'pos-' . $key . '_' . $key2;
					if ($pos == 'top' || $pos == 'bottom' || $pos == 'all') {
						$manual .= "\t\t\t" . '<div class="search" data-id="' . $posid . '" title="Click when Done with this Change.">';
						if ($pos == 'all') {
							$manual .= 'REPLACE ALL CODE WITH:';
						} else {
							$manual .= 'ADD TO <b>';
							if ((int)$_POST['offset'][$key][$key2] > 0) {
								$manual .= (int)$_POST['offset'][$key][$key2] . ' LINES ';
								$manual .= ($pos == 'top') ? 'BELOW ' : 'ABOVE ';
							}
							$manual .= strtoupper($pos) . '</b> OF THE FILE:';
						}
						$manual .= '</div>'."\n";
					} else {
						$manual .= "\t\t\t" . '<div class="search" data-id="' . $posid . '" title="Click when Done with this Change.">FIND ';
						if (!$index) {
							$manual .= '<b>ALL</b> Occurences';
						} elseif (strpos($index, ',') === false) {
							$index = (int)$index;
							$manual .= '<b>' . $index . '<sup>';
							$manual .= ($index == 1) ? 'st' : (($index == 2) ? 'nd' : (($index == 3) ? 'rd' : 'th'));
							$manual .= '</sup></b> Occurence';
						} else {
							$indexs = explode(',', $index);
							$last = count($indexs) - 1;
							$manual .= 'Occurences: ';
							foreach ($indexs as $key => $ndx) {
								$ndx = trim($ndx);
								$manual .= '<b>' . $ndx . '</b>' . ($key == $last ? '' : ($key + 1 == $last ? ' and ' : ', '));
							}
						}
						$manual .= ' OF:</div>'."\n";
						$manual .= "\t\t\t" . '<div class="find ' . $posid . '"><textarea rows="1">' . stripslashes($val) . '</textarea></div>'."\n";
						$manual .= "\t\t\t" . '<div class="action ' . $posid . '">';
						if ($pos == 'replace') {
							$manual .= 'AND REPLACE ' . ((isset($indexs) || !$_POST['index'][$key][$key2]) ? 'EACH ' : 'IT ');
							if ((int)$_POST['offset'][$key][$key2] > 0) $manual .= '<b>AND THE ' . (int)$_POST['offset'][$key][$key2] . ' LINES BELOW</b> ';
							$manual .= 'WITH:'."\n";
						} else {
							$manual .= 'AND ADD <b>';
							if ((int)$_POST['offset'][$key][$key2] > 0) $manual .= (int)$_POST['offset'][$key][$key2] . ' LINES ';
							$manual .= (($pos == 'after') ? 'BELOW ' : 'ABOVE ') . '</b>';
							$manual .= (isset($indexs) || !$_POST['index'][$key][$key2]) ? 'EACH:' : 'IT:';
						}
						$manual .= '</div>'."\n";
					}
					$manual .= "\t\t\t" . '<div class="code ' . $posid . '"><textarea>' . stripslashes($_POST['add'][$key][$key2]) . '</textarea></div><div class="code ' . $posid . '" style="display:none;"></div>'."\n";
// EOF - Zappo - vQGen Manual Generator - Added Simple html manual
					
					if($_POST['newop'][$key][$key2]>0){
						for($i=0; $i< $_POST['newop'][$key][$key2]; $i++){
							$output .= "\n\t\t" . '<operation>';
							$output .= "\n\t\t\t" . '<search';
							$output .= ' position="replace">';
							$output .= '<![CDATA[]]></search>';
							$output .= "\n\t\t\t" . '<add><![CDATA[]]></add>';
							$output .= "\n\t\t" . '</operation>';
						}
					}
				}
			}
			$output .= "\n\t" . '</file>';
// BOF - Zappo - vQGen Manual Generator - ONE LINE - Added Simple html manual
			$manual .= "\t\t" . '</div>'."\n";
		}
	}
	$output .= "\n" . '</modification>';
	
// BOF - Zappo - vQGen Manual Generator - Added Simple html manual
	if (isset($_POST['manual']) && $_POST['manual']) {
		$html = getHtmlPage($manual);
		$manual = PATH . stripText($_POST['filename']) . '.html';
		$fp = fopen($manual, "w");
		$fout = fwrite($fp, $html);
		fclose($fp);
		chmod($manual, 0777);
	}
// EOF - Zappo - vQGen Manual Generator - Added Simple html manual

	$fp = fopen( $file , "w" );
	$fout = fwrite( $fp , $output );
	fclose( $fp );
	chmod($file, 0777);
	header("Location:./?generated=1&file=" . stripText($_POST['filename']). '.xml_');
}

if(isset($_GET['file'])){
	if(substr($_GET['file'], -1)!='_'){
		@rename(PATH . $_GET['file'], PATH . $_GET['file'].'_');
		$_GET['file'] .= '_';
	}else{
		foreach (glob(CACHE . '*.*') as $cachefile) {
			@unlink($cachefile);
		}
	}
	
	function xml2assoc($xml) {
		$tree = null;
		while($xml->read())
			switch ($xml->nodeType) {
				case XMLReader::END_ELEMENT: return $tree;
				case XMLReader::ELEMENT:
					$node = array('tag' => $xml->name, 'value' => $xml->isEmptyElement ? '' : xml2assoc($xml));
					if($xml->hasAttributes)
						while($xml->moveToNextAttribute())
							$node['attributes'][$xml->name] = $xml->value;
					$tree[] = $node;
				break;
				case XMLReader::TEXT:
				case XMLReader::CDATA:
					$tree .= $xml->value;
			}
		return $tree;
	} 

    $xml = new XMLReader();
    $xml->open(PATH . $_GET['file']);
	$assoc = xml2assoc($xml);
    $xml->close();
	
	// Get Modification Data
	$data = $assoc[0]['value'];
	
	// Get header Info
	$id = array_shift($data);
	$version = array_shift($data);
	$vqmver = array_shift($data);
	$author = array_shift($data);
}

// BOF - Zappo - vQGen Manual Generator - Added Simple html manual
function getHtmlPage($html) {
	$manual = '<!DOCTYPE HTML>'."\n";
	$manual .= '<html>'."\n";
	$manual .= "\t" . '<head>'."\n";
	$manual .= "\t\t" . '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />'."\n";
	$manual .= "\t\t" . '<title>' . stripslashes($_POST['fileid']) . ' | ' . stripslashes($_POST['author']) . '</title>'."\n";
	$manual .= "\t\t" . '<style type="text/css">'."\n";
	$manual .= "\t\t\t" . 'body {'."\n";
	$manual .= "\t\t\t\t" . 'font:80%/1 Verdana, Geneva, sans-serif;'."\n";
	$manual .= "\t\t\t\t" . 'color: #457000;'."\n";
	$manual .= "\t\t\t" . '}'."\n";
	$manual .= "\t\t\t" . 'div {'."\n";
	$manual .= "\t\t\t\t" . 'width:950px;'."\n";
	$manual .= "\t\t\t\t" . 'padding: 6px;'."\n";
	$manual .= "\t\t\t\t" . 'margin: 20px;'."\n";
	$manual .= "\t\t\t" . '}'."\n";
	$manual .= "\t\t\t" . '#file {'."\n";
	$manual .= "\t\t\t\t" . 'height:100px;'."\n";
	$manual .= "\t\t\t\t" . 'font-size:24px;'."\n";
	$manual .= "\t\t\t\t" . 'margin-bottom: 0px;'."\n";
	$manual .= "\t\t\t\t" . 'background-color:#f2ffdd;'."\n";
	$manual .= "\t\t\t\t" . 'border:1px solid #86db00;'."\n";
	$manual .= "\t\t\t\t" . '-webkit-border-radius: 7px 7px 7px 7px;'."\n";
	$manual .= "\t\t\t\t" . '-moz-border-radius: 7px 7px 7px 7px;'."\n";
	$manual .= "\t\t\t\t" . '-khtml-border-radius: 7px 7px 7px 7px;'."\n";
	$manual .= "\t\t\t\t" . 'border-radius: 7px 7px 7px 7px;'."\n";
	$manual .= "\t\t\t\t" . '-webkit-box-shadow:4px 4px 5px #DDDDDD;'."\n";
	$manual .= "\t\t\t\t" . '-moz-box-shadow:4px 4px 5px #DDDDDD;'."\n";
	$manual .= "\t\t\t\t" . 'box-shadow:4px 4px 5px #DDDDDD;'."\n";
	$manual .= "\t\t\t" . '}'."\n";
	$manual .= "\t\t\t" . '#author {'."\n";
	$manual .= "\t\t\t\t" . 'width:900px;'."\n";
	$manual .= "\t\t\t\t" . 'font-size:12px;'."\n";
	$manual .= "\t\t\t" . '}'."\n";
	$manual .= "\t\t\t" . '.infile {'."\n";
	$manual .= "\t\t\t\t" . 'cursor: pointer;'."\n";
	$manual .= "\t\t\t\t" . 'font-size:18px;'."\n";
	$manual .= "\t\t\t\t" . 'margin: 40px 20px 0px 20px;'."\n";
	$manual .= "\t\t\t\t" . 'background-color:#86db00;'."\n";
	$manual .= "\t\t\t\t" . 'border:1px solid #457000;'."\n";
	$manual .= "\t\t\t\t" . '-webkit-border-radius: 7px 7px 0px 0px;'."\n";
	$manual .= "\t\t\t\t" . '-moz-border-radius: 7px 7px 0px 0px;'."\n";
	$manual .= "\t\t\t\t" . '-khtml-border-radius: 7px 7px 0px 0px;'."\n";
	$manual .= "\t\t\t\t" . 'border-radius: 7px 7px 0px 0px;'."\n";
	$manual .= "\t\t\t\t" . '-webkit-box-shadow:4px 4px 5px #DDDDDD;'."\n";
	$manual .= "\t\t\t\t" . '-moz-box-shadow:4px 4px 5px #DDDDDD;'."\n";
	$manual .= "\t\t\t\t" . 'box-shadow:4px 4px 5px #DDDDDD;'."\n";
	$manual .= "\t\t\t" . '}'."\n";
	$manual .= "\t\t\t" . '.vqfile {'."\n";
	$manual .= "\t\t\t\t" . 'margin: 0px 20px 40px 20px;'."\n";
	$manual .= "\t\t\t\t" . 'background-color:#f2ffdd;'."\n";
	$manual .= "\t\t\t\t" . 'border:1px solid #86db00;'."\n";
	$manual .= "\t\t\t\t" . '-webkit-border-radius: 0px 0px 7px 7px;'."\n";
	$manual .= "\t\t\t\t" . '-moz-border-radius: 0px 0px 7px 7px;'."\n";
	$manual .= "\t\t\t\t" . '-khtml-border-radius: 0px 0px 7px 7px;'."\n";
	$manual .= "\t\t\t\t" . 'border-radius: 0px 0px 7px 7px;'."\n";
	$manual .= "\t\t\t\t" . '-webkit-box-shadow:4px 4px 5px #DDDDDD;'."\n";
	$manual .= "\t\t\t\t" . '-moz-box-shadow:4px 4px 5px #DDDDDD;'."\n";
	$manual .= "\t\t\t\t" . 'box-shadow:4px 4px 5px #DDDDDD;'."\n";
	$manual .= "\t\t\t" . '}'."\n";
	$manual .= "\t\t\t" . '.search {'."\n";
	$manual .= "\t\t\t\t" . 'cursor: pointer;'."\n";
	$manual .= "\t\t\t\t" . 'width:890px;'."\n";
	$manual .= "\t\t\t\t" . 'margin-bottom: 0px;'."\n";
	$manual .= "\t\t\t\t" . 'background-color:#deffaa;'."\n";
	$manual .= "\t\t\t\t" . 'border:1px solid #457000;'."\n";
	$manual .= "\t\t\t\t" . 'border-bottom:0px;'."\n";
	$manual .= "\t\t\t\t" . '-webkit-border-radius: 7px 7px 0px 0px;'."\n";
	$manual .= "\t\t\t\t" . '-moz-border-radius: 7px 7px 0px 0px;'."\n";
	$manual .= "\t\t\t\t" . '-khtml-border-radius: 7px 7px 0px 0px;'."\n";
	$manual .= "\t\t\t\t" . 'border-radius: 7px 7px 0px 0px;'."\n";
	$manual .= "\t\t\t" . '}'."\n";
	$manual .= "\t\t\t" . '.find {'."\n";
	$manual .= "\t\t\t\t" . 'color: #FFFFF;'."\n";
	$manual .= "\t\t\t\t" . 'width:890px;'."\n";
	$manual .= "\t\t\t\t" . 'margin: 0px 20px;'."\n";
	$manual .= "\t\t\t\t" . 'background-color:#deffaa;'."\n";
	$manual .= "\t\t\t\t" . 'border:1px solid #457000;'."\n";
	$manual .= "\t\t\t\t" . 'border-bottom:0px;'."\n";
	$manual .= "\t\t\t\t" . 'border-top:0px;'."\n";
	$manual .= "\t\t\t" . '}'."\n";
	$manual .= "\t\t\t" . '.find > textarea {'."\n";
	$manual .= "\t\t\t\t" . 'width:885px;'."\n";
	$manual .= "\t\t\t\t" . 'height:20px;'."\n";
	$manual .= "\t\t\t\t" . 'background-color:#f2ffdd;'."\n";
	$manual .= "\t\t\t" . '}'."\n";
	$manual .= "\t\t\t" . '.action {'."\n";
	$manual .= "\t\t\t\t" . 'width:890px;'."\n";
	$manual .= "\t\t\t\t" . 'margin: 0px 20px;'."\n";
	$manual .= "\t\t\t\t" . 'background-color:#deffaa;'."\n";
	$manual .= "\t\t\t\t" . 'border:1px solid #457000;'."\n";
	$manual .= "\t\t\t\t" . 'border-bottom:0px;'."\n";
	$manual .= "\t\t\t\t" . 'border-top:0px;'."\n";
	$manual .= "\t\t\t" . '}'."\n";
	$manual .= "\t\t\t" . '.code {'."\n";
	$manual .= "\t\t\t\t" . 'width:890px;'."\n";
	$manual .= "\t\t\t\t" . 'margin: 0px 20px 40px 20px;'."\n";
	$manual .= "\t\t\t\t" . 'background-color:#deffaa;'."\n";
	$manual .= "\t\t\t\t" . 'border:1px solid #457000;'."\n";
	$manual .= "\t\t\t\t" . 'border-top:0px;'."\n";
	$manual .= "\t\t\t\t" . '-webkit-border-radius: 0px 0px 7px 7px;'."\n";
	$manual .= "\t\t\t\t" . '-moz-border-radius: 0px 0px 7px 7px;'."\n";
	$manual .= "\t\t\t\t" . '-khtml-border-radius: 0px 0px 7px 7px;'."\n";
	$manual .= "\t\t\t\t" . 'border-radius: 0px 0px 7px 7px;'."\n";
	$manual .= "\t\t\t" . '}'."\n";
	$manual .= "\t\t\t" . '.code > textarea {'."\n";
	$manual .= "\t\t\t\t" . 'width:885px;'."\n";
	$manual .= "\t\t\t\t" . 'height:240px;'."\n";
	$manual .= "\t\t\t\t" . 'margin-bottom: 20px;'."\n";
	$manual .= "\t\t\t\t" . 'background-color:#f2ffdd;'."\n";
	$manual .= "\t\t\t" . '}'."\n";
	$manual .= "\t\t" . '</style>'."\n";
	$manual .= "\t\t" . '<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>'."\n";
	$manual .= "\t" . '</head>'."\n";
	$manual .= "\t" . '<body>'."\n";
	$manual .= $html;
	$manual .= "\t\t" . '<script type="text/javascript">'."\n";
	$manual .= "\t\t\t" . '$(".infile").click(function() {'."\n";
	$manual .= "\t\t\t\t" . '$(this).next(".vqfile").slideToggle();'."\n";
	$manual .= "\t\t\t" . '});'."\n";
	$manual .= "\t\t\t" . '$(".search").click(function() {'."\n";
	$manual .= "\t\t\t\t" . '$("." + $(this).data("id")).slideToggle();'."\n";
	$manual .= "\t\t\t" . '});'."\n";
	$manual .= "\t\t" . '</script>'."\n";
	$manual .= "\t" . '</body>'."\n";
	$manual .= '</html>'."\n";
	return $manual;
}
// EOF - Zappo - vQGen Manual Generator - Added Simple html manual
