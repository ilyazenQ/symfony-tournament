## Разворот
Выполнить команды в терминале:
1. `git clone https://github.com/ilyazenQ/symfony-tournament.git` <br>
2. `cd symfony-tournament`
3. `cp docker/.env.dist docker/.env`<br>
4. `make dc_build`<br>
5. `make app_bash`<br>
6. `composer install` <br>
7. `bin/console doctrine:migrations:migrate`<br>
8. `exit`<br>
9. `make dc_up`<br>

## Описание
Сервис доступен по url - http://localhost:888/ <br>
API для генерации турниров (алгоритм круговой системы) -
<br>{GET}api/teams/ - список команд
<br>{POST}api/teams/create - создание команды ({"name": "myTeam"})
<br>{GET}api/tournaments/ - список турниров
<br>{POST}api/tournaments/create - создание турнира ({"name": "myTournament", "teams":[1,2,3]}) *если не передать teams, будет создан турнир со всеми командами

