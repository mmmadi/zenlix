SMSCenter
=========

Класс для работы с сервисом smsc.ru (SMS-Центр)

Функции:
* отправка одного/нескольких сообщений на один/несколько номеров одним запросом
* проверка статуса сообщений
* получение стоимости рассылки
* проверка баланса
* получение информации об операторе по номеру

Минимальные требования — **PHP 5.4**+

***

Допустимые ключи массива настроек (в скобках значения по-умолчанию):
```php
$default = [
    'sender',   // имя отправителя
    'translit', // кодировать ли сообщения в транслит (self::TRANSLIT_NONE)
    'charset',  // кодировка запроса и ответа (self::CHARSET_UTF8)
    'fmt',      // формат ответа сервера (self::FMT_JSON)
    'type',     // тип сообщения (self::MSG_SMS), замена push, ping, hlr и прочих
    'cost',     // запрашивать ли стоимость (self::COST_NO)
    'time',     // время отправки сообщения (null)
    'tz',       // часовой пояс параметра time (null)
    'period',   // (null)
    'freq',     // (null)
    'maxsms',   // (null)
    'err'       // (null)
];
```

***

Примеры использования:
```php
<?php
// Инициализация
$smsc = new \SMSCenter\SMSCenter('ivan', md5('ivanovich'), false, [
    'charset' => SMSCenter::CHARSET_UTF8
    'fmt' => SMSCenter::FMT_XML
]);

// Отправка сообщения
$smsc->send('+7991111111', 'Превед, медведы!', 'SuperIvan');

// Отправка сообщения на 2 номера
$smsc->send(['+7(999)1111111', '+7(999)222-22-22'], 'Превед, медведы!', 'SuperIvan');
$smsc->send('+7(999)1111111,+7(999)222-22-22', 'Превед, медведы!', 'SuperIvan');

// Отправка разных сообщений на разные номера
$sms->sendMulti([
    ['+79991111111', "Text 1\nnew line"],
    '+79992222222' => 'Text 2',
]);

// Получение стоимости рассылки
$smsc->getCost('7991111111,79992222222', 'Начало около 251 млн лет, конец — 201 млн лет назад.');

// Получение стоимости рассылки разных сообщений на разные номера
$sms->getCostMulti([
    '79991111111' => 'Text 1',
    '79992222222' => 'Text 2',
]);

// Получение баланса
echo $smsc->getBalance(), ' руб.'; // "72.2 руб."

// Получение информации об операторе
$smsc->getOperatorInfo('7991111111');

// Получения статуса сообщения
$smsc->getStatus('+7991111111', 6, SMSCenter::STATUS_INFO_EXT);

// Проверка тарифной зоны
if ($sms->getChargingZone('+79991111111') == self::ZONE_RU) {
    ...
}
```

***

Лицензия: Apache License, Version 2.0