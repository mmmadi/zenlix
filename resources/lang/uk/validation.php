<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Мовні ресурси перевірки введення
    |--------------------------------------------------------------------------
    |
    | Наступні ресурси містять стандартні повідомлення перевірки коректності
    | введення даних. Деякі з цих правил мають декілька варіантів, як,
    | наприклад, size. Ви можете змінити будь-яке з цих повідомлень.
    |
    */

    'accepted'             => 'Ви повині прийняти :attribute.',
    'active_url'           => 'Поле :attribute не є правильним URL.',
    'after'                => 'Поле :attribute повинно містити дату не раніше :date.',
    'alpha'                => 'Поле :attribute повинно містити лише літери.',
    'alpha_dash'           => 'Поле :attribute повинно містити лише літери, цифри та підкреслення.',
    'alpha_num'            => 'Поле :attribute повинно містити лише літери та цифри.',
    'array'                => 'Поле :attribute повинно бути масивом.',
    'before'               => 'Поле :attribute повинно містити дату не пізніше :date.',
    'between'              => [
        'numeric' => 'Поле :attribute повинно бути між :min та :max.',
        'file'    => 'Розмір файлу в полі :attribute повинен бути між :min та :max Кілобайт.',
        'string'  => 'Текст в полі :attribute повинен мати довжину від :min до :max символів.',
        'array'   => 'Поле :attribute повино містити від :min до :max елементів.',
    ],
    'boolean'              => 'Поле :attribute повинно містити логічний тип.',
    'confirmed'            => 'Поле :attribute не співпадає з підтвердженням.',
    'date'                 => 'Поле :attribute не є датою.',
    'date_format'          => 'Поле :attribute не співпадає з форматом :format.',
    'different'            => 'Поля :attribute та :other повинні бути різними.',
    'digits'               => 'Довжина цифрового поля :attribute повинна дорівнювати :digits.',
    'digits_between'       => 'Довжина цифрового поля :attribute повинна бути від :min до :max.',
    'email'                => 'Поле :attribute повинно містити коректну електронну адресу.',
    'filled'               => 'Поле :attribute є обов\'язковим для введення.',
    'exists'               => 'Вибране для :attribute значення не коректне.',
    'image'                => 'Поле :attribute повинно містити зображення.',
    'in'                   => 'Вибране для :attribute значення не коректне.',
    'integer'              => 'Поле :attribute повинно містити ціле число.',
    'ip'                   => 'Поле :attribute повинно містити IP адресу.',
    'json'                 => 'The :attribute must be a valid JSON string.',
    'max'                  => [
        'numeric' => 'Поле :attribute повинно бути не більше :max.',
        'file'    => 'Файл в полі :attribute повинен бути не більше :max Кілобайт.',
        'string'  => 'Текст в полі :attribute повинен мати довжину не більшу за :max.',
        'array'   => 'Поле :attribute повино містити не більше :max елементів.',
    ],
    'mimes'                => 'Поле :attribute повинно містити файл одного з типів: :values.',
    'min'                  => [
        'numeric' => 'Поле :attribute повинно бути не більше :min.',
        'file'    => 'Файл в полі :attribute повинен бути не менше :min Кілобайт.',
        'string'  => 'Текст в полі :attribute повинен мати довжину не меншу за :min.',
        'array'   => 'Поле :attribute повино містити не менше :min елементів.',
    ],
    'not_in'               => 'Вибране для :attribute значення не коректне.',
    'numeric'              => 'Поле :attribute повинно містити число.',
    'regex'                => 'Поле :attribute має хибний формат.',
    'required'             => 'Поле :attribute є обов\'язковим для введення.',
    'required_if'          => 'Поле :attribute є обов\'язковим для введення, коли :other є рівним :value.',
    'required_with'        => 'Поле :attribute є обов\'язковим для введення, коли :values вказано.',
    'required_with_all'    => 'Поле :attribute є обов\'язковим для введення, коли :values вказано.',
    'required_without'     => 'Поле :attribute є обов\'язковим для введення, коли :values не вказано.',
    'required_without_all' => 'Поле :attribute є обов\'язковим для введення, коли :values не вказано.',
    'same'                 => 'Поля :attribute та :other повинні співпадати.',
    'size'                 => [
        'numeric' => 'Поле :attribute повинно бути довжини :size.',
        'file'    => 'Файл в полі :attribute повинен бути розміром :size Кілобайт.',
        'string'  => 'Текст в полі :attribute повинен бути довжини :size.',
        'array'   => 'Поле :attribute повино містити :size елементів.',
    ],
    'timezone'             => 'Поле :attribute повино містити коректну часову зону.',
    'unique'               => 'Таке значення поля :attribute вже існує.',
    'url'                  => 'Формат поля :attribute неправильний.',

    /*
    |--------------------------------------------------------------------------
    | Додаткові ресурси для перевірки введення
    |--------------------------------------------------------------------------
    |
    | Тут Ви можете вказати власні ресурси для підтвердження введення,
    | використовуючи формат"attribute.rule", щоб дати назву текстовим змінним.
    | Так ви зможете легко додати текст повідомлення для заданого атрибуту.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Власні назви атрибутів
    |--------------------------------------------------------------------------
    |
    | Наступні правила дозволяють налаштувати заміну назв полів введення
    | для зручності користувачів. Наприклад, вказати"Електронна адреса"замість
    |"email".
    |
    */

    'attributes' => [],

];
