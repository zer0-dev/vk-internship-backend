# VK internship task

Упрощённый сервис с регистрацией и авторизацией с дополнительным функционалом хэширования паролей и временем действия у токенов

## Установка и запуск

Для запуска потребуется Docker с Docker Compose.

1. Скопировать репозиторий:
```bash
git clone -b additional https://github.com/zer0-dev/vk-internship-backend.git
```

2. В директории проекта запустить приложение:
```bash
docker-compose up -d
```

3. Установить пакеты composer:
```bash
docker-compose exec -w /var/www/task php composer install
```

Приложение будет доступно по адресу http://localhost

Дефолтный порт 80 можно изменить в `docker-compose.yml`.

Остановить приложение:
```bash
docker-compose down
```

## Использование приложения

Приложение предоставляет три метода:
- **/register** - регистрация пользователя
- **/authorize** - авторизация пользователя
- **/feed** - проверяет валидность токена доступа

### /register

Метод: `POST`

Параметры:
- `string email` - e-mail пользователя
- `string password` - пароль

Ответ:
```json
{
    "user_id": "int",
    "password_check_status": "string"
}
```

### /authorize

Метод: `POST`

Параметры:
- `string email` - e-mail пользователя
- `string password` - пароль

Ответ:
```json
{
    "access_token": "string",
}
```

### /feed

Метод: `GET`

Параметры:
- `string access_token` - токен доступа, полученный в /authorize

Ответ:

`HTTP 200 OK` в случае успеха

`HTTP 401 Unauthorized` в случае неудачи
