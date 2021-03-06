
## Router Katmanı

Router katmanı route işlevleri gerçekleşmeden önceki aşamayı yönetir ve varsayılan olarak katmanlar içerisinde tanımlıdır.

```php
public function __invoke(Request $request, Response $response, callable $next = null)
{
    if ($this->getContainer()->get('router')->getDefaultPage() == '') {

        $error = 'Unable to determine what should be displayed.';
        $error.= 'A default route has not been specified in the router middleware.';

        $body = $this->getContainer()->get('view')
            ->withStream()
            ->get(
                'templates::error', 
                [
                    'error' => $error
                ]
            );

        return $response->withStatus(404)
            ->withHeader('Content-Type', 'text/html')
            ->withBody($body);
    }
    
    $err = null;

    return $next($request, $response, $err);
}
```

#### Kurulum

```php
http://github.com/obullo/http-middlewares/
```

Eğer katman mevcut değilse yukarıdaki kaynaktan <kbd>Router.php</kbd> dosyasını uygulamanızın <kbd>app/classes/Http/Middlewares/</kbd> klasörüne kopyalayın.


#### Konfigürasyon

Katmanın çalışabilmesi için evrensel katmanlar içerisine eklenmesi gerekir.

```php
$middleware->init(
    [
        'Router',
        // 'View',
    ]
);
```
