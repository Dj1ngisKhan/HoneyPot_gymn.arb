<?php

    function GetClassName($file){
        $path = basename($_SERVER["PHP_SELF"], ".php");
        if($path === $file){
            return "login";
        } else { return "";}
    }


?>
