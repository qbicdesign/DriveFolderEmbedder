<?php
    /*
        Plugin Name: Drive Folder Embedder
        Plugin URI: https://github.com/azumbro/DriveFolderEmbedder
        Version: 1.1.0
        Description: A Wordpress plugin that dynamically creates a table with file names and links for a shared Google Drive folder.
        Author: azumbro
    */
    
    defined('ABSPATH') or die('No script kiddies please!');

    function driveFolderEmbedder($atts) {
        extract(shortcode_atts(array(
            "folderid" => "",
            "sort" => "DESC",
            "showheader" => "True",
            "tablecssclass" => ""
        ), $atts));
        $baseURL = "https://drive.google.com/embeddedfolderview?id=";
        $htmlStr = str_replace("script", "", (addslashes (file_get_contents($baseURL . $folderid))));
        $tableID = rand();
        return "

            <table id='driveFolderEmbedder-" . $tableID .  "' class='". $tablecssclass ."'>"
                . ($showheader == "True" ? "<thead><td>File Name</td></thead>" : "") .
                "<tbody></tbody>
            </table>

            <script>
                var sortMethod = '" . $sort . "';
                var sortFunctions = {
                    ASC: function(a, b) {
                        return b.name != a.name ? b.name < a.name ? -1 : 1 : 0
                    },
                    ASC_Natural: function(a, b) {
                        return a.name.localeCompare(b.name, undefined, {
                            numeric: true,
                            sensitivity: 'base'
                        });
                    },
                    DESC: function(a, b) {
                        console.log(a.name, b.name, a.name != b.name ? a.name < b.name ? -1 : 1 : 0)
                        return a.name != b.name ? a.name < b.name ? -1 : 1 : 0
                    },
                    DESC_Natural: function(a, b) {
                        return b.name.localeCompare(a.name, undefined, {
                            numeric: true,
                            sensitivity: 'base'
                        });
                    }
                };
                var sortFunction = sortFunctions[sortMethod];                                                                                                                 
                var parsedEntries = [];                                                                                                                                                                                                                                                                                      
                var htmlStr = '". $htmlStr ."';                                                                                                                                     
                var tempEl = document.createElement('div');                                                                                                                     
                tempEl.innerHTML = htmlStr;                                                                                                                                           
                var entries = tempEl.getElementsByClassName('flip-entry-info');                                                                                                                                                                                                                                               
                for(var entry of entries) {                                                                                                                                     
                    parsedEntries.push({                                                                                                                                  
                        name: entry.innerText,                                                                                                                            
                        url: entry.innerHTML.split('\"')[1]                                                                                                               
                    });                                                                                                                                                    
                }                                                                                                                                                                                                                                                                                                                                                                                                                                        
                parsedEntries.sort(sortFunction);                                                                                                                                                                                                                                                                                                           
                var tableEl = document.getElementById('driveFolderEmbedder-" . $tableID . "').getElementsByTagName('tbody')[0];                                                                                                                                                                                              
                for(var entry of parsedEntries) {                                                                                                                           
                    var tr = document.createElement('tr');                                                                                                                
                    tr.innerHTML = '<li><a href=\"' + entry.url + '\" target=\"_blank\">' + entry.name + '</a></li>';                                                                                                                                                                 
                    tableEl.appendChild(tr);                                                                                                                                   
                }                                                                                                                                                             
            </script>
        ";
    }
    add_shortcode('DriveFolderEmbeder', 'driveFolderEmbedder');
    add_shortcode('DriveFolderEmbedder', 'driveFolderEmbedder');
?>
