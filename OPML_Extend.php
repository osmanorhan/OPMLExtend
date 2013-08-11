<?php

require 'OPML_Extend_Interface.php';

/**
 * Description of OPML_Extend
 *
 * @author osmanorhan
 */
class OPML_Extend implements OPML_Extend_Interface {

    private $oldOPML;
    private $DOM;

    public function setOPML($opml) {
        $this->oldOPML = $opml;
        return $this;
    }

    public function process() {
        $this->DOM = new DOMDocument;
        $this->DOM->loadXML($this->oldOPML);
        return $this->setStatus()->removeInactive()->saveOPML();
    }

    public function setStatus() {
        foreach ($this->DOM->getElementsByTagName('outline') as $oOutlineField) {
            $aStatus = $this->feedStatus($oOutlineField->getAttribute('xmlUrl'));
            $oOutlineField->setAttribute('status', $aStatus['status']);
            if ('moved' === $aStatus['status']) {
                $oOutlineField->setAttribute('xmlUrl', $aStatus['xmlUrl']);
            }
        }
        $this->DOM->saveXML();
        return $this;
    }

    public function feedStatus($xmlUrl) {
        if ('' !== $xmlUrl) {
            $header = get_headers($xmlUrl, 1);
            $aReturn = array();
            if (preg_match('/HTTP.+/', $header[0]) ? true : false) {
                switch ($header[0]) {
                    case (preg_match('/HTTP\/1.* 200 OK/', $header[0]) ? true : false):
                        $aReturn['status'] = 'active';
                        break;
                    case (preg_match('/HTTP\/1.* 301 Moved Permanently/', $header[0]) ? true : false):
                        $aReturn['status'] = 'moved';
                        $aReturn['xmlUrl'] = is_array($header['Location']) ? $header['Location'][0] : $header['Location'];
                        break;
                    default:
                        $aReturn['status'] = 'inactive';
                        break;
                }
            } else {
                $aReturn['status'] = 'inactive';
            }
            return $aReturn;
        }
    }

    public function removeInactive() {
        foreach ($this->DOM->getElementsByTagName('outline') as $feed) {
            if ('inactive' === $feed->getAttribute('status')) {
                $feed->parentNode->removeChild($feed);
            }
        }
        $this->DOM->saveXML();
        return $this;
    }

    public function saveOPML() {
        $fileName = substr(md5(microtime()), 2, 6);
        $this->DOM->save($fileName . ".xml");
        return $fileName . '.xml';
    }

}

?>
