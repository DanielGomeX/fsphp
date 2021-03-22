<?php


namespace Source\Models\CafeApp;

use Source\Core\Model;

/**
 * Class AppKanboard
 * @package Source\Models\CafeApp
 */
class AppKanboard extends Model
{
    /**  @var mixed|null */
    private $callback;
    /**  @var array|null */
    private $build;

    /**  @var string */
    private $authApi;

    /** @var string */
    private $url;
    /**
     * @var object|null
     */
    private $obj;


    /**
     * AppKanboard constructor.
     * @param string $entity
     * @param array $protected
     * @param array $required
     */
    public function __construct()
    {
        parent::__construct("app_kanboard_users", ['id'], ["username","email","name","password","type"]);
        $this->url = CONF_KANBOARD_API;
        $this->authApi = array( 'X-API-Auth: '.CONF_KANBOARD_AUTHKEY);
    }

    public function login(string $user, string $password) :AppKanboard
    {
        $this->build = array(
            'jsonrpc'=>'2.0',
            'method' =>'login',
            'id'=>1769674782,
            'params'=>array(
                'username'=> $user,
                'password'=> $password
            )
        );
        $this->post();
        return $this;
    }

    /**
     * @param string $username
     * @param string $pass
     * @param string $name
     * @param string $email
     * @param string $role
     * @return $this
     */
    public function createUser(string $username, string $pass, string $name, string $email , string $role = "app-user") : AppKanboard
    {

        if(!$this->find("email = :e","e={$email}")->count()){
            $this->username = $username;
            $this->email = $email;
            $this->name = $name;
            $this->password = $pass;
            $this->type = $role;

            if (!$this->save()){
                $json["message"] = $this->message->render();
                echo json_encode($json);
                return $this;
            }
        }

        $this->build = array(
            'jsonrpc'=>'2.0',
            'method' =>'createUser',
            'id'=>1769674782,
            'params'=>array(
                'username'=> $username,
                'password'=> $pass,
                'name'=> $name,
                'email'=> $email,
                'role'=> $role
            )
        );
        $this->post();
        return $this;
    }

    /**
     * @param int $id
     * @return AppKanboard|null
     */
    public function getUserById(int $id) : ?AppKanboard
    {
        $this->build = array(
            'jsonrpc'=>'2.0',
            'method' =>'getUser',
            'id'=>1769674781,
            'params'=>array(
                'user_id'=> $id
            )
        );
        $this->post();


        /*if ($this->callback["result"]!= null){
            echo "Olá {$this->callback["result"]["username"]}, tudo bem com você ?";
        }*/
        return $this;

    }

    /**
     * @param string $username
     * @return AppKanboard|null
     */
    public function getUserByUsername(string $username) : ?AppKanboard
    {
        $this->build = array(
            'jsonrpc'=>'2.0',
            'method' =>'getUserByName',
            'id'=>1769674782,
            'params'=>array(
                'username'=> $username
            )
        );
        $this->post();
        return $this;
    }

    /**
     * @return $this|null
     */
    public function getAllUsers() : ?AppKanboard
    {
        $this->build = array(
            'jsonrpc'=>'2.0',
            'method' =>'getUser',
            'id'=>1438712131
        );
        $this->post();
        return $this;
    }

    /**
     * @param int $id
     * @param string|null $username
     * @param string|null $name
     * @param string|null $email
     * @param string|null $role
     */
    public function updateUser(int $id, string $username = null, string $name = null, string $email = null, string $role = null)
    {

        $params = array();

        if (!empty($email)){
            $params["email"] = $email;
        }
        if (!empty($username)){
             $params["username"] = $username;
        }

        if (!empty($name)){
            $params["name"] = $name;
        }
        if (!empty($role)){
           $params["role"] = $role;
        }
        $params["id"] = $id;

        $this->build = array(
            'jsonrpc'=>'2.0',
            'method' =>'updateUser',
            'id'=>322123657,
            'params'=>$params
        );
        $this->post();

        if ($this->callback["result"]){
            var_dump($this->callback);
        }


    }

    /**
     * @param $id
     * @return $this|null
     */
    public function removeUser($id) : ?AppKanboard
    {
        $this->build = array(
            'jsonrpc'=>'2.0',
            'method' =>'removeUser',
            'id'=>2094191872,
            'params'=>array(
                'user_id'=> $id
            )
        );
        $this->post();
        return $this;
    }

    /**
     * @return mixed|null
     */
    public function callback()
    {
        return $this->callback;
        
    }

    /**
     *
     */
    private function post()
    {
        $ch = curl_init($this->url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($this->build));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->authApi);
        $this->callback = curl_exec($ch);
        $this->callback =  json_decode(curl_exec($ch), true);
        curl_close($ch);
    }

}