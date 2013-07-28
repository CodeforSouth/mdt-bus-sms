<?php
/**
 * Bootstrap class that sets the configuration and instantiates the needed objects
 * User: aramonc
 * Date: 7/27/13
 * Time: 3:28 PM
 * To change this template use File | Settings | File Templates.
 */

namespace SmsBus;

use Services_Twilio_Twiml;
use Symfony\Component\HttpFoundation\Response;
use Zend\Config\Config;
use Zend\I18n\Translator\Translator;

class Bootstrap
{
    private $config;
    private $twiml;
    private $translator;
    private $response;
    private $locale = 'en-US';
    private $acceptedLocales;

    public function __construct()
    {
        // PREPARE THE CONFIGURATION
        $this->config = new Config(include __DIR__ . "/../../config/config.php");

        // INSTANTIATE THE TRANSLATOR
        $this->translator = new Translator();
        $this->translator->addTranslationFilePattern('phparray', $this->config->translation->base_dir, $this->config->translation->file_pattern, 'smsbus');

        // INSTANTIATE THE TWIML OBJECT
        $this->twiml = new \Services_Twilio_Twiml();

        // INSTANTIATE THE RESPONSE AS A TWIML XML RESPONSE
        $this->response = new Response();
        $this->response->headers->set('Content-type', 'text/xml');
        $this->response->setStatusCode(200);
    }

    /**
     * Application configuration
     * @return Config
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Twilio Twiml Service
     * @return Services_Twilio_Twiml
     */
    public function getTwiml()
    {
        return $this->twiml;
    }

    /**
     * Application translator
     * @return Translator
     */
    public function getTranslator()
    {
        return $this->translator;
    }

    /**
     * Response with XML headers for twiml
     * @param bool $empty
     * @return Response
     */
    public function getResponse($empty = false)
    {
        if(!$empty) {
            $this->response->setContent($this->twiml);
        }
        return $this->response;
    }

    /**
     * @param string $loc
     * @return $this
     */
    public function setLocale($loc)
    {
        $shortCode = substr($loc, 0, 2);
        if($this->isAcceptedLocale($shortCode)) {
            $this->locale = $this->config->translation->accepted[$shortCode];
            $this->translator->setLocale($this->locale);
        }

        return $this;
    }

    /**
     * @param string $loc
     * @return bool
     */
    public function isAcceptedLocale($loc)
    {
        return in_array($loc, $this->getAcceptedLocales());
    }

    /**
     * @return array
     */
    public function getAcceptedLocales()
    {
        if(!$this->acceptedLocales){
            $this->acceptedLocales = array_keys(iterator_to_array($this->config->translation->accepted));
        }

        return $this->acceptedLocales;
    }

}