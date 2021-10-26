# IsonseiNoCnunyu
Простая реализация автоматического контейнера зависимостей. 

```php
use Abethropalle\IsonseiNoChunyu\Container;

$container = new Container('config.yaml');
$main = $container->get('Main');
```

### Конфигурационный файл
Должен быть в формате yaml. Образец:
```yaml
namespace: Abethropalle\DataTransferProjectStub
dir: Tests\resources\DataTransferProjectStub

services:
    ProviderInterface: XmlProvider
    LoggerInterface: EchoLogger
```

**Сервис** - имя типа, т.е. интерфейса, абстрактного класса или конкретного класса.    
**Фабрика** - конкретный класс, который может быть инстанцирован.    

**namespace** - пространство имён, классы которого могут быть получены через контейнер.    
**dir** - путь к директории, в которой лежат классы файлов. Следует соблюдать PSR-4.    
**services** описывает сервисы в виде 
```yaml
services:
    some_service: 
        factory: 
            concrete_class: [ ...args]
        setup:
        - setup_method1: [ ...args]
        - setup_method1: [ ...args]
```
**some_service** - имя сервиса.    
**concrete_class** - имя фабрики, конструктору которой будет переданы указанные аргументы.    
**setup** - список методов, которые будут вызваны после создания объекта с указанными аргументами.    
**...args** - массив аргументов. Допустимы имена сервисов, типы и имена фабрик. Часть аргументов может быть опущена.    

Допустим сокращённый синтаксис:
```yaml
services:
    some_service1: some_factory1
    some_service2: some_factory2
```

Типы, в которые входит более одного класса, должны быть явно специфицированы в секции services.
