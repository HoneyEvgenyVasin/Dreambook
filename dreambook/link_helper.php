<?php
    function getLink($classname, $text, $href){
        printf('<a class="%s" href="%s">%s </a>', $classname, $href, $text);
    }
?>