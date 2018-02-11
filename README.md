# Blade for Viper Framework
A Blade templating language adaptation for the Viper Framework

For Blade doc, refer to the [official Laravel documentation on Blade](https://laravel.com/docs/5.5/blade)

## Usage

May be used from the controller like this:
```php
namespace App\Controllers;

use Viper\Core\Routing\Controller;
use Viper\Core\Routing\Methods\GET;
use Viper\Core\Viewable;
use Blade\View;
use App\Models\Client;

class HomeController extends Controller implements GET
{

    public function get (...$args): ?Viewable
    {
        $clients = Client::all();
        return new BladeView('test', [
            'folks' => $clients,
        ]);
    }

}

```


### Exception reporting
Exceptions may be parsed nicely in a custom view.
By default, the `error.blade.php` is invoked with `$e` variable for exception

use `\Blade\View::bindErrorView()` for configuration


### Setup
Extra variables and error preferences may be passed from a filter ([official doc on filters](https://github.com/arsengoian/viper-framework#filters)):

```php
namespace App\Filters;

use Blade\View;
use Viper\Core\Filter;

class BladeFilter extends Filter
{

    public function proceed ()
    {
        // Set up custom error reporting
        if (!Config::get('DEBUG'))
            View::bindErrorView('publicErrorView', 'exception');
        
        // These variables will be visible in all the views
        View::propagateVars([
            'myCustomVar' => 42,
            'clientHandler' => Config::get('Clients.HANDLER'),
        ]);
    }
}
```
