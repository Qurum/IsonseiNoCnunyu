# IsonseiNoCnunyu
Простая реализация автоматического контейнера зависимостей 

### Usage
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

Методу (в т.ч. конструктору) может быть передана строка, начинающаяся с префикса str:
```yaml
services:
    some_service: 
        factory: 
            some_factory:
            - "str: My String 1"
            - "str: My String 2"
```


Типы, в которые входит более одного класса, должны быть явно специфицированы в секции services.

## Installation:

Add this to composer.json:

```javascript
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://git@github.com/Qurum/IsonseiNoCnunyu.git"
        }
    ],
    "require": {
        "abethropalle/isonsei_no_cnunyu": "dev-master"
    }
}
```
You may be also interested in https://docs.github.com/en/authentication/troubleshooting-ssh/error-permission-denied-publickey

For tests: edit phpunit.xml and set PATH_TO_DATATRANSFERPROJECTSTUB, which should be the path to the folder that contains the source code of https://github.com/Qurum/DataTransferProjectStub
