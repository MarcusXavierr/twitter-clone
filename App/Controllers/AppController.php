<?php

namespace App\Controllers;

use MF\Controller\Action;
use MF\Model\Container;

class AppController extends Action{

    public function validarAutenticacao(){
        session_start();

        if(!isset($_SESSION['id']) || !isset($_SESSION['nome'])){
            header('Location:/?login=erro');
        }
    }

    public function timeline(){
        $this->validarAutenticacao();

        $tweet = Container::getModel('Tweet');

        $tweet->setId_usuario($_SESSION['id']);

        $tweets = $tweet->getAll();

        $usuario = Container::getModel('Usuario');

        $usuario->setId($_SESSION['id']);

        $this->view->info_usuario = $usuario->getInfoUsuario();

        $this->view->total_tweets = $usuario->getTotalTweets();

        $this->view->total_seguindo = $usuario->getTotalSeguindo();

        $this->view->total_seguidores = $usuario->getTotalSeguidores();

        $this->view->tweets = $tweets;

        $this->render('timeline');
    }

    public function tweet(){
        $this->validarAutenticacao();

        $tweet = Container::getModel('Tweet');

        $tweet->setTweet($_POST['tweet']);

        $tweet->setId_usuario($_SESSION['id']);

        $tweet->salvar();

        header('Location:/timeline');
    }

    public function quemSeguir(){
        $this->validarAutenticacao();
        $pesquisarPor = isset($_POST['pesquisar']) ? $_POST['pesquisar'] : '';

        $usuarios = array();

        $usuario = Container::getModel('Usuario');

        $usuario->setId($_SESSION['id']);

        $this->view->info_usuario = $usuario->getInfoUsuario();

        $this->view->total_tweets = $usuario->getTotalTweets();

        $this->view->total_seguindo = $usuario->getTotalSeguindo();

        $this->view->total_seguidores = $usuario->getTotalSeguidores();

        if($pesquisarPor != ''){
            $usuario->setNome($pesquisarPor);
            $usuarios = $usuario->getAll();
        }
        else
            $usuarios = $usuario->getAll();
        {
        }

        $this->view->usuarios = $usuarios;

        $this->render('quemSeguir');
    }

    public function acao(){
        $this->validarAutenticacao();

        $acao = isset($_GET['acao']) ? $_GET['acao'] : '';

        $id_usuario = isset($_GET['id_usuario']) ? $_GET['id_usuario'] : '';  

        $usuario = Container::getModel('Usuario');

        $usuario->setId($_SESSION['id']);

        if($acao == 'seguir'){

            $usuario->seguirUsuario($id_usuario);

        } else if($acao == 'parar_seguir'){

            $usuario->deixarSeguir($id_usuario);
        }

        header('Location:/quem_seguir');
    }

    public function apagarTweet(){
        $this->validarAutenticacao();

        $tweet = Container::getModel('Tweet');

        $tweet->setId($_GET['tweet_id']);

        $tweet->apagar();
        
       header('Location:/timeline');
    }
}

?>