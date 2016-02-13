<?php
$inputfile = $argv[1];
$outputfile = $argv[2];

$xml = loadForGroup($inputfile);

if(!is_string($xml))
{
    $json = handleXML($xml);
    fixPathArray($json);
    writeToFile(json_encode($json), $outputfile);
    echo "success";
}
else
{
    echo $xml;
}

function writeToFile($jsontext, $file)
{
    $jsontext = str_replace("@attributes", "attributes", $jsontext);
    
    $myfile = fopen($file, "w") or die("Unable to open file!");
    fwrite($myfile, $jsontext);
    fclose($myfile);
}

function json_prepare_xml($domNode)
{
    foreach($domNode->childNodes as $node)
    {
        if($node->hasChildNodes())
        {
            json_prepare_xml($node);
        }
        else
        {
            if($domNode->hasAttributes() && strlen($domNode->nodeValue))
            {
                $domNode->setAttribute("nodeValue", $node->textContent);
                $node->nodeValue = "";
            }
        }
    }
}

function loadForGroup($filename)
{
    if(file_exists($filename))
    {
        $content = file_get_contents($filename);
        if($content)
        {
            try
            {
                $dom = new DOMDocument();
                $dom->loadXML(file_get_contents($filename));
                json_prepare_xml($dom);
                $sxml = simplexml_load_string($dom->saveXML());
                $json = json_decode(json_encode($sxml), true);
                return $json;
            }
            catch(Exception $e)
            {
                return "empty";
            }
        }
        else
        {
            return "empty";
        }
    }
    else
    {
        return $groupid . " is non-existant";
    }
}

function handleXML($json)
{
    $count = countAuthorCommits($json);
    
    $finalAuthors = array();
    foreach($count as $authorname => $array)
    {
        $filestouched = countFilesTouchedByType($json, $authorname);
        
        $commits = $count[$authorname]["commits"];
        $finalAuthors[] = [
                "authorname" => $authorname,
                "gravatar" => makeMD5Gravatar($authorname),
                "touched" => $filestouched,
                "commits" => $commits
        ];
    }
    
    $json["authorinfo"] = $finalAuthors;
    
    return $json;
}

function makeMD5Gravatar($authorname)
{
    if(strpos($authorname, '@') === false)
    {
        $authorname .= "@informatik.uni-hamburg.de";
    }
    return "http://www.gravatar.com/avatar/".md5($authorname);
}

function countAuthorCommits($json)
{
    $counts = array();
    
    foreach($json["logentry"] as $logentry)
    {
        $auth = (string) $logentry["author"];
        if(!isset($counts[$auth]["commits"]))
            $counts[$auth]["commits"] = 0;
        $counts[$auth]["commits"] += 1;
    }
    
    arsort($counts, SORT_NUMERIC);
    
    return $counts;
}

function countFilesTouchedByType($json, $authorname)
{
    $count = [
            "A" => 0,
            "D" => 0,
            "M" => 0,
            "R" => 0
    ];
    
    foreach($json["logentry"] as $logentry)
    {
        if((string) $logentry["author"] == $authorname)
        {
            foreach($logentry["paths"]["path"] as $path)
            {
                if(isset($path["@attributes"]))
                {
                    $count[(string) $path["@attributes"]["action"]] += 1;
                }
                else // when "paths" has only a single entry, @attributes is the object:
                {
                    $count[(string) $path["action"]] += 1;
                }
            }
        }
    }
    return $count;
}

/**
 * Fixes path array in json to always be an array
 *
 * @param array $json
 *            as reference
 */
function fixPathArray(&$json)
{
    foreach($json["logentry"] as &$logentry)
    {
        if(!isset($logentry["paths"]["path"][0]))
        {
            $logentry["paths"]["path"] = array(
                    $logentry["paths"]["path"]
            );
        }
    }
}

?>