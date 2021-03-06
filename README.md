
### Http Middlewares

All middlewares of <kbd>version 1.0</kbd> are listed below.

### Available Docs

* [TR](docs/tr)

### List Of Middlewares

<table>
    <thead>
        <tr>
            <th>Middleware</th>
            <th>Description</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>FinalHandler/Zend</td>
            <td>Middleware for application shutdown and terminate http middlewares.</td>
        </tr>
        <tr>
            <td>Auth</td>
            <td>Middleware for authorized users.</td>
        </tr>
        <tr>
            <td>Debugger</td>
            <td>Middleware to forward page requests to http debugger websocket server.</td>
        </tr>
        <tr>
            <td>Error</td>
            <td>Middleware for catching all application exceptions. Also catches Throwable php errors for PHP 7 and newer versions.</td>
        </tr>
        <tr>
            <td>Guest</td>
            <td>Middleware for none authorized users.</td>
        </tr>
        <tr>
            <td>Https</td>
            <td>Middleware to force unsecure http requests to secure.</td>
        </tr>
        <tr>
            <td>Maintenance</td>
            <td>Middleware to control under the maintenance functionality.</td>
        </tr>
        <tr>
            <td>Not Allowed</td>
            <td>Middleware to create disallowed http method functionality.</td>
        </tr>
        <tr>
            <td>ParsedBody</td>
            <td>Middleware to parse http request body. (Json, xml etc.)</td>
        </tr>
        <tr>
            <td>RewriteLocale</td>
            <td>Middleware to rewrite language uri segment.</td>
        </tr>
        <tr>
            <td>Translation</td>
            <td>Middleware to read/write language variable to http cookie.</td>
        </tr>
        <tr>
            <td>TrustedIp</td>
            <td>Middleware to create whitelist for proxy IP addresses.</td>
        </tr>
    </tbody>
</table>
