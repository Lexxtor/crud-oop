<?php

class App
{
    /**
     * @var static
     */
    protected static $instance;

    /**
     * @var array
     */
    public $config;

    /**
     * @var DB
     */
    public $db;

    protected function __construct(array $config)
    {
        $this->config = $config;
        $this->db = new DB($this->config['db']['host'], $this->config['db']['port'], $this->config['db']['dbname'], $this->config['db']['user'], $this->config['db']['password']);
        $this->createDbTables();
//        $this->createDbTestData();
    }

    public static function getInstance(array $config = null) : App
    {
        if (!static::$instance) {
            static::$instance = new static($config);
        }

        return static::$instance;
    }

    private function createDbTables()
    {
        $this->db->query('CREATE TABLE IF NOT EXISTS news (
            `id` bigint unsigned NOT NULL AUTO_INCREMENT,
            `title` varchar(50) NOT NULL,
            `text` text NOT NULL,
            PRIMARY KEY (`Id`)
        )');
    }

    public function createDbTestData()
    {
        $this->db->query('INSERT INTO news (`title`, `text`) VALUES
                                          ("title 1", "text 1"),
                                          ("title 2", "text 2"),
                                          ("title 3", "text 3"),
                                          ("title 4", "text 4"),
                                          ("title 5", "text 5"),
                                          ("title 6", "text 6"),
                                          ("title 7", "text 7"),
                                          ("title 8", "text 8"),
                                          ("title 9", "text 9")
        ');
    }



    public function run()
    {
        $uri = trim($_SERVER['REQUEST_URI'], '/');

        if ($uri == 'admin') {
            $this->actionAdminNews();
        } elseif (preg_match('/^admin\/edit\/([1-9][0-9]*)$/', $uri, $matches)) {
            $id = $matches[1];
            $this->actionAdminEditNew($id);
        } elseif ($uri == 'admin/add') {
            $this->actionAdminAddNew();
        } elseif ($uri == 'admin/edit/success') {
            $this->render('message', ['message' => 'Новость обновлена.']);
        } elseif ($uri == 'admin/add/success') {
            $this->render('message', ['message' => 'Новость добавлена.']);
        } elseif (preg_match('/^admin\/delete\/([1-9][0-9]*)$/', $uri, $matches)) {
            $id = $matches[1];
            $this->actionAdminDeleteNew($id);
        } elseif (preg_match('/^[1-9][0-9]*$/', $uri, $matches)) {
            $id = $matches[0];
            $this->actionNew($id);
        } elseif ($uri == '') {
            $page = $_GET['page'] ?? 0;
            $this->actionNews($page);
        } else {
            http_response_code(404);
            $this->render('message', ['message' => 'Страница не найдена.']);
        }
    }

    public function render(string $view, $params = null)
    {
        extract($params, EXTR_SKIP);
        ob_start();
        require __DIR__ . '/views/' . $view . '.php';
        $content = ob_get_clean();

        require __DIR__ . '/views/layout.php';
    }

    public function actionNew($id)
    {
        $new = ModelNew::findOne($id);

        if ($new) {
            $this->render('showNew', ['new' => $new]);
        } else {
            $this->render('message', ['message' => 'Новость не найдена.']);
        }
    }

    public function actionNews()
    {
        $news = ModelNew::findAll();

        $this->render('listNews', ['news' => $news]);
    }

    public function actionAdminNews()
    {
        $news = ModelNew::findAll();

        $this->render('listNewsAdmin', ['news' => $news]);
    }

    public function actionAdminDeleteNew($id)
    {
        if (ModelNew::deleteOne($id)) {
            $message = 'Новость удалена.';
        } else {
            http_response_code(404);
            $message = 'Новость не найдена.';
        }

        $this->render('message', ['message' => $message]);
    }

    public function actionAdminEditNew($id)
    {
        if (!$new = ModelNew::findOne($id)) {
            http_response_code(404);
            $this->render('message', ['message' => 'Новость не найдена.']);
            return;
        }

        if ($new->load($_POST, ['title', 'text']) && $new->save()) {
            header('Location: /admin/edit/success',true,301);
            return;
        }

        $this->render('formNewAdmin', ['new' => $new, 'title' => 'Изменение новости']);
    }

    public function actionAdminAddNew()
    {
        $new = new ModelNew;

        if ($new->load($_POST, ['title', 'text']) && $new->save()) {
            header('Location: /admin/add/success',true,301);
            return;
        }

        $this->render('formNewAdmin', ['new' => $new, 'title' => 'Добавление новости']);
    }
}