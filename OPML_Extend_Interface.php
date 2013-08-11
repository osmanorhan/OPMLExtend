<?php

/**
 * Description of OPML_Extend_Interface
 *
 * @author osmanorhan
 */
interface OPML_Extend_Interface {

    function setOPML($opml);

    function process();

    function setStatus();

    function removeInactive();

    function feedStatus($xmlUrl);
    
    function saveOPML();
}

?>
