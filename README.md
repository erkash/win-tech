# win-tech тестовое задание
Для того чтоб развернуть и запустить проект нужно набрать команду `make init`

и необходимо подождать несколько минут пока установятся зависимости и выполнятся миграции

далее приложение будет запущено на порту 8337, принимает запросы по http://localhost:8337

---------

Основной стек: php 8.3 Symfony 6.4 Mysql 8 Nginx 1.27

---------

Реализованный функционал:

Пользователь:

Регистрация нового пользователя

	•	Метод: POST
	•	URL: `/api/user/register`
	•	Параметры: {“username”, “password”}
	•	Описание: Регистрация пользователя, после которой выдается access token, необходимый для взаимодействия с кошельком

Логин пользователя

	•	Метод: POST
	•	URL: `/api/user/login`
	•	Параметры: {“username”, “password”}
	•	Описание: логин пользователя, для повторного получения токена

Эти два URL-адреса доступны публично и не требуют токена для доступа.

----------

Кошелек:

Создание кошелька пользователя

	•	Метод: POST
	•	URL: /api/wallet/create
	•	Параметры: {“currency”}
	•	Описание: Создает кошелек пользователя, если он еще не создан.

Получение баланса кошелька

	•	Метод: GET
	•	URL: /api/wallet/{id}/balance
	•	Параметры: {id} (обязательный параметр - идентификатор кошелька)
	•	Описание: Возвращает баланс кошелька. (Примечание: В ТЗ указывается обязательное наличие идентификатора кошелька в качестве параметра, хотя можно было бы получать соответствующий кошелек по токену пользователя).

Изменение баланса кошелька

	•	Метод: POST
	•	URL: /api/wallet/{id}/update-balance
	•	Параметры: {“type”, “amount”, “currency”, “reason”}
	•	Описание: Изменяет баланс кошелька.

----------

Команды:

Команда для обновления курсов обмена валют

При развертывании проекта с помощью миграции, в базу данных будут автоматически добавлены две записи для курсов обмена: usd -> rub и rub -> usd.

В дальнейшем эти курсы можно будет обновить или добавить новые с помощью следующей команды:

app:update-exchange-rate с обязательными опциями --from, --to, --rate.

-------------

Тесты:
Тесты сами я не стал делать просто из-за времени, но для удобства тестирования добавлены файлы userRequests.http и walletRequests.http
Там указаны примеры запросов и можно запускать их прям в PHPSTORM


Общие примечания:

Все суммы и курсы хранятся в int умноженное на 100 (хранение в малом разряде - центах, копейках и тд) для более точных вычислений

Для подключения к БД используются следующие кредиталы:

      MYSQL_ROOT_PASSWORD: root
      MYSQL_DATABASE: wallet_db
      MYSQL_USER: user
      MYSQL_PASSWORD: user1234