# Change Log

## [v2.4.0.1](https://github.com/torrentpeer/torrentpier/tree/v2.4.0.1) (2022-11-10)

[Full Changelog](https://github.com/torrentpeer/torrentpier/compare/v2.4.0...v2.4.0.1)

- Misc fixes and improvements [79bd025](https://github.com/TorrentPeer/TorrentPier/commit/3ecbe69072085f551592bd63ec1f40c6eebaf3ae), [fd4af0a](https://github.com/TorrentPeer/TorrentPier/commit/09aba1b3bb45a8c7663e32564229a26a8ab3bdaa) ([belomaxorka](https://github.com/belomaxorka))
- Updated copyrights [7aa6e16](https://github.com/TorrentPeer/TorrentPier/commit/d00ebd8581ee1b5190a2965571354626fd69d140) ([belomaxorka](https://github.com/belomaxorka))
- Improved mention system [d00ebd8](https://github.com/TorrentPeer/TorrentPier/commit/6a3d9e16f16cbd9d194c9301425eec84108b6de4) ([belomaxorka](https://github.com/belomaxorka))
- Added support of some HTML tags in BBCode [bc83982](https://github.com/TorrentPeer/TorrentPier/commit/79bd02533c3f05d886b62fa646018f043d4f3978) ([belomaxorka](https://github.com/belomaxorka))
- Showing count of PM in page title [6a3d9e1](https://github.com/TorrentPeer/TorrentPier/commit/d465de7715f49d26a18b7f1766e4eac125f8ec64), [d465de7](https://github.com/TorrentPeer/TorrentPier/commit/57e474f41f101c246524d12429b62dcf95d3e80f) ([belomaxorka](https://github.com/belomaxorka))

## v2.4.0 (2022-11-09)

- Релиз 2.4.0 💤
- Функции AttachMod теперь в виде классов
- Добавлен мод "Invites"
- Добавлен вывод аватаров на странице с пользователями
- Улучшен цензор слов (Работает почти повсюду)
- Единый code-style во всех файлах (Исключение файлы шаблона)
- Добавлена возможность отключать копирайт через файл настроек
- Добавлена поддержка Vimeo в BBCode
- Изменено отображение видео с Youtube в BBCode
- Добавлен тэг "nfo" в BBCode
- Добавлен мод "Репутация" (AJAX)
- Добавлена возможность изменить лого
- Добавлена возможность указать своего провайдера
- Добавлены тэги "mp3" и "mp4" в BBCode
- Динамическое обновление ЛС (AJAX)
- Добавлена возможность писать заметки в админ-панели
- Добавлены тэги "sup" и "sub" в BBCode
- Добавлен вывод номера поста
- Написан PHPDocs для некоторых функций и методов
- Функции поискового движка Sphinx перенесены в соответствующий класс
- Добавлено больше возможностей настройки Sphinx
- Небольшие улучшения в файле конфигурации
- Отказ от environment файла (Для удобства и в целях безопасности)
- Небольшие улучшения шаблонов админ-панели
- Исправлен баг с типами данных в классе Cron
- Добавлена кнопка сброса в BBCode
- Добавлен мод "Пользователи за 24 часа на AJAX"
- Добавлен вывод User-Agent в списке "Кто онлайн"
- Добавлен мод "Advanced Meta Tags"
- Добавлен поиск по статусу раздачи
- Админ может смотреть чужие отслеживаемые темы
- Добавлена возможность отключить входящие ЛС
- Добавлены недостающие meta-тэги в шаблоне
- Добавлена возможность включить анимированный заголовок страницы
- Добавлена возможность показывать знак зодиака рядом с датой рождения
- Добавлена кнопка "Наверх"
- Переписана система обнаружения неактуального браузера
- Добавлена возможность указать Telegram в профиле
- Добавлена возможность указать ВКонтакте в профиле
- Мелкий рефакторинг во всех классах
- Работа куки реализована с помощью библиотеки Delight\Cookie
- Улучшена система уведомления поисковых систем при создании sitemap
- Удалены нерабочие URL из sitemap_sending
- Добавлена возможность настроить порт для MySQL
- Добавлена проверка на соответствие системным требованиям
- Небольшие улучшения в классах Cache & Datastore
- Заменены все конструкции array() на новый короткий синтаксис
- Небольшие исправления в склонениях (Для русского языка)
- Добавлена возможность настраивать длину временного пароля (при восстановлении аккаунта & генерации пароля)
- Добавлена возможность настраивать длину ключа активации аккаунта
- Добавлена возможность настраивать максимальную / минимальную длину вводимого пароля
- Исправлен неправильно работающий тэг "Акроним"
- Добавлено больше размеров шрифта в BBCode
- Добавлен метод для валидации пароля
- Исправлено некорректное отображение блока "Кто онлайн" на главной
- Добавлен вывод даты запуска форума в общей статистике
- Добавлен тэг "Акроним" в BBCode
- Добавлена возможность вставки таблиц через BBCode
- Добавлен мод "Похожие раздачи"
- Добавлен мод "Кто просматривает тему"
- Улучшена страница редактирования группы
- Улучшен класс Avatar
- Перенесены функции из common.php в классы
- Изменен метод шифрования паролей на password_hash()
- Улучшения в AJAX инициализации (Защита)
- Добавлены обратно отсутствующие файлы конфигурации (nginx & sphinx)
- Добавлено 2 новых цвета в BBCode
- Добавлено больше шрифтов в BBCode
- Добавлен Thumbnail мод для BBCode
- Все DBG функции перемещены в класс Dev
- Rych/Bencode библиотека заменена на более новый аналог
- Исправлена работа SQLite кэша
- Исправления в переводе (RU & EN)
- Функции для работы с аватаркой были перемещены в класс Avatar
- Редизайн страницы "Права доступа"
- Частичные исправления Strict Mode в SQL
- Исправлены все проблемы связанные с функцией count()
- Добавлена расширенная проверка почты на корректность
- Переписан класс Emailer (Переход на Symfony Mailer)
- Добавлен API сервиса Gravatar для пользовательских аватарок
- Улучшена система логирования ошибок
- Добавлен APCu метод кэширования
- Расширены возможности отлова ошибок + Переписана система отлова ошибок
- Исправлен баг при удалении аватарки через профиль (AJAX)
- Удалены все языки кроме EN и RU
- Исправлен нерабочий смайлик cd.gif
- Обновлена библиотека jQuery до версии 1.12.4 (Последняя на данный момент из ветки 1.x)
- Обновлены зависимости в Composer
- Добавлена библиотека Symfony Polyfills
- Убран устаревший подход FILTER_FLAG_SCHEME_REQUIRED & FILTER_FLAG_HOST_REQUIRED
- Исправлена работа функции file_write()
- Улучшения и исправления по всему движку

## [v2.3.0.1](https://github.com/torrentpier/torrentpier/tree/v2.3.0.1) (2018-06-27)

[Full Changelog](https://github.com/torrentpier/torrentpier/compare/v2.3.0...v2.3.0.1)

**Merged pull requests:**

- Fix cron jobs fail without global config
  variable [\#471](https://github.com/torrentpier/torrentpier/pull/471) ([Exileum](https://github.com/Exileum))
- Cleanup BBCode
  class [\#470](https://github.com/torrentpier/torrentpier/pull/470) ([Exileum](https://github.com/Exileum))

## [v2.3.0](https://github.com/torrentpier/torrentpier/tree/v2.3.0) (2018-06-26)

[Full Changelog](https://github.com/torrentpier/torrentpier/compare/v2.2.3...v2.3.0)

**Merged pull requests:**

- Release preparation. Crowdin language pack
  update [\#468](https://github.com/torrentpier/torrentpier/pull/468) ([Exileum](https://github.com/Exileum))
- PHP 7+ deprecations of old cache
  systems [\#467](https://github.com/torrentpier/torrentpier/pull/467) ([Exileum](https://github.com/Exileum))
- Fix global atom feed
  name [\#466](https://github.com/torrentpier/torrentpier/pull/466) ([Exileum](https://github.com/Exileum))
- Configurable download torrent
  url [\#465](https://github.com/torrentpier/torrentpier/pull/465) ([Exileum](https://github.com/Exileum))
- Fix some bugs with MySQL strict
  mode [\#464](https://github.com/torrentpier/torrentpier/pull/464) ([Exileum](https://github.com/Exileum))
- Fix release template
  editor [\#463](https://github.com/torrentpier/torrentpier/pull/463) ([Exileum](https://github.com/Exileum))
- Fix multiple variable cleanup in private
  messaging [\#462](https://github.com/torrentpier/torrentpier/pull/462) ([Exileum](https://github.com/Exileum))
- Fix magnet link passkey creation for new
  users [\#461](https://github.com/torrentpier/torrentpier/pull/461) ([Exileum](https://github.com/Exileum))
- Update required PHP version to
  7.1.3 [\#460](https://github.com/torrentpier/torrentpier/pull/460) ([Exileum](https://github.com/Exileum))
- Split functions to the composer
  autoloading [\#459](https://github.com/torrentpier/torrentpier/pull/459) ([Exileum](https://github.com/Exileum))
- Update copyright to the short
  syntax [\#458](https://github.com/torrentpier/torrentpier/pull/458) ([Exileum](https://github.com/Exileum))
- Fix \#451. Undefined index:
  L\_CRON\_EDIT\_HEAD [\#457](https://github.com/torrentpier/torrentpier/pull/457) ([Exileum](https://github.com/Exileum))
- Merge head
  branches [\#456](https://github.com/torrentpier/torrentpier/pull/456) ([Exileum](https://github.com/Exileum))
- Default value for user\_birthday causes exception on user password
  change [\#449](https://github.com/torrentpier/torrentpier/pull/449) ([yukoff](https://github.com/yukoff))
- Add back
  roave/security-advisories [\#446](https://github.com/torrentpier/torrentpier/pull/446) ([yukoff](https://github.com/yukoff))

## [v2.2.3](https://github.com/torrentpier/torrentpier/tree/v2.2.3) (2017-08-07)

[Full Changelog](https://github.com/torrentpier/torrentpier/compare/v2.2.2...v2.2.3)

**Merged pull requests:**

- Release 2.2.3 🔥  [\#443](https://github.com/torrentpier/torrentpier/pull/443) ([Exileum](https://github.com/Exileum))
- Release preparation. Crowdin language pack
  update [\#442](https://github.com/torrentpier/torrentpier/pull/442) ([Exileum](https://github.com/Exileum))
- Unique topic page title, undefined language variables
  fix [\#441](https://github.com/torrentpier/torrentpier/pull/441) ([Exileum](https://github.com/Exileum))
- Remove matching users with default IP from profile
  list [\#440](https://github.com/torrentpier/torrentpier/pull/440) ([Exileum](https://github.com/Exileum))
- Broken announcer fix, announcer debug
  removed [\#439](https://github.com/torrentpier/torrentpier/pull/439) ([Exileum](https://github.com/Exileum))
- Fix broken ajax [\#436](https://github.com/torrentpier/torrentpier/pull/436) ([Exileum](https://github.com/Exileum))
- Some deprecations, normalize.css, torrent file content sort
  fix [\#434](https://github.com/torrentpier/torrentpier/pull/434) ([Exileum](https://github.com/Exileum))
- Incorrect log file rotation
  regex [\#432](https://github.com/torrentpier/torrentpier/pull/432) ([Exileum](https://github.com/Exileum))
- Various bug fixes described on the
  forum [\#431](https://github.com/torrentpier/torrentpier/pull/431) ([Exileum](https://github.com/Exileum))
- Fixes \#412 - bug with dynamic language
  variables [\#430](https://github.com/torrentpier/torrentpier/pull/430) ([Exileum](https://github.com/Exileum))
- Update .htaccess for new Apache 2.4
  syntax [\#429](https://github.com/torrentpier/torrentpier/pull/429) ([Exileum](https://github.com/Exileum))
- Crowdin language pack update for new project domain
  name [\#415](https://github.com/torrentpier/torrentpier/pull/415) ([Exileum](https://github.com/Exileum))
- Composer support section
  error [\#414](https://github.com/torrentpier/torrentpier/pull/414) ([Exileum](https://github.com/Exileum))
- New project domain
  name [\#413](https://github.com/torrentpier/torrentpier/pull/413) ([Exileum](https://github.com/Exileum))

## [v2.2.2](https://github.com/torrentpier/torrentpier/tree/v2.2.2) (2017-06-22)

[Full Changelog](https://github.com/torrentpier/torrentpier/compare/v2.2.1...v2.2.2)

**Merged pull requests:**

- Release 2.2.2 🌞 [\#410](https://github.com/torrentpier/torrentpier/pull/410) ([Exileum](https://github.com/Exileum))
- Release preparation Crowdin language pack
  update [\#409](https://github.com/torrentpier/torrentpier/pull/409) ([Exileum](https://github.com/Exileum))
- Display source language if no user language
  variable [\#408](https://github.com/torrentpier/torrentpier/pull/408) ([Exileum](https://github.com/Exileum))
- Disable Bugsnag by
  default [\#407](https://github.com/torrentpier/torrentpier/pull/407) ([Exileum](https://github.com/Exileum))
- Fix empty birthday
  list [\#406](https://github.com/torrentpier/torrentpier/pull/406) ([Exileum](https://github.com/Exileum))
- Remove unused ranks
  functionality [\#405](https://github.com/torrentpier/torrentpier/pull/405) ([Exileum](https://github.com/Exileum))
- Complete renewal of the Ukrainian language from our toloka.to
  friends [\#404](https://github.com/torrentpier/torrentpier/pull/404) ([Exileum](https://github.com/Exileum))
- Some fixes, auto language removal \(so buggy\) and replenishable
  status [\#403](https://github.com/torrentpier/torrentpier/pull/403) ([Exileum](https://github.com/Exileum))

## [v2.2.1](https://github.com/torrentpier/torrentpier/tree/v2.2.1) (2017-06-16)

[Full Changelog](https://github.com/torrentpier/torrentpier/compare/v2.2.0...v2.2.1)

**Merged pull requests:**

- Release 2.2.1 🐛  [\#392](https://github.com/torrentpier/torrentpier/pull/392) ([Exileum](https://github.com/Exileum))
- Partial renewal of the Ukrainian language from our toloka.to
  friends [\#391](https://github.com/torrentpier/torrentpier/pull/391) ([Exileum](https://github.com/Exileum))
- Create
  CODE\_OF\_CONDUCT.md [\#390](https://github.com/torrentpier/torrentpier/pull/390) ([Exileum](https://github.com/Exileum))
- Fix default users language in
  dump [\#389](https://github.com/torrentpier/torrentpier/pull/389) ([Exileum](https://github.com/Exileum))
- Tracker search forum list
  simplification [\#388](https://github.com/torrentpier/torrentpier/pull/388) ([Exileum](https://github.com/Exileum))
- Fix some notices in admin panel reported by
  BugSnag [\#387](https://github.com/torrentpier/torrentpier/pull/387) ([Exileum](https://github.com/Exileum))
- Fixed SQL. Remove limit from
  update [\#368](https://github.com/torrentpier/torrentpier/pull/368) ([VasyOk](https://github.com/VasyOk))

## [v2.2.0](https://github.com/torrentpier/torrentpier/tree/v2.2.0) (2017-06-12)

[Full Changelog](https://github.com/torrentpier/torrentpier/compare/v2.1.5...v2.2.0)

**Merged pull requests:**

- Release 2.2.0 ☘️ [\#328](https://github.com/torrentpier/torrentpier/pull/328) ([Exileum](https://github.com/Exileum))
- Release preparation. Crowdin language pack
  update [\#322](https://github.com/torrentpier/torrentpier/pull/322) ([Exileum](https://github.com/Exileum))
- TorrentPier Aurochs release
  preparation [\#321](https://github.com/torrentpier/torrentpier/pull/321) ([Exileum](https://github.com/Exileum))
- Release preparation. Small bugfixes and readme
  translation [\#318](https://github.com/torrentpier/torrentpier/pull/318) ([Exileum](https://github.com/Exileum))
- Crowdin language pack
  update [\#314](https://github.com/torrentpier/torrentpier/pull/314) ([Exileum](https://github.com/Exileum))
- IP storage and attachment system bugfix. PHP
  5.6+ [\#313](https://github.com/torrentpier/torrentpier/pull/313) ([Exileum](https://github.com/Exileum))
- Bootstrap update & beginning of the develop branch partial
  merge [\#303](https://github.com/torrentpier/torrentpier/pull/303) ([Exileum](https://github.com/Exileum))
- Fix avatars display
  bug [\#302](https://github.com/torrentpier/torrentpier/pull/302) ([Exileum](https://github.com/Exileum))
- Cron subsystem rework.
  Environments [\#301](https://github.com/torrentpier/torrentpier/pull/301) ([Exileum](https://github.com/Exileum))
- New logotype, favicon and css split &
  reformat [\#293](https://github.com/torrentpier/torrentpier/pull/293) ([Exileum](https://github.com/Exileum))
- Whoops error handler for debug
  users [\#291](https://github.com/torrentpier/torrentpier/pull/291) ([Exileum](https://github.com/Exileum))
- Replace sitemap to the new external
  component [\#252](https://github.com/torrentpier/torrentpier/pull/252) ([Exileum](https://github.com/Exileum))
- Crowdin language pack update. Removed some
  languages [\#250](https://github.com/torrentpier/torrentpier/pull/250) ([Exileum](https://github.com/Exileum))
- IP detect subsystem replace. Trash cleanup.
  Defines [\#249](https://github.com/torrentpier/torrentpier/pull/249) ([Exileum](https://github.com/Exileum))
- Old ads module
  removal [\#244](https://github.com/torrentpier/torrentpier/pull/244) ([Exileum](https://github.com/Exileum))
- External bencode library and some other
  changes [\#243](https://github.com/torrentpier/torrentpier/pull/243) ([Exileum](https://github.com/Exileum))
- Added new logo to
  readme [\#242](https://github.com/torrentpier/torrentpier/pull/242) ([VasyOk](https://github.com/VasyOk))
- Bugsnag integration and some bugfixes in for
  cycles [\#239](https://github.com/torrentpier/torrentpier/pull/239) ([Exileum](https://github.com/Exileum))
- Bug with variables replacement and Crowdin localization
  fix [\#238](https://github.com/torrentpier/torrentpier/pull/238) ([Exileum](https://github.com/Exileum))
- PSR-4 compatible legacy code
  autoloading [\#237](https://github.com/torrentpier/torrentpier/pull/237) ([Exileum](https://github.com/Exileum))
- UFT-8 autocorrection removal from standart
  package [\#236](https://github.com/torrentpier/torrentpier/pull/236) ([Exileum](https://github.com/Exileum))
- New localization strings and full Crowdin language pack
  update [\#235](https://github.com/torrentpier/torrentpier/pull/235) ([Exileum](https://github.com/Exileum))
- Replace own emailer to
  SwiftMailer [\#234](https://github.com/torrentpier/torrentpier/pull/234) ([Exileum](https://github.com/Exileum))
- Force email charset and Crowdin language pack
  update [\#232](https://github.com/torrentpier/torrentpier/pull/232) ([Exileum](https://github.com/Exileum))
- Crowdin language pack
  update [\#231](https://github.com/torrentpier/torrentpier/pull/231) ([Exileum](https://github.com/Exileum))
- Static code analyzer inspection, part
  2 [\#230](https://github.com/torrentpier/torrentpier/pull/230) ([Exileum](https://github.com/Exileum))
- Static code analyzer cherry picked from
  \#228 [\#229](https://github.com/torrentpier/torrentpier/pull/229) ([VasyOk](https://github.com/VasyOk))
- Fix compare php
  version. [\#226](https://github.com/torrentpier/torrentpier/pull/226) ([VasyOk](https://github.com/VasyOk))
- Fixed compare version
  PHP [\#225](https://github.com/torrentpier/torrentpier/pull/225) ([VasyOk](https://github.com/VasyOk))
- Deprecated each\(\) function in php
  7.2 [\#211](https://github.com/torrentpier/torrentpier/pull/211) ([Exileum](https://github.com/Exileum))
- Performance refactoring. Remove test code. Fix path in
  config [\#208](https://github.com/torrentpier/torrentpier/pull/208) ([VasyOk](https://github.com/VasyOk))
- Fix many notices in
  admin\_attach\_cp.php [\#183](https://github.com/torrentpier/torrentpier/pull/183) ([Exileum](https://github.com/Exileum))
- Add check lang [\#178](https://github.com/torrentpier/torrentpier/pull/178) ([VasyOk](https://github.com/VasyOk))
- Remove order from
  sql [\#177](https://github.com/torrentpier/torrentpier/pull/177) ([VasyOk](https://github.com/VasyOk))
- Fix path to
  viewtorrent.php [\#176](https://github.com/torrentpier/torrentpier/pull/176) ([VasyOk](https://github.com/VasyOk))
- New Crowdin
  translations [\#168](https://github.com/torrentpier/torrentpier/pull/168) ([Exileum](https://github.com/Exileum))
- Localization trash
  cleanup [\#167](https://github.com/torrentpier/torrentpier/pull/167) ([Exileum](https://github.com/Exileum))
- New Crowdin translations
  \(develop\) [\#165](https://github.com/torrentpier/torrentpier/pull/165) ([Exileum](https://github.com/Exileum))
- New Crowdin translations
  \(master\) [\#164](https://github.com/torrentpier/torrentpier/pull/164) ([Exileum](https://github.com/Exileum))
- Crowdin localization integration prepare and stopwords
  removal [\#163](https://github.com/torrentpier/torrentpier/pull/163) ([Exileum](https://github.com/Exileum))
- Crowdin localization
  integration [\#162](https://github.com/torrentpier/torrentpier/pull/162) ([Exileum](https://github.com/Exileum))
- New Crowdin translations
  \(develop\) [\#161](https://github.com/torrentpier/torrentpier/pull/161) ([Exileum](https://github.com/Exileum))
- \#157. Fix Error in GET
  /bt/announce.php [\#159](https://github.com/torrentpier/torrentpier/pull/159) ([VasyOk](https://github.com/VasyOk))
- Added check composer
  install [\#148](https://github.com/torrentpier/torrentpier/pull/148) ([VasyOk](https://github.com/VasyOk))
- Fix operators [\#147](https://github.com/torrentpier/torrentpier/pull/147) ([VasyOk](https://github.com/VasyOk))
- \#144 Files should not be
  executable [\#145](https://github.com/torrentpier/torrentpier/pull/145) ([VasyOk](https://github.com/VasyOk))
- Change paths to absolute
  pathname [\#143](https://github.com/torrentpier/torrentpier/pull/143) ([VasyOk](https://github.com/VasyOk))
- Redundant pagination, mysql 5.7+ issue, release template
  option [\#141](https://github.com/torrentpier/torrentpier/pull/141) ([Exileum](https://github.com/Exileum))
- Transfer announce to the php7-optimized database
  layer [\#140](https://github.com/torrentpier/torrentpier/pull/140) ([Exileum](https://github.com/Exileum))
- Cleanup repository from old deprecated scripts and server
  configs [\#139](https://github.com/torrentpier/torrentpier/pull/139) ([Exileum](https://github.com/Exileum))
- Torrent ajax file list fixes and small
  reformat [\#138](https://github.com/torrentpier/torrentpier/pull/138) ([Exileum](https://github.com/Exileum))
- Codacy / Scrutinizer / Code Climate / Coveralls integration, Slack hook to Travis
  CI [\#137](https://github.com/torrentpier/torrentpier/pull/137) ([Exileum](https://github.com/Exileum))
- Add a Codacy badge to
  README.md [\#136](https://github.com/torrentpier/torrentpier/pull/136) ([codacy-badger](https://github.com/codacy-badger))
- Replace Sphinx API to the composer
  version [\#135](https://github.com/torrentpier/torrentpier/pull/135) ([Exileum](https://github.com/Exileum))
- Incorrect case close operators
  \(develop\) [\#134](https://github.com/torrentpier/torrentpier/pull/134) ([Exileum](https://github.com/Exileum))
- Incorrect case close operators
  \(master\) [\#133](https://github.com/torrentpier/torrentpier/pull/133) ([Exileum](https://github.com/Exileum))
- Composer init, editor config, some cleanup and much
  more [\#132](https://github.com/torrentpier/torrentpier/pull/132) ([Exileum](https://github.com/Exileum))
- Remove eval from admin\_attachments and
  emailer [\#129](https://github.com/torrentpier/torrentpier/pull/129) ([VasyOk](https://github.com/VasyOk))
- Fix sql group [\#128](https://github.com/torrentpier/torrentpier/pull/128) ([VasyOk](https://github.com/VasyOk))
- Remove Zend [\#127](https://github.com/torrentpier/torrentpier/pull/127) ([VasyOk](https://github.com/VasyOk))
- Small fix to the upgrade
  schema [\#126](https://github.com/torrentpier/torrentpier/pull/126) ([Exileum](https://github.com/Exileum))
- Fixed id sqllog table and name select
  db [\#125](https://github.com/torrentpier/torrentpier/pull/125) ([VasyOk](https://github.com/VasyOk))
- New external service for look up IP
  address [\#122](https://github.com/torrentpier/torrentpier/pull/122) ([Exileum](https://github.com/Exileum))
- New branding and
  copyright [\#121](https://github.com/torrentpier/torrentpier/pull/121) ([Exileum](https://github.com/Exileum))
- Poster birthday with no birthday date
  fix [\#120](https://github.com/torrentpier/torrentpier/pull/120) ([Exileum](https://github.com/Exileum))
- Tidy deprecated option merge-spans
  remove [\#119](https://github.com/torrentpier/torrentpier/pull/119) ([Exileum](https://github.com/Exileum))
- Db logging [\#118](https://github.com/torrentpier/torrentpier/pull/118) ([leroy0](https://github.com/leroy0))
- CircleCi, CodeCoverage and composer
  dependencies [\#117](https://github.com/torrentpier/torrentpier/pull/117) ([Exileum](https://github.com/Exileum))
- Db exceptions, query with
  binding [\#116](https://github.com/torrentpier/torrentpier/pull/116) ([leroy0](https://github.com/leroy0))
- PHP 7+ requirements, Travis and other small
  fixes [\#115](https://github.com/torrentpier/torrentpier/pull/115) ([Exileum](https://github.com/Exileum))
- New compatible with php7 classes: Db,
  Config [\#114](https://github.com/torrentpier/torrentpier/pull/114) ([Exileum](https://github.com/Exileum))
- Refactoring
  posting\_attachments [\#112](https://github.com/torrentpier/torrentpier/pull/112) ([VasyOk](https://github.com/VasyOk))
- Update the current year in the license
  text [\#110](https://github.com/torrentpier/torrentpier/pull/110) ([Exileum](https://github.com/Exileum))
- Reformat master branch to PSR-2 and MIT
  license [\#109](https://github.com/torrentpier/torrentpier/pull/109) ([Exileum](https://github.com/Exileum))
- Master branch up to php 7
  compatibility [\#107](https://github.com/torrentpier/torrentpier/pull/107) ([VasyOk](https://github.com/VasyOk))
- Removal of unused scripts and server
  configs [\#105](https://github.com/torrentpier/torrentpier/pull/105) ([Exileum](https://github.com/Exileum))
- New license - MIT [\#104](https://github.com/torrentpier/torrentpier/pull/104) ([Exileum](https://github.com/Exileum))
- New coding standart:
  PSR-2 [\#103](https://github.com/torrentpier/torrentpier/pull/103) ([Exileum](https://github.com/Exileum))
- Improvements in code and work
  cache [\#101](https://github.com/torrentpier/torrentpier/pull/101) ([VasyOk](https://github.com/VasyOk))
- Migration to the new config
  subsystem [\#100](https://github.com/torrentpier/torrentpier/pull/100) ([Exileum](https://github.com/Exileum))
- php-lang-correct
  removed [\#99](https://github.com/torrentpier/torrentpier/pull/99) ([Exileum](https://github.com/Exileum))
- Logical operators should be
  avoided [\#98](https://github.com/torrentpier/torrentpier/pull/98) ([Exileum](https://github.com/Exileum))
- Migration to the new cache
  subsystem [\#97](https://github.com/torrentpier/torrentpier/pull/97) ([Exileum](https://github.com/Exileum))
- Rework of feed.php and some other
  files [\#94](https://github.com/torrentpier/torrentpier/pull/94) ([Exileum](https://github.com/Exileum))
- Refactoring Cache [\#92](https://github.com/torrentpier/torrentpier/pull/92) ([VasyOk](https://github.com/VasyOk))
- Add new tests and
  refactoring [\#89](https://github.com/torrentpier/torrentpier/pull/89) ([VasyOk](https://github.com/VasyOk))
- Add tests [\#88](https://github.com/torrentpier/torrentpier/pull/88) ([VasyOk](https://github.com/VasyOk))
- Some fix after removed
  @ [\#87](https://github.com/torrentpier/torrentpier/pull/87) ([VasyOk](https://github.com/VasyOk))
- \#77 Add monolog [\#86](https://github.com/torrentpier/torrentpier/pull/86) ([VasyOk](https://github.com/VasyOk))
- Remove at [\#85](https://github.com/torrentpier/torrentpier/pull/85) ([VasyOk](https://github.com/VasyOk))
- Переделка файла dl.php на работу с новой
  базой [\#83](https://github.com/torrentpier/torrentpier/pull/83) ([Exileum](https://github.com/Exileum))
- Added use profiler and in\(de\)crement
  methods. [\#82](https://github.com/torrentpier/torrentpier/pull/82) ([VasyOk](https://github.com/VasyOk))
- Remove response service
  provider [\#80](https://github.com/torrentpier/torrentpier/pull/80) ([VasyOk](https://github.com/VasyOk))
- DI usage example [\#79](https://github.com/torrentpier/torrentpier/pull/79) ([Exileum](https://github.com/Exileum))
- Added methods to simplify the work with the
  database [\#75](https://github.com/torrentpier/torrentpier/pull/75) ([VasyOk](https://github.com/VasyOk))
- Captcha service
  provider [\#72](https://github.com/torrentpier/torrentpier/pull/72) ([Exileum](https://github.com/Exileum))
- Fixed a getting value from config through method
  toArray [\#71](https://github.com/torrentpier/torrentpier/pull/71) ([VasyOk](https://github.com/VasyOk))
- \#69 Fixed crypt
  notice [\#70](https://github.com/torrentpier/torrentpier/pull/70) ([VasyOk](https://github.com/VasyOk))
- \#58 Expansion Zend
  Config [\#68](https://github.com/torrentpier/torrentpier/pull/68) ([VasyOk](https://github.com/VasyOk))
- change preset to prs2 [\#61](https://github.com/torrentpier/torrentpier/pull/61) ([VasyOk](https://github.com/VasyOk))
- Applied fixes from
  StyleCI [\#60](https://github.com/torrentpier/torrentpier/pull/60) ([Exileum](https://github.com/Exileum))

## [v2.1.5](https://github.com/torrentpier/torrentpier/tree/v2.1.5) (2015-05-23)

[Full Changelog](https://github.com/torrentpier/torrentpier/compare/v2.1.4...v2.1.5)

**Merged pull requests:**

- Add a Gitter chat badge to
  README.md [\#47](https://github.com/torrentpier/torrentpier/pull/47) ([gitter-badger](https://github.com/gitter-badger))
- Фикс подтверждения
  пароля [\#43](https://github.com/torrentpier/torrentpier/pull/43) ([dreddred](https://github.com/dreddred))
- Fix port Ocelot [\#42](https://github.com/torrentpier/torrentpier/pull/42) ([Altairko](https://github.com/Altairko))
- Develop [\#40](https://github.com/torrentpier/torrentpier/pull/40) ([Exileum](https://github.com/Exileum))

## [v2.1.4](https://github.com/torrentpier/torrentpier/tree/v2.1.4) (2014-11-26)

[Full Changelog](https://github.com/torrentpier/torrentpier/compare/v2.1.3...v2.1.4)

**Merged pull requests:**

- Develop [\#39](https://github.com/torrentpier/torrentpier/pull/39) ([Exileum](https://github.com/Exileum))

## [v2.1.3](https://github.com/torrentpier/torrentpier/tree/v2.1.3) (2014-10-24)

[Full Changelog](https://github.com/torrentpier/torrentpier/compare/v2.1.2...v2.1.3)

**Merged pull requests:**

- Версия 2.1.3
  ALPHA-3 [\#38](https://github.com/torrentpier/torrentpier/pull/38) ([Exileum](https://github.com/Exileum))

## [v2.1.2](https://github.com/torrentpier/torrentpier/tree/v2.1.2) (2014-10-20)

[Full Changelog](https://github.com/torrentpier/torrentpier/compare/v2.1.1...v2.1.2)

**Merged pull requests:**

- Версия 2.1.2
  ALPHA-2 [\#37](https://github.com/torrentpier/torrentpier/pull/37) ([Exileum](https://github.com/Exileum))

## [v2.1.1](https://github.com/torrentpier/torrentpier/tree/v2.1.1) (2014-09-11)

[Full Changelog](https://github.com/torrentpier/torrentpier/compare/v2.1.0...v2.1.1)

**Merged pull requests:**

- Версия 2.1.1
  ALPHA-1 [\#34](https://github.com/torrentpier/torrentpier/pull/34) ([Exileum](https://github.com/Exileum))

## [v2.1.0](https://github.com/torrentpier/torrentpier/tree/v2.1.0) (2014-09-07)

[Full Changelog](https://github.com/torrentpier/torrentpier/compare/v2.0.599b...v2.1.0)

**Merged pull requests:**

- Версия 2.1 \(R600\) [\#32](https://github.com/torrentpier/torrentpier/pull/32) ([Exileum](https://github.com/Exileum))

## [v2.0.599b](https://github.com/torrentpier/torrentpier/tree/v2.0.599b) (2014-08-30)

[Full Changelog](https://github.com/torrentpier/torrentpier/compare/v2.0.599...v2.0.599b)

**Merged pull requests:**

- Develop [\#31](https://github.com/torrentpier/torrentpier/pull/31) ([Exileum](https://github.com/Exileum))
- Feature/terms [\#30](https://github.com/torrentpier/torrentpier/pull/30) ([Exileum](https://github.com/Exileum))

## [v2.0.599](https://github.com/torrentpier/torrentpier/tree/v2.0.599) (2014-08-29)

[Full Changelog](https://github.com/torrentpier/torrentpier/compare/v2.0.598...v2.0.599)

**Merged pull requests:**

- R599 [\#29](https://github.com/torrentpier/torrentpier/pull/29) ([Exileum](https://github.com/Exileum))

## [v2.0.598](https://github.com/torrentpier/torrentpier/tree/v2.0.598) (2014-08-27)

[Full Changelog](https://github.com/torrentpier/torrentpier/compare/v2.0.597...v2.0.598)

**Merged pull requests:**

- R598 [\#28](https://github.com/torrentpier/torrentpier/pull/28) ([Exileum](https://github.com/Exileum))

## [v2.0.597](https://github.com/torrentpier/torrentpier/tree/v2.0.597) (2014-08-24)

[Full Changelog](https://github.com/torrentpier/torrentpier/compare/v2.0.596...v2.0.597)

**Merged pull requests:**

- R597 [\#27](https://github.com/torrentpier/torrentpier/pull/27) ([Exileum](https://github.com/Exileum))

## [v2.0.596](https://github.com/torrentpier/torrentpier/tree/v2.0.596) (2014-08-20)

[Full Changelog](https://github.com/torrentpier/torrentpier/compare/v2.0.595...v2.0.596)

**Merged pull requests:**

- Develop [\#26](https://github.com/torrentpier/torrentpier/pull/26) ([Exileum](https://github.com/Exileum))

## [v2.0.595](https://github.com/torrentpier/torrentpier/tree/v2.0.595) (2014-08-14)

[Full Changelog](https://github.com/torrentpier/torrentpier/compare/v2.0.594b...v2.0.595)

**Merged pull requests:**

- Develop [\#22](https://github.com/torrentpier/torrentpier/pull/22) ([Exileum](https://github.com/Exileum))

## [v2.0.594b](https://github.com/torrentpier/torrentpier/tree/v2.0.594b) (2014-08-07)

[Full Changelog](https://github.com/torrentpier/torrentpier/compare/v2.0.594...v2.0.594b)

**Merged pull requests:**

- Develop [\#17](https://github.com/torrentpier/torrentpier/pull/17) ([Exileum](https://github.com/Exileum))
- Hotfix/bbcode [\#16](https://github.com/torrentpier/torrentpier/pull/16) ([Exileum](https://github.com/Exileum))

## [v2.0.594](https://github.com/torrentpier/torrentpier/tree/v2.0.594) (2014-08-07)

[Full Changelog](https://github.com/torrentpier/torrentpier/compare/v2.0.593b...v2.0.594)

**Merged pull requests:**

- Develop [\#15](https://github.com/torrentpier/torrentpier/pull/15) ([Exileum](https://github.com/Exileum))

## [v2.0.593b](https://github.com/torrentpier/torrentpier/tree/v2.0.593b) (2014-08-05)

[Full Changelog](https://github.com/torrentpier/torrentpier/compare/v2.0.593...v2.0.593b)

## [v2.0.593](https://github.com/torrentpier/torrentpier/tree/v2.0.593) (2014-08-05)

[Full Changelog](https://github.com/torrentpier/torrentpier/compare/v2.0.592...v2.0.593)

**Merged pull requests:**

- Develop [\#13](https://github.com/torrentpier/torrentpier/pull/13) ([Exileum](https://github.com/Exileum))

## [v2.0.592](https://github.com/torrentpier/torrentpier/tree/v2.0.592) (2014-08-01)

[Full Changelog](https://github.com/torrentpier/torrentpier/compare/v2.0.591...v2.0.592)

## [v2.0.591](https://github.com/torrentpier/torrentpier/tree/v2.0.591) (2014-07-13)

[Full Changelog](https://github.com/torrentpier/torrentpier/compare/v2.0.590...v2.0.591)

## [v2.0.590](https://github.com/torrentpier/torrentpier/tree/v2.0.590) (2014-06-21)

[Full Changelog](https://github.com/torrentpier/torrentpier/compare/v2.0.589...v2.0.590)

## [v2.0.589](https://github.com/torrentpier/torrentpier/tree/v2.0.589) (2014-06-19)

[Full Changelog](https://github.com/torrentpier/torrentpier/compare/v2.0.588...v2.0.589)

## [v2.0.588](https://github.com/torrentpier/torrentpier/tree/v2.0.588) (2014-06-17)

[Full Changelog](https://github.com/torrentpier/torrentpier/compare/v2.0.587...v2.0.588)

## [v2.0.587](https://github.com/torrentpier/torrentpier/tree/v2.0.587) (2014-06-15)

[Full Changelog](https://github.com/torrentpier/torrentpier/compare/v2.0.586...v2.0.587)

## [v2.0.586](https://github.com/torrentpier/torrentpier/tree/v2.0.586) (2014-06-13)

[Full Changelog](https://github.com/torrentpier/torrentpier/compare/v2.0.585...v2.0.586)

## [v2.0.585](https://github.com/torrentpier/torrentpier/tree/v2.0.585) (2014-05-14)

[Full Changelog](https://github.com/torrentpier/torrentpier/compare/v2.0.584...v2.0.585)

## [v2.0.584](https://github.com/torrentpier/torrentpier/tree/v2.0.584) (2014-03-07)

[Full Changelog](https://github.com/torrentpier/torrentpier/compare/v2.0.583...v2.0.584)

## [v2.0.583](https://github.com/torrentpier/torrentpier/tree/v2.0.583) (2014-02-10)

[Full Changelog](https://github.com/torrentpier/torrentpier/compare/v2.0.581...v2.0.583)

## [v2.0.581](https://github.com/torrentpier/torrentpier/tree/v2.0.581) (2014-02-03)

[Full Changelog](https://github.com/torrentpier/torrentpier/compare/v2.0.572...v2.0.581)

## [v2.0.572](https://github.com/torrentpier/torrentpier/tree/v2.0.572) (2014-01-28)

[Full Changelog](https://github.com/torrentpier/torrentpier/compare/v2.0.564...v2.0.572)

## [v2.0.564](https://github.com/torrentpier/torrentpier/tree/v2.0.564) (2014-01-20)

[Full Changelog](https://github.com/torrentpier/torrentpier/compare/v2.0.560...v2.0.564)

## [v2.0.560](https://github.com/torrentpier/torrentpier/tree/v2.0.560) (2014-01-17)

[Full Changelog](https://github.com/torrentpier/torrentpier/compare/v2.0.556...v2.0.560)

## [v2.0.556](https://github.com/torrentpier/torrentpier/tree/v2.0.556) (2014-01-12)

[Full Changelog](https://github.com/torrentpier/torrentpier/compare/v2.0.552...v2.0.556)

## [v2.0.552](https://github.com/torrentpier/torrentpier/tree/v2.0.552) (2013-09-05)

[Full Changelog](https://github.com/torrentpier/torrentpier/compare/v2.0.506...v2.0.552)

## [v2.0.506](https://github.com/torrentpier/torrentpier/tree/v2.0.506) (2013-06-23)

[Full Changelog](https://github.com/torrentpier/torrentpier/compare/v2.0.500...v2.0.506)

## [v2.0.500](https://github.com/torrentpier/torrentpier/tree/v2.0.500) (2013-05-14)

[Full Changelog](https://github.com/torrentpier/torrentpier/compare/v2.0.491...v2.0.500)

## [v2.0.491](https://github.com/torrentpier/torrentpier/tree/v2.0.491) (2013-01-12)

[Full Changelog](https://github.com/torrentpier/torrentpier/compare/v2.0.477...v2.0.491)

## [v2.0.477](https://github.com/torrentpier/torrentpier/tree/v2.0.477) (2012-11-14)

[Full Changelog](https://github.com/torrentpier/torrentpier/compare/v2.0.463...v2.0.477)

## [v2.0.463](https://github.com/torrentpier/torrentpier/tree/v2.0.463) (2012-10-16)

[Full Changelog](https://github.com/torrentpier/torrentpier/compare/v2.0.456...v2.0.463)

## [v2.0.456](https://github.com/torrentpier/torrentpier/tree/v2.0.456) (2012-09-07)

[Full Changelog](https://github.com/torrentpier/torrentpier/compare/v2.0.400...v2.0.456)

## [v2.0.400](https://github.com/torrentpier/torrentpier/tree/v2.0.400) (2012-04-13)

[Full Changelog](https://github.com/torrentpier/torrentpier/compare/v2.0.300...v2.0.400)

## [v2.0.300](https://github.com/torrentpier/torrentpier/tree/v2.0.300) (2011-10-14)

[Full Changelog](https://github.com/torrentpier/torrentpier/compare/v2.0.261...v2.0.300)

## [v2.0.261](https://github.com/torrentpier/torrentpier/tree/v2.0.261) (2011-08-28)

[Full Changelog](https://github.com/torrentpier/torrentpier/compare/v2.0.0...v2.0.261)

## [v2.0.0](https://github.com/torrentpier/torrentpier/tree/v2.0.0) (2011-08-08)
