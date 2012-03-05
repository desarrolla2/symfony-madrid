<?php

namespace SFM\WebsiteBundle\Service;

class SensioConnectService
{
    protected $groupName;

    /**
     * (@see services.yml)
     * @param string $name
     */
    public function __construct($name = null)
    {
        if (null !== $name) {
            $this->setGroupName($name);
        }
    }

    /**
     * Sets configured RSS Feeds (@see services.yml)
     * @param string $name
     * @return \SFM\WebsiteBundle\Service\SensioConnectService
     */
    public function setGroupName($name)
    {
        $this->groupName = $name;
        return $this;
    }

    /**
     * Gets injected RSS Feeds
     * @return array
     */
    public function getGroupName()
    {
        return $this->groupName;
    }

    /**
     * Gets Symfony-Madrid info from Sensio Connect. Info is stored in APC during an hour to increase speed
     * @return array
     */
    public function getGroupInfo()
    {
        $apcKey = 'sfm_sensioconnect';
        if (extension_loaded('apc')) {
            if (apc_exists($apcKey)) {
                $sfConnect = apc_fetch($apcKey);
            } else {
                $sfConnect = $this->getSensioJson();
                apc_store($apcKey, $sfConnect, 3600);
            }
        } else {
            $sfConnect = $this->getSensioJson();
        }

        return $sfConnect;
    }

    /**
     * Actually connects to Sensio Connect and gets decoded json info
     * @return array
     */
    private function getSensioJson()
    {
        return json_decode(file_get_contents('https://connect.sensiolabs.com/club/' . $this->getGroupName() . '.json'), true);
    }
}