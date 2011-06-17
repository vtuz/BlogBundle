## Установка BlogBundle

### Устанавливаем зависимости

* DoctrineExtensionsBundle - https://github.com/stof/DoctrineExtensionsBundle/blob/master/Resources/doc/index.rst
* FunctionalTestBundle - https://github.com/liip/FunctionalTestBundle/blob/master/README.md
* DoctrineFixturesBundle - https://github.com/symfony/DoctrineFixturesBundle

### Тянем исходники BlogBundle

    git submodule add git@github.com:stfalcon/BlogBundle.git src/Stfalcon/Bundle/BlogBundle

### Добавляем путь к пространству имен в автозагрузчик

    // app/autoload.php
    $loader->registerNamespaces(array(
        // ...
        'Stfalcon'                       => __DIR__.'/../src',
    ));

### Инициализируем бандл в AppKernel

    // app/AppKernel.php
    public function registerBundles()
    {
        $bundles = array(
        // ...
        new Stfalcon\Bundle\BlogBundle\StfalconBlogBundle(),
        );
    }

### Прописываем в конфиги настройки бандла:

    # app/config/config.yml
    stfalcon_blog:
        rss:
          title: "Блог веб-студии stfalcon.com"
          description: "Заметки о используемых технологиях, реализованных проектах, трудовых буднях и отдыхе :)"

    # app/config/routing.yml
    _blog:
        resource: "@StfalconBlogBundle/Resources/config/routing.yml"    


### Создаем или обновляем схему БД

    ./app/console doctrine:schema:create

### Создаем слои для проекта. 

По умолчанию контент передается в блоке content. Если в вашем основном слое используется другой блок, тогда можно обернуть в него контент. Например вы подключаете BlogBundle к проекту symfony-standart:

    # app/Resources/views/stfalcon_blog_layout.html.twig
    {% extends '::base.html.twig' %}

    {% block body %}
        {% block content %}{% endblock %}
    {% endblock %}

Для проекта портфолио https://github.com/stfalcon/portfolio я использую следующий шаблон:

    # app/Resources/views/stfalcon_blog_layout.html.twig
    {% extends '::layout.html.twig' %}
    
### Копируем ресурсы в директорию web:

    ./app/console assets:install web
