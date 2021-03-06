
## TrustedIp Katmanı

Eğer sunucunuz ters bir proxy sunucu arkasında ise kullanıcının gerçek ip adresini belirleyebilmek için proxy ip adreslerinin temiz listesine eklenerek ayrıştırılması gerekir. Aksi durumda <kbd>$this->request->getIpAddress()</kbd> metodu proxy sunucu adresine dönecektir. TrustedIp katmanı çalıştırılırsa liste içerisindeki ip adresleri dikkate alınmaz böylece <kbd>$this->request->getIpAddress()</kbd> metodu kullanıcının gerçek ip adresine döner.

```php
class TrustedIp implements MiddlewareInterface
{
    public function __invoke(Request $request, Response $response, callable $next = null)
    {
        /**
         * Reverse Proxy IPs : If your server is behind a reverse proxy,
         *                     you must whitelist the proxy IP addresses.
         *                     Comma-delimited, e.g. '10.0.1.200,10.0.1.201'
         */
        $proxyIps = '10.0.1.200,10.0.1.201';
        
        if (! empty($proxyIps)) {

            $server = $request->getServerParams();
            $remoteAddr = isset($server['REMOTE_ADDR']) ? $server['REMOTE_ADDR'] : '0.0.0.0';
            $ipAddress = $this->detectIpAddress($proxyIps, $remoteAddr, $server);
            
            $request = $request->withAttribute('TRUSTED_IP', $ipAddress);
        }

        return $next($request, $response);
    }

    // ...
}
```

#### Konfigürasyon

Eğer tanımlı değilse <kbd>app/middlewares.php</kbd> dosyası içerisine katmanı aşağıdaki gibi tanımlayın.

```php
$middleware->register(
    [
        'TrustedIp' => 'Http\Middlewares\TrustedIp',
    ]
);
```

Katmanın çalışabilmesi için evrensel katmanlar içerisine eklenmesi gerekir.

```php
$middleware->init(
    [
        'TrustedIp',
        'Router',
        // 'View',
    ]
);
```

#### Kurulum

```php
http://github.com/obullo/http-middlewares/
```

Eğer katman mevcut değilse yukarıdaki kaynaktan <kbd>TrustedIp.php</kbd> dosyasını uygulamanızın <kbd>app/classes/Http/Middlewares/</kbd> klasörüne kopyalayın.
