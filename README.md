# IsonseiNoCnunyu
Простая реализация автоматического контейнера зависимостей. 

```php
use Abethropalle\IsonseiNoChunyu\Container;

$container = new Container('config.yaml');
$main = $container->get('Main');
``
