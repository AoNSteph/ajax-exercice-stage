<?php
#region defs

#region session
class Session {
    /**
     *
     * @var ?Session 
     */
    protected static $singleton = null;
    public static function start(): Session {
        if(null === static::$singleton) {
            static::$singleton = new Session;
        }
        return static::$singleton;
    }
    
    protected function __construct() {
        session_start();
    }
    
    public function has($key) {
        return isset($_SESSION[$key]);
    }
    
    public function get($key, $def = null) {
        return $this->has($key)? $_SESSION[$key]:$def;
    }
    
    public function set($key, $val) {
        $_SESSION[$key] = $val;
        return $this;
    }
    
    public function del($key) {
        if($this->has($key)) {
            unset($_SESSION[$key]);
        }
        return $this;
    }
    
    public function __isset($name) {
        return $this->has($name);
    }
    
    public function __get($name) {
        return $this->get($name);
    }
    
    public function __set($name, $value) {
        $this->set($name, $value);
    }
    
    public function __unset($name) {
        $this->del($name);
    }
}
#endregion session

#region game
class Game {
    /**
     *
     * @var Game
     */
    protected static $singleton = null;
    public static function load(): Game {
        if(null === static::$singleton) {
            // check in session
            if(Session::start()->has('game')) { // load an existing one
                $res = Session::start()->get('game');
                static::$singleton = new Game(intval($res['t']), intval($res['m']));
            } else { // create a new one
                static::$singleton = new Game;
            }
        }
        return static::$singleton;
    }
    
    protected $mystery = null;
    protected $tries = 0;
    
    protected function __construct(int $tries = 0, ?int $mystery = null) {
        $this->tries = $tries;
        $this->mystery = $mystery;
    }
    
    public function save() {
        Session::start()->set('game', ['m' => $this->mystery, 't' => $this->tries,]);
    }
    
    public function isStarted() {
        return (null !== $this->mystery);
    }
    
    public function getTries() {
        return $this->tries;
    }
    
    public function getMystery() {
        return $this->mystery;
    }
    
    public function start() {
        if(!$this->isStarted()) {
            $this->mystery = mt_rand(0, 100);
            $this->save();
        }
        return $this;
    }
    
    public function finish() {
        if($this->isStarted()) {
            $this->mystery = null;
            $this->tries = 0;
            $this->save();
        }
        return $this;
    }
    
    public function tryit(int $nb) {
        $r = null;
        if($this->isStarted()) {
            $this->tries++;
            $r = $nb <=> $this->mystery;
            $this->save();
        }
        return $r;
    }
    
    
    
}
#endregion game

#endregion defs

$httpMethod = strtolower($_SERVER['REQUEST_METHOD']);

$httpResponse = null;
$httpCode = 200;

if(in_array($httpMethod, ['options', 'head',])) { // allow cors
    header('Access-Control-Allow-Methods: *');
    exit; // no body
} elseif(in_array($httpMethod, ['get',])) { // get status
    $httpResponse = [
        'started' => Game::load()->isStarted(),
        'tries' => Game::load()->getTries(),
    ];
} elseif(in_array($httpMethod, ['post',])) { // try
    if(Game::load()->isStarted()) {
        if(isset($_REQUEST['nb'])
                && is_numeric($_REQUEST['nb'])) {
            $httpResponse = [
                'mystery' => null,
                'result' => null,
                'tries' => 0,
            ];
            $httpResponse['result'] = Game::load()->tryit(intval($_REQUEST['nb']));
            if(0 === $httpResponse['result']) {
                $httpResponse['mystery'] = Game::load()->getMystery();
                $httpResponse['tries'] = Game::load()->getTries();
                Game::load()->finish();
            }
        } else {
            $httpCode = 400;
            $httpResponse = [
                'error' => 'Parameter "nb" is mandatory and must be an integer',
            ];
        }
    } else {
        $httpCode = 400;
        $httpResponse = [
            'error' => 'Game is not started',
        ];
    }
} elseif(in_array($httpMethod, ['put',])) { // new game
    if(!Game::load()->isStarted()) {
        Game::load()->start();
    } else {
        $httpCode = 400;
        $httpResponse = [
            'error' => 'Game is already started',
        ];
    }
} elseif(in_array($httpMethod, ['delete',])) { // abandon game
    if(Game::load()->isStarted()) {
        Game::load()->finish();
    } else {
        $httpCode = 400;
        $httpResponse = [
            'error' => 'Game is not started',
        ];
    }
} else {
    $httpCode = 405;
    $httpResponse = [
        'error' => 'Method not allowed',
    ];
}

echo json_encode($httpResponse);
exit;
