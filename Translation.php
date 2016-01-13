<?php

namespace Http\Middlewares;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use Obullo\Config\ConfigInterface as Config;
use Obullo\Container\ContainerAwareInterface;
use Obullo\Http\Middleware\MiddlewareInterface;
use Obullo\Container\ContainerInterface as Container;
use Obullo\Translation\TranslatorInterface as Translator;

class Translation implements MiddlewareInterface, ContainerAwareInterface
{
    protected $c;
    protected $config;
    protected $request;
    protected $translator;
    protected $cookieValue;

    /**
     * Constructor
     * 
     * @param Config     $config     config
     * @param Translator $translator translator
     */
    public function __construct(Config $config, Translator $translator)
    {
        $this->config = $config->load('translator');
        $this->translator = $translator;
    }

    /**
     * Sets the Container.
     *
     * @param ContainerInterface|null $container object or null
     *
     * @return void
     */
    public function setContainer(Container $container = null)
    {
        $this->c = $container;
    }

    /**
     * Invoke middleware
     * 
     * @param ServerRequestInterface $request  request
     * @param ResponseInterface      $response response
     * @param callable               $next     callable
     * 
     * @return object ResponseInterface
     */
    public function __invoke(Request $request, Response $response, callable $next = null)
    {
        $this->request = $request;
        $this->cookieValue = $this->readCookie();
        $this->setLocale();
        $this->setFallback();

        return $next($request, $response);
    }

    /**
     * Read "locale" cookie value
     * 
     * @return string|null
     */
    protected function readCookie()
    {
        $name = $this->config['cookie']['name'];
        $cookies = $this->request->getCookieParams();

        return isset($cookies[$name]) ? $cookies[$name] : null;
    }

    /**
     * Set default locale
     * 
     * @return void
     */
    protected function setLocale()
    {
        if (defined('STDIN')) { // Disable console & task errors
            return;
        }
        if ($this->setByUri()) {  // Sets using http://example.com/en/welcome first segment of uri
            return;
        }
        if ($this->setByOldCookie()) {   // Sets by reading old cookie 
            return;
        }
        if ($this->setByBrowserDefault()) {  // Sets by detecting browser language using intl extension  
            return;
        }
        $this->setDefault();  // Set using default language which is configured in translator config
    }

    /**
     * Set fallback value
     *
     * @return void
     */
    protected function setFallback()
    {
        $locale   = $this->translator->getLocale();
        $fallback = $this->translator->getFallback();

        if (! $this->translator->hasFolder($locale)) {  // If language folder does not exist,
            $this->translator->setLocale($fallback);    // set fallback language.
        }
    }

    /**
     * Set using uri http GET request
     *
     * @return bool
     */
    protected function setByUri()
    {
        if ($this->config['uri']['segment']) {

            $segment = $this->request->getUri()->segment($this->config['uri']['segmentNumber']);  // Set via URI Segment

            if (! empty($segment)) {
                $bool = ($this->cookieValue == $segment) ? false : true; // Do not write if cookie == segment value same
                if ($this->translator->setLocale($segment, $bool)) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Set using browser old cookie
     *
     * @return bool
     */
    protected function setByOldCookie()
    {       
        if (! empty($this->cookieValue)) {                           // If we have a cookie
            $this->translator->setLocale($this->cookieValue, false); // Do not write to cookie just set variable.
            return true;
        }
        return false;
    }

    /**
     * Set using php intl extension
     *
     * @return bool
     */
    protected function setByBrowserDefault()
    {
        $intl = extension_loaded('intl');     // Intl extension should be enabled.

        if ($intl == false) {
            $this->c['logger']->notice('Install php intl extension to enable detecting browser language feature.');
            return false;
        }
        $server = $this->request->getServerParams();

        if (isset($server['HTTP_ACCEPT_LANGUAGE']) && $intl) {   // Set via browser default value
            $default = strstr(\Locale::acceptFromHttp($server['HTTP_ACCEPT_LANGUAGE']), '_', true);
            $this->translator->setLocale($default);
            return true;
        }
        return false;
    }

    /**
     * Set using alternative default language
     *
     * @return void
     */
    protected function setDefault()
    {
        $this->translator->setLocale($this->translator->getDefault());
    }

}